<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = ['code', 'patient_id', 'doctor_id', 'items_count', 'status'];
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function items() { return $this->hasMany(PrescriptionItem::class); }
}