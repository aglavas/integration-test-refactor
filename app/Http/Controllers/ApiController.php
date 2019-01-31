<?php

namespace App\Http\Controllers;

use App\Http\Helpers\InfusionsoftHelper;
use App\Http\Requests\PostAssignReminderRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Response;
use App\User;
use App\Module;

class ApiController extends Controller
{

    // Todo: Module reminder assigner

    private function exampleCustomer($username){

        $infusionsoft = new InfusionsoftHelper();

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
     */
    public function create()
    {
        $this->exampleCustomer('test1');
        $this->exampleCustomer('test2');
        $this->exampleCustomer('test4');

        echo "created";
    }


    /**
     * Assign reminder method
     *
     * @param PostAssignReminderRequest $request
     * @param InfusionsoftHelper $infusionsoftHelper
     * @param Module $module
     * @param User $user
     */
    public function assignReminder(PostAssignReminderRequest $request,  InfusionsoftHelper $infusionsoftHelper, Module $module, User $user)
    {

    }
}
