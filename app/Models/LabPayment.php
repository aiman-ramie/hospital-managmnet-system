<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabPayment extends Model
{
    protected $fillable = ['lab_bill_id', 'amount', 'payment_method', 'received_by', 'reference'];

    protected $casts = ['amount' => 'decimal:2'];

    public function bill() { return $this->belongsTo(LabBill::class, 'lab_bill_id'); }
}