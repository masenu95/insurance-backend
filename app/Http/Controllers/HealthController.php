<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
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
            'name'=>'required',
            'dob'=>'required',
            'gender'=>'required',
            'region'=>'required',
            'district'=>'required',
            'street'=>'required',
            'marital'=>'required',

        ]);

        $data = Member::create([
            'name'=>$validated['name'],
            'dob'=>$validated['dob'],
            'gender'=>$validated['gender'],
            'region'=>$validated['region'],
            'district'=>$validated['district'],
            'street'=>$validated['street'],
            'marital_status'=>$validated['maritial'],
            'user_id'=>Auth::user()->id,
        ]);

        return response()->json($data, 200,);
    }


    public function beneficial(Request $request)
    {
        //

        $validated = $request->validate([
            'name'=>'required',
            'dob'=>'required',
            'gender'=>'required',
            'relationship'=>'required',
            'member'=>'required',


        ]);

        $data = Member::create([
            'name'=>$validated['name'],
            'dob'=>$validated['dob'],
            'gender'=>$validated['gender'],
            'relationship'=>$validated['relationship'],
            'member_id'=>$validated['member'],
            'user_id'=>Auth::user()->id,
        ]);

        return response()->json($data, 200,);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
