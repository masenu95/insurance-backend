<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Beneficial;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Foreach_;

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
            'dependents'=>'required',

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
            'status'=>'PENDING-PAYMENT',
        ]);

        if($request->json('dependents')){


        $dependents  =  $request->json('dependents');

        foreach ($dependents as $dependent) {



            $benef = Beneficial::create([
                'name'=>$dependent->name,
                'dob'=>$dependent->dob,
                'gender'=>$dependent->gender,
                'relationship'=>$dependent->relationship,
                'member_id'=>$dependent->member,
                'user_id'=>Auth::user()->id,
            ]);

        }

        }


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
    public function attachment(Request $request)
    {
        //

        $validated = $request->validate([
            'member'=>'required',
            'url'=>'required',
            'path'=>'required',
        ]);

        $data = Attachment::create([
            'member_id'=>$request->member,
            'url'=>$request->url,
            'path'=>$request->path,
            'user_id'=>Auth::user()->id,
        ]);
        return response()->json($data, 200);
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
