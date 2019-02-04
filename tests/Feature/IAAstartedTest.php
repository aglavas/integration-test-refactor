<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IAAstartedTest extends TestCase
{
    use DatabaseMigrations;

    public function  setUp()
    {
        parent::setEnvironment('courses_case_iea_ipa_finished', 'ipa,iea,iaa');
    }

    /**
     * @test
     */
    public function user_has_completed_ipa_and_iea_course_starts_iaa()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => $this->user->email]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'Reminder successfully added for IAA Module 1',
        ]);
    }
}
