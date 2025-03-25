<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use App\Models\Auth\User;
use App\Services\AuthService;
use App\Services\BlockchainService;
use App\Services\NotificationService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class AuthMutator
{
    protected $authService;
    protected $notificationService;
    protected $userService;
    protected $blockchainService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->notificationService = new NotificationService();
        $this->userService = new UserService();
        $this->blockchainService = new BlockchainService();
    }

    // Sign Up
    public function signUp($_, array $args): User
    {
        // Create a new user in auth service
        $authUser = $this->authService->saveUser([
            "firstName" => $args["first_name"],
            "lastName" => $args["last_name"],
            "email" => $args["email"],
            "password" => $args["password"],
        ]);

        $authUser = $authUser["data"]["user"];

        // Create a default profile for the user
        $this->userService->createProfile([
            "user_type" => "Customer",
            "auth_user_id" => $authUser["id"],
            "default_currency" => $args["default_currency"],
            "profileData" => [
                "country" => $args["country"],
                "city" => $args["state"],
            ],
        ]);

        // Let create a default wallet for the user

        // But before creating the wallet, a need an account to be generated on the blockchain for the new user
        $blockchainAccount = $this->blockchainService->createAccount([
            "account_type" => "user",
            "status" => "inactive",
        ]);

        $blockchainAccount = $blockchainAccount["data"];

        // Create a default wallet for the user
        $this->walletService->createWallet([
            "user_id" => $authUser["id"],
            "blockchain_account_id" => $blockchainAccount["id"],
            "currency" => "USDC",
        ]);

        // Send a verify email notification to the user
        // TODO: Implement email verification notification

        return User::where("id", $authUser["id"])->first();
    }

    public function signIn($_, array $args): User
    {
        $userAuth = $this->authService->signIn([
            "username" => $args["email"],
            "password" => $args["password"],
        ]);

        return [
            "token" => $userAuth["data"]["token"],
            "user" => User::where("id", $userAuth["data"]["id"])->first(),
        ];
    }

    public function resendEmailOTP($_, array $args): bool
    {
        $userWithEmail = User::where("email", $args["email"])->first();

        if (!$userWithEmail) {
            throw new GraphQLException("User with email not found");
        }

        // First reset user OTP
        $this->authService->resetOtp([
            "email" => $userWithEmail->email,
        ]);

        // TODO: Implement email verification notification

        return true;
    }

    public function sendResetPasswordOTP($_, array $args): bool
    {
        $userWithEmail = User::where("email", $args["email"])->first();

        if (!$userWithEmail) {
            throw new GraphQLException("User with email not found");
        }

        // First reset user OTP
        $this->authService->resetOtp([
            "email" => $userWithEmail->email,
        ]);

        // TODO: Implement email reset password notification

        return true;
    }

    public function resetPassword($_, array $args): bool
    {
        $userWithUuid = User::where("uuid", $args["user_uuid"])->first();

        if (!$userWithUuid) {
            throw new GraphQLException("User not found");
        }

        // First verify the user OTP
        $this->authService->verifyOtp([
            "userUuid" => $userWithUuid->uuid,
            "email" => $userWithUuid->email,
            "otp" => $args["otp"],
        ]);

        // If it succeeds, reset the password
        $this->authService->resetPassword([
            "currentPassword" => null,
            "newPassword" => $args["new_password"],
        ]);

        return true;
    }

    public function updatePassword($_, array $args): bool
    {
        $authUser = Auth::user();

        $this->authService->updatePassword([
            "currentPassword" => $args["current_password"],
            "newPassword" => $args["new_password"],
        ]);

        return true;
    }

    public function verifyUserOTP($_, array $args): bool
    {
        $userWithUuid = User::where("uuid", $args["user_uuid"])->first();

        if (!$userWithUuid) {
            throw new GraphQLException("User with UUID not found");
        }

        $payload = [
            "userUuid" => $userWithUuid->uuid,
            "email" => $userWithUuid->email,
            "otp" => $args["otp"],
        ];

        $this->userService->verifyOTP($payload);

        return true;
    }
}
