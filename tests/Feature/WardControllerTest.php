<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ward;
use App\Models\Bed;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_wards_index()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $response = $this->get(route('admin.wards.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.wards.index');
    }

    public function test_admin_can_view_create_ward_form()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $response = $this->get(route('admin.wards.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.wards.create');
    }

    public function test_admin_can_create_ward()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $wardData = [
            'ward_name' => 'Emergency Ward',
            'ward_type' => 'Emergency',
            'floor' => '1',
            'capacity' => 20,
            'status' => 'Active',
        ];

        $response = $this->post(route('admin.wards.store'), $wardData);

        $response->assertRedirect(route('admin.wards.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('wards', [
            'ward_name' => 'Emergency Ward',
            'ward_type' => 'Emergency',
        ]);
    }

    public function test_admin_can_create_ward_with_validation_errors()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $response = $this->post(route('admin.wards.store'), []);

        $response->assertSessionHasErrors(['ward_name', 'ward_type', 'floor', 'capacity']);
    }

    public function test_admin_can_view_ward()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create();

        $response = $this->get(route('admin.wards.show', $ward));

        $response->assertStatus(200);
        $response->assertViewIs('admin.wards.show');
    }

    public function test_admin_can_view_edit_ward_form()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create();

        $response = $this->get(route('admin.wards.edit', $ward));

        $response->assertStatus(200);
        $response->assertViewIs('admin.wards.edit');
    }

    public function test_admin_can_update_ward()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create();

        $updateData = [
            'ward_name' => 'Updated Ward',
            'ward_type' => 'General',
            'floor' => '2',
            'capacity' => 30,
            'status' => 'Active',
        ];

        $response = $this->put(route('admin.wards.update', $ward), $updateData);

        $response->assertRedirect(route('admin.wards.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('wards', [
            'id' => $ward->id,
            'ward_name' => 'Updated Ward',
            'capacity' => 30,
        ]);
    }

    public function test_admin_can_delete_ward_without_occupied_beds()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create();
        Bed::factory()->create([
            'ward_id' => $ward->id,
            'status' => 'Available',
        ]);

        $response = $this->delete(route('admin.wards.destroy', $ward));

        $response->assertRedirect(route('admin.wards.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('wards', ['id' => $ward->id]);
    }

    public function test_admin_cannot_delete_ward_with_occupied_beds()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create();
        Bed::factory()->create([
            'ward_id' => $ward->id,
            'status' => 'Occupied',
        ]);

        $response = $this->delete(route('admin.wards.destroy', $ward));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('wards', ['id' => $ward->id]);
    }

    public function test_admin_can_get_beds_for_ward()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create();
        Bed::factory()->count(3)->create(['ward_id' => $ward->id]);

        $response = $this->get(route('admin.wards.beds', $ward));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
        ]);
    }

    public function test_admin_can_create_bed()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create();

        $bedData = [
            'ward_id' => $ward->id,
            'bed_number' => 'B101',
            'bed_type' => 'Standard',
        ];

        $response = $this->postJson(route('admin.wards.beds.store'), $bedData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('beds', [
            'ward_id' => $ward->id,
            'bed_number' => 'B101',
            'status' => 'Available',
        ]);
    }

    public function test_admin_can_update_bed()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $bed = Bed::factory()->create();

        $updateData = [
            'bed_number' => 'B102',
            'bed_type' => 'ICU',
            'status' => 'Maintenance',
        ];

        $response = $this->putJson(route('admin.wards.beds.update', $bed), $updateData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('beds', [
            'id' => $bed->id,
            'bed_number' => 'B102',
            'bed_type' => 'ICU',
            'status' => 'Maintenance',
        ]);
    }

    public function test_admin_can_delete_bed()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $bed = Bed::factory()->create(['status' => 'Available']);

        $response = $this->deleteJson(route('admin.wards.beds.destroy', $bed));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('beds', ['id' => $bed->id]);
    }

    public function test_admin_cannot_delete_occupied_bed()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $bed = Bed::factory()->create(['status' => 'Occupied']);

        $response = $this->deleteJson(route('admin.wards.beds.destroy', $bed));

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
        $this->assertDatabaseHas('beds', ['id' => $bed->id]);
    }

    public function test_admin_can_assign_patient_to_bed()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $patient = User::factory()->create(['role' => 'patient']);
        $bed = Bed::factory()->create(['status' => 'Available']);

        $assignData = [
            'patient_id' => $patient->id,
            'bed_id' => $bed->id,
            'admission_reason' => 'Surgery recovery',
        ];

        $response = $this->postJson(route('admin.wards.assign-patient'), $assignData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('beds', [
            'id' => $bed->id,
            'status' => 'Occupied',
            'patient_id' => $patient->id,
        ]);
    }

    public function test_admin_cannot_assign_patient_to_unavailable_bed()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $patient = User::factory()->create(['role' => 'patient']);
        $bed = Bed::factory()->create(['status' => 'Occupied']);

        $assignData = [
            'patient_id' => $patient->id,
            'bed_id' => $bed->id,
        ];

        $response = $this->postJson(route('admin.wards.assign-patient'), $assignData);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_admin_can_discharge_patient_from_bed()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $patient = User::factory()->create(['role' => 'patient']);
        $bed = Bed::factory()->create([
            'status' => 'Occupied',
            'patient_id' => $patient->id,
        ]);

        $response = $this->postJson(route('admin.wards.discharge-patient'), [
            'bed_id' => $bed->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('beds', [
            'id' => $bed->id,
            'status' => 'Available',
            'patient_id' => null,
        ]);
    }

    public function test_admin_can_get_available_beds()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $ward = Ward::factory()->create(['status' => 'Active']);
        Bed::factory()->create(['ward_id' => $ward->id, 'status' => 'Available']);
        Bed::factory()->create(['ward_id' => $ward->id, 'status' => 'Occupied']);

        $response = $this->getJson(route('admin.wards.available-beds'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
        ]);
    }

    public function test_admin_can_get_ward_stats()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        Ward::factory()->create(['status' => 'Active']);
        Bed::factory()->create(['status' => 'Available']);
        Bed::factory()->create(['status' => 'Occupied']);

        $response = $this->getJson(route('admin.wards.stats'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'total_wards',
                'total_beds',
                'available_beds',
                'occupied_beds',
                'maintenance_beds',
            ],
        ]);
    }
}

