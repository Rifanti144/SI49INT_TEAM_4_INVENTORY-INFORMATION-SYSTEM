<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WarehouseApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, bool $active = true): User
    {
        return User::factory()->create(['role' => $role, 'is_active' => $active]);
    }

    private function makeWarehouse(User $owner): Warehouse
    {
        $record = new Warehouse([
            'warehouse_name' => 'Gudang A',
            'aisle_number' => 'A-01',
            'rack_code' => 'R-10',
            'physical_capacity' => '500 pallets',
        ]);
        $record->user_id = $owner->id;
        $record->save();

        return $record;
    }

    public function test_guest_is_rejected(): void
    {
        $this->getJson('/api/warehouses')->assertUnauthorized();
    }

    public function test_viewer_gets_warehouse_list_as_json(): void
    {
        $owner = $this->makeUser('staff');
        $this->makeWarehouse($owner);

        Sanctum::actingAs($this->makeUser('viewer'));

        $this->getJson('/api/warehouses')
            ->assertOk()
            ->assertJsonPath('data.0.warehouse_name', 'Gudang A')
            ->assertJsonPath('data.0.created_by', $owner->name)
            ->assertJsonStructure([
                'data' => [['id', 'warehouse_name', 'aisle_number', 'rack_code', 'physical_capacity', 'created_by', 'created_at']],
                'links',
                'meta',
            ])
            ->assertJsonMissingPath('data.0.user_id');
    }

    public function test_deactivated_user_is_forbidden(): void
    {
        Sanctum::actingAs($this->makeUser('staff', active: false));

        $this->getJson('/api/warehouses')->assertForbidden();
    }
}
