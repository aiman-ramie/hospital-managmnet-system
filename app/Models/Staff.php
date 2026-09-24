<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = ['name', 'role', 'shift', 'status'];

    public function labAssignments() { return $this->hasMany(LabStaffAssignment::class); }
    public function assignedLabRequests() { return $this->hasMany(LabRequest::class, 'assigned_staff_id'); }

    public function pharmacyAssignments() { return $this->hasMany(PharmacyStaffAssignment::class); }
}