<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PharmacyPurchase;
use App\Models\PharmacyPurchaseItem;
use App\Models\PharmacyReturn;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use App\Models\PharmacyStaffAssignment;
use App\Models\PharmacySupplier;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $medicines = Medicine::orderBy('name')->get();
        $patients = Patient::orderBy('name')->get();
        $doctors = Doctor::orderBy('name')->get();
        $suppliers = PharmacySupplier::orderBy('name')->get();
        $staff = Staff::with('pharmacyAssignments')->orderBy('name')->get();
        $purchases = PharmacyPurchase::with('supplier')->latest()->limit(15)->get();
        $sales = PharmacySale::with(['patient', 'items.medicine'])->latest()->limit(20)->get();
        $prescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])->latest()->limit(20)->get();
        $returns = PharmacyReturn::with(['medicine', 'patient'])->latest()->limit(15)->get();
        $kpis = [
            'medicines' => $medicines->count(),
            'low_stock' => $medicines->filter(fn ($medicine) => $medicine->stock <= $medicine->low_stock_threshold)->count(),
            'sales_today' => PharmacySale::whereDate('created_at', $today)->sum('total_amount'),
            'prescriptions_today' => Prescription::whereDate('created_at', $today)->count(),
        ];

        return view('pharmacy-operations', compact('medicines', 'patients', 'doctors', 'suppliers', 'staff', 'purchases', 'sales', 'prescriptions', 'returns', 'kpis'));
    }

    public function prescriptionsPage()
    {
        $prescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])->latest()->limit(100)->get();
        $patients = Patient::orderBy('name')->get();
        $doctors = Doctor::orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->get();

        return view('prescriptions', compact('prescriptions', 'patients', 'doctors', 'medicines'));
    }

    public function prescriptionsLive()
    {
        return response()->json(Prescription::with(['patient', 'doctor', 'items.medicine'])->latest()->limit(100)->get());
    }

    public function updatePrescriptionStatus(Request $request, Prescription $prescription)
    {
        $prescription->update($request->validate(['status' => ['required', 'in:Preparing,Ready,Collected']]));

        return back()->with('status', "Prescription {$prescription->code} status updated.");
    }

    public function storeMedicine(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'unit' => ['required', 'string', 'max:50'], 'stock' => ['required', 'integer', 'min:0'], 'low_stock_threshold' => ['required', 'integer', 'min:0']]);
        Medicine::create($data);
        return back()->with('status', 'Medicine added to inventory.');
    }

    public function storeSupplier(Request $request)
    {
        PharmacySupplier::create($request->validate(['name' => ['required', 'string', 'max:255'], 'contact_person' => ['nullable', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:50'], 'email' => ['nullable', 'email'], 'payment_terms' => ['nullable', 'string', 'max:100']]));
        return back()->with('status', 'Supplier added to pharmacy directory.');
    }

    public function storePurchase(Request $request)
    {
        $data = $request->validate(['supplier_id' => ['required', 'exists:pharmacy_suppliers,id'], 'medicine_id' => ['required', 'exists:medicines,id'], 'quantity' => ['required', 'integer', 'min:1'], 'unit_cost' => ['required', 'numeric', 'min:0'], 'batch_number' => ['nullable', 'string', 'max:100'], 'expiry_date' => ['nullable', 'date']]);
        DB::transaction(function () use ($data) {
            $purchase = PharmacyPurchase::create(['purchase_number' => 'PO-' . now()->format('YmdHis'), 'supplier_id' => $data['supplier_id'], 'purchase_date' => now()->toDateString(), 'total_amount' => $data['quantity'] * $data['unit_cost'], 'status' => 'Received']);
            PharmacyPurchaseItem::create($data + ['purchase_id' => $purchase->id]);
            Medicine::whereKey($data['medicine_id'])->increment('stock', $data['quantity']);
        });
        return back()->with('status', 'Purchase received and inventory updated.');
    }

    public function storeSale(Request $request)
    {
        $data = $request->validate(['patient_id' => ['nullable', 'exists:patients,id'], 'medicine_id' => ['required', 'exists:medicines,id'], 'quantity' => ['required', 'integer', 'min:1'], 'unit_price' => ['required', 'numeric', 'min:0'], 'payment_method' => ['required', 'in:Cash,Card,Insurance,Credit']]);
        DB::transaction(function () use ($data) {
            $medicine = Medicine::lockForUpdate()->findOrFail($data['medicine_id']);
            abort_if($medicine->stock < $data['quantity'], 422, "Insufficient stock for {$medicine->name}.");
            $sale = PharmacySale::create(['sale_number' => 'SL-' . now()->format('YmdHis'), 'patient_id' => $data['patient_id'] ?? null, 'total_amount' => $data['quantity'] * $data['unit_price'], 'payment_method' => $data['payment_method'], 'status' => 'Completed']);
            PharmacySaleItem::create(['sale_id' => $sale->id, 'medicine_id' => $medicine->id, 'quantity' => $data['quantity'], 'unit_price' => $data['unit_price']]);
            $medicine->decrement('stock', $data['quantity']);
        });
        return back()->with('status', 'Medicine sale recorded and stock reduced.');
    }

    public function storePrescription(Request $request)
    {
        $data = $request->validate(['patient_id' => ['required', 'exists:patients,id'], 'doctor_id' => ['required', 'exists:doctors,id'], 'medicine_id' => ['required', 'exists:medicines,id'], 'quantity' => ['required', 'integer', 'min:1'], 'dosage' => ['nullable', 'string', 'max:100'], 'duration' => ['nullable', 'string', 'max:100']]);
        DB::transaction(function () use ($data) {
            $prescription = Prescription::create(['code' => 'RX-' . now()->format('YmdHis'), 'patient_id' => $data['patient_id'], 'doctor_id' => $data['doctor_id'], 'items_count' => 1, 'status' => 'Preparing']);
            PrescriptionItem::create($data + ['prescription_id' => $prescription->id]);
        });
        return back()->with('status', 'Prescription added to the pharmacy queue.');
    }

    public function storeReturn(Request $request)
    {
        $data = $request->validate(['medicine_id' => ['required', 'exists:medicines,id'], 'patient_id' => ['nullable', 'exists:patients,id'], 'quantity' => ['required', 'integer', 'min:1'], 'refund_amount' => ['required', 'numeric', 'min:0'], 'reason' => ['required', 'string', 'max:255'], 'processed_by' => ['required', 'string', 'max:255']]);
        DB::transaction(function () use ($data) {
            PharmacyReturn::create($data + ['return_number' => 'RT-' . now()->format('YmdHis')]);
            Medicine::whereKey($data['medicine_id'])->increment('stock', $data['quantity']);
        });
        return back()->with('status', 'Return recorded and stock restored.');
    }

    public function storeStaff(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'role' => ['required', 'string', 'max:100'], 'shift' => ['required', 'in:Morning,Evening,Night'], 'responsibility' => ['required', 'string', 'max:255'], 'counter' => ['nullable', 'string', 'max:100']]);
        DB::transaction(function () use ($data) {
            $staff = Staff::create(['name' => $data['name'], 'role' => $data['role'], 'shift' => $data['shift'], 'status' => 'Present']);
            PharmacyStaffAssignment::create(['staff_id' => $staff->id, 'responsibility' => $data['responsibility'], 'counter' => $data['counter'] ?? null]);
        });
        return back()->with('status', 'Pharmacy staff member and responsibility added.');
    }
}