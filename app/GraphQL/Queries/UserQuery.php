<?php

namespace App\GraphQL\Queries;

use App\Models\Auth\User;
use App\Services\UserService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserQuery
{
    public function getAuthUser($_, array $args): ?User
    {
        $user = Auth::user();

        if ($user) {
            return $user;
        }

        return null;
    }

    public function searchUsers($_, array $args): array
    {
        $query = $args["query"] ?? ""; // Default to empty if not provided

        return User::where("first_name", "like", "%{$query}%")
            ->orWhere("last_name", "like", "%{$query}%")
            ->orWhere("email", "like", "%{$query}%")
            ->take(10)
            ->get()
            ->toArray();
    }
}
