<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\OrderIndexResource;
use App\Http\Resources\OrderResourceCollection;
use App\Http\Resources\ProductResourceCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Mail\restorePasswordMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\User;
use http\Env\Response;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use function Laravel\Prompts\table;


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

    public function login(Request $request){
        $type = $request->type;
        if($type == 'signin'){
        $User =User::select(['id','phone', 'password'])
            ->where('phone', $request->phone)
            ->first();
        if(!$User){
            return response()->json('کاربر پیدا نشد');
        }
        if(!hash::check($request->password ,$User->password )){
            return response()->json('رمز ورود اشتباه است');
        }
        $token = $User->createToken('token')->plainTextToken;
        return response()->json($token);
        }
        if($type == 'request'){
            $number=rand(10000,99999);
            $user = User::where('phone', $request->phone)->first();
            if($user){
            DB::table('code')->insert([
                'code'=>$number,
                'phone'=>$request->phone
            ]);
            return response()->json('کد تاییده با موفقیت ارسال شد');
            }
            else{
                return Response()->json('شماره مورد نظر وجود ندارد');
            }
        }
        if($type == 'received'){
            $phoneCodes = DB::table('code')->where('phone', $request->phone)->where('code',$request->code);
            if($phoneCodes->exists()){
                $User = User::where('phone',$request->phone)->first();
                $token = $User->createToken('token')->plainTextToken;
                DB::table('code')->where('phone' , $request->phone)->delete();
                return response()->json($token);
            }
            else{
                return response()->json('کد یا شماره موبایل نامعتبر است');
            }
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json('کاربر با موفقیت خارج شد');
    }

    public function me(){
        $User = Auth::user();

        if(Request('dashboard')){
            $orders = $User->orders()->select(['id','created_at','sum'])->get();
            $orders_count = $User->orders()->count();
            $orders_count = "$orders_count : مجموع دوره های خریداری شده ";
            return Response()->json([$orders,$orders_count]);
        }

        if(Request('orders')){
            $User = $User->orders()->get();
        }
        if(Request('products')){
            $User = $User->orderProducts();
        }
        if(Request('like')){
            $User = $User->labelProducts();
        }
        if(Request('ticket')){
            $user = Auth::user();
            $ticket = new Ticket();
            $ticket = $user->tickets()->get();
            $countOpen = $user->tickets()->where('status','open')->count();
            $countRun = $user->tickets()->where('status','running')->count();
            $countClose = $user->tickets()->where('status','closed')->count();
            $countAnswer = $user->tickets()->where('status','answered')->count();
            $counts = [
                'open' => $countOpen,
                'running' => $countRun,
                'closed' => $countClose,
                'answered' => $countAnswer,
            ];

            return response()->json([
                'tickets' => $ticket,
                'counts' => $counts,
            ]);
        }
        return response()->json([$User]);
    }

    public function delete(UserCreateRequest $request, $id = Null){
            User::destroy($id);
        return response()->json('کاربر با موفقیت حذف شد');
    }


    public function index(UserCreateRequest $request , $id = Null){
        $User = new User();
        if($id != Null){
            $User = $User->find($id)->first();
            return response()->json($User);

        }
        if(Request('students')){
            $role =Role::findByName('user' , 'web')->count();
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

    public function edit(EditUserRequest $request , $id){
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

    public function adminDashboard(){

            $role = User::withCount('roles')
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'student');
                })
                ->count();
            $role = "تعداد دانش اموزان $role";

            $sales = Order::all()
                ->groupBy(function ($date) {
                    return $date->created_at->format('Y-m-d');
                })
                ->map(function ($item) {
                    return [
                        "date" => $item->first()->created_at,
                        "total" => $item->sum('sum'),
                    ];
                });

            $response = [];
            foreach ($sales as $date => $sale) {
                $response[] = [
                    'date' => $sale['date']->format('Y-m-d'),
                    'total' => $sale['total'],
                ];
            }



            return response()->json([$response,$role]);
        }



    public function adminLogin(UserCreateRequest $request)
    {
        $user = User::where('phone',$request->phone)->first();
        if(!hash::check($request->password ,$user->password )){
            return response()->json('رمز ورود اشتباه است');
        }else{
            if($user->hasRole('admin')){
                $token =$user->createToken('token')->plainTextToken;
            }else{
                $token = 'شما اجازه مورد نیاز را ندارید';
            }
        }
        return response()->json($token);
    }

    public function adminAssign(UserCreateRequest $request){
        $user = User::where('phone',$request->phone)->first();
        $user= $user->assignRole('admin');
        return response()->json('رول ادمین با موفقیت داده شد');
    }

    public function adminIndex(){
        $user = new UserResourceCollection(User::all());
        return response()->json($user);
    }

    public function adminOrderIndex(){
        $order = new OrderResourceCollection(Order::all());
        return response()->json($order);
    }

    public function adminReports(Request $request){
        $report = new ProductResourceCollection(Product::all());
        return response()->json($report);
    }
}
