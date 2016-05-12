<?php

namespace logstore_caliper\Translator\Events;

class UserLoggedout extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $expandedevent
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $expandedevent) {
        return array_merge(parent::read($expandedevent), [
            'recipe' => 'user_loggedout',
        ]);
    }
}
