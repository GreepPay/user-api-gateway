<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use App\Models\Profile;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\PointTransaction;
use App\Models\Beneficiary;
use App\Models\Notification;
use App\Services\UserService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserQuery
{
    /**
     * Get the authenticated user with wallet and profile info.
     *
     * @param mixed $_
     * @param array $args
     * @return User|null
     */
    public function GetAuthUser($_, array $args): ?User
    {
        $user = Auth::user();

        if ($user) {
            // profile info
            $user->profile = Profile::where('user_id', $user->id)->first();

            return $user;
        }

        return null;
    }

    /**
     * Get a paginated mix of all the authenticated user's transactions and point transactions.
     *
     * @param mixed $_
     * @param array $args
     * @return $query
     */
     public function GetTransactions($_, array $args)
       {
           $user = Auth::user();
           $type = $args['type'] ?? 'both'; // 'both', 'transaction', or 'point_transaction'
   
           $query = collect();
   
           if ($type === 'both' || $type === 'transaction') {
               $query = $query->merge(
                   Transaction::where('user_id', $user->id)->get()
               );
           }
   
           if ($type === 'both' || $type === 'point_transaction') {
               $query = $query->merge(
                   PointTransaction::where('user_id', $user->id)->get()
               );
           }
   
           return $query->paginate();
       }


    /**
     * Get a paginated list of beneficiaries for the authenticated user.
     *
     * @param mixed $_
     * @param array $args
     * @return LengthAwarePaginator
     */
     public function GetBeneficiaries($_, array $args)
      {
          $user = Auth::user();
  
          return Beneficiary::where('user_id', $user->id)->paginate();
      }
   
    /**
     * Search users by a query string via the User Service.
     *
     * @param mixed $_
     * @param array $args
     * @return array
     */
    public function searchUsers($_, array $args): array
    {
        $query = $args['query'] ?? ''; // Default to empty if not provided
         $userService = new UserService();
    
        if (empty($query)) {
            return []; // Return empty array if query is not provided
        }
    
        return $this->userService->searchUsers($query);
    }


}

