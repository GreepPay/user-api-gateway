<?php

namespace App\Models\Wallet;

use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "beneficiaries";

    public function owner()
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "owner_id",
            ownerKey: "id"
        );
    }

    public function beneficiary()
    {
        return $this->belongsTo(
            User::class,
            foreignKey: "user_uuid",
            ownerKey: "id"
        );
    }
}
