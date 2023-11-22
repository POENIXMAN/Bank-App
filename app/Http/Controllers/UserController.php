<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{

    private function isLoggedin()
    {
        if (session()->has('user')) {
            return true;
        } else {
            return false;
        }
    }

    public function main_menu()
    {
        if ($this->isLoggedin()) {
            return view('main_menu');
        } else {
            return redirect('/login');
        }
    }

    public function create_acc_view()
    {
        if ($this->isLoggedin()) {
            return view('form_create_account');
        } else {
            return redirect('/login');
        }
    }

    public function createAccount(Request $request)
    {
        if ($this->isLoggedin()) {
            $newAccountData = [
                'accountNum' => $request->input('accountNum'),
                'clientName' => $request->input('name'),
                'ammount' => $request->input('ammount'),
                'currency' => $request->input('currency'),
                'clientId' => $request->input('clientId'),
            ];
            Account::createAccount($newAccountData);
            return redirect('/main-menu');
        } else {
            return redirect('/login');
        }
    }

    public function add_credit_view()
    {
        if (session('user')) {
            view('form_add_credit');
        } else {
            return redirect('/login');
        }
    }

    public function addCredit(Request $request)
    {
        $accountNum = $request->input('account_num');
        $ammount = $request->input('ammount');
        // add to database
        return redirect('/main-menu');
    }

    public function getAccounts()
    {
        if ($this->isLoggedin()) {
            $clientId = session('user')['id'];
            $accounts = Account::where('clientId', $clientId)->get(['accountNum', 'clientName', 'ammount', 'currency'])->toArray();
            // dd($accounts);
            return view('accounts_list', ['accounts' => $accounts]);
        } else {
            return redirect('/login');
        }
    }
}
