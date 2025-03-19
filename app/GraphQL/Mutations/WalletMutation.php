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
          "charge_id", "chargeable_type", "description", "charges", "reference", "status"
      ];
  
      foreach ($requiredFields as $field) {
          if (!isset($input[$field])) {
              throw new GraphQLException("Invalid input: '$field' is required");
          }
      }
  
      // Set default values for optional fields
      $input["currency"] = $input["currency"] ?? "USDC";
      $input["status"] = $input["status"] ?? "default";
  
      // Call the WalletService to create the transaction
      $response = $this->walletService->createPointTransaction($input);
  
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


public function createPointTransaction($_, array $args)
{
  // Validate input
  if (!isset($args["input"]) || !is_array($args["input"])) {
      throw new GraphQLException("Invalid input: 'input' field is required");
  }

  $input = $args["input"];

  // Validate required fields in the input
  $requiredFields = [
      "uuid", "dr_or_cr", "wallet_id", "user_id", "amount", " point_balance",
      "charge_id", "chargeable_type", "description", "reference", "state",
  ];

  foreach ($requiredFields as $field) {
      if (!isset($input[$field])) {
          throw new GraphQLException("Invalid input: '$field' is required");
      }
  }

  // Set default values for optional fields
  $input["currency"] = $input["currency"] ?? "USDC";
  $input["status"] = $input["status"] ?? "default";


  // Call the WalletService to create the pointTransaction
  $response = $this->walletService-> createPointTransaction($input);

  // Ensure the response contains the expected data
  if (!isset($response["data"]["pointTransaction"])) {
      throw new GraphQLException("Invalid response from WalletService. Missing 'pointTransaction' field.");
  }

  // Extract the transaction data from the response
  $pointTransaction = $response["data"]["pointTransaction"];

  // Return the transaction in the desired format
  return [
      "id" => $pointTransaction["id"],
      "uuid" => $pointTransaction["uuid"],
      "dr_or_cr" => $pointTransaction["dr_or_cr"],
      "currency" => $pointTransaction["currency"],
      "wallet_id" => $pointTransaction["wallet_id"],
      "user_id" => $pointTransaction["user_id"],
      "amount" => $pointTransaction["amount"],
      "point_balance" => $pointTransaction["point_balance"],
      "state"=> $pointTransaction["state"],
      "charge_id" => $pointTransaction["charge_id"],
      "chargeable_type" => $pointTransaction["chargeable_type"],
      "description" => $pointTransaction["description"],
      "status" => $pointTransaction["status"],
      "reference" => $pointTransaction["reference"],
      "extra_data" => $pointTransaction["reference"],
      "created_at" => $this->parseDateTime($pointTransaction["created_at"]),
      "updated_at" => $this->parseDateTime($pointTransaction["updated_at"]),
  ];
}




public function softDeletePointTransaction($_, array $args)
{
  // Call the WalletService to handle the soft delete operation
  $response = $this->walletService->softDeletePointTransaction(
      new Request([
          "point_transaction_id" => $args["point_transaction_id"],
      ])
  );

  // Return the response from the WalletService
  return $response["data"]["softDeletePointTransaction"] ?? null;
}



public function createWallet($_, array $args)
{
    // Validate input
    if (!isset($args["input"]) || !is_array($args["input"])) {
        throw new GraphQLException("Invalid input: 'input' field is required");
    }

    $input = $args["input"];

    // Validate required fields in the input
    $requiredFields = [
        "uuid", "user_id", "total_balance", "point_balance", "credited_amount",
        "debited_amount", "locked_balance", "credited_point_amount", "debited_point_amount",
        "cash_point_balance", "cash_per_point", "wallet_account", "currency",
    ];

    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            throw new GraphQLException("Invalid input: '$field' is required");
        }
    }

    // Set default values for optional fields
    $input["currency"] = $input["currency"] ?? "USDC";
    $input["wallet_account"] = $input["wallet_account"] ?? null;

    // Call the WalletService to create the wallet
    $response = $this->walletService->createWallet($input);

    // Ensure the response contains the expected data
    if (!isset($response["data"]["wallet"])) {
        throw new GraphQLException("Invalid response from WalletService. Missing 'wallet' field.");
    }

    // Extract the wallet data from the response
    $wallet = $response["data"]["wallet"];

    // Return the wallet in the desired format
    return [
        "id" => $wallet["id"],
        "uuid" => $wallet["uuid"],
        "user_id" => $wallet["user_id"],
        "total_balance" => $wallet["total_balance"],
        "point_balance" => $wallet["point_balance"],
        "credited_amount" => $wallet["credited_amount"],
        "debited_amount" => $wallet["debited_amount"],
        "locked_balance" => $wallet["locked_balance"],
        "credited_point_amount" => $wallet["credited_point_amount"],
        "debited_point_amount" => $wallet["debited_point_amount"],
        "cash_point_balance" => $wallet["cash_point_balance"],
        "cash_per_point" => $wallet["cash_per_point"],
        "wallet_account" => $wallet["wallet_account"],
        "currency" => $wallet["currency"],
        "created_at" => $this->parseDateTime($wallet["created_at"]),
        "updated_at" => $this->parseDateTime($wallet["updated_at"]),
    ];
}



public function softDeleteWallet($_, array $args)
{
  // Call the WalletService to handle the soft delete operation
  $response = $this->walletService->softDeleteWallet(
      new Request([
          "wallet_id " => $args["wallet_id "],
      ])
  );

  // Return the response from the WalletService
  return $response["data"]["softDeleteWallet"] ?? null;
}


}


