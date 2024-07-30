<?php

    namespace App\Services;

    use App\Models\Transaction;
    use App\Models\UserWallet;
    use Carbon\Carbon;
    use App\Mail\TransactionClaimed;
    use Illuminate\Support\Facades\Mail;

    class TransactionService
    {
        /**
         * Function createTransaction
         *
         * @param array<string, mixed> $data
         * @param UserWallet $wallet
         *
         * @return Transaction
         */
        public function createTransaction(array $data, UserWallet $wallet): Transaction
        {
            $transaction = new Transaction;
            $transaction->user_wallet_id = $wallet->id;
            $transaction->save();

            $wallet->balance += $data['amount'];
            $wallet->save();

            return $transaction;
        }

        public function claimTransaction(Transaction $transaction): Transaction
        {
            if ($transaction->is_claimed) {
                throw new \Exception('Transaction has already been claimed.');
            }

            $amount = $transaction->points * 0.01;

            $transaction->wallet->balance += $amount;
            $transaction->wallet->save();
            $transaction->is_claimed = true;
            $transaction->claimed_at = Carbon::now();
            $transaction->save();

            Mail::to($transaction->wallet->user->email)->send(new TransactionClaimed($transaction));

            return $transaction;
        }
    }
