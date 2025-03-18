<?php

namespace App\Services;

use App\Datasource\NetworkHandler;

class WalletService
{
    protected $serviceUrl;
    protected NetworkHandler $walletNetwork;

    public function __construct(
        bool $useCache = true,
        array $headers = [],
        string $apiType = "graphql"
    ) {
        // Set the service URL for the wallet API
        $this->serviceUrl = env(
            "WALLET_API",
            env("SERVICE_BROKER_URL") . "/broker/greep-wallet/" . env("APP_STATE")
        );

        // Initialize the network handler
        $this->walletNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    /**
     * Create a new transaction
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function createTransaction($request)
    {
        return $this->walletNetwork->post("/v1/transactions", $request->all());
    }


    /**
     * Delete a transaction.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function softDeleteTransaction($request){
        
          $transactionId = $request->input('transaction_id');
        return $this->walletNetwork->post("/v1/transactions/{$transactionId}/soft-delete", $request->all());
    }
}