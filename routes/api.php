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
        Route::put('selfedit', [UserController::class, 'selfedit'])->middleware('auth:sanctum')->name('selfedit');
        Route::post('profile', [UserController::class, 'profile'])->name('profile');
    });

    route::group(['prefix'=>'admin' , 'middleware'=>'auth:sanctum'], function () {
        route::post('dashboard',[UserController::class , 'adminDashboard'])->middleware('admin.dashboard')->name('dashboard');
        route::post('login',[UserController::class ,'adminLogin'])->withoutMiddleware('auth:sanctum')->name('login');
<<<<<<< HEAD
        route::post('assign',[UserController::class ,'adminAssign'])->middleware('admin.assign')->name('assign');
        route::get('userindex',[UserController::class ,'adminIndex'])->middleware('admin.userindex')->name('index');
        route::get('orderindex',[UserController::class,'adminOrderIndex'])->middleware('admin.orderindex')->name('orderIndex');
=======
        route::post('assign',[UserController::class ,'adminAssign'])->name('assign');
        route::get('userindex',[UserController::class ,'adminIndex'])->name('index');
        route::get('orderindex',[UserController::class,'adminOrderIndex'])->name('orderIndex');
        route::post('reports',[UserController::class,'adminReports'])->name('reports');
        route::post('answercomment',[CommentController::class ,'answer'])->name('answer');
>>>>>>> 17f1e9a20520097509839ad5237dee8ad0e18ec1
    });


Route::group(['prefix'=>'users' , 'as'=>'user' , 'middleware'=>'auth:sanctum'],function(){
    Route::get('index/{id?}', [UserController::class, 'index'])->middleware('user.index')->name('index');
    Route::put('edit/{id}', [UserController::class, 'edit'])->middleware('user.edit')->name('edit');
    Route::delete('delete/{id}', [UserController::class, 'delete'])->middleware('user.delete')->name('delete');
    Route::post('editpassword', [UserController::class, 'editPassword'])->middleware('user.editpassword')->name('editPassword');
});


Route::group(['prefix'=>'setting' , 'as'=>'setting'],function(){
   Route::get('index/{key?}', [SettingController::class, 'index'])->middleware('setting.index')->name('index');
   Route::put('edit/{key}', [SettingController::class, 'edit'])->middleware('setting.edit')->name('edit');
//   Route::post('create', [SettingController::class, 'create'])->name('create');
//   Route::delete('delete{id}', [SettingController::class, 'delete'])->name('delete');
});
Route::group(['prefix'=>'products' , 'as'=>'products' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\ProductController::class, 'create'])->middleware('products.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\ProductController::class, 'index'])->middleware('products.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\ProductController::class, 'edit'])->middleware('products.edit')->name('edit');
    Route::Post('addmedia/{id}',[ProductController::class , 'addmedia'])->middleware('products.addmedia')->name('addmedia');
    Route::delete('delete/{id}', [\App\Http\Controllers\ProductController::class, 'delete'])->middleware('products.delete')->name('delete');

});

Route::group(['prefix'=>'posts' , 'as'=>'posts' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\PostController::class, 'create'])->middleware('posts.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\PostController::class, 'index'])->middleware('posts.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\PostController::class, 'edit'])->middleware('posts.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\PostController::class, 'delete'])->middleware('posts.delete')->name('delete');

});

Route::group(['prefix'=>'orders' , 'as'=>'orders' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\OrderController::class, 'create'])->middleware('orders.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\OrderController::class, 'index'])->middleware('orders.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\OrderController::class, 'edit'])->middleware('orders.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\OrderController::class, 'delete'])->middleware('orders.delete')->name('delete');

});

route::group(['prefix'=>'comments' , 'as'=>'comments' , 'middleware'=>'auth:sanctum'],function(){
<<<<<<< HEAD
    Route::post('create', [CommentController::class, 'create'])->middleware('comments.create')->name('create');
    Route::post('index', [CommentController::class, 'index'])->middleware('comments.index')->name('index');
    route::delete('delete/{id}', [CommentController::class, 'delete'])->middleware('comments.delete')->name('delete');
=======
    Route::post('create', [CommentController::class, 'create'])->name('create');
    Route::post('index', [CommentController::class, 'index'])->name('index');
    route::delete('delete/{id}', [CommentController::class, 'delete'])->name('delete');
    route::post('like/{id}',[CommentController::class , 'like'])->name('like');
    route::post('dislike/{id}',[CommentController::class , 'dislike'])->name('like');
>>>>>>> 17f1e9a20520097509839ad5237dee8ad0e18ec1
});

