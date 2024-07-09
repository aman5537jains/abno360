<?php

use Abno\Abno360\Abno360Service;

Route::get("select-organization",function(){

    // $Abno360Service = new Abno360Service;
    // return $Abno360Service->getDatabase();
})->middleware("web")->name("abno360-handle-redirect");

Route::get("auth/callback",function(){

    $Abno360Service = new Abno360Service;
    return $Abno360Service->handleLogin(request("code"));
})->middleware("web")->name("abno360-handle-redirect");

Route::get("abno-360-organization-login",function(){

    $Abno360Service = new Abno360Service;
    return $Abno360Service->handleAuthContract(urldecode(request("cls")),request("userid"));
})->middleware("web")->name("abno-360-organization-login");

