<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyReturn extends Model
{
    protected $fillable = ['return_number', 'sale_id', 'medicine_id', 'patient_id', 'quantity', 'refund_amount', 'reason', 'processed_by'];
    public function medicine() { return $this->belongsTo(Medicine::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
}