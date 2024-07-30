<?php

    namespace App\Console\Commands;

    use Illuminate\Console\Command;
    use App\Models\Transaction;
    use App\Models\DailyGlobalStatistic;
    use Carbon\Carbon;

    class CalculateDailyStatistics extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'calculate:daily-statistics';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Calculate daily global statistics.';

        /**
         * Execute the console command.
         *
         * @return int
         */
        public function handle()
        {
            $today = Carbon::now()->format('Y-m-d');

            $transactionsCreated = Transaction::whereDate('created_at', $today)->count();

            $transactionsClaimed = Transaction::whereDate('claimed_at', $today)->count();
            $amountUsdClaimed = Transaction::whereDate('claimed_at', $today)->sum('points') * 0.01;

            DailyGlobalStatistic::create([
                'date' => $today,
                'points_transactions_created' => $transactionsCreated,
                'points_transactions_claimed' => $transactionsClaimed,
                'amount_usd_claimed' => $amountUsdClaimed,
            ]);

            $this->info('Daily Statistics calculated successfully.');

            return 0;
        }
    }
