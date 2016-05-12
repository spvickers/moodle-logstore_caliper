<?php

namespace logstore_caliper\Translator\Events;

class ModuleViewed extends CourseViewed {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $expandedevent
     * @return [String => Mixed]
     * @override CourseViewed
     */
    public function read(array $expandedevent) {
        return array_merge(parent::read($expandedevent), [
            'recipe' => 'module_viewed',
            'module_id' => "{$expandedevent['user']->url}/module/{$expandedevent['module']->id}",
            'module_type' => "http://www.moodle.org/mod/{$expandedevent['module']->type}",
            'module_name' => $expandedevent['module']->name,
            'module_description' => $expandedevent['module']->intro ?: 'A module',
        ]);
    }
}