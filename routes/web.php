<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::redirect('')
Route::redirect('/atlg', '/admin/login')->name('login');