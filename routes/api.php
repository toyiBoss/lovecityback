<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserProfileController; 
use App\Http\Controllers\UserMatchController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\UserProfileGalleryController;
use App\Http\Controllers\CarouselItemController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\DMessageController;
use App\Http\Controllers\LiveController;
use App\Http\Controllers\GiftTypeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SettingController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::middleware('auth:api')->group(function () {

    // Match
        Route::post('/match', [UserMatchController::class, 'store']);
    Route::get('/matches', [UserMatchController::class, 'index']);
    Route::post('/match/{id}/accept', [UserMatchController::class, 'accept']);
    Route::post('/match/{id}/reject', [UserMatchController::class, 'reject']);

    // Story
    Route::post('/stories', [App\Http\Controllers\StoryController::class, 'store']);
    Route::get('/stories', [App\Http\Controllers\StoryController::class, 'index']);
    Route::post('/stories/{storyId}/view', [App\Http\Controllers\StoryController::class, 'markViewed']);
    Route::delete('/stories/{storyId}', [App\Http\Controllers\StoryController::class, 'destroy']);

    // Gallery
    Route::get('/gallery', [UserProfileGalleryController::class, 'index']);
    Route::post('/gallery', [UserProfileGalleryController::class, 'store']);
    Route::patch('/gallery/{mediaId}', [UserProfileGalleryController::class, 'update']);
    Route::delete('/gallery/{mediaId}', [UserProfileGalleryController::class, 'destroy']);

    
    // User Profile
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::post('/profile', [UserProfileController::class, 'update']);

    Route::apiResource('carousel', CarouselItemController::class);
Route::post('discussions', [DiscussionController::class,'store']);
Route::get('discussions', [DiscussionController::class,'index']);
Route::get('discussions/{id}', [DiscussionController::class,'show']);
Route::post('discussions/{id}/status', [DiscussionController::class,'updateStatus']);

Route::get('discussions/{id}/messages', [DMessageController::class,'index']);
Route::post('discussions/{id}/messages', [DMessageController::class,'store']);
Route::post('messages/{id}/seen', [DMessageController::class,'markSeen']);

Route::apiResource('lives', LiveController::class);
Route::post('lives/{id}/start', [LiveController::class,'start']);
Route::post('lives/{id}/end', [LiveController::class,'end']);
Route::post('lives/{id}/viewer/inc', [LiveController::class,'incViewer']);
Route::post('lives/{id}/viewer/dec', [LiveController::class,'decViewer']);

Route::apiResource('gifts', GiftTypeController::class);

    Route::apiResource('subscriptions', SubscriptionController::class)->only(['index','show','store']);
    Route::post('payments', [PaymentController::class,'store']);
    Route::get('payments/user/{userId}', [PaymentController::class,'indexByUser']);
    Route::post('favorites', [FavoriteController::class,'store']);
    Route::delete('favorites/{targetId}', [FavoriteController::class,'destroy']);
    Route::get('favorites', [FavoriteController::class,'index']);

    Route::get('notifications', [NotificationController::class,'index']);
    Route::post('notifications/{id}/read', [NotificationController::class,'markRead']);

    Route::post('invitations', [InvitationController::class,'store']);
    Route::post('invitations/{code}/accept', [InvitationController::class,'accept']);

    Route::get('countries', [CountryController::class,'index']);
    Route::get('settings', [SettingController::class,'show']); // current user
    Route::put('settings', [SettingController::class,'update']);

});
