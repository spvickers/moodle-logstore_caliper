<?php

namespace logstore_caliper\Translator\Events;

class CourseViewed extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $expandedevent
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $expandedevent) {
        return array_merge(parent::read($expandedevent), [
            'recipe' => 'course_viewed',
            'course_id' => "{$expandedevent['user']->url}/course/{$expandedevent['course']->id}",
            'course_number' => $expandedevent['course']->idnumber,
            'course_name' => $expandedevent['course']->fullname ?: 'A Moodle course',
            'course_description' => $expandedevent['course']->summary ?: 'A Moodle course',
        ]);
    }
}