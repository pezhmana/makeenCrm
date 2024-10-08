<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Http\Requests\EditUsersRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\OrderResourceCollection;
use App\Http\Resources\ProductResourceCollection;
use App\Http\Resources\UserResourceCollection;
use App\Mail\restorePasswordMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Teacher;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;




class UserController extends Controller
{
    use HasRoles;

    public function sign(UserCreateRequest $request){
            $phone = $request->phone;
            $exist = User::where('phone',$phone)->first();
        if($request->type == 'in'){
            $user =User::select(['id','phone', 'password'])->where('phone', $request->phone)->first();
            if(!$user){
                return response()->json('کاربر پیدا نشد');
            }
            if(!hash::check($request->password,$user->password)){
                return response()->json('رمز اشتباه است');
            }
                $response = $user->createToken('token')->plainTextToken;

            return response()->json($response);
        }
        if($request->type == 'up'){
            $number = rand(10000, 99999);
            // $apiKey = "lcLfdQZmeU-I1HCkmKm2VpISIkQiFTjIE9m8M6qenCk=";
            // $client = new \IPPanel\Client($apiKey);
            // $patternValues = [
            //     'code'=>$number
            // ];
            // $messageId = $client->sendPattern(
            //     "xz6aa98o0r1hace",    // pattern code
            //     "+983000505",      // originator
            //     $request->phone,  // recipient
            //     $patternValues,  // pattern values
            // );
            $user = User::create($request->toArray());

            DB::table('code')->insert([
                'code' => $number,
                'phone' => $user->phone
            ]);
                $user->assignRole('user');
                return response()->json('کد تاییده با موفقیت ارسال شد');
        }
        if($request->type == 'received') {
            $phoneCodes = DB::table('code')->where('phone', $request->phone)->where('code', $request->code);
            if ($phoneCodes->exists()) {
                $User = User::where('phone', $request->phone)->first();
                $token = $User->createToken('token')->plainTextToken;
                DB::table('code')->where('phone', $request->phone)->delete();
                return response()->json($token);
            }else{
                return response()->json('کد نامعتبر است');
            }
        }
    }


    public function forget(EditUsersRequest $request){
        $user = User::where('phone', $request->phone);
        if(!$user->exists()){
            return Response()->json('شماره تلفن وجود ندارد');
        }
        $type =$request->type;
        if($type == 'request'){
            $number = rand(10000, 99999);
            DB::table('code')->insert([
                'code' => $number,
                'phone' => $user->first()->phone
            ]);
            return response()->json('کد تاییده با موفقیت ارسال شد');
        }
        if($type == 'received'){
            $phoneCodes = DB::table('code')->where('phone', $request->phone)->where('code',$request->code);
            if($phoneCodes->exists()){
                $User = User::where('phone',$request->phone)
                    ->update(['password'=>Hash::make($request->password)]);
                $token = $user->first()->createToken('token')->plainTextToken;
                DB::table('code')->where('phone' , $request->phone)->delete();
                return response()->json($token);
            }
            else{
                return response()->json('کد نامعتبر است');
            }
        }

    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json('کاربر با موفقیت خارج شد');
    }

