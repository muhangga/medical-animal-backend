<?php

namespace App\Http\Controllers\Api;

use App\Models\ClinicModel;
use App\Models\WorkingModel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clinic = ClinicModel::all();
        return ResponseFormatter::success($clinic, 'Data klinik');
    }

    public function nearLocation(Request $request)
    {
        $user_lat = $request->latitude;
        $user_long = $request->longitude;

        Redis::set('near_location',  ClinicModel::selectRaw("clinic.id, clinic.clinic_name, clinic.address, clinic.phone_number, clinic.rating, clinic.reviews, clinic.website, clinic.latitude, clinic.longitude, working_days.wednesday, working_days.thursday, working_days.friday, working_days.saturday, working_days.sunday, working_days.monday, working_days.tuesday, ( 6371 * acos( cos( radians(?) ) *
            cos( radians( latitude ) )
            * cos( radians( longitude ) - radians(?)
            ) + sin( radians(?) ) *
            sin( radians( latitude ) ) )
            ) AS distance ", [$user_lat, $user_long, $user_lat] )
            ->join('working_days', 'working_days.clinic_id', '=', 'clinic.id')
            ->having('distance', '<', 30)
            ->orderBy('distance')
            ->limit(10)
            ->get());

        $near_location = Redis::get('near_location');
        if ($near_location) {
            return ResponseFormatter::success(json_decode($near_location), 'Data klinik terdekat');
        } else {
            $clinic = ClinicModel::selectRaw("clinic.id, clinic.clinic_name, clinic.address, clinic.phone_number, clinic.rating, clinic.reviews, clinic.website, clinic.latitude, clinic.longitude, working_days.wednesday, working_days.thursday, working_days.friday, working_days.saturday, working_days.sunday, working_days.monday, working_days.tuesday, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )  * cos( radians( longitude ) - radians(?)) + sin( radians(?) ) *
                sin( radians( latitude )))
                ) AS distance", [$user_lat, $user_long, $user_lat])
                ->join('working_days', 'working_days.clinic_id', '=', 'clinic.id')
                ->having('distance', '<', 30)
                ->orderBy('distance')
                ->limit(10)
                ->get();

            if ($clinic) {
                return ResponseFormatter::success($clinic, 'Data klinik terdekat');
            } else {
                return ResponseFormatter::error(null, 'Data klinik tidak ditemukan', 404);
            }
        }

    }

    public function nearLocationById(Request $request, $id)
    {
        $user_lat = $request->latitude;
        $user_long = $request->longitude;

        $clinic = ClinicModel::find($id);
        return ResponseFormatter::success($clinic, 'Data klinik By Id');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'clinic_name' => ['required', 'max:255'],
                'address' => ['required', 'max:255'],
                'phone_number' => ['required', 'max:14', 'unique:clinic'],

                'path_image' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'errors' => $validator->errors()->first()
                ], 'Data klinik tidak valid', 422);
            }

            $clinic = ClinicModel::create($request->all());
            return ResponseFormatter::success($clinic, 'Data klinik berhasil ditambahkan');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => $error->getMessage(),
                'error' => $error,
            ], 'Data klinik gagal ditambahkan', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clinic = ClinicModel::find($id);
        if (!$clinic) {
            return ResponseFormatter::error([], 'Data klinik tidak ditemukan', 404);
        }
        return ResponseFormatter::success($clinic, 'Data berhasil di temukan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'clinic_name' => ['required', 'max:255'],
                'address' => ['required', 'max:255'],
                'phone_number' => ['required', 'max:14'],
                'path_image' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'errors' => $validator->errors()->first()
                ], 'Data klinik tidak valid', 422);
            }

            $clinic = ClinicModel::findOrFail($id);
            $clinic->update($request->all());
            return ResponseFormatter::success($clinic, 'Data klinik berhasil diubah');

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => $error->getMessage(),
                'error' => $error,
            ], 'Data klinik gagal diubah', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $clinic = ClinicModel::find($id);
            if (!$clinic) {
                return ResponseFormatter::error([], 'Data klinik tidak ditemukan', 404);
            }
            return ResponseFormatter::success($clinic, 'Data klinik berhasil dihapus');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => $error->getMessage(),
                'error' => $error,
            ], 'Data klinik gagal dihapus', 500);
        }
    }

    public function searchClinic($clinic)
    {
        $clinic = ClinicModel::where('clinic_name', 'like', '%' . $clinic . '%')->get();
        if ($clinic) {
            return ResponseFormatter::success($clinic, 'Data klinik berhasil di temukan');
        } else {
            return ResponseFormatter::error([], 'Data klinik tidak ditemukan', 404);
        }
    }

    public function feathAllClinic()
    {
        Redis::set('all_clinic', ClinicModel::join('working_days', 'clinic.id', '=', 'working_days.clinic_id')
            ->select('clinic.*', 'working_days.wednesday', 'working_days.thursday', 'working_days.friday', 'working_days.saturday', 'working_days.sunday', 'working_days.monday', 'working_days.tuesday')
            ->get());

        $cached = Redis::get('all_clinic');
        if ($cached) {
            return ResponseFormatter::success(json_decode($cached), 'Data klinik berhasil diambil dari redis');
        } else {
             $clinic = ClinicModel::join('working_days', 'clinic.id', '=', 'working_days.clinic_id')
                ->select('clinic.*', 'working_days.wednesday', 'working_days.thursday', 'working_days.friday', 'working_days.saturday', 'working_days.sunday', 'working_days.monday', 'working_days.tuesday')
                ->get();

            if ($clinic) {
                return ResponseFormatter::success($clinic, 'Data klinik berhasil diambil');
            } else {
                return ResponseFormatter::error([], 'Data klinik tidak ditemukan', 404);
            }
        }
    }

    public function fetchAllClinicPerPage($page)
    {
        $clinic = ClinicModel::join('working_days', 'clinic.id', '=', 'working_days.clinic_id')
            ->select('clinic.*', 'working_days.wednesday', 'working_days.thursday', 'working_days.friday', 'working_days.saturday', 'working_days.sunday', 'working_days.monday', 'working_days.tuesday')
            ->paginate($page);

            if ($clinic) {
                return ResponseFormatter::success($clinic, 'Data klinik berhasil di temukan');
            } else {
                return ResponseFormatter::error([], 'Data klinik tidak ditemukan', 404);
            }
    }


}
