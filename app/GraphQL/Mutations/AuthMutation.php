<?php

namespace App\GraphQL\Mutations;

use App\Services\AuthService;
use App\Services\NotificationService;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class AuthMutator
{
    protected $authService;
    protected $notificationService;
    protected $userService;

    public function __construct(
        AuthService $authService,
        NotificationService $notificationService,
        UserService $userService
    ) {
        $this->authService = $authService;
        $this->notificationService = $notificationService;
        $this->userService = $userService;
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
        $user = $this->authService->saveUser([
            'first_name' => $args['first_name'],
            'last_name' => $args['last_name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
            'state' => $args['state'],
            'country' => $args['country'],
            'default_currency' => $args['default_currency'],
            'uuid' => Str::uuid(),
        ]);

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
            $args['user_uuid'],
            $args['id_number'],
            $args['id_country'],
            $args['id_type']
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
        $user = $this->authService->findUserByEmail($args['email']);

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
            $args['email'],
            $args['otp_code'],
            $args['user_uuid']
        );

        return $user;
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
            $args['email'],
            $args['password']
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
            $args['user_uuid'],
            $args['otp_code'],
            $args['new_password']
        );
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
            $args['old_password'],
            $args['new_password']
        );
    }
    
   
}