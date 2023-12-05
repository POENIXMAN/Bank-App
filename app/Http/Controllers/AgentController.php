<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;


class AgentController extends BaseController
{

    public function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        // Define exchange rates
        $exchangeRates = [
            'LBP' => [
                'USD' => 0.00066, // 1 LBP to USD
                'EUR' => 0.00059, // 1 LBP to EUR
                'LBP' => 1,
            ],
            'USD' => [
                'LBP' => 1512.00, // 1 USD to LBP
                'EUR' => 0.89,    // 1 USD to EUR
                'USD' => 1,
            ],
            'EUR' => [
                'LBP' => 1700.00, // 1 EUR to LBP
                'USD' => 1.12,    // 1 EUR to USD
                'EUR' => 1,
            ],
        ];

        // Check if the currencies are valid
        if (!isset($exchangeRates[$fromCurrency]) || !isset($exchangeRates[$toCurrency])) {
            throw new \Exception('Invalid currencies provided.');
        }

        // Check if the conversion rates are available
        if (!isset($exchangeRates[$fromCurrency][$toCurrency]) || !isset($exchangeRates[$toCurrency][$fromCurrency])) {
            throw new \Exception('Conversion rate not available.');
        }

        // Perform the conversion
        $convertedAmount = $amount * $exchangeRates[$fromCurrency][$toCurrency];

