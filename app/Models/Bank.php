<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

 class Bank {

    public $accountsList;


    public function  __construct() {
        $this->accountsList=[];
        $this->add_mock_accounts();
    }

    public function add_mock_accounts(){
        $this->addAccount(new Account("1000","Concettina Bockh",2000));
        $this->addAccount(new Account("1050","Esme McAree",3500));
        $this->addAccount(new Account("1100","Yoko Roggeron",4000));
    }
    
    public function accountsCount(){
        return count($this->accountsList);
    }

    public function addAccount($account) {
        $this->accountsList[]=$account;
    }

    public function getAccount($index){
        return $this->accountsList[$index];
    }
    
    public function getAllAccounts(){
        return $this->accountsList;
    }
    public function searchAccount($accountNum) {
        $index=0;
        foreach ($this->accountsList as $account) {
            if ($account->accountNum==$accountNum) {
                return $index;
            }
            $index++;
        }
        return -1;
    }

    public function saveAccount($account) {
        $account->save(); 
    }

    public function addCredit($index, $ammount)
    {
        if ($index<$this->accountsCount())
            $this->accountsList[$index]->ammount +=intval($ammount);
    }

}