    public function me(){
        $User = Auth::user();
        $User->makeHidden(['permissions','roles']);
        if(Request('orders')){
            $User= $User->orders()->get();
            foreach ($User as $order) {
                $product = Product::find($order->product_id);
                if ($product) {
                    $order->product_name = $product->name;
                    $order->product_image = $product->getFirstMediaUrl('product.image');
                }
            }
            return $User;
        }

        if(Request('products')){
                $User = $User->orderProducts();
                foreach($User as $item){
                    $count=DB::table('user_video')
                        ->where('user_id',Auth::user()->id)->where('product_id',$item->id)->count();
                    $video_count = Product::find($item->id)->videos()->count();
                    $percent=round(($count/$video_count)*100,);
                    $item->percent = $percent;
            }
            if(is_numeric(Request('products'))){
                $product = Product::find(Request('products'));
                $user =Auth::user()->id;
                $purchase = Order::where('user_id',$user)->where('product_id',Request('products'));
                $chapter = Product::find(Request('products'))->chapters()->first();
                if($purchase->exists()){
                    if(Request('video')){
                        $id=Request('video');
                        $url =$chapter->videos->find($id);
                        if($url == null){
                            return response()->json('همچین ویدیو ای برای دوره وجود ندارد');
                        }
                        $user_video = DB::table('user_video')->where('user_id',$user)->where('video_id',$url->id);
                        if(!$user_video->exists()){
                            Auth::user()->videos()->attach($url->id,[   'product_id'=>$product->id]);
                        }
                        $url = $url->url;
                        return response()->json($url);
                    }
                }else{
                    $video = 'برای دسترسی باید دوره را خریداری نمایید';
                }
                return response()->json([$product,$chapter]);
            }
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

    public function delete($id = Null){
            User::find($id)->delete();
        return response()->json('کاربر با موفقیت حذف شد');
    }


    public function index($id = Null){
        $User = new User();
        if($id != Null){
            $User = $User->find($id)->first();
            return response()->json($User);

        }
        if(Request('students')){
            $User =Role::findByName('user' , 'web')->count();
        }
        $User = $User->OrderBy('id' , 'DESC')->paginate(10);
        return response()->json($User);
    }

//    public function editPassword(Request $request){
//        $User = $request->user();
//        if(!hash::check($request->password ,$User->password)){
//            return response()->json('رمز اشتباه است');
//        }
//        User::where('id', $User->id)
//            ->update(['password' => Hash::make($request->new_password)]);
//            Mail::to($User->email)->send(new restorePasswordMail($User));
//            return response()->json('رمز با موفقیت تغییر یافت');
//
//    }

//    public function edit(Request $request , $id){
//        $User = User::find($id);
//        $User->update($request->all());
//        return response()->json($User);
//    }

    public function selfedit(EditUsersRequest $request ){
        $User = $request->user();
        User::where('id', $User->id)->update($request->all());
        return response()->json('اطلاعات شما با موفقیت تغییر یافت');
    }


    public function dashboard()
    {
        $user =  Auth::user();
            $orders = $user->orders()->select(['id','created_at','sum'])->take(4)->orderByDesc('created_at')->get();
            $orders_count = $user->orders()->count();

            $compelete = $user->compelete();

            $product_id = DB::table('user_video')
                ->select(['product_id','created_at'])
                ->where('user_id',$user->id)
                ->take(4)->orderByDesc('created_at')->get()->toArray();
            $latest_course=[];
            foreach ($product_id as $course){
                $product=Product::find($course->product_id);
                $latest_course[]=[
                    'product_name'=>$product->name,
                    'teacher_name'=>Teacher::find($product->teacher_id)->name,
                    'created_at'=>$course->created_at
                ];
            }

            $percent=[];
            $recent_products = collect($user->orderProducts())->take(5);
            foreach($recent_products as $item){
                $count=DB::table('user_video')
                    ->where('user_id',Auth::user()->id)->where('product_id',$item->id)->count();
                $video_count = Product::find($item->id)->videos()->count();
                $percent[]=[
                    'product_name'=>$item->name,
                    'percent'=>round(($count/$video_count)*100,)
                ];
            }



            return Response()->json([
                'latest_orders'=>$orders,
                'user_orders'=>$orders_count,
                'compelete'=>$compelete,
                'latest_course'=>$latest_course,
                'watchtime_chart'=>$percent

            ]);

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

            $product =new Product();
        $ordersCount = $product->has('orders')->count();
        $product_count = $product->count();

//       $categ=[];
//       $category = Category::with('products')->get();
//       foreach ($category as $cat) {
//           $categ[]=[
//             'category_name'=>$cat->name,
//               'percent'=>(array_sum($cat->ProductOrder())/Order::all()->count())*100,
//           ];
//       }

        $products=[];
       foreach ($product->take(10)->get() as $p) {
           $products[] = [
               'product_name' => $p->name,
               'percent' => ($p->orders()->count()/Order::all()->count())*100  .'%',
           ];
       }
       $rating=[];
        $rate= Product::take(4)->get();
        foreach ($rate as $rates) {
            $rating[]=[
                'product_name' => $rates->name,
                'rating'=>($rates->comments->avg('rating'))*20 .'%',
            ];
        }






            return response()->json([
                'weekly_sales'=>$response,
                'student_count'=>$role,
                'product_sale'=>"$ordersCount / $product_count",
                'product_rating'=>$rating,
                'chart_most'=>$products,

            ]);
        }



    public function adminLogin(EditUsersRequest $request)
    {
        $user = User::where('phone',$request->phone)->first();
        if(!hash::check($request->password ,$user->password )){
            return response()->json('رمز ورود اشتباه است');
        }else{
            if($user->hasRole('admin')){
                $token =$user->createToken('admin')->plainTextToken;
            }else{
                $token = 'شما اجازه مورد نیاز را ندارید';
            }
        }
        return response()->json($token);
    }

    public function adminAssign(Request $request){
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

    public function adminReports(EditUsersRequest $request){
        $report = new ProductResourceCollection(Product::all());
        return response()->json($report);
    }

    public function exportProduct($which,$id)
    {
        if($which == 'report'){
            $product = Product::find($id);
            $jsonData = new ProductResourceCollection(collect([$product]));
        }
        if($which == 'order'){
            $order = Order::find($id);
            $jsonData = new OrderResourceCollection(collect([$order]));
        }
        $jsonData = $jsonData->toJson();
        return Excel::download(new ProductsExport($jsonData), 'data.xlsx');
    }
}
