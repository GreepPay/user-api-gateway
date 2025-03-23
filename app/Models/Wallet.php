<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'uuid',
        'total_balance',
        'point_balance',
        'credited_amount',
        'debited_amount',
        'locked_balance',
        'credited_point_amount',
        'debited_point_amount',
        'cash_point_balance',
        'cash_per_point',
        'wallet_account',
        'currency',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_balance' => 'decimal:2',
        'point_balance' => 'decimal:2',
        'credited_amount' => 'decimal:2',
        'debited_amount' => 'decimal:2',
        'locked_balance' => 'decimal:2',
        'credited_point_amount' => 'decimal:2',
        'debited_point_amount' => 'decimal:2',
        'cash_point_balance' => 'decimal:2',
        'cash_per_point' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet.
     *
     * @return BelongsTo<User, Wallet>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions associated with the wallet.
     *
     * @return HasMany<Transaction>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all point transactions associated with the wallet.
     *
     * @return HasMany<PointTransaction>
     */
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }
}