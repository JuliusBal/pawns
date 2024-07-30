<?php

    namespace App\Http\Requests\ProfilingQuestion;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class ProfilingQuestionRequest extends FormRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, string|array<string>>
         */
        public function rules(): array
        {
            $rules = [
                'question_text' => 'required',
                'type' => ['required', Rule::in(['date', 'single choice', 'multiple choice'])],
                'options' => 'array|nullable',
                'validation' => 'string|nullable'
            ];

            if ($this->type == 'single choice' || $this->type == 'multiple choice') {
                $rules['options'] = 'required|array';
            }

            return $rules;
        }
    }
