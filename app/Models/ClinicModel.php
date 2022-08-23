<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bagusindrayana\LaravelCoordinate\Traits\LaravelCoordinate;

class ClinicModel extends Model
{
    use HasFactory, LaravelCoordinate;
    protected $table = 'clinic';
    protected $fillable = [
        'clinic_name', 'address', 'phone_number', 'path_image', 'latitude', 'longitude'
    ];
}
