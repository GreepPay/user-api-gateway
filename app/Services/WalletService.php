<?php

namespace App\Services;

use App\Datasource\NetworkHandler;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletService
{
    protected $serviceUrl;
    protected NetworkHandler $walletNetwork;

    
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
            "WALLET_API",
            env("SERVICE_BROKER_URL") . "/broker/greep-wallet/" . env("APP_STATE")
        );

        $this->walletNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    /**
     * makePayment
     *
     * @param string $receiverUuid
     * @param float $amount
     * @param string $currency
     * @return mixed
     */
    public function makePayment($receiverUuid, $amount, $currency) 
    {
        return $this->walletNetwork->post("/v1/transactions", [
            'receiver_uuid' => $receiverUuid,
            'amount' => $amount,
            'currency' => $currency
        ]);
    } 

    
    /**
     * addBeneficiary
     *
     * @param int $user_uuid
     * @return mixed
     */
    public function addBeneficiary($user_uuid) 
    {
        return $this->walletNetwork->post("/v1/beneficiaries", [
            'user_uuid' => $user_uuid,
        ]);
    } 
    
    
    
    /**
     * Remove a beneficiary.
     *
     * @param int $user_uuid
     * @return mixed
     */
    public function removeBeneficiary($user_uuid) 
    {
        return $this->walletNetwork->post("/v1/beneficiaries/{$user_uuid}/soft-delete");
    }

    
    
    
    
    /**
     * initiateTopup
     *
     * @param string $method
     * @param float $amount
     * @param string $currency
      * @param string $payment_metadata
     * @return mixed
     */
    public function initiateTopup($method, $amount, $currency, $payment_metadata) 
    {
        return $this->walletNetwork->post("/v1/transactions", [
            'method' => $method,
            'amount' => $amount,
            'currency' => $currency,
            'payment_metadata'=> $payment_metadata
        ]);
    } 
    
    
    
    
    /**
     * Get exchange rates.
     *
     * @param Request $request
     * @return mixed
     */
    public function getExchangeRate(Request $request)
    {
        $fromCurrency = $request->input('from_currency');
        $toCurrency = $request->input('to_currency');

        return $this->walletNetwork->get("/v1/onramp/rates/{$fromCurrency}", [
            'fromCurrency' => $fromCurrency,
            'to_currency' => $toCurrency
        ]);
    }

    
    /**
     * Get exchange rates.
     *
     * @param Request $request
     * @return mixed
     */
    public function getOnRampCurrencies(Request $request)
    {
        return $this->walletNetwork->get("/v1/onramp/supported-countries");
    }

    
    
    
    
    /**
     * Create a new point transaction
     *
     * @param $grp_amount
     * @return mixed
     */
    public function redeemGRPToken($grp_amount)
    {
        return $this->walletNetwork->post("/v1/point-transactions", [
            'grp_amount' => $grp_amount
        ]);
    }
    
    
/**
         * Get a wallet for the user.
         *
         * @param int $user_id
         * @return wallet
         */
        public function getWallet(int $user_id): Wallet
        {
            // Check if the user already has a wallet
            $wallet = Wallet::where('user_id', $user_id)->first();
        
            // If no wallet exists, create one with default values using the network
            if ($wallet === null) {
                // Prepare the request data for wallet creation
                $requestData = [
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
    
                ];
        
                // Create the wallet using the network
                $response = $this->walletNetwork->post("/v1/wallets", $requestData);
        
                // the network returns the created wallet data, create a local Wallet model instance
                $wallet = new Wallet($response);
                $wallet->save(); // Save the wallet to the local database
            }
        
            return $wallet;
        }

} 
