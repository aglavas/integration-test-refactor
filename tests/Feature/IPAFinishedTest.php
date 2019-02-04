<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IPAFinishedTest extends TestCase
{
    use DatabaseMigrations;

    public function  setUp()
    {
        parent::setEnvironment('courses_case_ipa_finished');
    }

    /**
     * @test
     */
    public function user_has_completed_all_modules_in_ipa_course()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => $this->user->email]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'Reminder successfully added for IEA Module 1',
        ]);
    }
}
