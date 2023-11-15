<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Bank;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private $bank;

    public function __construct(){
        session_start();
        $sessionName = "bank";
        if(!isset($_SESSION[$sessionName])){
            $_SESSION[$sessionName] = new Bank();
        }
        $this->bank = $_SESSION[$sessionName];
    }

    public function index(){
        return view('main_menu', ['total' => $this->bank->accountsCount()]);
    }


    public function createAccount(Request $request){
        $accountNum = request('accountNum');
        $clientName = request('clientName');
        $ammount = request('ammount');
        $account = new Account($accountNum, $clientName, $ammount);
        $this->bank->addAccount($account);
        return redirect('/');
    }

    public function add_Ammount()
    {
        $accountNum = request('accountNum');
        $ammount = request('ammount');
        $index = $this->bank->searchAccount($accountNum);
        if ($index >= 0) {
            $this->bank->addCredit($index, $ammount);
            return redirect('/');
        } else return redirect('/form_add_credit');

    }

    public function show_list(){
        $accounts = $this->bank->getAllAccounts();
        return view('accounts_list', ['accounts' => $accounts]);
    }
}


