<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});






    route::group(['prefix' => 'auth'], function () {
        Route::post('login', [UserController::class, 'login'])->name('login');
        Route::get('logout', [UserController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
        Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum')->name('me');
        Route::post('create', [UserController::class, 'create'])->withoutMiddleware('auth:sanctum')->name('create');
        Route::put('selfedit', [UserController::class, 'selfedit'])->name('selfedit');
        Route::post('profile', [UserController::class, 'profile'])->name('profile');
    });

    route::group(['prefix'=>'admin' , 'middleware'=>'auth:sanctum'], function () {
        route::post('dashboard',[UserController::class , 'adminDashboard'])->name('dashboard');
        route::post('login',[UserController::class ,'adminLogin'])->withoutMiddleware('auth:sanctum')->name('login');
        route::post('assign',[UserController::class ,'adminAssign'])->name('assign');
        route::get('userindex',[UserController::class ,'adminIndex'])->name('index');
        route::get('orderindex',[UserController::class,'adminOrderIndex'])->name('orderIndex');
    });


Route::group(['prefix'=>'users' , 'as'=>'user' , 'middleware'=>'auth:sanctum'],function(){
    Route::get('index/{id?}', [UserController::class, 'index'])->name('index');
    Route::put('edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [UserController::class, 'delete'])->name('delete');
    Route::post('editpassword', [UserController::class, 'editPassword'])->name('editPassword');
});

Route::group(['prefix'=>'setting' , 'as'=>'setting'],function(){
   Route::get('index/{key?}', [SettingController::class, 'index'])->name('index');
   Route::put('edit/{key}', [SettingController::class, 'edit'])->name('edit');
//   Route::post('create', [SettingController::class, 'create'])->name('create');
//   Route::delete('delete{id}', [SettingController::class, 'delete'])->name('delete');
});
Route::group(['prefix'=>'products' , 'as'=>'products' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\ProductController::class, 'create'])->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\ProductController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\ProductController::class, 'edit'])->name('edit');
    Route::Post('addmedia/{id}',[ProductController::class , 'addmedia'])->name('addmedia');
    Route::delete('delete/{id}', [\App\Http\Controllers\ProductController::class, 'delete'])->name('delete');

});

Route::group(['prefix'=>'posts' , 'as'=>'posts' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\PostController::class, 'create'])->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\PostController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\PostController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\PostController::class, 'delete'])->name('delete');

});

Route::group(['prefix'=>'orders' , 'as'=>'orders' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\OrderController::class, 'create'])->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\OrderController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\OrderController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\OrderController::class, 'delete'])->name('delete');

});

route::group(['prefix'=>'comments' , 'as'=>'comments' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [CommentController::class, 'create'])->name('create');
    Route::post('index', [CommentController::class, 'index'])->name('index');
    route::delete('delete/{id}', [CommentController::class, 'delete'])->name('delete');
});

Route::group(['prefix'=>'teachers' , 'as'=>'teachers' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\TeacherController::class, 'create'])->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\TeacherController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\TeacherController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\TeacherController::class, 'delete'])->name('delete');

});


route::group(['prefix'=>'categories' , 'as'=>'categories','middleware'=>'auth:sanctum'],function(){
    Route::post('create', [CategoryController::class, 'create'])->name('create');
    Route::post('add/{id}', [CategoryController::class, 'add'])->name('add');
    Route::get('index/{id?}', [CategoryController::class, 'index'])->name('index');
    Route::put('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [CategoryController::class, 'delete'])->name('delete');



});

Route::group(['prefix'=>'tickets' , 'as'=>'tickets' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\TicketController::class, 'create'])->name('create');
    Route::get('index', [\App\Http\Controllers\TicketController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\TicketController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\TicketController::class, 'delete'])->name('delete');


});

Route::group(['prefix'=>'messages' , 'as'=>'messages' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\MessageController::class, 'create'])->name('create');
    Route::get('index', [\App\Http\Controllers\MessageController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\MessageController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\MessageController::class, 'delete'])->name('delete');



});

Route::group(['prefix'=>'teachers' , 'as'=>'teachers' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\TeacherController::class, 'create'])->name('create');
    Route::get('index', [\App\Http\Controllers\TeacherController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\TeacherController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\TeacherController::class, 'delete'])->name('delete');

});


route::group(['prefix'=>'label','as'=>'label','middleware'=>'auth:sanctum'],function(){
    Route::post('create', [LabelController::class, 'create'])->name('create');
    Route::post('addFave', [LabelController::class, 'addFave'])->name('add');
    Route::post('unFave',[LabelController::class, 'unFave'])->name('unFave');
    Route::get('index/{id?}', [LabelController::class, 'index'])->name('index');
    Route::put('edit/{id}', [LabelController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [LabelController::class, 'delete'])->name('delete');

});

route::group(['prefix'=>'rating', 'as'=>'rating' , 'middleware'=>'auth:sanctum'] ,function () {
    route::post('add/{id}', [\App\Http\Controllers\RatingController::class, 'addrating'])->name('addrating');
//    route::get('index/{id}', [\App\Http\Controllers\RatingController::class, 'index'])->name('index');
});

route::group(['prefix'=>'discount','as'=>'discount','middleware'=>'auth:sanctum'],function (){
    route::post('create',[\App\Http\Controllers\DiscountController::class,'create'])->name('create');
    route::get('index',[\App\Http\Controllers\DiscountController::class,'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\DiscountController::class, 'edit'])->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\DiscountController::class, 'delete'])->name('delete');
});
