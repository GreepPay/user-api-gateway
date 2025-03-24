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
    public function sendResetPasswordPin($request)
    {
        return $this->authNetwork->post("/v1/auth/reset-otp", $request->all()); 
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
     * @param Request $request
     * @return mixed
     */
    public function updateUserProfile(Request $request)
    {
        return $this->authNetwork->put("/v1/auth/update-profile", $request->all()); 
          
    }

    /**
     * Resend email OTP.
     *
     * @param Request $request
     * @return mixed
     */
    public function resendEmailOTP(Request $request)
    {
        return $this->authNetwork->post("/v1/auth/resend-otp", $request->all()); 
        
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
     * 
     * @param Request $request
     * @return bool
     */
    public function resetPassword(Request $request): bool {
        // Find the user by UUID
        $user = User::where("uuid", $userUuid)->first();
        $userUuid = $request->input('user_uuid');
        $otpCode = $request->input('otp_code');
        $newPassword = $request->input('new_password'); 

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
