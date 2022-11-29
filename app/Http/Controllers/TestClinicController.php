<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class TestClinicController extends Controller
{
    public function index() {
        $cached = Cache::get('users', );
    }

    public function getUser() {
        $clinic = ClinicModel::all();

        foreach ($clinic as $key => $value) {
            echo $value->clinic_name;
            echo $value->address;
            echo $value->phone_number;
        }
    }
}
