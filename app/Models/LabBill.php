<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabBill extends Model
{
    protected $fillable = [
        'bill_number', 'lab_request_id', 'patient_id', 'amount', 'discount', 'net_amount', 'paid_amount', 'status',
    ];

    protected $casts = ['amount' => 'decimal:2', 'discount' => 'decimal:2', 'net_amount' => 'decimal:2', 'paid_amount' => 'decimal:2'];

    public function request() { return $this->belongsTo(LabRequest::class, 'lab_request_id'); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function payments() { return $this->hasMany(LabPayment::class); }
}