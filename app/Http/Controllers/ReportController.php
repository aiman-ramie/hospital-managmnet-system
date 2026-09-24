<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\LabRequest;
use App\Models\Patient;
use App\Models\PharmacySale;
use App\Models\Prescription;

class ReportController extends Controller
{
    public function index()
    {
        $today = today();
        $kpis = [
            'patients' => Patient::count(),
            'appointments' => Appointment::whereDate('appointment_date', $today)->count(),
            'doctors' => Doctor::whereIn('status', ['Available', 'In clinic'])->count(),
            'admitted' => Admission::whereNull('discharged_on')->where('status', '!=', 'Discharged')->count(),
        ];
        $summary = [
            'prescriptions' => Prescription::whereDate('created_at', $today)->count(),
            'lab_requests' => LabRequest::whereDate('created_at', $today)->count(),
            'pharmacy_sales' => PharmacySale::whereDate('created_at', $today)->sum('total_amount'),
            'completed_appointments' => Appointment::whereDate('appointment_date', $today)->where('status', 'Confirmed')->count(),
        ];

        return view('reports', compact('kpis', 'summary'));
    }
}