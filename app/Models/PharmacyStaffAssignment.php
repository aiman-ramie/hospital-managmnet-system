<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyStaffAssignment extends Model
{
    protected $fillable = ['staff_id', 'responsibility', 'counter', 'status'];
    public function staff() { return $this->belongsTo(Staff::class); }
}