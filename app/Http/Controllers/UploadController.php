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
        $lat = $metadata['lat'];
        $long = $metadata['long'];

        return response()->json(['url'=>$url,'location'=>$location,'time'=>$time,'lat'=>$lat,'long'=>$long,'path'=>$path], 200);


    }

    public function uploadImage(Request $request){

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:10048',
        ]);

        $file =$validated['image'];

        $path = $file->store('public/logo');

        $url = Storage::url($path);

        return response()->json(['url'=>$url,'path'=>$path], 200);
    }

}
