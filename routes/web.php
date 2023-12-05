<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgentController;

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
    Route::get('/agent-dashboard','agent_dashboard')->name('agent-dashboard');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(UserController::class)->group(function() {
    Route::get('/main-menu', 'main_menu')->name('main-menu');

    Route::get('/create-acc-view', 'create_acc_view')->name('create-acc-view');
    Route::post('/submit-form-create','createAccount')->name('createAccount');

    Route::get('/transactions', 'view_transactions')->name('view-transactions');
    
    Route::get('/display-acc', 'getAccounts')->name('getAccounts');

    Route::get('/transfer', 'transfer')->name('transfer');
    Route::get('/tranfer-from-acc', 'transferFromAcc')->name('tranfer-from-acc');
    Route::post('/submit-transfer', 'transferCredit')->name('transferCredit');
});

Route::controller(AgentController::class)->group(function() {
    Route::get('/agent-dashboard','agent_dashboard')->name('agent-dashboard');
    
    Route::get('/list-clients', 'list_clients')->name('list-clients');

    Route::get('/approve-accounts', 'approve_accounts')->name('approve-accounts');
    Route::post('/approve-account', 'approveAccount')->name('approve-account');
    Route::post('/reject-account', 'rejectAccount')->name('reject-account');

    Route::post('/enable-account', 'enable_account')->name('enable-account');
    Route::post('/disable-account', 'disable_account')->name('disable-account');

    Route::get('/view-physical-transactions', 'view_physical_transactions')->name('view-physical-transactions');
    Route::post('/physical-transactions', 'physical_transactions')->name('physical-transactions');

    Route::get('/client-transactions', 'view_client_transactions')->name('client-transactions');

    Route::get('/agent-tranfer', 'view_transfer_form')->name('agent-tranfer');
    Route::post('/agent-submit-transfer', 'submit_transfer')->name('agent-submit-transfer');
});
