<?php
namespace Abno\Abno360\Contracts;

abstract class AuthModelContract{
    public $user;
    public $model;
    public $query;

    public function __construct($user,$localUserModel='')
    {
        $this->user=$user;
        if($localUserModel!=''){
         $this->setUserModel($localUserModel);
        }
    }

    public function query(){
      return $this->getModel()::where("email",$this->user->email);
    }

    public function setUserModel($localUserModel){
            $this->model = $this->query()->where("id",$localUserModel)->first();
    }
    public function getName(){
        return  $this->model->name;
    }
    public function getID(){
        return  $this->model->id;
    }
    public function getEmail(){
        return $this->model->email;
    }
    public function redirectUrl(){
        return "";
    }

    public function getClass(){
        return get_class($this);
    }
    public function render(){
        return "<div> ".$this->getName()."-".$this->getEmail()." <a href='".route("abno-360-organization-login")."'>Enter</a></div>";
    }

    public function register(){
        $model  = $this->getModel();
        $modelClass = new $model;
        $modelClass->email = $this->user->email;
        $modelClass->name = $this->user->name;
        $modelClass->save();
        return $modelClass;

    }
    public function isExist()
    {
        return empty($this->model)?false:true;
    }
    abstract public function auth();
    abstract public function getModel();

}
