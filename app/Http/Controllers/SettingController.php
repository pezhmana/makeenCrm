<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isJson;

class SettingController extends Controller
{
    public function index($key = null){
        if($key){
            $setting = Setting::where('key', $key)->first();
            if (Str::isJson($setting->value)) {
                $setting = json_decode($setting->value, true);
            }
        }
        else{
            $setting = Setting::paginate(10);
        }
        return response()->json($setting);

    }

    public function edit($key = null ,EditSettingRequest $request ){
        $setting = Setting::where('key', $key)->update(['value'=>$request->value]);
        return response()->json('تغییرات با موفقیت انجام شد');
    }
}
