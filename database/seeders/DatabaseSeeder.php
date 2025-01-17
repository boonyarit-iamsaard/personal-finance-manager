<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Start seeding database...');

        $this->call([
            AdminSeeder::class,
        ]);

        if (App::environment('local')) {
            $factories = [
                'users' => User::factory(10),
            ];

            foreach ($factories as $key => $factory) {
                $this->command->info("Start seeding {$key}...");

                $factory->create();

                $this->command->info(Str::ucfirst($key) . ' seeded successfully');
            }
        }

        $this->command->info('Database seeded successfully');
    }
}
