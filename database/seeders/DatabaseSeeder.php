<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Spatie Roles
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'cashier']);

        // 2. Seed Users
        $admin = User::create([
            'name' => 'Ivory Dolina',
            'email' => 'admin@elviracafe.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        $cashier = User::create([
            'name' => 'John Doe',
            'email' => 'cashier@elviracafe.com',
            'password' => Hash::make('password123'),
        ]);
        $cashier->assignRole('cashier');

        // Seed some store configs for the admin
        $admin->storeConfig()->create([
            'business_type' => 'cafe',
            'locations_count' => '1',
            'is_migrating' => false,
        ]);

        // 2. Seed Categories
        $coffee = Category::create(['name' => 'Coffee']);
        $milkbase = Category::create(['name' => 'Milkbase']);
        $snacks = Category::create(['name' => 'Snacks']);

        // 3. Seed Products & their initial Price History
        $productsData = [
            // Coffee Category
            [
                'category_id' => $coffee->id,
                'name' => 'Americano',
                'selling_price' => 12000,
                'cost_price' => 4000,
                'current_stock' => 45,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Cappuccino',
                'selling_price' => 14500,
                'cost_price' => 5000,
                'current_stock' => 32,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Affogato',
                'selling_price' => 18000,
                'cost_price' => 6000,
                'current_stock' => 8, // Low Stock!
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Iced Coffee Milk',
                'selling_price' => 15000,
                'cost_price' => 5000,
                'current_stock' => 50,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Luwak Coffee',
                'selling_price' => 32000,
                'cost_price' => 12000,
                'current_stock' => 15,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Double Espresso',
                'selling_price' => 11000,
                'cost_price' => 3500,
                'current_stock' => 6, // Low Stock!
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $coffee->id,
                'name' => 'Oat Milk Latte',
                'selling_price' => 16000,
                'cost_price' => 5250,
                'current_stock' => 12,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],

            // Milkbase Category
            [
                'category_id' => $milkbase->id,
                'name' => 'Iced Matcha Latte',
                'selling_price' => 17500,
                'cost_price' => 5750,
                'current_stock' => 25,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $milkbase->id,
                'name' => 'Iced Pistachio Latte',
                'selling_price' => 45000,
                'cost_price' => 18500,
                'current_stock' => 18,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],

            // Snacks Category
            [
                'category_id' => $snacks->id,
                'name' => 'Almond Croissant',
                'selling_price' => 14500,
                'cost_price' => 6000,
                'current_stock' => 4, // Low Stock!
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
            [
                'category_id' => $snacks->id,
                'name' => 'Sea Salt Cookie',
                'selling_price' => 9500,
                'cost_price' => 3500,
                'current_stock' => 40,
                'low_stock_threshold' => 10,
                'track_inventory' => true,
            ],
        ];

        foreach ($productsData as $pData) {
            $product = Product::create($pData);
            
            // Create initial price history
            PriceHistory::create([
                'product_id' => $product->id,
                'old_price' => $product->selling_price,
                'new_price' => $product->selling_price,
                'reason' => 'Initial Setup',
            ]);
        }
    }
}
