<?php
namespace Abno\Abno360\Contracts;

use Abno\Abno360\Models\Abno360User;
use App\Models\User;

class SelectOrganizationContract{
    public $user;
    public $allContracts;
    public function __construct($user)
    {
        $this->user=$user;
        $this->getAndSetContracts();
    }

    public function authContracts(){
        return  config("abno360.auth_contracts");
    }

    public function getAndSetContracts(){
        $contracts = $this->authContracts();
        foreach($contracts as $contract){
            $contractObject = new $contract($this->user);
            if($contractObject->isExist()){
                $this->allContracts[] =  new $contract($this->user);
            }
        }
        return $this->allContracts;
    }

    public function isUserConnectedWithAnyAuth(){
        return count($this->allContracts)>0;
    }




    public function render(){


        return view("Abno360::organization-selector",['contracts'=>$this->allContracts]);
    }

}
