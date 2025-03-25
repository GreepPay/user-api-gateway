<?php

namespace App\Models\Blockchain;

use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class Trustline extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-blockchain";

    protected $table = "trustlines";

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
