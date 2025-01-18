<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Transaction extends Model
{
    protected $fillable = [
        'amount',
        'note',
        'transaction_category_id',
        'wallet_id',
    ];

    /**
     * @return BelongsTo<TransactionCategory, covariant Transaction>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    /**
     * @return BelongsTo<Wallet, covariant Transaction>
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @return HasOneThrough<User, Wallet, covariant Transaction>
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Wallet::class);
    }
}
