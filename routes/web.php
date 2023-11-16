<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/index', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/main-menu', function () {
    return view('main_menu');
});

Route::get('/create-acc', function () {
    return view('form_create_account');
});

Route::post('/submit-form-create', [App\Http\Controllers\Controller::class, 'createAccount']);

Route::get('/add-credit', function () {
    return view('form_add_credit');
});

Route::get('/display-acc', [App\Http\Controllers\Controller::class, 'getAccounts']);

Route::post('/submit-form-add-credit', [App\Http\Controllers\Controller::class, 'addCredit']);