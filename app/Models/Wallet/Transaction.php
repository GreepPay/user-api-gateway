<?php
namespace App\Models\Wallet;

use App\Models\Auth\User;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "transactions";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
