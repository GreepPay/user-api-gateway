<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

final class WalletQuery
{
    /**
     * Fetch transaction history for the authenticated user's wallet with pagination.
     *
     * @param  mixed  $_
     * @param  array  $args
     * @return LengthAwarePaginator
     */
     public function transactionHistory($_, array $args): LengthAwarePaginator
     {
         $user = Auth::user();
     
         if (!$user) {
             throw new \Exception("User not authenticated.");
         }
     
         $page = $args['page'] ?? 1;
         $perPage = $args['perPage'] ?? 10;
     
         // Get the user's wallet
         $wallet = $user->wallet;
     
         if (!$wallet) {
             throw new \Exception("User does not have a wallet.");
         }
     
         // Fetch transactions for the wallet
         return Transaction::where('wallet_id', $wallet->id)
             ->orderBy('created_at', 'desc') // Order by `created_at` in descending order
             ->orderBy('chargeable_type')    // Then order by `chargeable_type` (ascending by default)
             ->orderBy('dr_or_cr')           // Then order by `dr_or_cr` (ascending by default)
             ->orderBy('status') 
             ->paginate($perPage, ['*'], 'page', $page);
     }