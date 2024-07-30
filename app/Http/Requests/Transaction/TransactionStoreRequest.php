<?php

    namespace App\Http\Requests\Transaction;

    use Illuminate\Foundation\Http\FormRequest;

    class TransactionStoreRequest extends FormRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, string>
         */
        public function rules(): array
        {
            return [
                'amount' => 'required|numeric',
                'type' => 'required|string',
            ];
        }
    }
