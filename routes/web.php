<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;

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
    return view('index');
})->name('index');

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
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