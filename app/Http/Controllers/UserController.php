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
use Spatie\Permission\Traits\HasRoles;



class UserController extends Controller
{
    use HasRoles;
    public function create(UserCreateRequest $request){
        $User = User::create(
            $request->merge(['password' => Hash::make($request->password)])
                ->toArray());
        $User->assignRole('user');
        return response()->json('اطلاعات کاربر با موفقیت ثبت شد');
    }

    public function login(UserCreateRequest $request){
        $User =User::select(['id','email', 'password'])
            ->where('email', $request->email)
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
        return response()->json($User);
    }

    public function delete(UserCreateRequest $request, $id = Null){
        $User = $request->user();
        if($User->hasAnyRole(['admin','super_admin'])){
            User::destroy($id);
        }
        else{
            if(!Hash::check($request->password , $User->password)){
                return response()->json('رمز عبور اشتباه است');
            }
            else{
                User::where('id', $User->id)->delete();
            }
        }
        return response()->json('کاربر با موفقیت حذف شد');
    }

    public function index(UserCreateRequest $request , $id = Null){
        $User = new User();
        if($id != Null){
            $User = $User->find($id)->first();
        }
        $User = $User->OrderBy('id' , 'DESC')->paginate(10);
        return response()->json($User);
    }

    public function editPassword(UserCreateRequest $request){
        $User = $request->user();
        if($User->pasword == $request->oldPassword){
            return response()->json('رمز اشتباه است');
        }
        else{
            User::where('id',$request->id)->update($request->only('password'));
            Mail::to($User->email)->send(new restorePasswordMail($User));
            return response()->json('رمز با موفقیت تغییر یافت');
        }
    }

    public function restorePassword(Request $request){
        $User = $request->user();
        $code = random_int(0 , 10000);
        if($request->sent){
            Mail::to($User->email)->send(new restorePasswordMail($code));
            if(!$code == $request->code){
                return response()->json('کد تاییدیه اشتباه است');
            }
            else{
                User::where('id', $User->id)
                    ->update(['pasword' => Hash::make($request->password)]);
                return response()->json('رمز عبور با موفقیت تغییر یافت');
            }
        }
    }
}
