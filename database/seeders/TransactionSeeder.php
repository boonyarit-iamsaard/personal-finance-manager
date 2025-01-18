<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use App\Models\TransactionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    // TODO: add subcategories
    /**
     * @var array<string, array<int,string>>
     */
    protected static array $categories = [
        'income' => [
            'Salary',
            'Business',
            'Other Income',
        ],
        'expense' => [
            'Bills & Utilities',
            'Business',
            'Education',
            'Entertainment',
            'Family',
            'Fees & Charges',
            'Food & Beverage',
            'Friends & Lover',
            'Gifts & Donations',
            'Health & Fitness',
            'Insurance',
            'Investments',
            'Other Expense',
            'Shopping',
            'Transportation',
            'Travel',
        ],
        'debt/loan' => [
            'Debt',
            'Loan',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding transaction types and categories...');

        foreach (self::$categories as $type => $categories) {
            $transactionType = TransactionType::create([
                'name' => $type,
                'slug' => Str::slug($type),
            ]);

            foreach ($categories as $category) {
                TransactionCategory::create([
                    'transaction_type_id' => $transactionType->id,
                    'name' => $category,
                    'slug' => Str::slug($category),
                ]);
            }
        }

        $this->command->info('Transaction types and categories seeded successfully.');
    }
}
