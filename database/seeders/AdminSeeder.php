<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding admin...');

        $admin = Storage::json('json/admin.json');

        if ($admin === null || empty($admin)) {
            $this->command->warn('Admin seed data not found, skipping...');

            return;
        }

        foreach ($admin as $item) {
            User::create([
                'email' => $item['email'],
                'name' => $item['name'],
                'role' => Role::ADMINISTRATOR->value,
                'password' => Hash::make($item['password']),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        $this->command->info('Admin seeded successfully');
    }
}
