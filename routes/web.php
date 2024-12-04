<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(Auth::user());
});

Route::get('/login', function () {
    return redirect('/admin');
})->name('login');
