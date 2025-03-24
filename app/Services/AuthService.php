<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

use Illuminate\Http\Request;

class AuthService
{
    protected $serviceUrl;
    protected $authNetwork;

    /**
     * construct
     *
     * @param bool $useCache
     * @param array $headers
     * @param string $apiType
     * @return mixed
     */
    public function __construct(
        $useCache = true,
        $headers = [],
        $apiType = "graphql"
    ) {
        $this->serviceUrl = env(
            "AUTH_API",
            env("SERVICE_BROKER_URL") . "/broker/greep-auth/" . env("APP_STATE")
        );
        $this->authNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    /**
     * Get the authenticated user.
     *
     * @return mixed
     */
    public function authUser()
    {
        return $this->authNetwork->get("/v1/auth/me");
    }

    /**
     * Create a new user.
     *
     * @param array $request
     * @return mixed
     */
    public function saveUser(array $request)
    {
        return $this->authNetwork->post("/v1/auth/users", $request);
    }

    /**
     * Authenticate a user.
     *
     * @param Request $request
     * @return mixed
     */
    public function authenticateUser(Request $request)
    {
        return $this->authNetwork->post("/v1/auth/login", $request->all());
    }

    /**
     * Reset user OTP.
     *
     * @param Request $request
     * @return mixed
     */
    public function sendResetPasswordPin(string $email)
    {
        return $this->authNetwork->post("/v1/auth/reset-otp", [
            "email" => $email,
        ]);
    }

    /**
     * Verify user OTP.
     *
     * @param Request $request
     * @return mixed
     */
    public function verifyUserOtp(Request $request)
    {
        return $this->authNetwork->post("/v1/auth/verify-otp", $request->all());
    }

    /**
     * Update user password.
     *
     * @param Request $request
     * @return mixed
     */
    public function updatePassword(Request $request)
    {
        return $this->authNetwork->post(
            "/v1/auth/update-password",
            $request->all()
        );
    }

    /**
     * Update user profile.
     *
     * @param array $data
     * @return mixed
     */
    public function updateUserProfile(array $data)
    {
        return $this->authNetwork->put("/v1/auth/update-profile", [
            "first_name" => $data["first_name"] ?? null,
            "last_name" => $data["last_name"] ?? null,
            "profile_photo" => $data["profile_photo"] ?? null,
            "state" => $data["state"] ?? null,
            "country" => $data["country"] ?? null,
        ]);
    }

    /**
     * Resend email OTP.
     *
     * @param array $data
     * @return mixed
     */
    public function resendEmailOTP(array $data)
    {
        return $this->authNetwork->post("/v1/auth/resend-otp", [
            "email" => $data["email"],
        ]);
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        return User::where("email", $email)->first();
    }

    /**
     * Log out the authenticated user.
     *
     * @return mixed
     */
    public function logOut()
    {
        return $this->authNetwork->post("/v1/auth/logout");
    }

    /**
     * Reset user password using OTP.
     *
     * @param string $userUuid
     * @param string $otpCode
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword(
        string $userUuid,
        string $otpCode,
        string $newPassword
    ): bool {
        // Find the user by UUID
        $user = User::where("uuid", $userUuid)->first();

        if (!$user) {
            throw new \Exception("User not found.");
        }

        // Verify the OTP code (you can use your OTP verification logic here)
        if (!$this->verifyOtp($user->id, $otpCode)) {
            throw new \Exception("Invalid OTP code.");
        }

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        return true;
    }

    /**
     * Delete a user.
     *
     * @param Request $request
     * @return mixed
     */
    public function deleteUser(Request $request)
    {
        $userId = $request->route("id"); // Extract user ID from the request
        return $this->authNetwork->delete("/v1/auth/users/{$userId}");
    }
}
