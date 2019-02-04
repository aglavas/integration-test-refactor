<?php

namespace App\Services;

use App\Collections\ModuleCollection;
use App\Exceptions\CourseCompletedException;
use App\Exceptions\CourseInvalidNameException;
use App\Exceptions\CourseMissingException;
use App\Http\Helpers\InfusionsoftHelper;
use App\Module;
use App\ModuleTagId;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class TagAssigner
{

    /**
     * @var InfusionsoftHelper
     */
    private $infusionHelper;

    /**
     * @var Module
     */
    private $module;

    /**
     * @var ModuleTagId
     */
    private $moduleTagId;

    /**
     * @var User
     */
    private $user;

    /**
     * TagAssigner constructor.
     * @param InfusionsoftHelper $infusionsoftHelper
     * @param Module $module
     * @param User $user
     * @param ModuleTagId $moduleTagId
     */
    public function __construct(InfusionsoftHelper $infusionsoftHelper, Module $module, User $user, ModuleTagId $moduleTagId)
    {
        $this->infusionHelper = $infusionsoftHelper;
        $this->module = $module;
        $this->moduleTagId = $moduleTagId;
        $this->user = $user;
    }

    public function assign($contactEmail)
    {
        /** @var ModuleCollection $moduleTagsCollection */
        $moduleTagsCollection = $this->infusionHelper->getAllTags($this->moduleTagId, $this->module);

        $contact = $this->infusionHelper->getContact($contactEmail);

        $user = $this->user->where('email', $contactEmail)->get()->first();

        $coursesExploded = [];

        if (isset($contact['_Products']) && $contact['_Products'] != '') {
            $coursesExploded = explode(',', $contact['_Products']);
        } else {
            throw new CourseMissingException('User has no courses.');
        }

        foreach ($coursesExploded as $course) {
            if (!$this->module->where('course_key', $course)->exists()) {
                throw new CourseInvalidNameException('Course name does not exists');
            }

            $user->load(['completed_modules' => function ($query) use ($course){
                $query->where('course_key', $course);
            }]);

            /** @var Collection $completedModules */

            $completedModules = $user->completed_modules;

            //if none is completed
            if ($completedModules->count() === 0) {
                //add tag to first module
                return $this->startedCourse($moduleTagsCollection, $course, $contact);
            } else {
                $lastCompleted = $completedModules->max('module_number');

                try {
                    $nextModule = $moduleTagsCollection->nextModule($lastCompleted, $course);
                    return $this->addTag($nextModule, $contact);
                } catch (CourseCompletedException $exception) {
                    continue;
                }
            }
        }

        return $this->addCompletedTag($contact);
    }

    /**
     * Add completed tag
     *
     * @param $contact
     * @return bool
     */
    private function addCompletedTag($contact)
    {
        $completedTagId = $this->moduleTagId->where('completed', 1)->first()->infusion_id;

        $result = $this->sendTag($contact['Id'], $completedTagId);

        if($result) {
            return true;
        }

        return false;
    }

    /**
     * Send tag to InfusionSoft
     *
     * @param $contactId
     * @param $tagId
     * @return bool
     */
    private function sendTag($contactId, $tagId)
    {
        return $this->infusionHelper->addTag($contactId, $tagId);
    }

    /**
     * Add tag
     *
     * @param $module
     * @param $contact
     * @return bool
     */
    private function addTag($module, $contact)
    {
        $tagId = $module->infusionId->infusion_id;

        $result = $this->sendTag($contact['Id'], $tagId);

        if ($result) {
            return $module->name;
        }
        return false;
    }

    /**
     * Handle first module of course
     *
     * @param Collection $moduleTagsCollection
     * @param $course
     * @param $contact
     * @return bool
     */
    private function startedCourse(Collection $moduleTagsCollection, $course, $contact)
    {
        $module = $moduleTagsCollection->where('course_key', $course)->where('module_number', 1)->first();

        return $this->addTag($module, $contact);
    }
}
