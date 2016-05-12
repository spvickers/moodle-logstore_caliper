<?php

namespace logstore_caliper\RecipeEmitter\Events;

use \IMSGlobal\Caliper\entities;
use \IMSGlobal\Caliper\events;
use \IMSGlobal\Caliper\entities\lis;

class ModuleViewed extends Viewed {

    public function __construct($translatorevent){
        parent::__construct($translatorevent);
        $type = new ModuleType($translatorevent['module_type']);
        $module = new entities\DigitalResource($translatorevent['module_id']);
        $module->setType($type)
               ->setName($translatorevent['module_name'])
               ->setDescription($translatorevent['module_description']);
        $this->setObject($module);
        $course = new lis\CourseSection($translatorevent['course_id']);
        $course->setName($translatorevent['course_name'])
               ->setDescription($translatorevent['course_description'])
               ->setCourseNumber($translatorevent['course_number']);
        $this->setGroup($course);
    }

}