<?php

    namespace App\Http\Resources;

    use App\Models\ProfilingQuestion;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * @mixin \App\Models\User
     */
    class TransactionResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request $request
         * @return array{id: int, name: string, points: int}
         */
        public function toArray(Request $request): array
        {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'points' => $this->transactions->sum('points'),
            ];
        }
    }
