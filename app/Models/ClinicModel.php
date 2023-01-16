<?php

namespace App\Models;

use App\Models\WorkingModel;
use App\Models\FacilityModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bagusindrayana\LaravelCoordinate\Traits\LaravelCoordinate;

class ClinicModel extends Model
{
    use HasFactory, LaravelCoordinate;
    protected $table = 'clinic';
    protected $fillable = [
        'clinic_name', 'address', 'phone_number', 'rating', 'reviews', 'website', 'latitude', 'longitude'
    ];

    public function workingDays()
    {
        return $this->hasMany(WorkingModel::class, 'clinic_id', 'id');
    }

    public function facility()
    {
        return $this->hasOne(FacilityModel::class, 'clinic_id', 'id');
    }
}
