<?php
namespace Abno\Abno360\Contracts;

use Abno\Abno360\Models\Abno360User;

class Abno360UserContract{

    public $name = "";
    public $email;
    public $password ;
    public $phone_no ;
    public $abno360_user_id ;
    public $relative_id ;
    public $relative_type ;
    public $country_code = "254";
    public $item ;
    public $config ;

    public function __construct($item,$guard='')
    {
        $this->item  = $item;
        $this->config= $abno360user = method_exists($this, 'getAbno360userConfig')? $item->getAbno360userConfig():[];

        //guard not set in model then pick default model
        if(!isset( $abno360user["auth"])){
            $this->config["auth"]= empty($guard) ? config("auth.defaults.guard") :$guard;
        }
        if(!isset( $abno360user["email"])){
            $this->config["email"]="email";
        }
        if(!isset( $abno360user["password"])){
            $this->config["password"]="password";
        }
        if(!isset( $abno360user["phone"])){
            $this->config["phone"]="phone_no";
        }

        $this->setEmail($item->{$this->config["email"]});
        $this->setPhone( $item->{$this->config["phone"]});
        $this->setPassword( $item->{$this->config["password"]});

        $this->setRelative(get_class($item));
        $this->setRelativeId($item->id);
    }
    public function auth(){
        return $this->config["auth"];
    }

    public function afterLogin(){
        return isset($this->config["afterLogin"])?$this->config["afterLogin"]():true;
    }
    public function beforeLogin(){
        return isset($this->config["beforeLogin"])?$this->config["beforeLogin"]():true;
    }
    public function setName($name){
        $this->name= $name;
    }
    public function getName(){
        return $this->name;
    }
    public function setEmail($email){
        $this->email= $email;
    }
    public function getEmail(){
        return $this->email;
    }

    public function setPhone($phone){
        $this->phone_no = $phone;
    }
    public function getPhone(){
        return $this->phone_no;
    }

    public function setAbno360UserId($id){
        $this->abno360_user_id = $id;
    }
    public function getAbno360UserId(){
        return $this->abno360_user_id;
    }

    public function setRelative($name){
        $this->relative_type= $name;
    }
    public function getRelative(){
        return  $this->relative_type;
    }
    public function setRelativeId($id){
        $this->relative_id= $id;
    }
    public function getRelativeId(){
        return  $this->relative_id;
    }
    public function setPassword($pass){
        $this->password= $pass;
    }
    public function getPassword(){
        return  $this->password;
    }

}

