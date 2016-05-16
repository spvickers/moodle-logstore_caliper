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

class Event extends \stdClass {

    /**
     * Reads data for an event.
     * @param [String => Mixed] $expandedevent
     * @return [String => Mixed]
     */
    public function read(array $expandedevent) {
        $other = unserialize($expandedevent['event']['other']);
        $sessionid = (isset($other['sessionid'])) ? $other['sessionid'] : session_id();
        return [
            'session_id' => $sessionid,
            'user_id' => "{$expandedevent['user']->url}/user/{$expandedevent['user']->id}",
            'user_name' => "{$expandedevent['user']->firstname} {$expandedevent['user']->lastname}",
            'time' => date('c', $expandedevent['event']['timecreated']),
            'app_name' => $expandedevent['app']->fullname ?: 'A Moodle course',
            'app_description' => $expandedevent['app']->summary ?: 'A Moodle course',
        ];
    }
}
