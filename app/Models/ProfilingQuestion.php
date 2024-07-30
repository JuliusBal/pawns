<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class ProfilingQuestion extends Model
    {
        protected $table = 'profiling_questions';

        /**
         * The attributes that should be mutated to dates.
         *
         * @var array<string>
         */
        protected $dates = [
            'created_at',
            'updated_at',
        ];
        protected $casts = [
            'options' => 'array',
        ];

        protected $fillable = [
            'question_text',
            'type',
            'options',
            'validation'
        ];

        public $timestamps = true;

        public function answers(): hasMany
        {
            return $this->hasMany(QuestionAnswer::class);
        }
    }
