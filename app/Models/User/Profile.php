<?php

namespace App\Models\User;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class Profile extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-user";

    protected $table = "user_profiles";

    public function user()
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }

    public function customer()
    {
        return $this->hasOne(
            Customer::class,
            foreignKey: "auth_user_id",
            ownerKey: "auth_user_id"
        );
    }

    public function verifications()
    {
        return $this->hasMany(
            Verification::class,
            foreignKey: "auth_user_id",
            ownerKey: "auth_user_id"
        );
    }
}
