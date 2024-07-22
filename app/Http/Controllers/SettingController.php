<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index($key = null){
        if($key){
            $setting = Setting::where('key', $key)->first();
        }
        else{
            $setting = Setting::paginate(10);
        }
        return response()->json($setting);

    }

    public function edit($key = null ,EditSettingRequest $request ){
        $setting = Setting::where('key', $key)->update($request->toArray());
        return response()->json($setting);
    }
}
