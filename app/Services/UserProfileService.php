<?php

    namespace App\Services;

    use App\Models\Transaction;
    use App\Models\ProfilingQuestion;
    use App\Models\User;
    use App\Models\UserWallet;
    use Illuminate\Support\Facades\Validator;
    use Carbon\Carbon;

    class UserProfileService
    {
        public function hasUpdatedProfileToday(int $userId): bool
        {
            return Transaction::where('user_id', $userId)
                ->whereDate('created_at', Carbon::today())
                ->exists();
        }

        /**
         * Function validateUserProfileData
         *
         * @param array<string, mixed> $data
         *
         * @return array<int, \Illuminate\Support\MessageBag>|null
         */
        public function validateUserProfileData(array $data): ?array
        {
            $profilingQuestions = ProfilingQuestion::whereIn(
                'id',
                array_column($data['answers'], 'profiling_question_id')
            )->get();

            $errors = [];

            foreach ($data['answers'] as $answer) {
                $questionValidation = $profilingQuestions->firstWhere(
                    'id',
                    $answer['profiling_question_id']
                )->validation;
                $validator = Validator::make($answer, [
                    'answer' => explode('|', $questionValidation),
                ]);

                if ($validator->fails()) {
                    $errors[] = $validator->errors();
                }
            }

            if (!empty($errors)) {
                return $errors;
            }

            return null;
        }

        /**
         * Function updateUserProfileAndCheckChanges
         *
         * @param User $user
         * @param array<string, array<array<string, mixed>>> $data
         *
         * @return bool
         */
        public function updateUserProfileAndCheckChanges(User $user, array $data): bool
        {
            $hasChanges = false;

            foreach ($data['answers'] as $answerData) {
                $existingAnswer = $user->answers()->where(
                    'profiling_question_id',
                    $answerData['profiling_question_id']
                )->first();

                if (!$existingAnswer || $existingAnswer->answer != $answerData['answer']) {
                    $user->answers()->updateOrCreate(
                        ['profiling_question_id' => $answerData['profiling_question_id']],
                        ['answer' => $answerData['answer']]
                    );

                    $hasChanges = true;
                }
            }

            return $hasChanges;
        }

        /**
         * Function awardUserProfileUpdatePoints
         *
         * @param User $user
         *
         * @return void
         */
        public function awardUserProfileUpdatePoints(User $user): void
        {
            $wallet = $user->userWallet?->first();
            if (!$wallet) {
                $wallet = new UserWallet();
                $wallet->fill(['user_id' => $user->id, 'balance' => 0])->save();
            }

            $transaction = new Transaction;
            $transaction->fill(['user_id' => $user->id, 'user_wallet_id' => $wallet->id, 'points' => 5])->save();
        }
    }

