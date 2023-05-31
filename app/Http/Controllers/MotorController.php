<?php

namespace App\Http\Controllers;

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
            "owner_address"=>'required',
            "motor_category"=>'required',
            "motor_type"=>'required',
            "registration_number"=>'required',
            "chassis_number"=>'required',
            "insurancetypeid"=>'required',
            "insuranceproductid"=>'required',
            "coverageid"=>'required',
            "requestid"=>'required',
        ]);

     /*   if($request->input('insurance_coverage_product_type')=="IT"){
            $registrationnumber = $request->input("chassisnumber");
        }else{
            $registrationnumber = $request->input("registrationnumber");
        }*/

        $callback = "http://ilink.co.tz/api/covernoteref/resp";
        $covernotetype = 1;


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
                "owner_address"=>$validated['sitting_capacity'],
                "motor_category"=>$validated['owner_address'],
                "motor_type"=>$validated['motor_type'],
                "registration_number"=>$validated['registration_number'],
                "chassis_number"=>$validated['chassis_number'],
                "first_loss"=>$request->first_loss,
                "image_reference"=>$request->imageReference,
                "insurancetypeid"=>$validated['insurancetypeid'],
                "insuranceproductid"=>$validated['insuranceproductid'],
                "coverageid"=>$validated['coverageid'],
                "requestid"=>$validated['requestid'],
                'user_id'=> Auth::user()->id,
                'addon_amount' => "0.00",
                'addon_premium_rate' => "0.00",
                'callback_url' => $callback,
                'covernote_type' => $covernotetype,

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
            "owner_address"=>'required',
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
                "owner_address"=>$validated['sitting_capacity'],
                "motor_category"=>$validated['owner_address'],
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
