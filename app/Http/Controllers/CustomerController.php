<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $data = Customer::with('region')->with('district')->get();
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
        $validated = $request->validate([
            'region_id' => 'required',
            'full_name' => 'required',
            'dob' => 'required',
            'customer_type' => 'required',
            'id_number' => 'required',
            'id_type' => 'required',
            'gender' => 'required',
            'country_code' => 'required',
            'phone' => 'required',
        ]);

        $data = Customer::create([
            'user_id'=>Auth::user()->id,
            'region_id'=>$validated['region_id'],
            'district_id'=>$request->district_id,
            'full_name'=>$validated['full_name'],
            'birth_date'=>$validated['dob'],
            'customer_type'=>$validated['customer_type'],
            'id_number'=>$validated['id_number'],
            'id_type'=>$validated['id_type'],
            'gender'=>$validated['gender'],
            'country_code'=>$validated['country_code'],
            'street'=>$request->street,
            'phone_number'=>$validated['phone'],
            'fax'=>$request->fax,
            'postal_address'=>$request->postal,
            'email'=>$request->email,
        ]);

        return response()->json($data, 200);
        //
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
            'region_id' => 'required',
            'district_id' => 'required',
            'full_name' => 'required',
            'dob' => 'required',
            'customer_type' => 'required',
            'id_number' => 'required',
            'id_type' => 'required',
            'gender' => 'required',
            'country_code' => 'required',
            'street' => 'required',
            'phone' => 'required',
        ]);

        $customer = Customer::find($id);

        $data = $customer->update([
            'user_id'=>Auth::user()->id,
            'region_id'=>$validated['region_id'],
            'district_id'=>$validated['district_id'],
            'full_name'=>$validated['full_name'],
            'birth_date'=>$validated['dob'],
            'customer_type'=>$validated['customer_type'],
            'id_number'=>$validated['id_number'],
            'id_type'=>$validated['id_type'],
            'gender'=>$validated['gender'],
            'country_code'=>$validated['country_code'],
            'street'=>$validated['street'],
            'phone_number'=>$validated['phone'],
            'fax'=>$request->fax,
            'postal_address'=>$request->postal,
            'email'=>$request->email,
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
