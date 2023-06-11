<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
   public function uploadMotorImage(Request $request){

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10048',
        ]);
        $file =$validated['image'];

        $path = $file->store('public/motor');

        $url = Storage::url($path);
        $metadata  =  Helper::getImageMetadata($path);

        $location = $metadata['location'];
        $time = $metadata['timeTaken'];


        return response()->json(['url'=>$url,'location'=>$location,'time'=>$time], 200);


    }


}
