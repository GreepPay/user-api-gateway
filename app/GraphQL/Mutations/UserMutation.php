<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\UserService;

final class UserMutation
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    
     // * Convert DateTime string to Carbon instance.
     
    private function parseDateTime(?string $date): ?string
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : null;
    }


    /**
     * Update user profile.
     *
     * @param mixed $_
     * @param array $args
     * @return User
     */
    public function updateUserProfile($_, array $args): User
    {
        $user = $this->authService->updateProfile([
            'user_uuid' => $args['user_uuid'],
            'first_name' => $args['first_name'] ?? null,
            'last_name' => $args['last_name'] ?? null,
            'profile_photo' => $args['profile_photo'] ?? null,
            'state' => $args['state'] ?? null,
            'country' => $args['country'] ?? null,
        ]);
    
        return $user;
    }

    public function deleteProfile($_, array $args)
    {
        $response = $this->userService->deleteProfile(
            new Request([
                "auth_user_id" => $args["auth_user_id"],
            ])
        );

        return $response["data"]["deleteProfile"] ?? null;
    }
    
} 




