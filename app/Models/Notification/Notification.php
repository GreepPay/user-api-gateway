<?php

namespace App\Models\Notification;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class Notification extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-notification";

    protected $table = "notifications";

    public function user()
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "auth_user_id",
            ownerKey: "id"
        );
    }
}
