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
            // Fetch wallet and profile info
            $walletService = new WalletService();
            $user->wallet = $walletService ->getWallet($user->id);
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
     * @return LengthAwarePaginator
     */
    public function GetTransactions($_, array $args): LengthAwarePaginator
    {
        $user = Auth::user();
        $type = $args['type'] ?? 'both'; // 'both', 'transaction', or 'point_transaction'
        $perPage = $args['perPage'] ?? 10;
        $page = $args['page'] ?? 1;

        $transactions = collect();

        if ($type === 'both' || $type === 'transaction') {
            $transactions = $transactions->merge(
                Transaction::where('user_id', $user->id)->get()
            );
        }

        if ($type === 'both' || $type === 'point_transaction') {
            $transactions = $transactions->merge(
                PointTransaction::where('user_id', $user->id)->get()
            );
        }

        // Paginate the results
        return new LengthAwarePaginator(
            $transactions->forPage($page, $perPage),
            $transactions->count(),
            $perPage,
            $page
        );
    }

    /**
     * Get a single transaction by UUID.
     *
     * @param mixed $_
     * @param array $args
     * @return Transaction|null
     */
    public function GetSingleTransaction($_, array $args): ?Transaction
    {
        return Transaction::where('uuid', $args['uuid'])->first();
    }

    /**
     * Get a single point transaction by UUID.
     *
     * @param mixed $_
     * @param array $args
     * @return PointTransaction|null
     */
    public function GetSinglePointTransaction($_, array $args): ?PointTransaction
    {
        return PointTransaction::where('uuid', $args['uuid'])->first();
    }

    /**
     * Get a paginated list of beneficiaries for the authenticated user.
     *
     * @param mixed $_
     * @param array $args
     * @return LengthAwarePaginator
     */
    public function GetBeneficiaries($_, array $args): LengthAwarePaginator
    {
        $user = Auth::user();
        $perPage = $args['perPage'] ?? 10;
        $page = $args['page'] ?? 1;

        return Beneficiary::where('user_id', $user->id)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get a paginated list of notifications for the authenticated user.
     *
     * @param mixed $_
     * @param array $args
     * @return LengthAwarePaginator
     */
    public function GetNotifications($_, array $args): LengthAwarePaginator
    {
        $user = Auth::user();
        $perPage = $args['perPage'] ?? 10;
        $page = $args['page'] ?? 1;

        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
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

