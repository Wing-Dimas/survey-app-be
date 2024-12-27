<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SurveyController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/', [AdminController::class, 'index'])->name('index');
Route::get('/signin', [AdminController::class, 'showLogin'])->name('auth.show.signin');
Route::post('/signin', [AdminController::class, 'signin'])->name('auth.store.signin');
Route::get('/signup', [AdminController::class, 'showRegristration'])->name('auth.show.signup');
Route::post('/signup', [AdminController::class, 'signup'])->name('auth.store.signup');

// GUEST
// Route::get('/survey/{survey_token:token}', [SurveyController::class, 'survey'])->name('survey.index');
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

// ADMIN
Route::middleware(['auth'])->group(function(){
    // Dasboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::delete('/signout', [AdminController::class, 'destroy'])->name('auth.signout');

    Route::prefix('dashboard')->group(function(){
        // Api Key
        Route::resource('api-key', ApiKeyController::class)->except('show');
        Route::get('api-key/{apiKey}/export', [ApiKeyController::class, 'export'])->name('api-key.export');

        // Form Submission
        Route::resource('form-submission', FormSubmissionController::class)->except('show');

        // Response
        Route::resource('response', ResponseController::class)->only('index', 'show', 'destroy');

    });
});
