<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CoachController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\GymClassController;
use App\Http\Controllers\Api\GymController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\OwnerController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Gym Routes
    Route::apiResource('gyms', GymController::class);
    Route::post('gyms/{gym}/images', [GymController::class, 'addImages'])->name('gyms.add.images');
    Route::get('gyms/search', [GymController::class, 'search'])->name('gyms.search');
    Route::delete('gym-images/{id}', [GymController::class, 'deleteImage'])->name('gyms.image.delete');

    // Owner Routes
    Route::apiResource('owners', OwnerController::class);

    // Contact Routes
    Route::apiResource('contacts', ContactController::class);

    // Admin Routes
    Route::apiResource('admins', AdminController::class);

    // GymClass Routes
    Route::apiResource('classes', GymClassController::class);

    // Coach Routes
    Route::apiResource('coaches', CoachController::class);
    Route::post('coaches/{coach}/images', [CoachController::class, 'addImages']);
    Route::delete('coach-images/{id}', [CoachController::class, 'deleteImage']);
    Route::get('coaches/search', [CoachController::class, 'search']);

    // Facility Routes
    Route::apiResource('facilities', FacilityController::class);

    // Subscription Routes
    Route::apiResource('subscriptions', SubscriptionController::class);

    // Member Routes
    Route::apiResource('members', MemberController::class);

    // Address Routes
    Route::apiResource('addresses', AddressController::class);

    // Branch Routes
    Route::apiResource('branches', BranchController::class);

    // Review Routes
    Route::apiResource('reviews', ReviewController::class);
    Route::get('gyms/{gym}/reviews', [ReviewController::class, 'gymReviews']);
    Route::get('members/{member}/reviews', [ReviewController::class, 'memberReviews']);

    // Schedule Routes
    Route::apiResource('schedules', ScheduleController::class);
    Route::delete('schedule-images/{id}', [ScheduleController::class, 'deleteImage']);
//});
