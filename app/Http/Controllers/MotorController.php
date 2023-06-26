<?php

namespace App\Http\Controllers;

use App\Models\MotorGallery;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validated = $request->validate([
            'make'=>'required',
            'model'=>'required',
            'model_number'=>'required',
            'body_type'=>'required',
            'color'=>'required',
            'engine_number'=>'required',
            'engine_capacity'=>'required',
            'fuel_used'=>'required',
            'number_of_axles'=>'required',
            'axle_distance'=>'required',
            'sitting_capacity'=>'required',
            'year_of_manufacture'=>'required',
            'tare_weight'=>'required',
            'gross_weight'=>'required',
            "motor_usage" =>'required',
            "owner_name"=>'required',
            "owner_category"=>'required',

            "motor_category"=>'required',
            "motor_type"=>'required',
            "registration_number"=>'required',
            "chassis_number"=>'required',
            "insurance_type"=>'required',
            "insurance_product"=>'required',
            "coverageid"=>'required',
            "requestid"=>'required',
            "vfi_url"=>'required',
            "vfi_path"=>'required',
            "vfi_lat"=>'required',
            "vfi_long"=>'required',
            "vfi_time"=>'required',
            "vfi_location"=>'required',
            "vbi_url"=>'required',
            "vbi_path"=>'required',
            "vbi_location"=>'required',
            "vbi_time"=>'required',
            "vbi_lat"=>'required',
            "vbi_long"=>'required',
            "vlsi_url"=>'required',
            "vlsi_path"=>'required',
            "vlsi_lat"=>'required',
            "vlsi_long"=>'required',
            "vlsi_location"=>'required',
            "vlsi_time"=>'required',
            "vrsi_url"=>'required',
            "vrsi_path"=>'required',
            "vrsi_location"=>'required',
            "vrsi_long"=>'required',
            "vrsi_lat"=>'required',
            "vrsi_time"=>'required',
            "vdi_url"=>'required',
            "vdi_path"=>'required',
            "vdi_location"=>'required',
            "vdi_long"=>'required',
            "vdi_lat"=>'required',
            "vdi_time"=>'required',
            "vobi_url"=>'required',
            "vobi_path"=>'required',
            "vobi_location"=>'required',
            "vobi_long"=>'required',
            "vobi_lat"=>'required',
            "vobi_time"=>'required',
            "id_url"=>'required',
            "id_path"=>'required',
            "id_location"=>'required',
            "id_long"=>'required',
            "id_lat"=>'required',
            "id_time"=>'required',
        ]);

     /*   if($request->input('insurance_coverage_product_type')=="IT"){
            $registrationnumber = $request->input("chassisnumber");
        }else{
            $registrationnumber = $request->input("registrationnumber");
        }*/

        $callback = "http://ilink.co.tz/api/covernoteref/resp";
        $covernotetype = 1;


        $data = Transaction::create([
                "make"=>$validated['make'],
                "model"=>$validated['model'],
                "model_number"=>$validated['model_number'],
                "body_type"=>$validated['body_type'],
                "color"=>$validated['color'],
                "engine_number"=>$validated['engine_number'],
                "engine_capacity"=>$validated['engine_capacity'],
                "fuel_used"=>$validated['fuel_used'],
                "number_of_axles"=>$validated['number_of_axles'],
                "axle_distance"=>$validated['axle_distance'],
                "sitting_capacity"=>$validated['sitting_capacity'],
                "year_of_manufacture"=>$validated['year_of_manufacture'],
                "tare_weight"=>$validated['tare_weight'],
                "gross_weight"=>$validated['gross_weight'],
                "motor_usage"=>$validated['motor_usage'],
                "owner_name"=>$validated['owner_name'],
                "owner_category"=>$validated['owner_category'],
                "owner_category"=>$validated['owner_category'],
                "owner_address"=>$request->owner_address,
                "motor_category"=>$validated['motor_category'],
                "motor_type"=>$validated['motor_type'],
                "registration_number"=>$validated['registration_number'],
                "chassis_number"=>$validated['chassis_number'],
                "first_loss"=>$request->first_loss,
                "image_reference"=>$request->imageReference,

                "requestid"=>$validated['requestid'],
                'user_id'=> Auth::user()->id,
                'addon_amount' => "0.00",
                'addon_premium_rate' => "0.00",
                'callback_url' => $callback,
                'covernote_type' => $covernotetype,
                'insurance_type_id'=>$request->insurance_type,
                'insurance_product_id'=>$request->insurance_product,
                'insurance_coverage_id'=>$request->insurance_coverage,
                'insurance_company_id'=>$request->insurance_company,
                'agent_id'=>'1',
                'request_id'=>'1'


        ]);


        $gallerty = MotorGallery::create([
            'transaction_id'=>$data->id,
            'name'=>'Vehicle font image',
            'url'=>$validated['vfi_url'],
            'path'=>$validated['vfi_path'],
            'lat'=>$validated['vfi_lat'],
            'long'=>$validated['vfi_long'],
            'location'=>$validated['vfi_location'],

            'time'=>$validated['vfi_time'],
        ]);

        $gallery = MotorGallery::create([
            'transaction_id'=>$data->id,
            'name'=>'Vehicle back image',
            'url'=>$validated['vbi_url'],
            'path'=>$validated['vbi_path'],
            'lat'=>$validated['vbi_lat'],
            'long'=>$validated['vbi_long'],
            'location'=>$validated['vbi_location'],

            'time'=>$validated['vbi_time'],
        ]);

        $gallery = MotorGallery::create([
            'transaction_id'=>$data->id,
            'name'=>'Vehicle left side image',
            'url'=>$validated['vlsi_url'],
            'path'=>$validated['vlsi_path'],
            'lat'=>$validated['vlsi_lat'],
            'long'=>$validated['vlsi_long'],
            'location'=>$validated['vlsi_location'],

            'time'=>$validated['vlsi_time'],
        ]);

        $gallery = MotorGallery::create([
            'transaction_id'=>$data->id,
            'name'=>'Vehicle right side image',
            'url'=>$validated['vrsi_url'],
            'path'=>$validated['vrsi_path'],
            'lat'=>$validated['vrsi_lat'],
            'long'=>$validated['vrsi_long'],
            'location'=>$validated['vrsi_location'],

            'time'=>$validated['vrsi_time'],
        ]);

        $gallery = MotorGallery::create([
            'transaction_id'=>$data->id,
            'name'=>'Vehicle dashboard image',
            'url'=>$validated['vdi_url'],
            'path'=>$validated['vdi_path'],
            'lat'=>$validated['vdi_lat'],
            'long'=>$validated['vdi_long'],
            'location'=>$validated['vdi_location'],

            'time'=>$validated['vdi_time'],
        ]);


        $gallery = MotorGallery::create([
            'transaction_id'=>$data->id,
            'name'=>'Vehicle open bonet image',
            'url'=>$validated['vobi_url'],
            'path'=>$validated['vobi_path'],
            'lat'=>$validated['vobi_lat'],
            'long'=>$validated['vobi_long'],
            'location'=>$validated['vobi_location'],

            'time'=>$validated['vobi_time'],
        ]);



        $gallery = MotorGallery::create([
            'transaction_id'=>$data->id,
            'name'=>'id image',
            'url'=>$validated['id_url'],
            'path'=>$validated['id_path'],
            'lat'=>$validated['id_lat'],
            'long'=>$validated['id_long'],
            'location'=>$validated['id_location'],
            'time'=>$validated['id_time'],
        ]);


        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $validated = $request->validate([
            'make'=>'required',
            'model'=>'required',
            'model_number'=>'required',
            'body_type'=>'required',
            'color'=>'required',
            'engine_number'=>'required',
            'engine_capacity'=>'required',
            'fuel_used'=>'required',
            'number_of_axles'=>'required',
            'axle_distance'=>'required',
            'sitting_capacity'=>'required',
            'year_of_manufacture'=>'required',
            'tare_weight'=>'required',
            'gross_weight'=>'required',
            "motor_usage" =>'required',
            "owner_name"=>'required',
            "owner_category"=>'required',
            "motor_category"=>'required',
            "motor_type"=>'required',
            "registration_number"=>'required',
            "chassis_number"=>'required',

        ]);


        $data = Transaction::create([
                "make"=>$validated['name'],
                "model"=>$validated['model'],
                "model_number"=>$validated['model_number'],
                "body_type"=>$validated['body_type'],
                "color"=>$validated['color'],
                "engine_number"=>$validated['engine_number'],
                "engine_capacity"=>$validated['engine_capacity'],
                "fuel_used"=>$validated['fuel_used'],
                "number_of_axles"=>$validated['number_of_axles'],
                "axle_distance"=>$validated['axle_distance'],
                "sitting_capacity"=>$validated['sitting_capacity'],
                "year_of_manufacture"=>$validated['year_of_manufacture'],
                "tare_weight"=>$validated['tare_weight'],
                "gross_weight"=>$validated['gross_weight'],
                "motor_usage"=>$validated['motor_usage'],
                "owner_name"=>$validated['owner_name'],
                "owner_category"=>$validated['owner_category'],
                "owner_address"=>$request->owner_address,
                "motor_category"=>$validated['motor_category'],
                "motor_type"=>$validated['motor_type'],
                "registration_number"=>$validated['registration_number'],
                "chassis_number"=>$validated['chassis_number'],
                "first_loss"=>$request->first_loss,
        ]);

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
