<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }
    }

    public function create_acc_view()
    {
        if ($this->isLoggedin()) {
            return view('form_create_account');
        } else {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }
    }

    public function createAccount(Request $request)
    {
        if ($this->isLoggedin()) {
            // check if ammount is more than 20 digits
            // if (!preg_match('/^\d{1,20}(\.\d{1,2})?$/', $request['amount'])) {
            //     return back()->withErrors([
            //         'ammount' => 'The amount must be a valid number with a maximum of 20 digits and up to 2 decimal places',
            //     ])->onlyInput('ammount');
            // }            
            $newAccountData = [
                'accountNum' => $request->input('accountNum'),
                'clientName' => $request->input('name'),
                'ammount' => $request->input('ammount'),
                'currency' => $request->input('currency'),
                'clientId' => $request->input('clientId'),
            ];
            $account = Account::createAccount($newAccountData);

            // Retrieve the account ID directly
            $accountId = $account->id;

            DB::table('account_creation_requests')->insert([
                'clientId' => $request->input('clientId'),
                'accountId' => $accountId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect('/main-menu')
                ->withSuccess('Account created successfully!');
        } else {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }
    }

    public function getAccounts()
    {
        if ($this->isLoggedin()) {
            $clientId = session('user')['id'];
            $accounts = Account::where('clientId', $clientId)->get(['accountNum', 'clientName', 'ammount', 'currency'])->toArray();
            return view('accounts_list', ['accounts' => $accounts]);
        } else {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }
    }

    public function transfer(Request $request)
    {
        if ($this->isLoggedin()) {
            $accountNum = $request->input('accountNum');
            return view('transfer', ['accountNumFrom' => $accountNum]);
        } else {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }
    }

    public function transferCredit(Request $request)
    {
        // Check if the user is logged in
        if (!$this->isLoggedin()) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }

        // Check if the user has an account with the specified accountNumFrom
        $clientId = session('user')['id'];
        $accountNumFrom = $request->input('fromAccount');

        if ($accountNumFrom == $request->input('toAccount')) {
            return back()->withErrors([
                'toAccount' => 'You cannot transfer money to the same account',
            ]);
        }

        $account = Account::where('accountNum', $accountNumFrom)->first(['clientId'])->toArray();

        if ($account && $clientId == $account['clientId']) {
            return $this->processTransfer($request, $accountNumFrom);
        } else {
            // AccountNumFrom does not exist or does not belong to the user
            return back()->withErrors([
                'fromAccount' => 'The selected account does not exist or does not belong to you.',
            ]);
        }
    }


    private function processTransfer(Request $request, $accountNumFrom)
    {
        $accountTo = $request->input('toAccount');
        $accountFrom = Account::where('accountNum', $accountNumFrom)->first(['id', 'currency', 'ammount'])->toArray();

        // Check if the accountNumTo exists
        $toAccount = Account::where('accountNum', $accountTo)->first(['id', 'currency'])->toArray();
        if ($toAccount['id']) {
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
            if ($amountConverted <= $accountFrom['ammount']) {
                // Start a database transaction
                DB::beginTransaction();

                try {
                    // Deduct amountConverted from accountFrom
                    Account::where('accountNum', $accountNumFrom)->decrement('ammount', $amountConverted);
                    // Add amount to accountTo
                    Account::where('accountNum', $accountTo)->increment('ammount', $amountToSend);


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
            $accountsId = Account::where('clientId', $clientId)->get(['id']);
            $transactions = Transactions::whereIn('from_account_id', $accountsId)
                ->orWhereIn('to_account_id', $accountsId)
                ->select([
                    'from_account_id',
                    'to_account_id',
                    'amount',
                    'currency',
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d at %H:%i:%s') as formatted_created_at"),
                ])
                ->get()
                ->toArray();
            return view('transactions', ['transactions' => $transactions]);
        } else {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }
    }
}
