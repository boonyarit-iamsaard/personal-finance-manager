<?php

use App\Models\TransactionCategory;
use App\Models\TransactionType;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TransactionType::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['transaction_type_id', 'slug'], 'unique_transaction_category');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TransactionCategory::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Wallet::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_categories');
        Schema::dropIfExists('transaction_types');
    }
};
