<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_index()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_admin_can_create_user()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $userData = [
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'role' => 'patient',
            'phone' => '1234567890',
        ];

        $response = $this->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'fname' => 'John',
            'lname' => 'Doe',
        ]);
    }

    public function test_admin_can_create_user_with_validation_errors()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $response = $this->post(route('admin.users.store'), []);

        $response->assertSessionHasErrors(['fname', 'lname', 'email', 'password', 'role']);
    }

    public function test_admin_can_view_user()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $user = User::factory()->create();

        // Use JSON request to avoid view dependency
        $response = $this->getJson(route('admin.users.show', $user));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'fname',
                'lname',
                'email',
            ],
        ]);
    }

    public function test_admin_can_update_user()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $updateData = [
            'fname' => 'Jane',
            'lname' => 'Smith',
            'email' => $user->email,
            'role' => 'doctor',
            'phone' => '9876543210',
        ];

        $response = $this->put(route('admin.users.update', $user), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fname' => 'Jane',
            'lname' => 'Smith',
            'role' => 'doctor',
        ]);
    }

    public function test_admin_can_update_user_password()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $updateData = [
            'fname' => $user->fname,
            'lname' => $user->lname,
            'email' => $user->email,
            'password' => 'newpassword123',
            'role' => $user->role,
        ];

        $response = $this->put(route('admin.users.update', $user), $updateData);

        $response->assertRedirect();
        $this->assertTrue(\Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_admin_can_delete_user_without_related_records()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $user = User::factory()->create();

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_user_with_appointments()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $user = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);
        
        \App\Models\Appointment::factory()->create([
            'patient_id' => $user->id,
            'doctor_id' => $doctor->id,
        ]);

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_admin_can_search_users()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $user = User::factory()->create(['fname' => 'John', 'lname' => 'Doe']);

        $response = $this->get(route('admin.users.index', ['search_term' => 'John']));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_admin_can_filter_users_by_role()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        User::factory()->create(['role' => 'patient']);
        User::factory()->create(['role' => 'doctor']);

        $response = $this->get(route('admin.users.index', ['role_filter' => 'patient']));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_admin_can_get_user_stats()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        User::factory()->count(5)->create(['role' => 'patient']);
        User::factory()->count(3)->create(['role' => 'doctor']);

        $response = $this->get(route('admin.users.stats'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'total_users',
                'users_by_role',
                'recent_users',
            ],
        ]);
    }

    public function test_non_admin_cannot_access_user_routes()
    {
        $patient = $this->createPatientUser();
        $this->actingAs($patient);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(403);
    }
}

