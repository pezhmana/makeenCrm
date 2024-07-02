<?php

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

Route::post('login', [UserController::class, 'login'])->name('login');
Route::get('logout', [UserController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum')->name('me');


Route::group(['prefix'=>'users' , 'as'=>'user' , 'middleware'=>'auth:sanctum'],function(){
    Route::post('create', [UserController::class, 'create'])->withoutMiddleware('auth:sanctum')->name('create');
    Route::put('edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('selfedit', [UserController::class, 'selfedit'])->name('selfedit');
    Route::delete('delete/{id}', [UserController::class, 'delete'])->name('delete');
    Route::post('selfdelete', [UserController::class, 'selfdelete'])->name('selfdelete');
    Route::post('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('index/{id?}', [UserController::class, 'index'])->name('index');
    Route::post('restorePassword', [UserController::class, 'restorePassword'])->name('restorePassword');
    Route::post('editpassword', [UserController::class, 'editPassword'])->name('editPassword');
});

Route::group(['prefix'=>'setting' , 'as'=>'setting'],function(){
   Route::post('create', [SettingController::class, 'create'])->name('create');
   Route::put('edit/{id}', [SettingController::class, 'edit'])->name('edit');
   Route::delete('delete{id}', [SettingController::class, 'delete'])->name('delete');
   Route::get('index/{id?}', [SettingController::class, 'index'])->name('index');
});
Route::group(['prefix'=>'products' , 'as'=>'products'],function (){
   Route::post('create',[ProductController::class,'create'])->name('create');
   Route::put('edit',[ProductController::class,'edit'])->name('edit');
   Route::delete('delete',[ProductController::class,'delete'])->name('delete');
   Route::get('index',[ProductController::class,'index'])->name('index');
});
Route::group(['prefix'=>'orders' , 'as'=>'orders'],function (){
    Route::post('create',[OrderController::class,'create'])->name('create');
    Route::put('edit',[OrderController::class,'edit'])->name('edit');
    Route::delete('delete',[OrderController::class,'delete'])->name('delete');
    Route::get('index',[OrderController::class,'index'])->name('index');
});
Route::group(['prefix'=>'posts' , 'as'=>'posts'],function (){
    Route::post('create',[PostController::class,'create'])->name('create');
    Route::put('edit',[PostController::class,'edit'])->name('edit');
    Route::delete('delete',[PostController::class,'delete'])->name('delete');
    Route::get('index',[PostController::class,'index'])->name('index');
});

