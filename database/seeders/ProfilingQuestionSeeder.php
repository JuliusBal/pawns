<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use App\Models\ProfilingQuestion;

    class ProfilingQuestionSeeder extends Seeder
    {
        public function run()
        {
            $questions = [
                [
                    'question_text' => 'Gender',
                    'type' => 'single choice',
                    'options' => ['Male', 'Female'],
                ],
                [
                    'question_text' => 'Date of birth',
                    'type' => 'date',
                ],
            ];

            foreach ($questions as $question) {
                $options = $question['options'] ?? null;
                ProfilingQuestion::firstOrCreate(
                    ['question_text' => $question['question_text']],
                    ['type' => $question['type'], 'options' => $options]
                );
            }
        }
    }
