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
        Route::post('sign', [UserController::class, 'sign'])->name('login');
        Route::get('logout', [UserController::class, 'logout'])->middleware('auth:sanctum|permission:auth.index')->name('logout');
        route::get('dashboard', [UserController::class, 'dashboard'])->middleware('auth:sanctum|permission:auth.dashboard')->name('dashboard');
        Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum|permission:auth.me')->name('me');
//        Route::post('create', [UserController::class, 'create'])->withoutMiddleware('auth:sanctum')->name('create');
        Route::put('selfedit', [UserController::class, 'selfedit'])->middleware('auth:sanctum|permission:auth.selfedit')->name('selfedit');
//        Route::post('profile', [UserController::class, 'profile'])->name('profile');
    });

    route::group(['prefix'=>'admin' , 'middleware'=>'auth:sanctum'], function () {
        route::post('dashboard',[UserController::class , 'adminDashboard'])->middleware('permission:admin.dashboard')->name('dashboard');
        route::post('login',[UserController::class ,'adminLogin'])->withoutMiddleware('auth:sanctum')->name('login');
        route::post('assign',[UserController::class ,'adminAssign'])->middleware('permission:admin.assign')->name('assign');
        route::get('userindex',[UserController::class ,'adminIndex'])->middleware('permission:admin.userindex')->name('index');
        route::get('orderindex',[UserController::class,'adminOrderIndex'])->middleware('permission:admin.orderindex')->name('orderIndex');
        route::post('reports',[UserController::class,'adminReports'])->middleware('permission:admin.reports')->name('reports');
        route::post('answercomment',[CommentController::class ,'answer'])->middleware('permission:admin.answercomment')->name('answer');
    });


Route::group(['prefix'=>'users' , 'as'=>'user' , 'middleware'=>'auth:sanctum'],function(){
    Route::get('index/{id?}', [UserController::class, 'index'])->middleware('permission:user.index')->name('index');
    Route::put('edit/{id}', [UserController::class, 'edit'])->middleware('permission:user.edit')->name('edit');
    Route::delete('delete/{id}', [UserController::class, 'delete'])->middleware('permission:user.delete')->name('delete');
    Route::post('editpassword', [UserController::class, 'editPassword'])->middleware('permission:user.editpassword')->name('editPassword');
});

Route::group(['prefix'=>'setting' , 'as'=>'setting'],function(){
   Route::get('index/{key?}', [SettingController::class, 'index'])->name('index');
   Route::put('edit/{key}', [SettingController::class, 'edit'])->middleware('auth:sanctum|permission:setting.edit')->name('edit');
//   Route::post('create', [SettingController::class, 'create'])->name('create');
//   Route::delete('delete{id}', [SettingController::class, 'delete'])->name('delete');
});
Route::group(['prefix'=>'products' , 'as'=>'products' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\ProductController::class, 'create'])->middleware('permission:products.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\ProductController::class, 'index'])->withoutMiddleware('auth:sanctum')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\ProductController::class, 'edit'])->middleware('permission:products.edit')->name('edit');
    Route::Post('addmedia/{id}',[ProductController::class , 'addmedia'])->middleware('permission:products.addmedia')->name('addmedia');
    Route::delete('delete/{id}', [\App\Http\Controllers\ProductController::class, 'delete'])->middleware('permission:products.delete')->name('delete');

});

Route::group(['prefix'=>'posts' , 'as'=>'posts' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\PostController::class, 'create'])->middleware('permission:posts.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\PostController::class, 'index'])->withoutMiddleware('auth:sanctum')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\PostController::class, 'edit'])->middleware('permission:posts.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\PostController::class, 'delete'])->middleware('permission:posts.delete')->name('delete');
});

Route::group(['prefix'=>'orders' , 'as'=>'orders' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\OrderController::class, 'create'])->middleware('permission:orders.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\OrderController::class, 'index'])->middleware('permission:orders.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\OrderController::class, 'edit'])->middleware('permission:orders.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\OrderController::class, 'delete'])->middleware('permission:orders.delete')->name('delete');

});

route::group(['prefix'=>'comments' , 'as'=>'comments' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [CommentController::class, 'create'])->middleware('permission:comments.create')->name('create');
    Route::post('index', [CommentController::class, 'index'])->withoutMiddleware('auth:sanctum')->name('index');
    route::delete('delete/{id}', [CommentController::class, 'delete'])->middleware('permission:comments.delete')->name('delete');
    route::post('like/{id}',[CommentController::class , 'like'])->middleware('permission:comments.like')->name('like');
    route::post('dislike/{id}',[CommentController::class , 'dislike'])->middleware('permission:comments.dislike')->name('like');
});

