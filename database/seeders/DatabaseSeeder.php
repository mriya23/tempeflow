<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@tempeflow.test'],
            [
                'name' => 'Admin TempeFlow',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->update([
            'name' => 'Admin TempeFlow',
            'role' => 'admin',
        ]);

        $production = User::query()->firstOrCreate(
            ['email' => 'production@tempeflow.test'],
            [
                'name' => 'Staff Produksi',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );
        $production->update([
            'name' => 'Staff Produksi',
            'role' => 'staff',
        ]);

        $distribution = User::query()->firstOrCreate(
            ['email' => 'distribution@tempeflow.test'],
            [
                'name' => 'Staff Distribusi',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );
        $distribution->update([
            'name' => 'Staff Distribusi',
            'role' => 'staff',
        ]);

        $customer = User::query()->firstOrCreate(
            ['email' => 'customer@tempeflow.test'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
        $customer->update([
            'name' => 'Customer Demo',
            'role' => 'customer',
        ]);

        $testUser = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
        $testUser->update([
            'name' => 'Test User',
            'role' => 'customer',
        ]);

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
