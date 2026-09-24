<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['patient_code', 'name', 'gender', 'age', 'phone', 'department', 'doctor_id', 'last_visit', 'status'];

    protected $casts = [
        'last_visit' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function cashReceipts()
    {
        return $this->hasMany(CashReceipt::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function labRequests()
    {
        return $this->hasMany(LabRequest::class);
    }

    public function labBills()
    {
        return $this->hasMany(LabBill::class);
    }
}
