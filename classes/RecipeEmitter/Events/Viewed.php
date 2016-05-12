<?php

namespace logstore_caliper\RecipeEmitter\Events;

use \IMSGlobal\Caliper\events;

abstract class Viewed extends events\ViewEvent {

    public function __construct($translatorevent){
        parent::__construct();
    }

}