<?php

namespace logstore_caliper\RecipeEmitter;

use \IMSGlobal\Caliper;

use \stdClass as PhpObj;

class Repository extends PhpObj {
    protected $sensor;

    /**
     * Constructs a new Repository.
     * @param Caliper\Sensor $sensor
     */
    public function __construct(Caliper\Sensor $sensor) {
        $this->sensor = $sensor;
    }

    /**
     * Creates an event in the store.
     * @param Caliper\events\Event $event
     */
    public function createEvent($event) {
        return $this->sensor->send($this->sensor, $event);
    }
}
