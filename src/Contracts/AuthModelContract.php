<?php
namespace Abno\Abno360\Contracts;

abstract class AuthModelContract{
    public $user;
    public $model;

    public function __construct($user)
    {
        $this->user=$user;
         $this->setUser();
    }
    public function setUser(){
        $this->model = $this->getModel()::where("email",$this->user->email)->first();
    }



    public function getName(){
        return  $this->user->name;
    }
    public function getEmail(){
        return $this->user->email;
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
