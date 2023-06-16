<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
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
            'username'=>'required',
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'role'=>'required',
            'region'=>'required',
            'district'=>'required',
            'idtype'=>'required',
            'idno'=>'required',
            'rate'=>'required',
        ]);


        $user = User::create([
            'username'=>$validated['username'],
            'first_name'=>$validated['fname'],
            'last_name'=>$validated['lname'],
            'email'=>$validated['email'],
            'phone'=>$validated['phone'],
            'role'=>$validated['role'],
            'password'=>Hash::make(123456),

        ]);


        $agent = Agent::create([
            'region'=>$validated['region'],
            'district'=>$validated['district'],
            'idtype'=>$validated['idtype'],
            'idno'=>$validated['idno'],
            'commission_rate'=>$validated['rate'],
            'user_id'=>$user->id,
        ]);

        $data = Agent::where('id',$agent->id)->with('user')->first();


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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
