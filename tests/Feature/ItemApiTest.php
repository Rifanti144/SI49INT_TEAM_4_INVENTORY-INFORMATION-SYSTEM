<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, bool $active = true): User
    {
        return User::factory()->create(['role' => $role, 'is_active' => $active]);
    }

    private function makeItem(User $owner): Item
    {
        $record = new Item([
            'item_name' => 'Sensor Suhu',
            'tech_spec' => '24V DC',
            'unit_size' => '1 pcs',
            'category' => 'Electronics',
        ]);
        $record->user_id = $owner->id;
        $record->save();

        return $record;
    }

    public function test_guest_is_rejected(): void
    {
        $this->getJson('/api/items')->assertUnauthorized();
    }

    public function test_viewer_gets_item_list_as_json(): void
    {
        $owner = $this->makeUser('staff');
        $this->makeItem($owner);

        Sanctum::actingAs($this->makeUser('viewer'));

        $this->getJson('/api/items')
            ->assertOk()
            ->assertJsonPath('data.0.item_name', 'Sensor Suhu')
            ->assertJsonPath('data.0.created_by', $owner->name)
            ->assertJsonStructure([
                'data' => [['id', 'item_name', 'tech_spec', 'unit_size', 'category', 'created_by', 'created_at']],
                'links',
                'meta',
            ])
            ->assertJsonMissingPath('data.0.user_id');
    }

    public function test_deactivated_user_is_forbidden(): void
    {
        Sanctum::actingAs($this->makeUser('staff', active: false));

        $this->getJson('/api/items')->assertForbidden();
    }
}
