<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionCategory extends Model
{
    protected $fillable = [
        'name',
        'transaction_type_id',
    ];

    /**
     * @return BelongsTo<TransactionType, covariant TransactionCategory>
     */
    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    /**
     * @return HasMany<Transaction, covariant TransactionCategory>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
