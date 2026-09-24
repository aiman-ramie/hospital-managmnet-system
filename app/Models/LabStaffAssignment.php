<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabStaffAssignment extends Model
{
    protected $fillable = ['staff_id', 'responsibility', 'bench', 'status'];

    public function staff() { return $this->belongsTo(Staff::class); }
}