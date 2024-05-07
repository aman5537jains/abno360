<?php
namespace Abno\Abno360\Contracts;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAuthModelContract extends AuthModelContract{
    public $user;
    public $model;

    public function __construct($user)
    {
        parent::__construct($user);

    }

    public function getModel()
    {
        return  User::class;
    }



    public function auth(){
        Auth::guard("web")->login($this->model);
        request()->session()->regenerate();
        return true;
    }
}
