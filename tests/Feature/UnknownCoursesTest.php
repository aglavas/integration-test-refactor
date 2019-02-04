<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UnknownCoursesTest extends TestCase
{
    use DatabaseMigrations;

    public function  setUp()
    {
        parent::setEnvironment('courses_case_iea_started','xxx,yyy');
    }

    /**
     * @test
     */
    public function user_has_unknown_courses_returned_from_api()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => $this->user->email]);

        $response->assertStatus(500);

        $response->assertJson([
            'success' => false,
            'message' => 'Course name does not exists',
        ]);
    }
}
