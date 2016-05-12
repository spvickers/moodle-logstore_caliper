<?php

namespace logstore_caliper\RecipeEmitter\Events;

use \IMSGlobal\Caliper\entities\lis;

class CourseViewed extends Viewed {

    public function __construct($translatorevent){
        parent::__construct($translatorevent);
        $course = new lis\CourseSection($translatorevent['course_id']);
        $course->setName($translatorevent['course_name'])
               ->setDescription($translatorevent['course_description'])
               ->setCourseNumber($translatorevent['course_number']);
        $this->setObject($course);
    }

}