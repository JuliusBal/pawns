<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class UserWallet extends Model
    {
        protected $table = 'user_wallets';
        protected $fillable = [
            'user_id',
            'balance',
        ];

        /**
         * The attributes that should be mutated to dates.
         *
         * @var array<string>
         */
        protected $dates = [
            'created_at',
            'updated_at',
        ];

        public $timestamps = true;

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

        public function transactions(): HasMany
        {
            return $this->hasMany(Transaction::class);
        }

        public function getUnclaimedPointsAttribute(): int
        {
            return (int)$this->transactions()->where('is_claimed', false)->sum('points');
        }

        public function getPendingBalanceAttribute(): float
        {
            return $this->unclaimed_points * 0.01;
        }
    }