Route::group(['prefix'=>'teachers' , 'as'=>'teachers' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\TeacherController::class, 'create'])->middleware('teachers.create')->name('create');
    Route::get('index/{id?}', [\App\Http\Controllers\TeacherController::class, 'index'])->middleware('teachers.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\TeacherController::class, 'edit'])->middleware('teachers.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\TeacherController::class, 'delete'])->middleware('teachers.delete')->name('delete');

});


route::group(['prefix'=>'categories' , 'as'=>'categories','middleware'=>'auth:sanctum'],function(){
    Route::post('create', [CategoryController::class, 'create'])->middleware('categories.create')->name('create');
    Route::post('add/{id}', [CategoryController::class, 'add'])->middleware('categories.add')->name('add');
    Route::get('index/{id?}', [CategoryController::class, 'index'])->middleware('categories.index')->name('index');
    Route::put('edit/{id}', [CategoryController::class, 'edit'])->middleware('categories.edit')->name('edit');
    Route::delete('delete/{id}', [CategoryController::class, 'delete'])->middleware('categories.delete')->name('delete');



});

Route::group(['prefix'=>'tickets' , 'as'=>'tickets' , 'middleware'=>'auth:sanctum'],function(){
<<<<<<< HEAD
    Route::post('create', [\App\Http\Controllers\TicketController::class, 'create'])->middleware('tickets.create')->name('create');
    Route::get('index', [\App\Http\Controllers\TicketController::class, 'index'])->middleware('tickets.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\TicketController::class, 'edit'])->middleware('tickets.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\TicketController::class, 'delete'])->middleware('tickets.delete')->name('delete');
=======
    Route::post('create', [\App\Http\Controllers\TicketController::class, 'create'])->name('create');
    Route::get('index', [\App\Http\Controllers\TicketController::class, 'index'])->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\TicketController::class, 'edit'])->name('edit');
    Route::get('userticket', [\App\Http\Controllers\TicketController::class, 'userTicket'])->name('index');
    Route::delete('delete/{id}', [\App\Http\Controllers\TicketController::class, 'delete'])->name('delete');
>>>>>>> 17f1e9a20520097509839ad5237dee8ad0e18ec1


});

Route::group(['prefix'=>'messages' , 'as'=>'messages' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [\App\Http\Controllers\MessageController::class, 'create'])->middleware('massages.create')->name('create');
    Route::get('index', [\App\Http\Controllers\MessageController::class, 'index'])->middleware('massages.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\MessageController::class, 'edit'])->middleware('massages.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\MessageController::class, 'delete'])->middleware('massages.delete')->name('delete');



});



route::group(['prefix'=>'label','as'=>'label','middleware'=>'auth:sanctum'],function(){
    Route::post('create', [LabelController::class, 'create'])->middleware('label.create')->name('create');
    Route::post('addFave', [LabelController::class, 'addFave'])->middleware('label.addFave')->name('add');
    Route::post('unFave',[LabelController::class, 'unFave'])->middleware('label.unFave')->name('unFave');
    Route::get('index/{id?}', [LabelController::class, 'index'])->middleware('label.index')->name('index');
    Route::put('edit/{id}', [LabelController::class, 'edit'])->middleware('label.edit')->name('edit');
    Route::delete('delete/{id}', [LabelController::class, 'delete'])->middleware('label.delete')->name('delete');

});

route::group(['prefix'=>'rating', 'as'=>'rating' , 'middleware'=>'auth:sanctum'] ,function () {
    route::post('add/{id}', [\App\Http\Controllers\RatingController::class, 'addrating'])->middleware('rating.add')->name('addrating');
//    route::get('index/{id}', [\App\Http\Controllers\RatingController::class, 'index'])->name('index');
});

route::group(['prefix'=>'discount','as'=>'discount','middleware'=>'auth:sanctum'],function (){
    route::post('create',[\App\Http\Controllers\DiscountController::class,'create'])->middleware('discount.create')->name('create');
    route::get('index',[\App\Http\Controllers\DiscountController::class,'index'])->middleware('discount.index')->name('index');
    Route::put('edit/{id}', [\App\Http\Controllers\DiscountController::class, 'edit'])->middleware('discount.edit')->name('edit');
    Route::delete('delete/{id}', [\App\Http\Controllers\DiscountController::class, 'delete'])->middleware('discount.delete')->name('delete');
});
