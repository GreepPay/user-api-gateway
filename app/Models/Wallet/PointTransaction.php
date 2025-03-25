<?php
namespace App\Models\Wallet;

use App\Models\Auth\User;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use ReadOnlyTrait;

    protected $connection = "greep-wallet";

    protected $table = "point_transactions";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
