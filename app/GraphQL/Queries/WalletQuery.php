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
    /**
       * Get the current exchange rate between two currencies.
       *
       * @param mixed $_
       * @param array $args
       * @return float
       */
       public function GetExchangeRate($_, array $args): float
       {
           $walletService = new WalletService();
           
           //Create a request object instead of passing raw strings
           $request = new Request([
               'from_currency' => $args['from_currency'],
               'to_currency' => $args['to_currency']
           ]);
       
           return $walletService->getExchangeRate($request);
       }

    

      /**
        * Get the currently supported on-ramp currencies from the wallet service.
        *
        * @param mixed $_
        * @param array $args
        * @return array
        */
       public function GetOnRampCurrencies($_, array $args): array
       {
           $walletService = new WalletService();
           return $walletService->getOnRampCurrencies();
       }
        
}