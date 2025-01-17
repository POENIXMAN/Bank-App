<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
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
        if (auth()->check() && session()->has('user')) {
            return true;
        } else {
            return false;
        }
    }

    public function main_menu()
    {
        if ($this->isLoggedin()) {
            return view('user.main_menu');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }
    }


    public function create_acc_view()
    {
        if ($this->isLoggedin()) {
            return view('user.form_create_account');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }
    }

    public function createAccount(Request $request)
    {
        if ($this->isLoggedin()) {

            //Validate the inputs
            $validator = Validator::make($request->all(), [
                'accountNum' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        // Check if an account with the same number already exists
                        $userAccounts = Account::where('accountNum', $value)->exists();
                        if ($userAccounts) {
                            $fail("An account with the same account Number $value already exists.");
                        }
                    },
                ],
                'name' => 'required',
                'amount' => 'required|integer|min:0|max:9223372036854775807', // Maximum value for bigint(20)
                'currency' => 'required',
                'clientId' => 'required',
            ], [
                'amount.max' => 'The amount should be within the valid range between 0 and 9223372036854775807.',
            ]);
            

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            //create the account
            $newAccountData = [
                'accountNum' => $request->input('accountNum'),
                'clientName' => $request->input('name'),
                'amount' => $request->input('amount'),
                'status' => 'pending',
                'currency' => $request->input('currency'),
                'clientId' => $request->input('clientId'),
            ];
            Account::createAccount($newAccountData);

            return redirect('/main-menu')
                ->withSuccess('Account creation request is now pending acceptance');
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }
    }

    public function getAccounts()
    {
        if ($this->isLoggedin()) {
            $clientId = session('user')['id'];
            $accounts = Account::where('clientId', $clientId)->get(['accountNum', 'clientName', 'amount', 'currency', 'status', 'is_enabled'])->toArray();
            return view('user.accounts_list', ['accounts' => $accounts]);
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }
    }

    public function transfer(){
        if ($this->isLoggedin()) {
            $clientId = session('user')['id'];
            $accounts = Account::where('clientId', $clientId)->pluck('accountNum')->toArray();

            return view('user.transfer', ['accounts' => $accounts, 'accountNumFrom' => null]);
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }

    }
    
    public function transferFromAcc(Request $request)
    {
        if ($this->isLoggedin()) {
            $accountNum = $request->input('accountNum');
            return view('user.transfer', ['accountNumFrom' => $accountNum]);
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }
    }

    public function transferCredit(Request $request)
    {
        // Check if the user is logged in
        if (!$this->isLoggedin()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }

        //make sure the user is not sending to the same account
        $clientId = session('user')['id'];
        $accountNumFrom = $request->input('fromAccount');

        if ($accountNumFrom == $request->input('toAccount')) {
            return back()->withErrors([
                'toAccount' => 'You cannot transfer money to the same account',
            ]);
        }

        // Check if the user has an account with the specified accountNumFrom
        $account = Account::where('accountNum', $accountNumFrom)->first(['id', 'status', 'clientId', 'is_enabled']);

        if (!$account || !($clientId == $account['clientId'])) {
            // AccountNumFrom does not exist or does not belong to the user
            return back()->withErrors([
                'fromAccount' => 'The selected account does not exist or does not belong to you.',
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

        return $this->processTransfer($request, $accountNumFrom);
    }


    private function processTransfer(Request $request, $accountNumFrom)
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

                    return redirect()->route('main-menu')->withSuccess('Transfer successful!');
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

    public function view_transactions()
    {
        if ($this->isLoggedin()) {
            $clientId = session('user')['id'];

            $transactions = Transactions::select([
                    'from_account.accountNum as from_account_num',
                    'to_account.accountNum as to_account_num',
                    'transactions.amount',
                    'transactions.currency',
                    DB::raw("DATE_FORMAT(transactions.created_at, '%Y-%m-%d at %H:%i:%s') as formatted_created_at"),
                ])
                ->join('accounts as from_account', 'transactions.from_account_id', '=', 'from_account.id')
                ->join('accounts as to_account', 'transactions.to_account_id', '=', 'to_account.id')
                ->whereIn('transactions.from_account_id', function($query) use ($clientId) {
                    $query->select('id')
                        ->from('accounts')
                        ->where('clientId', $clientId);
                })
                ->orWhereIn('transactions.to_account_id', function($query) use ($clientId) {
                    $query->select('id')
                        ->from('accounts')
                        ->where('clientId', $clientId);
                })
                ->get()
                ->toArray();
            
            return view('user.transactions', ['transactions' => $transactions]);
            
        } else {
            return redirect()->route('login')
                ->with('error', 'You must be logged in as a user to access this service.');
        }
    }
}
