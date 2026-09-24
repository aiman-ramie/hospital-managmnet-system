<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'bed_id', 'admitted_on', 'discharged_on', 'status'];

    protected $casts = [
        'admitted_on' => 'date',
        'discharged_on' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
