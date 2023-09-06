<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use App\Models\InsuranceType;
use App\Models\User;
use Exception;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $data = InsuranceCompany::get();
        return response()->json($data, 200);


    }



    public function getCompanyByType($id)
    {
        //

        $data = InsuranceType::find($id);

        return response()->json($data->companies, 200);


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
            'name'=>'required',
            'code'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'url'=>'required',
            'path'=>'required',
        ]);


        $user = User::create([
            'username'=>$validated['name'],
            'first_name'=>$validated['name'],
            'last_name'=>$validated['name'],
            'email'=>$validated['email'],
            'phone'=>$validated['phone'],
            'role'=>'company',
            'password'=>Hash::make(123456),

        ]);

        $data = InsuranceCompany::create([
            'name'=>$validated['name'],
            'company_code'=>$validated['code'],
            'email'=>$validated['email'],
            'phone'=>$validated['phone'],
            'logo_url'=>$validated['url'],
            'logo_path'=>$validated['path'],
            'user_id'=>Auth::user()->id,
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
    public function addProduct(Request $request)
    {
        //
        $validated = $request->validate([
            'company'=>'required',
            'type'=>'required',
        ]);

        $data = InsuranceType::find($validated['type']);

        $data->companies()->attach($validated['company']);

        $data = InsuranceCompany::where('id',$validated['company'])->with('types')->first();


        return $data;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $validated = $request->validate([
            'name'=>'required',
            'code'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'url'=>'required',
            'path'=>'required',
        ]);

        $data = InsuranceCompany::find($id);

        $data->update([
            'name'=>$validated['name'],
            'company_code'=>$validated['code'],
            'email'=>$validated['email'],
            'phone'=>$validated['phone'],
            'logo_url'=>$validated['url'],
            'logo_path'=>$validated['path'],
            'user_id'=>Auth::user()->id,
        ]);

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $data = InsuranceCompany::find($id);

        if($data->policies >=1){
            $data->update([
                'status'=>'DELETED'
            ]);

            return response()->json(['message'=>'success'], 200);
        }else{
            try{
                $data->delete();
            }catch(Exception $e){
                $data->update([
                    'status'=>'DELETED'
                ]);
            }

                return response()->json(['message'=>'success'], 200);

        }


    }
}
