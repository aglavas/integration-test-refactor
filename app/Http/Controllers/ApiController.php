<?php

namespace App\Http\Controllers;

use App\Http\Helpers\InfusionsoftHelper;
use App\Http\Requests\PostAssignReminderRequest;
use App\Services\TagAssigner;
use App\User;
use App\Module;

class ApiController extends Controller
{

    // Todo: Module reminder assigner

    private function exampleCustomer($username, $infusionsoft){

        $uniqid = uniqid();

        $infusionsoft->createContact([
            'Email' => $username.'@test.com',
            "_Products" => 'ipa,iea'
        ]);

        $user = User::create([
            'name' => 'Test ' . $username,
            'email' => $username.'@test.com',
            'password' => bcrypt($username)
        ]);

        // attach IPA M1-3 & M5
        $user->completed_modules()->attach(Module::where('course_key', 'ipa')->limit(7)->get());
        $user->completed_modules()->attach(Module::where('name', 'IEA Module 7')->first());


        return $user;
    }

    /**
     * Create example customers
     *
     * @param InfusionsoftHelper $infusionsoftHelper
     */
    public function create(InfusionsoftHelper $infusionsoftHelper)
    {
        $this->exampleCustomer('newtest11', $infusionsoftHelper);
        $this->exampleCustomer('newtest12', $infusionsoftHelper);
        $this->exampleCustomer('newtest13', $infusionsoftHelper);

        echo "created";
    }

    /**
     * Assign reminder tag method
     *
     * @param PostAssignReminderRequest $request
     * @param TagAssigner $assigner
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignReminder(PostAssignReminderRequest $request, TagAssigner $assigner)
    {
        try {
            $result = $assigner->assign($request->input('contact_email'));
        } catch (\Exception $exception) {
            return $this->errorMessageResponse($exception->getMessage(), 500);
        }

        if ($result === true) {
            return $this->successMessageResponse('Module reminders completed.', 200);
        } elseif ($result) {
            return $this->successMessageResponse('Reminder successfully added for ' . $result, 200);
        }

        return $this->errorMessageResponse('Error occurred while communicating with InfusionSoft API.', 500);
    }
}
