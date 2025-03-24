<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

class UserService
{
    protected $serviceUrl;
    protected NetworkHandler $userNetwork;
    /**
     * construct
     *
     * @param bool $useCache
     * @param array $headers
     * @param string $apiType
     * @return mixed
     */

    public function __construct(
        bool $useCache = true,
        array $headers = [],
        string $apiType = "graphql"
    ) {
        $this->serviceUrl = env(
            "USER_API",
            env("SERVICE_BROKER_URL") . "/broker/greep-user/" . env("APP_STATE")
        );

        $this->userNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    public function createProfile($request)
    {
        return $this->userNetwork->post("/v1/profiles", $request->all());
    }

    public function updateProfile($request)
    {
        return $this->userNetwork->put("/v1/profiles", $request->all());
    }

    public function deleteProfile($request)
    {
        return $this->userNetwork->delete("/v1/profiles", $request->all());
    }
    
    
  
    /**
     * Create a new user.
     *
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data)
    {
        return $this->userNetwork->post("/v1/auth/users", [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'state' => $data['state'],
            'country' => $data['country'],
            'default_currency' => $data['default_currency'],
            'uuid' => Str::uuid(),
        ]);
    }
    
    /**
     * Update user profile.
     *
     * @param array $data
     * @return mixed
     */
    public function updateUserProfile(array $data)
    {
        return $this->userNetwork->put("/v1/auth/update-profile", [
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'profile_photo' => $data['profile_photo'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? null,
        ]);
    }

    
    /**
     * Verify user identity.
     *
     * @param string $userUuid
     * @param string $idNumber
     * @param string $idCountry
     * @param string $idType
     * @return mixed
     */
    public function verifyIdentity(string $userUuid, string $idNumber, string $idCountry, string $idType)
    {
        return $this->userNetwork->post("/v1/verification", [
            'user_uuid' => $userUuid,
            'id_number' => $idNumber,
            'id_country' => $idCountry,
            'id_type' => $idType,
        ]);
    }
    
   
    

    /**
     * Send reset password PIN.
     *
     * @param array $data
     * @return mixed
     */
    public function sendResetPasswordPin(array $data)
    {
        return $this->userNetwork->post("/v1/auth/update-password", [
            'email' => $data['email'],
        ]);
    }
    /**
     * Search users by name or email.
     *
     * @param string $query
     * @return array
     */
    public function searchUsers(string $query): array
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->toArray();
    }

}
