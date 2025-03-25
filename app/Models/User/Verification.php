<?php

namespace App\Models\User;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class Verification extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-user";

    protected $table = "verifications";

    public function user()
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }
}
