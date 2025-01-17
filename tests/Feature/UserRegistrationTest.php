<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('new user has default cash wallet after registration', function () {
    $user = User::factory()->create();

    expect($user->wallets)->toHaveCount(1);

    $wallet = $user->wallets->first();

    expect($wallet)
        ->name->toBe('Cash')
        ->slug->toBe('cash');
});
