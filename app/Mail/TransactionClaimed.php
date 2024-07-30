<?php

    namespace App\Mail;

    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    use App\Models\Transaction;

    class TransactionClaimed extends Mailable
    {
        use Queueable, SerializesModels;

        /**
         * The transaction instance.
         *
         * @var Transaction
         */
        public $transaction;

        /**
         * Create a new message instance.
         *
         * @return void
         */
        public function __construct(Transaction $transaction)
        {
            $this->transaction = $transaction;
        }

        /**
         * Build the message.
         *
         * @return $this
         */
        public function build()
        {
            return $this->from('email@pawns.com')
                ->view('emails.transaction_claimed')
                ->with([
                    'transaction' => $this->transaction,
                    'amount' => $this->transaction->points * 0.01,
                ]);
        }
    }
