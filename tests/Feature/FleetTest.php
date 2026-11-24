<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FleetTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        return User::factory()->create(['role' => $role, 'is_active' => true]);
    }

    private function makeVehicle(User $owner, array $attributes = []): Vehicle
    {
        $vehicle = new Vehicle(array_merge(['serial' => 'FL-001', 'type' => 'forklift', 'status' => 'available'], $attributes));
        $vehicle->user_id = $owner->id;
        $vehicle->save();

        return $vehicle;
    }

    public function test_staff_can_add_a_vehicle(): void
    {
        $staff = $this->makeUser('staff');

        $this->actingAs($staff)->get(route('fleet.create'))->assertOk()->assertSee('Serial Number');

        $this->actingAs($staff)
            ->post(route('fleet.store'), ['serial' => 'FL-001', 'type' => 'forklift', 'status' => 'available'])
            ->assertRedirect(route('fleet.index'));

        $this->assertDatabaseHas('vehicles', ['serial' => 'FL-001', 'user_id' => $staff->id]);
    }

    public function test_serial_must_be_unique_but_edit_can_keep_its_own(): void
    {
        $staff = $this->makeUser('staff');
        $first = $this->makeVehicle($staff);
        $this->makeVehicle($staff, ['serial' => 'TR-002', 'type' => 'trolley']);

        $this->actingAs($staff)
            ->post(route('fleet.store'), ['serial' => 'FL-001', 'type' => 'pickup', 'status' => 'available'])
            ->assertSessionHasErrors('serial');

        $this->actingAs($staff)
            ->put(route('fleet.update', $first), ['serial' => 'FL-001', 'type' => 'forklift', 'status' => 'maintenance'])
            ->assertRedirect(route('fleet.index'));

        $this->assertDatabaseHas('vehicles', ['id' => $first->id, 'status' => 'maintenance']);
    }

    public function test_index_searches_and_filters(): void
    {
        $staff = $this->makeUser('staff');
        $this->makeVehicle($staff, ['serial' => 'ABC123']);
        $this->makeVehicle($staff, ['serial' => 'XYZ999', 'status' => 'maintenance']);

        $this->actingAs($staff)->get(route('fleet.index', ['q' => 'BC1']))
            ->assertOk()->assertSee('ABC123')->assertDontSee('XYZ999');

        $this->actingAs($staff)->get(route('fleet.index', ['status' => 'maintenance']))
            ->assertOk()->assertSee('XYZ999')->assertDontSee('ABC123');
    }

    public function test_index_paginates_ten_per_page(): void
    {
        $staff = $this->makeUser('staff');
        for ($i = 1; $i <= 12; $i++) {
            $this->makeVehicle($staff, ['serial' => "S{$i}"]);
        }

        $this->actingAs($staff)->get(route('fleet.index'))
            ->assertOk()->assertSee('Page 1 of 2')->assertSee('Next');
    }

    public function test_staff_can_delete_a_vehicle(): void
    {
        $staff = $this->makeUser('staff');
        $vehicle = $this->makeVehicle($staff);

        $this->actingAs($staff)->delete(route('fleet.destroy', $vehicle))->assertRedirect(route('fleet.index'));

        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }

    public function test_viewer_can_list_but_not_change_vehicles(): void
    {
        $viewer = $this->makeUser('viewer');
        $vehicle = $this->makeVehicle($this->makeUser('staff'));

        $this->actingAs($viewer)->get(route('fleet.index'))->assertOk()->assertSee('FL-001')->assertDontSee('Add Another Vehicle');
        $this->actingAs($viewer)->get(route('fleet.create'))->assertForbidden();
        $this->actingAs($viewer)->delete(route('fleet.destroy', $vehicle))->assertForbidden();
    }
}
