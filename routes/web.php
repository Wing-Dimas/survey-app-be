<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signin', fn () => view('pages.auth.signin'));
Route::get('/signup', fn () => view('pages.auth.signup'));
// Route::get('/contact', fn () => view('contact'))->name('contact');
