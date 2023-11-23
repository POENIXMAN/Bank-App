<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';
    public $from_account_id;
    public $to_account_id;
    public $amount;
    public $currency;

    public $fillable = ['from_account_id', 'to_account_id', 'amount', 'currency'];

    public static function createTransaction(array $data)
    {
        return self::create([
            'from_account_id' => $data['from_account_id'],
            'to_account_id' => $data['to_account_id'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
        ]);
    }

}
