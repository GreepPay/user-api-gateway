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
     * @param Request $request
     * @return mixed
     */
    public function initiateTopup(Request $request) 
    {
        return $this->walletNetwork->post("/v1/transactions", $request->all());
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

        return $this->walletNetwork->get("/v1/onramp/rates/{$fromCurrency}", $request->all());
    }

    
    /**
     * Get exchange rates.
     *
     *
     * @return mixed
     */
    public function getOnRampCurrencies()
    {
        return $this->walletNetwork->get("/v1/onramp/supported-countries");
    }


    
    /**
     * Create a new point transaction
     *
     * @param $request
     * @return mixed
     */
    public function redeemGRPToken($request)
    {
        return $this->walletNetwork->post("/v1/point-transactions", $request->all());
    }
    

} 
