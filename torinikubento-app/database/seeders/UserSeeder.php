<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get owner role
        $ownerRole = Role::where('name', 'owner')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $kasirRole = Role::where('name', 'kasir')->first();

        if (!$ownerRole) {
            $this->command->error('Owner role not found! Please run RoleSeeder first.');
            return;
        }

        // Create default owner account
        $owner = User::updateOrCreate(
            ['username' => 'owner'],
            [
                'name' => 'Owner Toriniku Bento',
                'email' => 'owner@torinikubento.com',
                'username' => 'owner',
                'password' => Hash::make('password123'),
                'pin' => Hash::make('1234'),
                'role_id' => $ownerRole->id,
                'is_active' => true,
                'phone' => '081234567890'
            ]
        );

        // Create sample admin
        if ($adminRole) {
            User::updateOrCreate(
                ['username' => 'admin'],
                [
                    'name' => 'Admin Manager',
                    'email' => 'admin@torinikubento.com',
                    'username' => 'admin',
                    'password' => Hash::make('admin123'),
                    'pin' => Hash::make('2345'),
                    'role_id' => $adminRole->id,
                    'is_active' => true,
                    'phone' => '081234567891'
                ]
            );
        }

        // Create sample kasir
        if ($kasirRole) {
            User::updateOrCreate(
                ['username' => 'kasir1'],
                [
                    'name' => 'Kasir 1',
                    'email' => 'kasir1@torinikubento.com',
                    'username' => 'kasir1',
                    'password' => Hash::make('kasir123'),
                    'pin' => Hash::make('3456'),
                    'role_id' => $kasirRole->id,
                    'is_active' => true,
                    'phone' => '081234567892'
                ]
            );
        }

        $this->command->info('Default users created successfully!');
        $this->command->info('Owner Login: username=owner, password=password123, pin=1234');
        $this->command->info('Admin Login: username=admin, password=admin123, pin=2345');
        $this->command->info('Kasir Login: username=kasir1, password=kasir123, pin=3456');
    }
}
