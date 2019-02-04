<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AllFinishedTest extends TestCase
{
    use DatabaseMigrations;

    public function  setUp()
    {
        parent::setEnvironment('all_courses_finished', 'ipa,iea,iaa');
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
            'message' => 'Module reminders completed.',
        ]);
    }
}
