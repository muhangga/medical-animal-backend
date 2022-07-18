<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicModel extends Model
{
    use HasFactory;
    protected $table = 'clinic';
    protected $fillable = [
        'clinic_name', 'address', 'phone_number', 'path_image', 'latitude', 'longitude'
    ];
}
