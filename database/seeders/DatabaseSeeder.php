<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // CategorySeeder::class,
            WarehouseSeeder::class,
            MarketSeeder::class,
            SupplierSeeder::class,
            // ProductSeeder::class,
            // EmployeeSeeder::class,
            // ClientSeeder::class,
            // ProductExistenceSeeder::class,
            // SupplyRequestSeeder::class,
            // OrderSeeder::class,
            // DeliverySeeder::class,
            // RefundSeeder::class,
            // FeatureSeeder::class,
            // ProductFeatureSeeder::class,
            // ProductTransferSeeder::class,
            // ProductOrderSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Shapovalenko Store',
            'email' => 'test@example.com',
        ]);
    }
}
