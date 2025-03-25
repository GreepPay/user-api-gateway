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
            env("SERVICE_BROKER_URL") .
                "/broker/greep-wallet/" .
                env("APP_STATE")
        );

        $this->walletNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    // Wallet

    /**
     * Create a wallet
     *
     * @param array $data
     * @return mixed
     */
    public function createWallet(array $data)
    {
        return $this->walletNetwork->post("/v1/wallets", $data);
    }

    // Beneficiaries

    /**
     * Create a beneficiary
     *
     * @param array $data
     * @return mixed
     */
    public function createBeneficiary(array $data)
    {
        return $this->walletNetwork->post("/v1/beneficiaries", $data);
    }

    /**
     * Update a beneficiary
     *
     * @param string $id
     * @param array $data
     * @return mixed
     */
    public function updateBeneficiary(string $id, array $data)
    {
        return $this->walletNetwork->put("/v1/beneficiaries/{$id}", $data);
    }

    /**
     * Delete a beneficiary
     *
     * @param string $id
     * @return mixed
     */
    public function deleteBeneficiary(string $id)
    {
        return $this->walletNetwork->post(
            "/v1/beneficiaries/{$id}/soft-delete"
        );
    }

    // Collections (OnRamp)

    /**
     * Get OnRamp Supported Countries
     *
     * @return mixed
     */
    public function getOnRampSupportedCountries()
    {
        return $this->walletNetwork->get("/v1/onramp/supported-countries");
    }

    /**
     * Get channels by country code
     *
     * @param string $countryCode
     * @return mixed
     */
    public function getOnRampChannelsByCountryCode(string $countryCode)
    {
        return $this->walletNetwork->get("/v1/onramp/channels/{$countryCode}");
    }

    /**
     * Get network by country code
     *
     * @param string $countryCode
     * @return mixed
     */
    public function getOnRampNetworkByCountryCode(string $countryCode)
    {
        return $this->walletNetwork->get("/v1/onramp/networks/{$countryCode}");
    }

    /**
     * Get exchange rates
     *
     * @param string $toCurrency. Default from currency is USD
     * @return mixed
     */
    public function getExchangeRates(string $toCurrency)
    {
        return $this->walletNetwork->get(
            "/v1/onramp/exchange-rates/{$toCurrency}"
        );
    }

    /**
     * Get payment collection
     *
     * @param int $id
     * @return mixed
     */
    public function getPaymentCollection($id)
    {
        return $this->walletNetwork->get("/v1/onramp/collection/{$id}");
    }

    /**
     * Create payment collection
     *
     * @param array $data
     * @param int $wallet_id
     * @param int $user_id
     * @return mixed
     */
    public function createPaymentCollection(array $data, $wallet_id, $user_id)
    {
        return $this->walletNetwork->post(
            "/v1/onramp/{$wallet_id}/{$user_id}",
            $data
        );
    }

    /**
     * Accept payment collection
     * @param int $id
     * @return mixed
     */
    public function acceptPaymentCollection($id)
    {
        return $this->walletNetwork->post("/v1/onramp/accept/{$id}", []);
    }

    /**
     * Deny payment collection
     * @param int $id
     * @return mixed
     */
    public function denyPaymentCollection($id)
    {
        return $this->walletNetwork->post("/v1/onramp/deny/{$id}", []);
    }

    /**
     * Cancel payment collection
     * @param int $id
     * @return mixed
     */
    public function cancelPaymentCollection($id)
    {
        return $this->walletNetwork->post("/v1/onramp/cancel/{$id}", []);
    }

    /**
     * Refund payment collection
     * @param int $id
     * @return mixed
     */
    public function refundPaymentCollection($id)
    {
        return $this->walletNetwork->post("/v1/onramp/refund/{$id}", []);
    }

    // Point transactions

    /**
     * Create point transaction
     * @param array $data
     * @return mixed
     */
    public function createPointTransaction(array $data)
    {
        return $this->walletNetwork->post("/v1/point-transactions", $data);
    }

    // Update point transaction status
    /**
     * Update point transaction status
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updatePointTransactionStatus($id, $status)
    {
        return $this->walletNetwork->post(
            "/v1/point-transactions/{$id}/status",
            ["status" => $status]
        );
    }

    // Transaction (normal)

    /**
     * Create transaction
     * @param array $data
     * @return mixed
     */
    public function createTransaction(array $data)
    {
        return $this->walletNetwork->post("/v1/transactions", $data);
    }

    /**
     * Update transaction status
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTransactionStatus($id, $status)
    {
        return $this->walletNetwork->post("/v1/transactions/{$id}/status", [
            "status" => $status,
        ]);
    }
}
