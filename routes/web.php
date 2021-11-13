<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\ProfilePageController;
use App\Http\Controllers\User\MessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix'=>'user','middleware'=>['auth']],function (){
    Route::get('dashboard',[UserDashboardController::class,'index']);
    Route::post('comment/share',[UserDashboardController::class,'commentShare']);
    Route::post('comment/delete',[UserDashboardController::class,'commentDelete']);
    Route::post('comment/update',[UserDashboardController::class,'commentUpdate']);
    Route::post('reply/share',[UserDashboardController::class,'replyShare']);
    Route::post('reply/delete',[UserDashboardController::class,'replyDelete']);
    Route::post('reply/update',[UserDashboardController::class,'replyUpdate']);
    Route::post('comment/like',[UserDashboardController::class,'commentLike']);
    Route::post('reply/like',[UserDashboardController::class,'replyLike']);
    Route::post('reply/dislike',[UserDashboardController::class,'replyDislike']);
    Route::post('comment/dislike',[UserDashboardController::class,'commentDislike']);
    Route::post('friend/add',[ProfilePageController::class,'addFriend']);
    Route::post('friend/back',[ProfilePageController::class,'backRequest']);
    Route::post('friend/accept',[ProfilePageController::class,'accept']);
    Route::post('friend/decline',[ProfilePageController::class,'decline']);
    Route::post('friend/remove',[ProfilePageController::class,'removeFriend']);
    Route::post('friend/block',[ProfilePageController::class,'blockFriend']);
    Route::post('friend/block/escape',[ProfilePageController::class,'blockEscape']);


    Route::get('/profile/{user}',[ProfilePageController::class,'index'])->name("user.profile");

    Route::get('/message/{user}',[MessageController::class,'index'])->name("user.message");
    Route::post('/message',[MessageController::class,'send']);

});