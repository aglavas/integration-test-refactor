<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\CourseCompletedException;

class ModuleCollection extends Collection
{
    /**
     * Get next unwatched module from course
     *
     * @param $moduleId
     * @param $courseCode
     * @return mixed
     * @throws CourseCompletedException
     */
    public function nextModule($moduleId, $courseCode)
    {
        $courseModulesLeft = $this->where('course_key', $courseCode)->where('module_number', '>', $moduleId);

        $nextModule = $courseModulesLeft->first();

        if(!$nextModule) {
            throw new CourseCompletedException();
        }

        return $nextModule;
    }
}
