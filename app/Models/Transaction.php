<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wallet_id',
        'uuid',
        'dr_or_cr', // debit or credit
        'currency',
        'amount',
        'wallet_balance',
        'charge_id',
        'chargeable_type',
        'description',
        'status', // e.g., default, pending, successful
        'charges',
        'reference',
        'gateway',
        'extra_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
        'charges' => 'decimal:2',
    ];

    /**
     * Get the wallet that owns the transaction.
     *
     * @return BelongsTo<Wallet, Transaction>
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}