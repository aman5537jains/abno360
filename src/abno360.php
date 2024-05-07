<?php

use Abno\Abno360\Contracts\UserAuthModelContract;

return [
    "abno360url"        => "http://127.0.0.1:8000",
    "defaultUser" => "",
    "client_id"=>"",
    "default_auth_contract"=>UserAuthModelContract::class,
    "auth_contracts"=>[
        UserAuthModelContract::class
    ]
];
