<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $accountNum;
    public $clientName;
    public $ammount;
    protected $fillable = ['accountNum', 'clientName', 'ammount'];
    public function __construct($accountNum = null, $clientName = null, $ammount = null)
    {
        if ($accountNum != null && $clientName!=null && $ammount!=null) {
            $this->accountNum = $accountNum;
            $this->clientName = $clientName;
            $this->ammount = $ammount;
        }
    }

    public function addCredit($addedAmmount)
    {
        $this->ammount += $addedAmmount;
    }
}
