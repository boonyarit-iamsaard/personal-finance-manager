<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TransactionType extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany<TransactionCategory, covariant TransactionType>
     */
    public function categories(): HasMany
    {
        return $this->hasMany(TransactionCategory::class);
    }

    /**
     * @return HasManyThrough<Transaction, TransactionCategory, covariant TransactionType>
     */
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, TransactionCategory::class);
    }
}
