<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relationship: UserProfile belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user's type (e.g., regular user, premium user).
     */
    public function getUserTypeAttribute()
    {
        return $this->attributes['user_type'] ?? 'standard';
    }

    /**
     * Relationship: UserProfile has one Wallet.
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: UserProfile has many Transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id');
    }
    
    /**
     * Relationship: UserProfile has many Transactions.
     */
    public function  pointTransactions()
    {
        return $this->hasMany(PointTransaction::class, 'user_id', 'user_id');
    }
}
