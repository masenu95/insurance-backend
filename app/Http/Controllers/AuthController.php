<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function register(Request $request){
        $validated = $request->validate([
            'username'=>'required',
            'fname'=>'required',
            'lname'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'role'=>'required',
            'password'=>'required|confirmed',
        ]);


        $data = User::create([
            'username'=>$validated['username'],
            'first_name'=>$validated['fname'],
            'last_name'=>$validated['lname'],
            'email'=>$validated['email'],
            'phone'=>$validated['phone'],
            'role'=>$validated['role'],
            'password'=>Hash::make($validated['password']),
        ]);


        return response()->json($data, 200);
    }


    public function getToken(Request $request){

        $user = User::Where('username', $request->username)->first();



        if ($user && Hash::check($request->password, $user->password)) {

        $token = $user->createToken('ILink')->plainTextToken;

        return response()->json($token, 200);
    }

    }
}
