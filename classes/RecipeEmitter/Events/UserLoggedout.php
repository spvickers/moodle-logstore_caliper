<?php

namespace logstore_caliper\RecipeEmitter\Events;

use \IMSGlobal\Caliper\events;
use \IMSGlobal\Caliper\actions;

class UserLoggedout extends events\SessionEvent {

    public function __construct($translatorevent){
        parent::__construct();
        $this->setAction(new actions\Action(actions\Action::LOGGED_OUT));
    }

}