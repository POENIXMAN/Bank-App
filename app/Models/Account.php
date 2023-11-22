<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $accountNum;
    public $clientName;
    public $ammount;
    public $currency;
    public $clientId;
    protected $fillable = ['accountNum', 'clientName', 'ammount', 'currency', 'clientId'];

    public static function createAccount(array $data)
    {
        return self::create([
            'accountNum' => $data['accountNum'],
            'clientName' => $data['clientName'],
            'ammount' => $data['ammount'],
            'currency' => $data['currency'],
            'clientId' => $data['clientId'],
        ]);
    }

    public function addCredit($addedAmmount)
    {
        $this->ammount += $addedAmmount;
    }
}
