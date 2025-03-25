<?php

namespace App\Models\Blockchain;

use App\Models\Wallet\Wallet;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class Account extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-blockchain";

    protected $table = "accounts";

    public function wallet()
    {
        return $this->hasOne(Wallet::class, "blockchain_account_id", "id");
    }

    public function trustlines()
    {
        return $this->hasMany(Trustline::class);
    }
}
