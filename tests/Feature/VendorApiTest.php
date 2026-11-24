<?php

namespace Tests\Feature;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VendorApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, bool $active = true): User
    {
        return User::factory()->create(['role' => $role, 'is_active' => $active]);
    }

    private function makeVendor(User $owner): Vendor
    {
        $record = new Vendor([
            'vendor_name' => 'PT Contoh',
            'office_address' => 'Jl. Contoh No. 1',
            'company_legality' => 'PT',
            'pic_name' => 'Budi',
            'pic_contact' => '0812345678',
        ]);
        $record->user_id = $owner->id;
        $record->save();

        return $record;
    }

    public function test_guest_is_rejected(): void
    {
        $this->getJson('/api/vendors')->assertUnauthorized();
    }

    public function test_viewer_gets_vendor_list_as_json(): void
    {
        $owner = $this->makeUser('staff');
        $this->makeVendor($owner);

        Sanctum::actingAs($this->makeUser('viewer'));

        $this->getJson('/api/vendors')
            ->assertOk()
            ->assertJsonPath('data.0.vendor_name', 'PT Contoh')
            ->assertJsonPath('data.0.created_by', $owner->name)
            ->assertJsonStructure([
                'data' => [['id', 'vendor_name', 'office_address', 'company_legality', 'pic_name', 'pic_contact', 'created_by', 'created_at']],
                'links',
                'meta',
            ])
            ->assertJsonMissingPath('data.0.user_id');
    }

    public function test_deactivated_user_is_forbidden(): void
    {
        Sanctum::actingAs($this->makeUser('staff', active: false));

        $this->getJson('/api/vendors')->assertForbidden();
    }
}
