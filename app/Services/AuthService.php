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
     * @param Request $request
     * @return mixed
     */
    public function saveUser(Request $request)
    {
        return $this->authNetwork->post("/v1/auth/users", $request->all());
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
    public function resetUserOtp(Request $request)
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
        return $this->authNetwork->post("/v1/auth/update-password", $request->all());
    }

    /**
     * Update user profile.
     *
     * @param Request $request
     * @return mixed
     */
    public function updateUserProfile(Request $request)
    {
        return $this->authNetwork->post("/v1/auth/update-profile", $request->all());
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
     * Delete a user.
     *
     * @param Request $request
     * @return mixed
     */
    public function deleteUser(Request $request)
    {
        $userId = $request->route('id'); // Extract user ID from the request
        return $this->authNetwork->delete("/v1/auth/users/{$userId}");
    }
}