<?php

namespace App\Http\Controllers;

use App\Http\Helpers\InfusionsoftHelper;
use App\Http\Requests\PostAssignReminderRequest;
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
        $user->completed_modules()->attach(Module::where('course_key', 'ipa')->limit(3)->get());
        $user->completed_modules()->attach(Module::where('name', 'IPA Module 5')->first());


        return $user;
    }

    /**
     * Create example customers
     *
     * @param InfusionsoftHelper $infusionsoftHelper
     */
    public function create(InfusionsoftHelper $infusionsoftHelper)
    {
        $this->exampleCustomer('test1', $infusionsoftHelper);
        $this->exampleCustomer('test2', $infusionsoftHelper);
        $this->exampleCustomer('test4', $infusionsoftHelper);

        echo "created";
    }

    /**
     * @param PostAssignReminderRequest $request
     */
    public function assignReminder(PostAssignReminderRequest $request)
    {

    }
}
