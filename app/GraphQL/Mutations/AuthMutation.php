<?php

namespace App\GraphQL\Mutations;

use App\Services\AuthService;
use App\Services\NotificationService;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Str;

final class AuthMutator
{
    protected $authService;
    protected $notificationService;
    protected $userService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->notificationService = new NotificationService();
        $this->userService = new UserService();
    }

    /**
     * Sign up a new user.
     *
     * @param mixed $_
     * @param array $args
     * @return User
     */
    public function SignUp($_, array $args): User
    {
        $user = $this->authService->saveUser(
          new Request([
            "first_name" => $args["first_name"],
            "last_name" => $args["last_name"],
            "email" => $args["email"],
            "password" => $args["password"],
            "state" => $args["state"],
            "country" => $args["country"],
            "default_currency" => $args["default_currency"],
            "uuid" => Str::uuid(),
        ])
        
        );

        return $user;
    }

    /**
     * Verify user identity.
     *
     * @param mixed $_
     * @param array $args
     * @return User
     */
    public function VerifyIdentity($_, array $args): User
    {
        $user = $this->userService->verifyIdentity(
            new Request([
            $args["user_uuid"],
            $args["id_number"],
            $args["id_country"],
            $args["id_type"]
            ])
        );

        return $user;
    }

    /**
     * Resend email OTP.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function ResendEmailOTP($_, array $args): bool
    {
        $user = $this->authService->resendEmailOTP(      
        new Request([
           "email" =>  $args["email"]
        ])
        
       );

        return true;
    }

    /**
     * Verify OTP.
     *
     * @param mixed $_
     * @param array $args
     * @return User
     */
    public function VerifyOTP($_, array $args): User
    {
        $user = $this->authService->verifyOTP(
          new Request([
           "email" => $args["email"],
           "otp_code" => $args["otp_code"],
           "user_uuid"=> $args["user_uuid"]
           
                ])
        );

        return $user;
    }

    /**
     * Send reset password PIN.
     *
     * @param mixed $_
     * @param array $args
     * @return Boolean
     */
    public function SendResetPasswordPin($_, array $args): bool
    {
        return $this->authService->sendResetPasswordPin(
         new Request([
            "email" =>  $args["email"]
         ])
         
        );
    }

    /**
     * Sign in a user.
     *
     * @param mixed $_
     * @param array $args
     * @return array
     */
    public function SignIn($_, array $args): array
    {
        $response = $this->authService->signIn(
        new Request([
            "email" => $args["email"],
           "password" =>  $args["password"],
       ])
       
       );

        return $response;
    }

    /**
     * Reset user password.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function ResetPassword($_, array $args): bool
    {
        return $this->authService->resetPassword(
         new Request([
             "user_uuid" => $args["user_uuid"],
            "otp_code" =>  $args["otp_code"],
            "new_password" =>  $args["new_password"],
            
        ])
        );
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
        $user = $this->authService->updateProfile(
         new Request([
            "user_uuid" => $args["user_uuid"],
            "first_name" => $args["first_name"] ?? null,
            "last_name" => $args["last_name"] ?? null,
            "profile_photo" => $args["profile_photo"] ?? null,
            "state" => $args["state"] ?? null,
            "country" => $args["country"] ?? null,
        ])
       );
        return $user;
    }

    /**
     * Update user password.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function UpdateUserPassword($_, array $args): bool
    {
        return $this->authService->updatePassword(
         new Request([
             "old_password" => $args["old_password"],
             "new_password" => $args["new_password"]
        ])
        );
    }
    
    
}
