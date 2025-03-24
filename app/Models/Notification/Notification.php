<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use App\Traits\ReadOnlyTrait;

class Notification extends Model
{
    use HasFactory;
    // use ReadOnlyTrait;

    protected $connection = 'greep-notification';

    protected $guarded = [];
    
    
    /**
         * Scope for auth user
         */
        public function scopeIsAuth(Builder $query): void
        {
            $query->where("auth_user_id", auth()->id());
        }

}
