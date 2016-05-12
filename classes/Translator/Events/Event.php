<?php

namespace logstore_caliper\Translator\Events;

use \logstore_caliper\Translator\Repository as Repository;
use \stdClass as PhpObj;

class Event extends PhpObj {
    protected static $xapi_type = 'http://lrs.learninglocker.net/define/type/moodle/';

    /**
     * Reads data for an event.
     * @param [String => Mixed] $expandedevent
     * @return [String => Mixed]
     */
    public function read(array $expandedevent) {
        $other = unserialize($expandedevent['event']['other']);
        $session_id = (isset($other['sessionid'])) ? $other['sessionid'] : session_id();
        return [
            'session_id' => $session_id,
            'user_id' => "{$expandedevent['user']->url}/user/{$expandedevent['user']->id}",
            'user_name' => "{$expandedevent['user']->firstname} {$expandedevent['user']->lastname}",
            'time' => date('c', $expandedevent['event']['timecreated']),
            'app_name' => $expandedevent['app']->fullname ?: 'A Moodle course',
            'app_description' => $expandedevent['app']->summary ?: 'A Moodle course',
        ];
    }
}
