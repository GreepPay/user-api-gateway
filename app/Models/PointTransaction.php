<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'dr_or_cr',
        'wallet_id',
        'user_id',
        'amount',
        'point_balance',
        'charge_id',
        'chargeable_type',
        'description',
        'state',
        'status',
        'reference',
        'extra_data',
        'currency',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'point_balance' => 'decimal:2',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}