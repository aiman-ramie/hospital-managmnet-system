<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacySupplier extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'payment_terms', 'status'];
    public function purchases() { return $this->hasMany(PharmacyPurchase::class, 'supplier_id'); }
}