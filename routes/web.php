<?php

namespace App\Models;
use App\Models\Account;
use App\Models\Bank;
use App\Http\Controllers\Controller;

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
    return view('main_menu');
});

Route::get('/form_create_account', function () {
    return view('form_create_account');
});

Route::post('/submit_form_create', [Controller::class,'createAccount']); 

Route::get('/form_add_credit', function () {
    return view('form_add_credit');
});

Route::post('/submit_form_add', [Controller::class,'add_Ammount']); 

Route::get('/show_list', [Controller::class,'show_list']);
