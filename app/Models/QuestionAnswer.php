<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class QuestionAnswer extends Model
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
            'user_id',
            'profiling_question_id',
            'answer'
        ];

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

        public function profilingQuestion(): BelongsTo
        {
            return $this->belongsTo(ProfilingQuestion::class);
        }
    }
