<?php

    namespace App\Http\Resources;

    use App\Models\ProfilingQuestion;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * @mixin ProfilingQuestion
     */
    class ProfilingQuestionResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request $request
         * @return array{question_text: string, type: string, options: mixed}
         */
        public function toArray(Request $request): array
        {
            return [
                'question_text' => $this->question_text,
                'type' => $this->type,
                'options' => $this->options,
            ];
        }
    }
