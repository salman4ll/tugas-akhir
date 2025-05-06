<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\SubDistrict;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getProvinces()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    public function getCities($provinceId)
    {
        $cities = City::where('m_provinsi_id', $provinceId)->get();
        return response()->json($cities);
    }

    public function getDistricts($cityId)
    {
        $districts = District::where('m_kabupaten_id', $cityId)->get();
        return response()->json($districts);
    }

    public function getSubDistricts($districtId)
    {
        $subDistricts = SubDistrict::where('m_kecamatan_id', $districtId)->get();
        return response()->json($subDistricts);
    }
}
