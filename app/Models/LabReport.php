<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReport extends Model
{
    protected $fillable = [
        'lab_request_id', 'prepared_by', 'verified_by', 'result_value', 'reference_range',
        'findings', 'remarks', 'status', 'verified_at',
    ];

    protected $casts = ['verified_at' => 'datetime'];

    public function request() { return $this->belongsTo(LabRequest::class, 'lab_request_id'); }
    public function preparedBy() { return $this->belongsTo(Staff::class, 'prepared_by'); }
    public function verifiedBy() { return $this->belongsTo(Staff::class, 'verified_by'); }
}