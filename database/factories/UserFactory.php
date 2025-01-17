<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Counter for generating sequential user numbers
     */
    protected static int $totalCount = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        self::$totalCount++;

        $paddingLength = strlen((string) $this->count);
        $paddedSequence = str_pad((string) self::$totalCount, $paddingLength, '0', STR_PAD_LEFT);

        $name = "User-{$paddedSequence}";
        $email = "user-{$paddedSequence}@example.com";

        return [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make(static::$password ?? 'password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Set the password that should be used for the users.
     */
    public function password(string $password): static
    {
        static::$password = $password;

        return $this;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
