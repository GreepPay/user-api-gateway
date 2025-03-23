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

       
     
     
     
     /**
         * Get a wallet for the user.
         *
         * @param  mixed  $_
         * @param  array  $args
         * @return LengthAwarePaginator
         */
        public function getuserWallet($_, array $args): LengthAwarePaginator
        {
            
            $user = Auth::user();
        
            if (!$user) {
                throw new \Exception("User not authenticated.");
            }
        
            $page = $args['page'] ?? 1;
            $perPage = $args['perPage'] ?? 10;
        
            // Get the user's wallet using WalletService
            $walletService = new WalletService();
            $wallet = $walletService->getWallet($user->id);
        
            if (!$wallet) {
                throw new \Exception("User does not have a wallet.");
            }
            // Fetch point transactions for the wallet
            return Wallet::where('wallet_id', $wallet->id)
                ->orderBy('created_at', 'desc') // Order by `created_at` in descending order
                ->orderBy('total_balance')    // Then order by total balance
                ->orderBy(' user_id')           // Then order by `dr_or_cr` (ascending by default)
                ->paginate($perPage, ['*'], 'page', $page);
        
        }
        
        
        /**
              * Get a user bank account for the authenticated user.
              *
              * @param  mixed  $_
              * @param  array  $args
              * @return LengthAwarePaginator
              */
             public function getUserBank($_, array $args): LengthAwarePaginator
             {
                 // Authenticate the user
                 $user = Auth::user();
         
                 if (!$user) {
                     throw new \Exception("User not authenticated.");
                 }
         
                 // Extract arguments
                 $user_id = $args['user_id'];
                 $wallet_id = $args['wallet_id'];
                 $account_no = $args['account_no'];
                 $page = $args['page'] ?? 1;
                 $perPage = $args['perPage'] ?? 10;
         
                 // Ensure the authenticated user matches the requested user_id
                 if ($user->id !== $user_id) {
                     throw new \Exception("Unauthorized access.");
                 }
         
                 // Fetch the user bank accounts with pagination
                 return UserBank::where('user_id', $user_id)
                     ->where('wallet_id', $wallet_id)
                     ->where('account_no', $account_no)
                     ->orderBy('created_at', 'desc') // Order by creation date (descending)
                     ->paginate($perPage, ['*'], 'page', $page);
             }
        
}