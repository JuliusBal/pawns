<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;

    class User extends Authenticatable
    {
        use HasFactory, Notifiable, HasApiTokens;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'name',
            'email',
            'password',
            'country',
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

        /**
         * The attributes that should be hidden for serialization.
         *
         * @var array<int, string>
         */
        protected $hidden = [
            'password',
            'remember_token',
        ];

        /**
         * Get the attributes that should be cast.
         *
         * @return array<string, string>
         */
        protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
            ];
        }

        public function userWallet(): hasOne
        {
            return $this->hasOne(UserWallet::class);
        }

        public function answers(): hasMany
        {
            return $this->hasMany(QuestionAnswer::class);
        }

        public function transactions(): hasMany
        {
            return $this->hasMany(Transaction::class);
        }
    }
