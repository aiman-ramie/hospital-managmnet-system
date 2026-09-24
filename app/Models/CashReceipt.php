<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashReceipt extends Model
{
    protected $fillable = ['receipt_number', 'patient_id', 'amount', 'purpose', 'received_by'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
