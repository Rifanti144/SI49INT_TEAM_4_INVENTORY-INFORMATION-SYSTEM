<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FleetApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, bool $active = true): User
    {
        return User::factory()->create(['role' => $role, 'is_active' => $active]);
    }

    private function makeVehicle(User $owner): Vehicle
    {
        $record = new Vehicle([
            'serial' => 'FL-001',
            'type' => 'forklift',
            'status' => 'available',
        ]);
        $record->user_id = $owner->id;
        $record->save();

        return $record;
    }

    public function test_guest_is_rejected(): void
    {
        $this->getJson('/api/vehicles')->assertUnauthorized();
    }

    public function test_viewer_gets_vehicle_list_as_json(): void
    {
        $owner = $this->makeUser('staff');
        $this->makeVehicle($owner);

        Sanctum::actingAs($this->makeUser('viewer'));

        $this->getJson('/api/vehicles')
            ->assertOk()
            ->assertJsonPath('data.0.serial', 'FL-001')
            ->assertJsonPath('data.0.created_by', $owner->name)
            ->assertJsonStructure([
                'data' => [['id', 'serial', 'type', 'status', 'created_by', 'created_at']],
                'links',
                'meta',
            ])
            ->assertJsonMissingPath('data.0.user_id');
    }

    public function test_deactivated_user_is_forbidden(): void
    {
        Sanctum::actingAs($this->makeUser('staff', active: false));

        $this->getJson('/api/vehicles')->assertForbidden();
    }
}
