<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NoCoursesTest extends TestCase
{
    use DatabaseMigrations;

    public function  setUp()
    {
        parent::setEnvironment('courses_case_iea_started', '');
    }

    /**
     * @test
     */
    public function user_has_no_courses_returned_from_api()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => $this->user->email]);

        $response->assertStatus(500);

        $response->assertJson([
            'success' => false,
            'message' => 'User has no courses.',
        ]);
    }

    /**
     * @test
     */
    public function non_existent_mail_entered()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => rand() . '@test.com']);

        $response->assertStatus(422);

        $response->assertJsonFragment(['field' => 'contact_email']);
        $response->assertJsonFragment(['message' => 'The selected contact email is invalid.']);
    }

    /**
     * @test
     */
    public function wrong_format_mail_entered()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => rand() . 'test.com']);

        $response->assertStatus(422);

        $response->assertJsonFragment(['field' => 'contact_email']);
        $response->assertJsonFragment(['message' => 'The contact email must be a valid email address.']);

    }

    /**
     * @test
     */
    public function contact_email_field_missing()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact' => rand() . 'test.com']);

        $response->assertStatus(422);

        $response->assertJsonFragment(['field' => 'contact_email']);
        $response->assertJsonFragment(['message' => 'The contact email field is required.']);

    }

    /**
     * @test
     */
    public function contact_email_field_empty()
    {
        $response = $this->post('api/module_reminder_assigner', ['contact_email' => '']);

        $response->assertStatus(422);

        $response->assertJsonFragment(['field' => 'contact_email']);
        $response->assertJsonFragment(['message' => 'The contact email field is required.']);

    }




}
