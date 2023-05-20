<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\InsuranceCoverage;
use App\Models\InsuranceProduct;
use App\Models\InsuranceType;
use App\Models\Region;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    //

    public function getInsuranceType(){
        $data = InsuranceType::get();
        return response()->json($data, 200);
    }



    public function getInsuranceProduct($id){
        $data = InsuranceProduct::where('insurance_type_id',$id)->get();
        return response()->json($data, 200);
    }




    public function getInsuranceCoverage($id){
        $data = InsuranceCoverage::where('insurance_product_id',$id)->get();
        return response()->json($data, 200);
    }


    public function getRegion(){
        $data = Region::get();
        return response()->json($data, 200);
    }


    public function district($id){
        $data = District::where('region_id',$id)->get();
        return response()->json($data, 200);
    }
}
