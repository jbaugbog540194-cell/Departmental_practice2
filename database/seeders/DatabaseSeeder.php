<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Inventory Administrator',
                'username' => 'admin',
                'password' => 'password',
            ],
        );

        $demonstrationEquipment = [
            ['Carbon Fiber Paddle', 'paddles', 89.99, 'Carbon fiber face, 16 mm polymer core, and cushioned grip.', 'available'],
            ['Fiberglass Paddle', 'paddles', 54.50, 'Fiberglass face, lightweight honeycomb core, and standard grip.', 'unavailable'],
            ['Indoor Pickleball Pack', 'balls', 12.99, 'Pack of six indoor balls with larger holes for gym play.', 'available'],
            ['Outdoor Pickleball Pack', 'balls', 14.99, 'Pack of six outdoor balls with a durable, high-visibility shell.', 'unavailable'],
            ['Portable Pickleball Net', 'nets', 129.00, 'Portable 22-foot net system with a center support and carry bag.', 'available'],
            ['Replacement Net', 'nets', 42.00, 'Regulation-size replacement net mesh for portable net systems.', 'unavailable'],
            ['Paddle Grip Tape', 'accessories', 7.50, 'Perforated overgrip tape designed to absorb moisture.', 'available'],
            ['Pickleball Equipment Bag', 'accessories', 39.95, 'Padded bag with room for paddles, balls, and personal items.', 'unavailable'],
        ];

        foreach ($demonstrationEquipment as [$name, $category, $price, $description, $status]) {
            $admin->equipment()->updateOrCreate(
                ['equipment_name' => $name],
                [
                    'category' => $category,
                    'unit_price' => $price,
                    'description' => $description,
                    'availability_status' => $status,
                ],
            );
        }
    }
}
