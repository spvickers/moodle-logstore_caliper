<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains ...
 *
 * @package    logstore_caliper
 * @copyright  2016 Moodlerooms Inc. http://www.moodlerooms.com
 * @author     Stephen Vickers
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_caliper\local\Translator\Events;

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