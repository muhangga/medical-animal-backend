<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityModel extends Model
{
    use HasFactory;
    protected $table = 'facility';
    protected $fillable = [
        'clinic_id',
        'konsultasi',
        'layanan_medis',
        'penginapan',
        'grooming',
    ];

    public function clinic()
    {
        return $this->belongsTo(ClinicModel::class, 'clinic_id', 'id');
    }
}
