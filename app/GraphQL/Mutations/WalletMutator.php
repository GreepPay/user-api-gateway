<?php

namespace App\GraphQL\Mutations;
use App\Exceptions\GraphQLException;
use App\Models\Auth\User;
use App\Models\Wallet\Beneficiary;
use App\Services\BlockchainService;
use Carbon\Carbon;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

final class WalletMutator
{
    protected WalletService $walletService;
    protected BlockchainService $blockchainService;

    public function __construct()
    {
        $this->walletService = new WalletService();
        $this->blockchainService = new BlockchainService();
    }

    public function addAsBeneficiary($_, array $args): Beneficiary
    {
        $authUser = Auth::user();

        $userToAdd = User::where("uuid", $args["user_uuid"])->first();

        if (!$userToAdd) {
            throw new GraphQLException("User not found");
        }

        $payload = [
            "owner_uuid" => $authUser->id,
            "user_uuid" => $userToAdd->id,
            "metadata" => json_encode($args["metadata"]),
        ];

        return $this->walletService->createBeneficiary($payload);
    }

    public function removeAsBeneficiary($_, array $args): bool
    {
        $authUser = Auth::user();

        $beneficiaryToRemove = Beneficiary::where(
            "uuid",
            $args["beneficiary_uuid"]
        )->first();

        if (!$beneficiaryToRemove) {
            throw new GraphQLException("Beneficiary not found");
        }

        $this->walletService->deleteBeneficiary($beneficiaryToRemove->id);

        return true;
    }

    public function redeemGRPToken($_, array $args): bool
    {
        $authUser = Auth::user();

        // TODO: Implement redeemGRPToken method
        return false;
    }

    public function initiateTopup($_, array $args): bool
    {
        $authUser = Auth::user();

        // TODO: Implement initiateTopup method
        return false;
    }

    public function makePayment($_, array $args): bool
    {
        $authUser = Auth::user();

        // TODO: Implement makePayment method
        return false;
    }
}
