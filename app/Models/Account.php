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
    public $status;
    public $clientId;
    public $timestamps = true;
    protected $fillable = ['accountNum', 'clientName', 'amount', 'status', 'currency', 'clientId'];

    public static function createAccount(array $data)
    {
        return self::create([
            'accountNum' => $data['accountNum'],
            'clientName' => $data['clientName'],
            'amount' => $data['amount'],
            'status' => $data['status'],
            'currency' => $data['currency'],
            'clientId' => $data['clientId'],
        ]);
    }

}
