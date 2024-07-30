<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class DailyGlobalStatistic extends Model
    {
        /**
         * The table associated with the model.
         * @var string
         */
        protected $table = 'daily_global_statistics';

        /**
         * The attributes that should be mutated to dates.
         *
         * @var array<string>
         */
        protected $dates = [
            'created_at',
            'updated_at',
        ];

        /**
         * The attributes that are mass assignable.
         * @var array<int, string>
         */
        protected $fillable = [
            'date',
            'points_transactions_created',
            'points_transactions_claimed',
            'amount_usd_claimed',
        ];

        /**
         * Indicates if the model should be timestamped.
         * @var bool
         */
        public $timestamps = true;
    }
