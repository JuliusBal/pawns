<?php

    namespace App\Http\Resources;

    use App\Models\UserWallet;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Pagination\LengthAwarePaginator;

    /**
     * @mixin UserWallet
     */
    class UserWalletResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request $request
         * @return array{user_id: int, balance: mixed}
         */
        public function toArray(Request $request): array
        {
            return [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'balance' => $this->balance,
                'unclaimed_points' => $this->unclaimedPoints,
                'pending_balance' => $this->pendingBalance,
            ];
        }
    }
