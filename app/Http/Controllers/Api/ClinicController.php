<?php

namespace App\Http\Controllers\Api;

use App\Models\ClinicModel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
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
}
