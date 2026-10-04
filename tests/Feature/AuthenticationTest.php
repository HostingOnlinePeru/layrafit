<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_login_with_phone_and_password(): void
    {
        $response = $this->post(route('login.perform'), [
            'phone' => '999888777',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_student_can_register_with_unique_phone(): void
    {
        $location = Location::first();

        $response = $this->post(route('register.perform'), [
            'phone' => '988776655',
            'name' => 'Carla',
            'last_name' => 'Mendoza',
            'email' => 'carla@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'location_id' => $location->id,
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('users', ['phone' => '988776655', 'role' => 'student']);
        $this->assertDatabaseHas('students', ['location_id' => $location->id]);
    }

    public function test_student_registration_fails_if_phone_already_exists(): void
    {
        $location = Location::first();

        $response = $this->post(route('register.perform'), [
            'phone' => '999888777', // Existente
            'name' => 'Lucía',
            'last_name' => 'Pérez',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'location_id' => $location->id,
        ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_user_with_must_change_password_flag_is_redirected(): void
    {
        $user = User::create([
            'phone' => '977665544',
            'name' => 'Temp',
            'last_name' => 'User',
            'password' => Hash::make('temp123'),
            'role' => 'student',
            'must_change_password' => true,
        ]);

        $response = $this->post(route('login.perform'), [
            'phone' => '977665544',
            'password' => 'temp123',
        ]);

        $response->assertRedirect(route('password.change'));
    }
}
