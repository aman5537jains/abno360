<?php

use Abno\Abno360\Contracts\UserAuthModelContract;

return [
    "abno360url"        => "http://127.0.0.1:8000",
    "defaultUser"       => "",
    "default_domain"    => "abnocloud.com",
    "domains"           => [
                                "abnocloud.com"=>[
                                    "client_id"=>"9c5eef7b-3857-4f48-998c-9c2021ea4165",
                                    "client_secret"=>"sKyuZO8KH4mvyes7IeGyP8RVOoFqiqJNBieCdzZQ",
                                    "redirect_url"=>null
                                ],
                            ],
    "default_auth_contract"=>UserAuthModelContract::class,
    "auth_contracts"=>[
        UserAuthModelContract::class
    ]
];
