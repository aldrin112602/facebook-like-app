<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\VideoCallController;


Route::get('/', function () {
    return view('welcome');
})->name('homepage');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/feed', [PostController::class, 'index'])->name('feed');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');


    // Friend routes
    Route::prefix('friends')->name('friends.')->group(function () {
        Route::get('/', [FriendController::class, 'index'])->name('index');
        Route::post('/request', [FriendController::class, 'sendRequest'])->name('request');
        Route::post('/accept/{id}', [FriendController::class, 'acceptRequest'])->name('accept');
        Route::delete('/decline/{id}', [FriendController::class, 'declineRequest'])->name('decline');
        Route::delete('/remove/{id}', [FriendController::class, 'remove'])->name('remove');
    });

    // Message routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/{id}', [MessageController::class, 'show'])->name('show');
        Route::post('/send', [MessageController::class, 'send'])->name('send');
        Route::get('/{message}/download', [MessageController::class, 'downloadFile'])->name('download');
    });


    // Video call routes
    Route::post('/video-call/initiate', [VideoCallController::class, 'initiate'])->name('video.call.initiate');
    Route::post('/video-call/answer', [VideoCallController::class, 'answer'])->name('video.call.answer');
    Route::get('/video-call/{call_id}', [VideoCallController::class, 'show'])->name('video.call');
    Route::post('/video-call/candidate', [VideoCallController::class, 'sendCandidate'])->name('video.call.candidate');
    Route::post('/video-call/offer', [VideoCallController::class, 'sendOffer'])->name('video.call.offer');
    Route::post('/video-call/answer-webrtc', [VideoCallController::class, 'sendAnswer'])->name('video.call.answer.webrtc');
    Route::post('/video-call/end', [VideoCallController::class, 'endCall'])->name('video.call.end');
});

require __DIR__ . '/auth.php';
