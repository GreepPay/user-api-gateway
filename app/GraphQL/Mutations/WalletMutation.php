<?php

namespace App\GraphQL\Mutations;

use App\Exceptions\GraphQLException;
use Carbon\Carbon;
use App\Services\WalletService;

final class WalletMutation
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function createTransaction($_, array $args)
    {
        // Validate input
        if (!isset($args["input"]) || !is_array($args["input"])) {
            throw new GraphQLException("Invalid input: 'input' field is required");
        }
    
        $input = $args["input"];
    
        // Validate required fields in the input
        $requiredFields = [
            "uuid", "dr_or_cr", "wallet_id", "user_id", "amount", "wallet_balance",
            "charge_id", "chargeable_type", "description", "charges", "reference"
        ];
    
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                throw new GraphQLException("Invalid input: '$field' is required");
            }
        }
    
        // Set default values for optional fields
        $input["currency"] = $input["currency"] ?? "USDC";
        $input["status"] = $input["status"] ?? "default";
        $input["gateway"] = $input["gateway"] ?? "wallet";
    
        // Call the WalletService to create the transaction
        $response = $this->walletService->createTransaction($input);
    
        // Ensure the response contains the expected data
        if (!isset($response["data"]["transaction"])) {
            throw new GraphQLException("Invalid response from WalletService. Missing 'transaction' field.");
        }
    
        // Extract the transaction data from the response
        $transaction = $response["data"]["transaction"];
    
        // Return the transaction in the desired format
        return [
            "id" => $transaction["id"],
            "uuid" => $transaction["uuid"],
            "dr_or_cr" => $transaction["dr_or_cr"],
            "currency" => $transaction["currency"],
            "wallet_id" => $transaction["wallet_id"],
            "user_id" => $transaction["user_id"],
            "amount" => $transaction["amount"],
            "wallet_balance" => $transaction["wallet_balance"],
            "charge_id" => $transaction["charge_id"],
            "chargeable_type" => $transaction["chargeable_type"],
            "description" => $transaction["description"],
            "status" => $transaction["status"],
            "charges" => $transaction["charges"],
            "reference" => $transaction["reference"],
            "gateway" => $transaction["gateway"],
            "created_at" => $this->parseDateTime($transaction["created_at"]),
            "updated_at" => $this->parseDateTime($transaction["updated_at"]),
        ];
    }
    
    /**
     * Convert DateTime string to Carbon instance and format it.
     */
    private function parseDateTime(?string $date): ?string
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : null;
    }
    
    
    public function softDeleteTransaction($_, array $args)
    {
        // Call the WalletService to handle the soft delete operation
        $response = $this->walletService->softDeleteTransaction(
            new Request([
                "transaction_id" => $args["transaction_id"],
            ])
        );
    
        // Return the response from the WalletService
        return $response["data"]["softDeleteTransaction"] ?? null;
    }

}