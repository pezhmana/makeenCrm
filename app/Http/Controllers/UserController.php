<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Mail\restorePasswordMail;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;



class UserController extends Controller
{
    use HasRoles;
    public function create(UserCreateRequest $request){
        $User = User::create(
            $request->merge(['password' => Hash::make($request->password)])
                ->toArray());
//        $User->assignRole('user');
        if ($request->image) {
            $User->addMediaFromRequest('image')->toMediaCollection('avatar');
        }
        return response()->json('اطلاعات کاربر با موفقیت ثبت شد');
    }

    public function login(UserCreateRequest $request){
        $User =User::select(['id','email', 'password' , 'username'])
            ->where('email', $request->email)->orWhere('username' , $request->email)
            ->first(); //, 'phone_number'
        if(!$User){
            return response()->json('کاربر پیدا نشد');
        }
        if(!hash::check($request->password ,$User->password )){
            return response()->json('رمز ورود اشتباه است');
        }
        $token = $User->createToken('token')->plainTextToken;
        return response()->json($token);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json('کاربر با موفقیت خارج شد');
    }

    public function me(){
        $User = Auth::user();
        $media = $User->getMedia('avatar');
        $url = $media[0]->getUrl();
        return response()->json([$User, $url]);
    }

    public function delete(UserCreateRequest $request, $id = Null){
            User::destroy($id);
        return response()->json('کاربر با موفقیت حذف شد');
    }

    public function selfdelete(Request $request){
        $User = $request->user();
        if(!Hash::check($request->password , $User->password)){
            return response()->json('رمز عبور اشتباه است');
        }
        else{
            User::where('id', $User->id)->delete();
            return response()->json('کاربر با موفقیت حذف شد');
        }
    }

    public function index(UserCreateRequest $request , $id = Null){
        $User = new User();
        if($id != Null){
            $User = $User->find($id)->first();
            return response()->json($User);

        }
        $User = $User->OrderBy('id' , 'DESC')->paginate(10);
        return response()->json($User);
    }

    public function editPassword(Request $request){
        $User = $request->user();
        if(!hash::check($request->password ,$User->password)){
            return response()->json('رمز اشتباه است');
        }
        User::where('id', $User->id)
            ->update(['password' => Hash::make($request->new_password)]);
            Mail::to($User->email)->send(new restorePasswordMail($User));
            return response()->json('رمز با موفقیت تغییر یافت');

    }

    public function restorePassword(Request $request){
        $User = $request->user();
        $code = random_int(10000 , 99999);
        if($request->sent){
            Mail::to($User->email)->send(new restorePasswordMail($code));
            if(!$code == $request->code){
                return response()->json('کد تاییدیه اشتباه است');
            }
            else{
                User::where('id', $User->id)
                    ->update(['password' => Hash::make($request->password)]);
                return response()->json('رمز عبور با موفقیت تغییر یافت');
            }
        }
    }

    public function edit(Request $request , $id){
        $User = User::find($id);
        $User->update($request->all());
        return response()->json($User);
    }

    public function selfedit(Request $request ){
        $User = $request->user();
        User::where('id', $User->id)->update($request->all());
        return response()->json($User);
    }

    public function profile(Request $request){
        $User = $request->user();
        if($request->image){
            $delete = Media::where('model_type' , 'App\Models\User')->where('model_id' , $User->id)->delete();
            $media = $User->addMediaFromRequest('image')->toMediaCollection('avatar');
        }
        return response()->json($media);
    }

}
