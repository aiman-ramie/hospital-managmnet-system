<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\LabBill;
use App\Models\LabPayment;
use App\Models\LabReport;
use App\Models\LabRequest;
use App\Models\LabStaffAssignment;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaboratoryController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $tests = LabTest::orderBy('category')->orderBy('name')->get();
        $patients = Patient::orderBy('name')->get();
        $doctors = Doctor::orderBy('name')->get();
        $staff = Staff::with('labAssignments')->orderBy('name')->get();
        $requests = LabRequest::with(['patient', 'doctor', 'test', 'assignedStaff', 'report', 'bill'])
            ->orderByDesc('created_at')->limit(50)->get();
        $bills = LabBill::with(['patient', 'request', 'payments'])->orderByDesc('created_at')->limit(30)->get();

        $kpis = [
            'tests' => $tests->where('is_active', true)->count(),
            'requests_today' => LabRequest::whereDate('created_at', $today)->count(),
            'pending_reports' => LabReport::whereIn('status', ['Draft', 'Verified'])->count(),
            'outstanding' => LabBill::whereIn('status', ['Unpaid', 'Partially Paid'])->sum(DB::raw('net_amount - paid_amount')),
        ];

        return view('laboratory', compact('tests', 'patients', 'doctors', 'staff', 'requests', 'bills', 'kpis'));
    }

    public function storeTest(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:lab_test_catalogue,code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'specimen' => ['nullable', 'string', 'max:100'],
            'turnaround_hours' => ['required', 'integer', 'min:1', 'max:720'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        LabTest::create($data + ['is_active' => true]);

        return back()->with('status', 'Laboratory test added to the catalogue.');
    }

    public function updateTest(Request $request, LabTest $test)
    {
        $data = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);
        $test->update($data);

        return back()->with('status', "Pricing/status updated for {$test->name}.");
    }

    public function storeRequest(Request $request)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'lab_test_id' => ['required', 'exists:lab_test_catalogue,id'],
            'priority' => ['required', 'in:Routine,Urgent,Stat'],
            'clinical_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data) {
            $test = LabTest::findOrFail($data['lab_test_id']);
            $number = 'LR-' . now()->format('Ymd') . '-' . str_pad((string) (LabRequest::whereDate('created_at', now()->toDateString())->count() + 1), 4, '0', STR_PAD_LEFT);
            $labRequest = LabRequest::create([
                'request_number' => $number,
                'patient_id' => $data['patient_id'],
                'requested_by' => $data['doctor_id'],
                'lab_test_id' => $test->id,
                'test_name' => $test->name,
                'priority' => $data['priority'],
                'status' => 'Collected',
                'requested_at' => now(),
                'collected_at' => now(),
                'clinical_notes' => $data['clinical_notes'] ?? null,
            ]);

            LabBill::create([
                'bill_number' => 'LB-' . now()->format('Ymd') . '-' . str_pad((string) (LabBill::whereDate('created_at', now()->toDateString())->count() + 1), 4, '0', STR_PAD_LEFT),
                'lab_request_id' => $labRequest->id,
                'patient_id' => $data['patient_id'],
                'amount' => $test->price,
                'net_amount' => $test->price,
            ]);
        });

        return back()->with('status', 'Lab request created and billing entry generated.');
    }

    public function assignRequest(Request $request, LabRequest $labRequest)
    {
        $data = $request->validate([
            'assigned_staff_id' => ['required', 'exists:staff,id'],
            'status' => ['required', 'in:Collected,Processing,Ready'],
        ]);
        $labRequest->update($data + ($data['status'] === 'Processing' ? ['collected_at' => $labRequest->collected_at ?? now()] : []));

        return back()->with('status', "{$labRequest->request_number} assigned and status updated.");
    }

    public function storeStaff(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
            'shift' => ['required', 'in:Morning,Evening,Night'],
            'responsibility' => ['required', 'string', 'max:255'],
            'bench' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($data) {
            $staff = Staff::create([
                'name' => $data['name'], 'role' => $data['role'], 'shift' => $data['shift'], 'status' => 'Present',
            ]);
            LabStaffAssignment::create([
                'staff_id' => $staff->id, 'responsibility' => $data['responsibility'], 'bench' => $data['bench'] ?? null,
            ]);
        });

        return back()->with('status', 'Laboratory staff member and responsibility added.');
    }

    public function storeReport(Request $request, LabRequest $labRequest)
    {
        $data = $request->validate([
            'prepared_by' => ['required', 'exists:staff,id'],
            'result_value' => ['required', 'string', 'max:255'],
            'reference_range' => ['nullable', 'string', 'max:255'],
            'findings' => ['required', 'string', 'max:5000'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);
        $labRequest->report()->updateOrCreate([], $data + ['status' => 'Draft']);
        $labRequest->update(['status' => 'Ready', 'reported_at' => now()]);

        return back()->with('status', "Draft report saved for {$labRequest->request_number}.");
    }

    public function verifyReport(Request $request, LabReport $report)
    {
        $data = $request->validate(['verified_by' => ['required', 'exists:staff,id']]);
        $report->update($data + ['status' => 'Verified', 'verified_at' => now()]);

        return back()->with('status', 'Report verified and ready for authorized release.');
    }

    public function storePayment(Request $request, LabBill $bill)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:Cash,Card,Insurance,Online'],
            'received_by' => ['required', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($data, $bill) {
            $outstanding = (float) $bill->net_amount - (float) $bill->paid_amount;
            abort_if((float) $data['amount'] > $outstanding, 422, 'Payment exceeds the outstanding balance.');
            LabPayment::create($data + ['lab_bill_id' => $bill->id]);
            $paid = (float) $bill->paid_amount + (float) $data['amount'];
            $bill->update([
                'paid_amount' => $paid,
                'status' => $paid >= (float) $bill->net_amount ? 'Paid' : 'Partially Paid',
            ]);
        });

        return back()->with('status', "Payment recorded for {$bill->bill_number}.");
    }
}