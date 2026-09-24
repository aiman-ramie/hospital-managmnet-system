<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabRequest extends Model
{
    protected $fillable = [
        'request_number', 'patient_id', 'requested_by', 'lab_test_id', 'assigned_staff_id',
        'test_name', 'status', 'priority', 'requested_at', 'collected_at', 'reported_at', 'clinical_notes',
    ];

    protected $casts = ['requested_at' => 'datetime', 'collected_at' => 'datetime', 'reported_at' => 'datetime'];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class, 'requested_by'); }
    public function test() { return $this->belongsTo(LabTest::class, 'lab_test_id'); }
    public function assignedStaff() { return $this->belongsTo(Staff::class, 'assigned_staff_id'); }
    public function report() { return $this->hasOne(LabReport::class); }
    public function bill() { return $this->hasOne(LabBill::class); }
}