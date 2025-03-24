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

    
    public function updateProfile($request)
    {
        return $this->userNetwork->put("/v1/profiles", $request->all());
    }

    
    
    /**
     * Verify user identity.
     *
     * @param $request
     * @return mixed
     */
    public function verifyIdentity($request)
    {
        return $this->userNetwork->post("/v1/verification", $request->all()); 
         
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
