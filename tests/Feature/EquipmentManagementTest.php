<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_and_can_log_in_by_email_or_username(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => 'secret-password',
        ]);

        $this->get(route('equipment.index'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('Email address or username');
        $this->post(route('login.store'), [
            'identifier' => 'admin',
            'password' => 'secret-password',
        ])->assertRedirect(route('equipment.index'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post(route('login.store'), [
            'identifier' => 'admin@example.com',
            'password' => 'secret-password',
        ])->assertRedirect(route('equipment.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_equipment_validation_and_crud_assign_creator_on_the_server(): void
    {
        $admin = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($admin)
            ->from(route('equipment.create'))
            ->post(route('equipment.store'), [
                'equipment_name' => 'Example Paddle',
                'category' => 'unknown',
                'unit_price' => '-1',
                'description' => 'A demo paddle.',
                'availability_status' => 'missing',
                'user_id' => $otherUser->id,
            ])
            ->assertRedirect(route('equipment.create'))
            ->assertSessionHasErrors(['category', 'unit_price', 'availability_status'])
            ->assertSessionHas('_old_input.equipment_name', 'Example Paddle');

        $this->from(route('equipment.create'))
            ->post(route('equipment.store'), [
                'equipment_name' => 'Example Paddle',
                'category' => 'paddles',
                'unit_price' => 'not-a-price',
                'description' => 'A demo paddle.',
                'availability_status' => 'available',
            ])
            ->assertRedirect(route('equipment.create'))
            ->assertSessionHasErrors('unit_price');

        $this->assertDatabaseCount('equipment', 0);

        $this->post(route('equipment.store'), [
            'equipment_name' => 'Carbon Fiber Paddle',
            'category' => 'paddles',
            'unit_price' => '89.99',
            'description' => 'Carbon fiber face with a 16 mm core.',
            'availability_status' => 'available',
            'user_id' => $otherUser->id,
        ])->assertRedirect(route('equipment.index'));

        $item = Equipment::query()->sole();
        $this->assertSame($admin->id, $item->user_id);
        $this->assertSame('89.99', $item->unit_price);

        $this->get(route('equipment.index'))->assertOk()
            ->assertSee($item->equipment_name)
            ->assertSee('₱89.99');
        $this->get(route('equipment.create'))->assertOk();
        $this->get(route('equipment.show', $item))->assertOk()->assertSee('Carbon fiber face');
        $this->get(route('equipment.edit', $item))->assertOk();
        $this->put(route('equipment.update', $item), [
            'equipment_name' => 'Updated Paddle',
            'category' => 'paddles',
            'unit_price' => '0',
            'description' => 'Updated specifications.',
            'availability_status' => 'unavailable',
        ])->assertRedirect(route('equipment.show', $item));
        $this->assertDatabaseHas('equipment', [
            'id' => $item->id,
            'equipment_name' => 'Updated Paddle',
            'availability_status' => 'unavailable',
        ]);

        $this->delete(route('equipment.destroy', $item))->assertRedirect(route('equipment.index'));
        $this->assertDatabaseCount('equipment', 0);
    }

    public function test_report_filters_combine_and_pdf_and_excel_exports_use_them(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $matching = $this->makeEquipment($admin, 'Available paddle', 'paddles', 'available');
        $this->makeEquipment($admin, 'Unavailable paddle', 'paddles', 'unavailable');
        $this->makeEquipment($admin, 'Available ball', 'balls', 'available');

        $this->get(route('reports.index', [
            'category' => 'paddles',
            'availability_status' => 'available',
        ]))->assertOk()
            ->assertSee($matching->equipment_name)
            ->assertSee('₱10.00')
            ->assertDontSee('Unavailable paddle')
            ->assertDontSee('Available ball');

        $this->get(route('reports.index', [
            'category' => 'nets',
            'availability_status' => 'available',
        ]))->assertOk()->assertSee('No equipment matches the selected filters.');

        $pdf = $this->get(route('reports.pdf', [
            'category' => 'paddles',
            'availability_status' => 'available',
        ]));
        $pdf->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $pdf->getContent());

        $excel = $this->get(route('reports.excel', [
            'category' => 'paddles',
            'availability_status' => 'available',
        ]));
        $excel->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8')
            ->assertHeader('content-disposition', 'attachment; filename="pickleball-equipment-report.xls"')
            ->assertSee('Available paddle', false)
            ->assertSee('Unit price (PHP)', false)
            ->assertSee('₱', false)
            ->assertDontSee('Unavailable paddle', false);
        $this->assertNotFalse(simplexml_load_string($excel->getContent()));
    }

    public function test_database_seeder_creates_eight_demo_items_across_all_categories_and_statuses(): void
    {
        (new DatabaseSeeder())->run();

        $this->assertDatabaseCount('equipment', 8);
        $this->assertSame(4, Equipment::query()->where('availability_status', 'available')->count());
        $this->assertSame(4, Equipment::query()->where('availability_status', 'unavailable')->count());

        foreach (Equipment::CATEGORIES as $category) {
            $this->assertDatabaseHas('equipment', ['category' => $category]);
        }
    }

    private function makeEquipment(User $user, string $name, string $category, string $status): Equipment
    {
        return $user->equipment()->create([
            'equipment_name' => $name,
            'category' => $category,
            'unit_price' => '10.00',
            'description' => 'Demonstration equipment description.',
            'availability_status' => $status,
        ]);
    }
}
