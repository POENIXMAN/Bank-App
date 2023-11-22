<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
            ],
            'USD' => [
                'LBP' => 1512.00, // 1 USD to LBP
                'EUR' => 0.89,    // 1 USD to EUR
            ],
            'EUR' => [
                'LBP' => 1700.00, // 1 EUR to LBP
                'USD' => 1.12,    // 1 EUR to USD
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
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }
    }

    public function add_credit_view()
    {
        if (session('user')) {
            view('form_add_credit');
        } else {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
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
        $accountFrom = Account::where('accountNum', $accountNumFrom)->first(['currency', 'ammount'])->toArray();

        // Check if the accountNumTo exists
        $toAccount = Account::where('accountNum', $accountTo)->get('id')->toArray();
        // dd($toAccount);
        if ($toAccount) {
            $currency = $request->input('currency');
            $amount = $request->input('amount');
            $accountCurrency = $accountFrom['currency'];

            // Convert currency if necessary
            if ($currency != $accountCurrency) {
                $amountConverted = $this->convertCurrency($amount, $currency, $accountCurrency);
            }

            // Check if there are sufficient funds
            if ($amountConverted <= $accountFrom['ammount']) {
                // Start a database transaction
                DB::beginTransaction();

                try {
                    // Deduct amountConverted from accountFrom
                    $accountFromId = Account::where('accountNum', $accountNumFrom)->value('id');
                    $accountFrom = Account::find($accountFromId);
                    dd($accountFrom);
                    $accountFrom->amount -= $amountConverted;
                    $accountFrom->update();

                    // Add amount to accountTo
                    $accountFrom = Account::where('accountNum', $accountTo)->first('id')->toArray();
                    $accountTo = Account::find($accountFrom['id']);
                    $toAccount->amount += $amount;
                    $toAccount->update();

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


    public function nottransferCredit(Request $request)
    {
        // Check if the user is logged in
        if (!$this->isLoggedin()) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the service',
                ])->onlyInput('email');
        }

        // i want to check if the user has an account with the same accountNumFrom 
        $clientId = session('user')['id'];
        $accounts = Account::where('clientId', $clientId)->get(['accountNum'])->toArray();

        $accountNumFrom = $request->input('accountNumFrom');
        if (in_array($accountNumFrom, $accounts)) {
            $accountTo = $request->input('accountNumTo');
            $accountFrom = Account::where('accountNum', $accountNumFrom)->get(['currency', 'ammount'])->toArray();
            if (!empty(Account::where('accountNum', $accountTo)->get(['id'])->toArray())) {
                $currency = $request->input('currency');
                $amount = $request->input('amount');
                $accountCurrency = $accountFrom['currency'];
                if ($currency != $accountCurrency) {
                    $amount = $this->convertCurrency($amount, $currency, $accountCurrency);
                }
                if ($amount <= $accountFrom['ammount']) {
                    // make the transaction


                    return redirect()->route('main-menu')
                        ->withSuccess('Transfer successful!');
                }
            }
            return back()->withErrors([
                'toAccount' => 'The account number does not exist',
            ])->onlyInput('toAccount');
        } else {
            // AccountNumFrom does not exist, handle accordingly (e.g., return an error response)
            return redirect()->route('main-menu')->withErrors([
                'accountNumFrom' => 'The selected account does not exist or does not belong to you.',
            ]);
        }
    }
}
