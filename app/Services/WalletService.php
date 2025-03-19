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
    
    
   
    /**
        * Get a wallet for the user.
        *
        * @param int $user_id
        * @return Wallet
        */
       public function getWallet(int $user_id): Wallet
       {
           // Check if the user already has a wallet
           $wallet = Wallet::where('user_id', $user_id)->first();
   
           // If no wallet exists, create one with default values
           if ($wallet === null) {
               $wallet = Wallet::create([
                   'user_id' => $user_id,
                   'uuid' => \Illuminate\Support\Str::uuid(), // Generate a UUID
                   'total_balance' => 0,
                   'point_balance' => 0,
                   'credited_amount' => 0,
                   'debited_amount' => 0,
                   'locked_balance' => 0,
                   'credited_point_amount' => 0,
                   'debited_point_amount' => 0,
                   'cash_point_balance' => 0,
                   'cash_per_point' => 0,
                   'currency' => 'USDC', // Default currency
                   'state' => 'active', // Default state
               ]);
           }
   
           return $wallet;
       }
       
       
       
       /**
        * soft Delete a transaction.
        *
        * @param \Illuminate\Http\Request $request
        * @return mixed
        */
       public function softDeleteWallet($request){
           
             $walletId = $request->input('wallet_id');
           return $this->walletNetwork->post("/v1/wallets/{$walletId}/soft-delete", $request->all());
       }

       /**
        * Create a new wallet
        *
        * @param \Illuminate\Http\Request $request
        * @return mixed
        */
       
       public function createWallet($request)
       {
           return $this->walletNetwork->post("/v1/wallets", $request->all());
       }
}

