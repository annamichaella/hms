<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create an authenticated admin user.
     */
    protected function createAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Create an authenticated doctor user.
     */
    protected function createDoctorUser(): User
    {
        return User::factory()->create([
            'role' => 'doctor',
            'email' => 'doctor@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Create an authenticated patient user.
     */
    protected function createPatientUser(): User
    {
        return User::factory()->create([
            'role' => 'patient',
            'email' => 'patient@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Create an authenticated staff user.
     */
    protected function createStaffUser(): User
    {
        return User::factory()->create([
            'role' => 'staff',
            'email' => 'staff@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Create an authenticated nurse user.
     */
    protected function createNurseUser(): User
    {
        return User::factory()->create([
            'role' => 'nurse',
            'email' => 'nurse@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Authenticate as a specific user.
     */
    protected function actingAsUser(User $user): self
    {
        return $this->actingAs($user);
    }

    /**
     * Disable CSRF middleware for tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }
}
