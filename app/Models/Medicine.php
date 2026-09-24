<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name', 'unit', 'stock', 'low_stock_threshold'];
    public function purchaseItems() { return $this->hasMany(PharmacyPurchaseItem::class); }
    public function saleItems() { return $this->hasMany(PharmacySaleItem::class); }
    public function prescriptionItems() { return $this->hasMany(PrescriptionItem::class); }
}