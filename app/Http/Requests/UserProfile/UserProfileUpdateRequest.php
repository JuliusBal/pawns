<?php

    namespace App\Http\Requests\UserProfile;

    use Illuminate\Foundation\Http\FormRequest;

    class UserProfileUpdateRequest extends FormRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, string>
         */
        public function rules(): array
        {
            return [
                'answers.*.profiling_question_id' => 'required|exists:profiling_questions,id',
                'answers.*.answer' => 'required',
            ];
        }
    }
