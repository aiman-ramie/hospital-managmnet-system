<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyPurchaseItem extends Model
{
    protected $fillable = ['purchase_id', 'medicine_id', 'quantity', 'unit_cost', 'batch_number', 'expiry_date'];
    public function purchase() { return $this->belongsTo(PharmacyPurchase::class, 'purchase_id'); }
    public function medicine() { return $this->belongsTo(Medicine::class); }
}