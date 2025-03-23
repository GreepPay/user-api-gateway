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
   

    /**
     * Initiate a top-up transaction.
     *
     * @param mixed $_
     * @param array $args
     * @return array
     */
    public function InitiateTopup($_, array $args): array
    {
        return $this->walletService->initiateTopup(
            $args['method'],
            $args['amount'],
            $args['currency'],
            $args['payment_metadata']
        );
    }

    /**
     * Make a payment to another user.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function MakePayment($_, array $args): bool
    {
        return $this->walletService->makePayment(
            $args['receiver_uuid'],
            $args['amount'],
            $args['currency']
        );
    }

    /**
     * Redeem GRP tokens.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function RedeemGRPToken($_, array $args): bool
    {
        return $this->walletService->redeemGRPToken($args['grp_amount']);
    }
    
    
    /**
     * Add a user as a beneficiary.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function AddAsBeneficiary($_, array $args): bool
    {
        return $this->walletService->addBeneficiary($args['user_uuid']);
    }

    /**
     * Remove a user as a beneficiary.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function RemoveAsBeneficiary($_, array $args): bool
    {
        return $this->walletService->removeBeneficiary($args['user_uuid']);
    }
    
    

}

