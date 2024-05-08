<?php

use Abno\Abno360\Abno360Service;

Route::get("abno360-handle-callback",function(){

    $Abno360Service = new Abno360Service;
    return $Abno360Service->handleLogin(request("access_token"));
})->middleware("web")->name("abno360-handle-redirect");

Route::get("abno-360-organization-login",function(){

    $Abno360Service = new Abno360Service;
    return $Abno360Service->handleAuthContract(urldecode(request("cls")),request("userid"));
})->middleware("web")->name("abno-360-organization-login");

