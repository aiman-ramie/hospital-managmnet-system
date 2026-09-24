<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $table = 'lab_test_catalogue';

    protected $fillable = ['code', 'name', 'category', 'specimen', 'turnaround_hours', 'price', 'is_active'];

    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];

    public function requests()
    {
        return $this->hasMany(LabRequest::class);
    }
}