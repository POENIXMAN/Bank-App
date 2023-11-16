<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    private $bank;
    public function __construct()
    {
        session_start();
        $session_name = "Bank";
        if (!isset($_SESSION[$session_name])) {
            $_SESSION[$session_name] = new Bank();
        }
        $this->bank = &$_SESSION[$session_name];
    }

    public function createAccount(Request $request)
    {
        $accountNum = $request->input('accountNum');
        $clientName = $request->input('name');
        $ammount = $request->input('ammount');
        $account = new Account($accountNum, $clientName, $ammount);
        $this->bank->addAccount($account);
        return redirect('/main-menu');
    }

    public function getAccounts(){
        $accounts = $this->bank->getAllAccounts();
        return view('accounts_list' , ['accounts' => $accounts]);
    }

    public function addCredit(Request $request){
        $accountNum = $request->input('account_num');
        $ammount = $request->input('ammount');
        $index = $this->bank->searchAccount($accountNum);
        if ($index != -1) {
            $this->bank->addCredit($index, $ammount);
        }
        return redirect('/main-menu');
    }
}
