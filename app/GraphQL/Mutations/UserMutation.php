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

        $this->userService = new UserService();
    }

     /**
      * Update user profile.
      *
      * @param mixed $_
      * @param array $args
      * @return array
      */
     public function updateProfile($_, array $args): array
     {
         $user = Auth::user();
         if (!$user) {
             throw new \Exception("User not authenticated.");
         }
     
         return $this->userService->updateProfile(
          new Request([
             'user_type' =>  $args["user_type"],
             'profile_picture'=>  $args['profile_picture'] ?? null,
               'registration_number' => $args['profileData']['registration_number'] ?? null,
               'logo' => $args['profileData']['logo'] ?? null,
               'location' => $args['profileData']['location'] ?? null,
             'banner'=>  $args['profileData']['banner'] ?? null,
               'description' => $args['profileData']['description'] ?? null,
              'website' =>  $args['profileData']['website'] ?? null,
              'resident_permit' => $args['profileData']['resident_permit'] ?? null,
              'passport' =>  $args['profileData']['passport'] ?? null
             ])
         );
         
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




