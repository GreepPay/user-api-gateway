<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Transaction;
use App\Models\PointTransaction;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

final class WalletQuery
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function getExchangeRate($_, array $args): float
    {
        return $this->walletService->getExchangeRates(
            $args["to_currency"]
        )["data"];
    }

    public function getOnRampCurrencies($_, array $args): array
    {
        return $this->walletService->getOnRampSupportedCountries()["data"];
    }
}
