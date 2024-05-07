<?php

use Abno\Abno360\Abno360Service;

Route::get("abno360-handle-callback",function(){

    $Abno360Service = new Abno360Service;
    return $Abno360Service->handleLogin(request("access_token"));
})->middleware("web")->name("abno360-handle-redirect");
