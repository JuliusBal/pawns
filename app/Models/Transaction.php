<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Transaction extends Model
    {
        /**
         * The attributes that should be mutated to dates.
         *
         * @var array<string>
         */
        protected $dates = [
            'created_at',
            'updated_at',
        ];

        protected $fillable = [
            'points',
            'is_claimed',
            'claimed_at',
            'user_id',
            'user_wallet_id',
        ];

        /**
         * Get the wallet that owns the transaction.
         */
        public function wallet(): BelongsTo
        {
            return $this->belongsTo(UserWallet::class, 'user_wallet_id');
        }
    }
