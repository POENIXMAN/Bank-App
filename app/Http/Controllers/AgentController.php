<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

class AgentController extends BaseController
{

    private function isLoggedin()
    {
        if (session()->has('agent')) {
            return true;
        } else {
            return false;
        }
    }
    public function list_clients()
{
    if ($this->isLoggedin()) {
        // Fetch all users
        $users = User::all();

        // Fetch accounts for each user
        $usersWithAccounts = [];
        foreach ($users as $user) {
            $accounts = Account::where('clientId', $user->id)->get()->toArray();
            $usersWithAccounts[] = ['user' => $user->toArray(), 'accounts' => $accounts];
        }

        // Return view with user list and their accounts
        return view('agent.client_list', ['usersWithAccounts' => $usersWithAccounts]);
    } else {
        return redirect()->route('login')
            ->with('error', 'You must be logged in to view this page.');
    }
}

}