        return $convertedAmount;
    }

    private function isLoggedin()
    {
        if (session()->has('agent')) {
            return true;
        } else {
            return false;
        }
    }

    public function agent_dashboard()
    {
        if ($this->isLoggedin()) {
            return view('agent.agent_dashboard');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
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
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function approve_accounts()
    {
        if ($this->isLoggedin()) {
            // Fetch all accounts that have pending status and retrieve as an array
            $accounts = Account::where('status', 'pending')->get()->toArray();

            // Format the created_at field in each account
            foreach ($accounts as &$account) {
                $account['created_at'] = Carbon::parse($account['created_at'])->format('Y-m-d H:i:s');
            }

            // Return view with accounts
            return view('agent.approve_accounts', ['accounts' => $accounts]);
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }



    public function approveAccount(Request $request)
    {
        if ($this->isLoggedin()) {

            $id = $request->input('id');
            $account = Account::find($id)->toArray();

            // Exclude timestamps from the array
            unset($account['created_at']);
            unset($account['updated_at']);

            $account['status'] = 'approved';

            // Use Carbon to get the current timestamp
            $now = Carbon::now();

            // Update the 'updated_at' timestamp in the array
            $account['updated_at'] = $now;

            // Save the updated array back to the database
            Account::where('id', $id)->update($account);

            return redirect()->back()->with('success', 'Account approved successfully.');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function rejectAccount(Request $request)
    {
        if ($this->isLoggedin()) {

            $id = $request->input('id');
            $account = Account::find($id)->toArray();

            // Exclude timestamps from the array
            unset($account['created_at']);
            unset($account['updated_at']);

            $account['status'] = 'disapproved';

            // Use Carbon to get the current timestamp
            $now = Carbon::now();

            // Update the 'updated_at' timestamp in the array
            $account['updated_at'] = $now;

            // Save the updated array back to the database
            Account::where('id', $id)->update($account);

            return redirect()->back()->with('success', 'Account disapproved successfully.');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function enable_account(Request $request)
    {
        if ($this->isLoggedin()) {

            $id = $request->input('id');
            $account = Account::find($id)->toArray();

            // Exclude timestamps from the array
            unset($account['created_at']);
            unset($account['updated_at']);

            $account['is_enabled'] = 1;

            // Use Carbon to get the current timestamp
            $now = Carbon::now();

            // Update the 'updated_at' timestamp in the array
            $account['updated_at'] = $now;

            // Save the updated array back to the database
            Account::where('id', $id)->update($account);

            return redirect()->back()->with('success', 'Account enabled successfully.');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function disable_account(Request $request)
    {
        if ($this->isLoggedin()) {

            $id = $request->input('id');
            $account = Account::find($id)->toArray();

            // Exclude timestamps from the array
            unset($account['created_at']);
            unset($account['updated_at']);

            $account['is_enabled'] = 0;

            // Use Carbon to get the current timestamp
            $now = Carbon::now();

            // Update the 'updated_at' timestamp in the array
            $account['updated_at'] = $now;

            // Save the updated array back to the database
            Account::where('id', $id)->update($account);

            return redirect()->back()->with('success', 'Account Disabled successfully.');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function view_physical_transactions()
    {
        if ($this->isLoggedin()) {
            return view('agent.view_physical_transactions');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function physical_transactions(Request $request)
    {
        if ($this->isLoggedin()) {
            $transactionType = $request->input('transactionType');
            $accountNum = $request->input('accountNumber');
            $transactions = Account::where('accountNum', $accountNum)->first()->toArray();
            // dd($transactions);
            if (!$transactions) {
                return redirect()->back()->with('error', 'Account Number does not exist.');
            }

            if (!$transactions['is_enabled']) {
                return back()->withErrors([
                    'accountNumber' => 'This account is Disabled.',
                ]);
            } else if ($transactions['status'] == 'pending') {
                return back()->withErrors([
                    'accountNumber' => 'This account is still pending approval.',
                ]);
            } elseif ($transactions['status'] == 'disapproved') {
                return back()->withErrors([
                    'accountNumber' => 'This account has been disapproved.',
                ]);
            }

            $amount = $this->convertCurrency($request->input('amount'), $request->input('currency'), $transactions['currency']);
            if ($transactionType == "deposit") {
                $transactions['amount'] += $amount;
            } else if ($transactionType == "withdrawal") {
                if ($transactions['amount'] < $amount) {
                    return redirect()->back()->withErrors(['amount' => 'Insufficient funds.']);
                }
                $transactions['amount'] -= $amount;
            } else {
                return redirect()->back()->with('error', 'Invalid transaction type.');
            }

            // Exclude timestamps from the array
            unset($transactions['created_at']);
            unset($transactions['updated_at']);

            $now = Carbon::now();
            $transactions['updated_at'] = $now;

            // Save the updated array back to the database
            Account::where('accountNum', $accountNum)->update($transactions);

            return redirect()->back()->with('success', 'Transaction completed successfully.');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function view_client_transactions()
    {
        if ($this->isLoggedin()) {
            // get all transactions
            $transactions = Transactions::select([
                'from_account.accountNum as from_account_num',
                'to_account.accountNum as to_account_num',
                'transactions.amount',
                'transactions.currency',
                DB::raw("DATE_FORMAT(transactions.created_at, '%Y-%m-%d at %H:%i:%s') as formatted_created_at"),
            ])
            ->join('accounts as from_account', 'transactions.from_account_id', '=', 'from_account.id')
            ->join('accounts as to_account', 'transactions.to_account_id', '=', 'to_account.id')
            ->get()
            ->toArray();
        
        return view('agent.all_client_transactions', ['transactions' => $transactions]);
        
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function view_transfer_form()
    {
        if ($this->isLoggedin()) {
            return view('agent.transfer_form');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    public function submit_transfer(Request $request)
    {
        if ($this->isLoggedin()) {
            $accountNumFrom = $request->input('fromAccount');
            $account = Account::where('accountNum', $accountNumFrom)->first(['id', 'status', 'clientId', 'is_enabled']);

            if (!$account) {
                return back()->withErrors([
                    'fromAccount' => 'The account number does not exist',
                ]);
            }

            if (!$account['is_enabled']) {
                return back()->withErrors([
                    'fromAccount' => 'This account is Disabled.',
                ]);
            } else if ($account['status'] == 'pending') {
                return back()->withErrors([
                    'fromAccount' => 'This account is still pending approval.',
                ]);
            } elseif ($account['status'] == 'disapproved') {
                return back()->withErrors([
                    'fromAccount' => 'This account has been disapproved.',
                ]);
            }

            return $this->agent_processTransfer($request, $accountNumFrom);
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as an agent to access this service.');
        }
    }

    private function agent_processTransfer(Request $request, $accountNumFrom)
    {
        $accountNumTo = $request->input('toAccount');
        $accountFrom = Account::where('accountNum', $accountNumFrom)->first(['id', 'currency', 'amount'])->toArray();

        // Check if the accountNumTo exists
        $toAccount = Account::where('accountNum', $accountNumTo)->first(['id', 'status', 'currency', 'is_enabled'])->toArray();
        if ($toAccount['id']) {
            //check account to status
            if (!$toAccount['is_enabled']) {
                return back()->withErrors([
                    'toAccount' => 'The account you are trying to send money to is Disabled.',
                ]);
            } else if ($toAccount['status'] == 'pending') {
                return back()->withErrors([
                    'toAccount' => 'The account you are trying to send money to is still pending approval.',
                ]);
            } elseif ($toAccount['status'] == 'disapproved') {
                return back()->withErrors([
                    'toAccount' => 'The account you are trying to send money to has been disapproved.',
                ]);
            }

            $currency = $request->input('currency');
            $amount = $request->input('amount');
            $accountCurrency = $accountFrom['currency'];

            // Convert currency if necessary
            if ($currency != $accountCurrency) {
                $amountConverted = $this->convertCurrency($amount, $currency, $accountCurrency);
            } else {
                $amountConverted = $amount;
            }

            $accountToCurrency = $toAccount['currency'];
            if ($currency != $accountToCurrency) {
                $amountToSend = $this->convertCurrency($amountConverted, $accountCurrency, $accountToCurrency);
            } else {
                $amountToSend = $amountConverted;
            }

            // Check if there are sufficient funds
            if ($amountConverted <= $accountFrom['amount']) {
                // Start a database transaction
                DB::beginTransaction();

                try {
                    // Deduct amountConverted from accountFrom
                    Account::where('accountNum', $accountNumFrom)->decrement('amount', $amountConverted);
                    // Add amount to accountTo
                    Account::where('accountNum', $accountNumTo)->increment('amount', $amountToSend);


                    $transactionData = [
                        'from_account_id' => $accountFrom['id'],
                        'to_account_id' => $toAccount['id'],
                        'amount' => $amount,
                        'currency' => $currency,
                    ];

                    Transactions::createTransaction($transactionData);
                    // Commit the transaction
                    DB::commit();

                    return redirect()->back()->withSuccess('Transfer successful!');
                } catch (\Exception $e) {
                    // An error occurred, rollback the transaction
                    DB::rollBack();

                    return back()->withErrors([
                        'general' => 'An error occurred during the transfer. Please try again.',
                    ]);
                }
            } else {
                return back()->withErrors([
                    'amount' => 'Insufficient funds in the account.',
                ])->onlyInput('amount');
            }
        } else {
            return back()->withErrors([
                'toAccount' => 'The account number does not exist',
            ])->onlyInput('toAccount');
        }
    }
}
