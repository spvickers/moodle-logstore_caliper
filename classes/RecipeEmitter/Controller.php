<?php

namespace logstore_caliper\RecipeEmitter;

use \stdClass as PhpObj;
use \IMSGlobal\Caliper\entities\agent;
use \IMSGlobal\Caliper\entities\session;

class Controller extends PhpObj {
    protected $repo;
    public static $routes = [
        'course_viewed' => 'CourseViewed',
        'discussion_viewed' => 'DiscussionViewed',
        'module_viewed' => 'ModuleViewed',
        'attempt_started' => 'AttemptStarted',
        'attempt_completed' => 'AttemptCompleted',
        'user_loggedin' => 'UserLoggedin',
        'user_loggedout' => 'UserLoggedout',
        'assignment_graded' => 'AssignmentGraded',
        'assignment_submitted' => 'AssignmentSubmitted',
//        'user_registered' => 'UserRegistered',
        'enrolment_created' => 'EnrolmentCreated',
        'scorm_launched' => 'ScormLaunched',
    ];

    /**
     * Constructs a new Controller.
     * @param Repository $repo
     */
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }

    /**
     * Creates a new event.
     * @param [String => Mixed] $translatorevent
     * @return [String => Mixed]
     */
    public function createEvent(array $translatorevent) {
        $route = isset($translatorevent['recipe']) ? $translatorevent['recipe'] : '';
        if (isset(static::$routes[$route])) {
            $event = '\logstore_caliper\RecipeEmitter\Events\\'.static::$routes[$route];
            $caliperevent = new $event($translatorevent);
            $t = new \DateTime($translatorevent['time']);
            $edApp = new agent\SoftwareApplication('http://www.moodle.org/');
            $edApp->setName($translatorevent['app_name'])
                  ->setDescription($translatorevent['app_description']);
            $person = new agent\Person($translatorevent['user_id']);
            $person->setName($translatorevent['user_name']);
            $session = new session\Session($translatorevent['session_id']);
            $session->setActor($person);
            $caliperevent->setEdApp($edApp)
                         ->setActor($person)
                         ->setFederatedSession($session)
                         ->setEventTime($t);
            return $this->repo->createEvent($caliperevent);
        } else {
            return null;
        }
    }
}
