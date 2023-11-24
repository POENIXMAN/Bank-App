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

    public function agent_dashboard(){
        if ($this->isLoggedin()) {
            return view('agent.agent_dashboard');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to view this page.');
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

    public function approve_accounts()
    {
        if ($this->isLoggedin()) {
            // Fetch all accounts that have pending status and retrieve as an array
            $accounts = Account::where('status', 'pending')->get()->toArray();
    
            // Format the created_at field in each account
            foreach ($accounts as &$account) {
                $account['created_at'] = \Carbon\Carbon::parse($account['created_at'])->format('Y-m-d H:i:s');
            }
    
            // Return view with accounts
            return view('agent.approve_accounts', ['accounts' => $accounts]);
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to view this page.');
        }
    }
    
    

    public function approveAccount(Request $request)
    {
        if ($this->isLoggedin()) {
            $id = $request->input('id');
            $account = Account::find($id);
            $account->status = 'approved';
            $account->save();
    
            return redirect()->back()->with('success', 'Account approved successfully.');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to view this page.');
        }
    }


    public function rejectAccount(Request $request)
    {
        if ($this->isLoggedin()) {
            $id = $request->input('id');
            $account = Account::find($id);
            $account->status = 'rejected';
            $account->save();
    
            return redirect()->back()->with('success', 'Account rejected successfully.');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to view this page.');
        }
    }
}
