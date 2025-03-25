<?php

namespace App\Models\User;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class Customer extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-user";

    protected $table = "customers";

    public function user()
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }
}
