<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('icon');
            $table->timestamps();

            $table->unique(['user_id', 'slug'], 'unique_user_wallet');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