//Route::group(['prefix'=>'teachers' , 'as'=>'teachers' , 'middleware'=>'auth:sanctum'],function(){
//    Route::post('create', [\App\Http\Controllers\TeacherController::class, 'create'])->name('create');
//    Route::get('index/{id?}', [\App\Http\Controllers\TeacherController::class, 'index'])->withoutMiddleware('auth:sanctum')->name('index');
//    Route::put('edit/{id}', [\App\Http\Controllers\TeacherController::class, 'edit'])->name('edit');
//    Route::delete('delete/{id}', [\App\Http\Controllers\TeacherController::class, 'delete'])->name('delete');
//
//});


route::group(['prefix'=>'categories' , 'as'=>'categories','middleware'=>'auth:sanctum'],function(){
    Route::post('create', [CategoryController::class, 'create'])->middleware('permission:categories.create')->name('create');
    Route::post('add/{id}', [CategoryController::class, 'add'])->middleware('permission:categories.add')->name('add');
    Route::get('index/{id?}', [CategoryController::class, 'index'])->withoutMiddleware('auth:sanctum')->name('index');
    Route::put('edit/{id}', [CategoryController::class, 'edit'])->middleware('permission:categories.edit')->name('edit');
    Route::delete('delete/{id}', [CategoryController::class, 'delete'])->middleware('permission:categories.delete')->name('delete');



});

Route::group(['prefix'=>'tickets' , 'as'=>'tickets' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\TicketController::class, 'create'])->middleware('permission:tickets.create')->name('create');
    Route::get('index', [\App\Http\Controllers\TicketController::class, 'index'])->middleware('permission:tickets.index')->name('index');
    Route::put('edit/{id?}', [\App\Http\Controllers\TicketController::class, 'edit'])->middleware('permission:tikcets.edit')->name('edit');
    Route::get('userticket', [\App\Http\Controllers\TicketController::class, 'userTicket'])->middleware('permission:tikcets.delete')->name('index');
    Route::delete('delete/{id}', [\App\Http\Controllers\TicketController::class, 'delete'])->middleware('permission:tickets.usertickets')->name('delete');


});

Route::group(['prefix'=>'messages' , 'as'=>'messages' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\MessageController::class, 'create'])->middleware('permission:messages.create')->name('create');
    Route::get('index', [\App\Http\Controllers\MessageController::class, 'index'])->middleware('permission:messages.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\MessageController::class, 'edit'])->middleware('permission:messages.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\MessageController::class, 'delete'])->middleware('permission:messages.delete')->name('delete');



});

Route::group(['prefix'=>'teachers' , 'as'=>'teachers' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\TeacherController::class, 'create'])->middleware('permission:teachers.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\TeacherController::class, 'index'])->withoutMiddleware('auth:sanctum')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\TeacherController::class, 'edit'])->middleware('permission:teachers.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\TeacherController::class, 'delete'])->middleware('permission:teachers.delete')->name('delete');

});


route::group(['prefix'=>'label','as'=>'label','middleware'=>'auth:sanctum'],function(){
    Route::post('create', [LabelController::class, 'create'])->middleware('permission:label.create')->name('create');
    Route::post('addFave', [LabelController::class, 'addFave'])->middleware('permission:label.addFave')->name('add');
    Route::post('unFave',[LabelController::class, 'unFave'])->middleware('permission:label.unFave')->name('unFave');
    Route::get('index/{id?}', [LabelController::class, 'index'])->middleware('permission:label.index')->name('index');
    Route::put('edit/{id}', [LabelController::class, 'edit'])->middleware('permission:label.edit')->name('edit');
    Route::delete('delete/{id}', [LabelController::class, 'delete'])->middleware('permission:label.delete')->name('delete');

});

route::group(['prefix'=>'rating', 'as'=>'rating' , 'middleware'=>'auth:sanctum'] ,function () {
    route::post('add/{id}', [\App\Http\Controllers\RatingController::class, 'addrating'])->middleware('permission:rating.add')->name('addrating');
//    route::get('index/{id}', [\App\Http\Controllers\RatingController::class, 'index'])->name('index');
});

route::group(['prefix'=>'discount','as'=>'discount','middleware'=>'auth:sanctum'],function (){
    route::post('create',[\App\Http\Controllers\DiscountController::class,'create'])->middleware('permission:discount.create')->name('create');
    route::get('index',[\App\Http\Controllers\DiscountController::class,'index'])->middleware('permission:discount.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\DiscountController::class, 'edit'])->middleware('permission:discount.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\DiscountController::class, 'delete'])->middleware('permission:discount.delete')->name('delete');
});
