<?php
namespace App\Models\Wallet;

use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "wallets";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
