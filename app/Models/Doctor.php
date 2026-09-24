<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['name', 'specialty', 'schedule', 'room', 'status'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function requestedLabTests()
    {
        return $this->hasMany(LabRequest::class, 'requested_by');
    }
}
