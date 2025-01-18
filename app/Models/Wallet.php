<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperWallet
 */
class Wallet extends Model
{
    protected $fillable = [
        'icon',
        'name',
        'slug',
        'user_id',
    ];

    /**
     * @return BelongsTo<User, covariant Wallet>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
