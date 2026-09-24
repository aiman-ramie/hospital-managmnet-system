<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyPurchase extends Model
{
    protected $fillable = ['purchase_number', 'supplier_id', 'total_amount', 'status', 'purchase_date'];
    protected $casts = ['purchase_date' => 'date', 'total_amount' => 'decimal:2'];
    public function supplier() { return $this->belongsTo(PharmacySupplier::class, 'supplier_id'); }
    public function items() { return $this->hasMany(PharmacyPurchaseItem::class, 'purchase_id'); }
}