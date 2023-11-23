<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts'; 
    public $accountNum;
    public $clientName;
    public $amount;
    public $currency;
    public $clientId;
    public $timestamps = true;
    protected $fillable = ['accountNum', 'clientName', 'amount', 'currency', 'clientId'];

    public static function createAccount(array $data)
    {
        return self::create([
            'accountNum' => $data['accountNum'],
            'clientName' => $data['clientName'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'clientId' => $data['clientId'],
        ]);
    }

    public function addCredit($addedAmount)
    {
        $this->amount += $addedAmount;
    }
}
