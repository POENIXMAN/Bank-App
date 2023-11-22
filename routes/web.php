<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\UserController;

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

Route::controller(UserController::class)->group(function() {
    Route::get('/main-menu', 'main_menu')->name('main-menu');

    Route::get('/create-acc-view', 'create_acc_view')->name('create_acc_view');
    Route::post('/submit-form-create','createAccount')->name('createAccount');

    Route::get('/transactions', 'view_transactions')->name('view_transactions');
    
    Route::get('/display-acc', 'getAccounts')->name('getAccounts');

    Route::get('/tranfer', 'transfer')-> name('transfer');
    Route::post('/submit-transfer', 'transferCredit')->name('transferCredit');
});