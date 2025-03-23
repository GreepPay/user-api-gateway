<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBank extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid', // UUID for external identification
        'user_id', // ID of the user
        'wallet_id', // ID of the wallet
        'bank_code', // Bank code
        'bank_name', // Bank name
        'account_no', // Bank account number
        'currency', // Currency of the bank account
        'is_verified', // Verification status
        'meta_data', // Additional metadata
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean', // Cast is_verified to boolean
        'meta_data' => 'array', // Cast meta_data to array (if storing JSON)
    ];

    /**
     * Get the wallet that owns the user bank.
     *
     * @return BelongsTo<Wallet, UserBank>
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the user that owns the user bank.
     *
     * @return BelongsTo<User, UserBank>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}