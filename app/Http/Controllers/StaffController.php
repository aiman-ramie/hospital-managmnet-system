<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with(['labAssignments', 'pharmacyAssignments'])->orderBy('name')->get();
        $kpis = [
            'total' => $staff->count(),
            'present' => $staff->where('status', 'Present')->count(),
            'on_leave' => $staff->where('status', 'On Leave')->count(),
            'absent' => $staff->where('status', 'Absent')->count(),
        ];

        return view('staff', compact('staff', 'kpis'));
    }

    public function store(Request $request)
    {
        Staff::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
            'shift' => ['required', 'in:Morning,Evening,Night'],
            'status' => ['required', 'in:Present,On Leave,Absent'],
        ]));

        return back()->with('status', 'Staff member added to the hospital staff directory.');
    }
}