<?php
namespace Abno\Abno360\Traits;

use Abno\Abno360\Abno360Service;
use Abno\Abno360\Contracts\Abno360UserContract;

trait Abno360{

    public static function bootAbno360() {



            static::created(function($item) {
                (new Abno360Service)->addUser(new Abno360UserContract($item));
            });

            static::updated(function($item) {

            });

    }
}
