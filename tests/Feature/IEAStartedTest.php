<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IEAStartedTest extends TestCase
{
    use DatabaseMigrations;

    public function  setUp()
    {
        parent::setEnvironment('courses_case_iea_started');
    }

    /**
     * @test
     */
    public function user_has_completed_ipa_course_and_started_some_modules_in_iea_course()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => $this->user->email]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'Reminder successfully added for IEA Module 3',
        ]);
    }
}
