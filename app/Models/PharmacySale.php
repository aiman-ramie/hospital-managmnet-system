<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacySale extends Model
{
    protected $fillable = ['sale_number', 'patient_id', 'total_amount', 'payment_method', 'status'];
    protected $casts = ['total_amount' => 'decimal:2'];
    public function patient() { return $this->belongsTo(Patient::class); }
    public function items() { return $this->hasMany(PharmacySaleItem::class, 'sale_id'); }
}