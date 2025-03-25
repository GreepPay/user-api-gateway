<?php

namespace App\Models\Auth;

use App\Models\Notification\Notification;
use App\Models\User\Profile;
use App\Models\Wallet\PointTransaction;
use App\Models\Wallet\Transaction;
use App\Models\Wallet\Wallet;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class User extends Model implements AuthenticatableContract
{
    use ReadOnlyTrait, Authenticatable;

    protected $connection = "greep-auth";
    protected $table = "users";

    public function profile()
    {
        return $this->hasOne(Profile::class, "auth_user_id", "id");
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, "auth_user_id", "id");
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
