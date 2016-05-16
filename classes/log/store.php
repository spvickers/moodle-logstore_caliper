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
 * External Caliper log store plugin
 *
 * @package    logstore_caliper
 * @copyright  2016 MoodleRooms
 * @author     Stephen Vickers
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_caliper\log;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../vendor/autoload.php');

use \core\event;

use \LogExpander;
use \logstore_caliper\local\Translator;
use \logstore_caliper\local\RecipeEmitter;

use \IMSGlobal\Caliper;

/**
 * This class processes events and enables them to be sent to a logstore.
 *
 */
class store extends \stdClass implements \tool_log\log\writer {
    use \tool_log\helper\store;
    use \tool_log\helper\buffered_writer;

    /**
     * Constructs a new store.
     * @param \tool_log\log\manager $manager
     */
    public function __construct(\tool_log\log\manager $manager) {
        $this->helper_setup($manager);
    }

    /**
     * Should the event be ignored (not logged)? Overrides tool_log\helper\buffered_writer.
     * @param event\base $event
     * @return bool
     *
     */
    protected function is_event_ignored(event\base $event) {
        return false;
    }

    /**
     * Insert events in bulk to the database. Overrides tool_log\helper\buffered_writer.
     * @param array $evententries raw event data
     *
     */
    protected function insert_event_entries($evententries) {
        global $DB;

        // If in background mode, just save them in the database.
        if (get_config('logstore_caliper', 'backgroundmode')) {
            $DB->insert_records('logstore_caliper_log', $evententries);
        } else {
            $this->process_events($evententries);
        }

    }

    public function process_events(array $events) {

        // Initializes required services.
        $moodlecontroller = new LogExpander\Controller($this->connect_moodle_repository());
        $translatorcontroller = new Translator\Controller();
        $calipercontroller = new RecipeEmitter\Controller($this->connect_caliper_repository());

        // Emits events to other APIs.
        foreach ($events as $event) {
            $event = (array) $event;
            $moodleevent = $moodlecontroller->createEvent($event);
            if (is_null($moodleevent)) {
                continue;
            }

            $translatorevent = $translatorcontroller->create_event($moodleevent);
            if (is_null($translatorevent)) {
                continue;
            }

            $caliperevent = $calipercontroller->create_event($translatorevent);
        }

    }

    /**
     * Determines if a connection exists to the store.
     * @return boolean
     */
    public function is_logging() {
        try {
            $this->connect_caliper_repository();
            return true;
        } catch (\moodle_exception $e) {
            debugging('Cannot connect to LRS: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return false;
        }
    }

    /**
     * Creates a connection to the Caliper Event Store.
     * @return Sensor
     */
    private function connect_caliper_repository() {
        global $CFG;

        $sensor = new Caliper\Sensor($CFG->wwwroot);

        $options = new Caliper\Options();
        $options->setHost($this->get_config('endpoint', ''));
        $options->setApiKey($this->get_config('apikey', ''));
        $options->setDebug(true);

        $sensor->registerClient('http', new Caliper\Client('default', $options));

        return new RecipeEmitter\Repository($sensor);

    }

    /**
     * Creates a connection to the Moodle Log Repository.
     * @return LogExpander\Repository
     */
    private function connect_moodle_repository() {
        global $DB;
        global $CFG;
        return new LogExpander\Repository($DB, $CFG);
    }
}
