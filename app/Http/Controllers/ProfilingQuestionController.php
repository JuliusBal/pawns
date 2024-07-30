<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\ProfilingQuestion\ProfilingQuestionRequest;
    use App\Http\Resources\ProfilingQuestionResource;
    use App\Models\ProfilingQuestion;
    use App\Traits\ApiResponse;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Response;
    use Symfony\Component\HttpFoundation\Response as ResponseAlias;

    class ProfilingQuestionController extends Controller
    {
        use ApiResponse;

        public function index(): JsonResponse
        {
            try {
                return $this->jsonResponse(
                    'success',
                    ProfilingQuestionResource::collection(ProfilingQuestion::paginate(10))
                );
            } catch (\Exception $e) {
                return $this->jsonResponse($e->getMessage(), [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        public function store(ProfilingQuestionRequest $request): JsonResponse
        {
            try {
                $profilingQuestion = ProfilingQuestion::create($request->validated());

                return $this->jsonResponse(
                    'success',
                    new ProfilingQuestionResource($profilingQuestion),
                    Response::HTTP_CREATED
                );
            } catch (\Exception $e) {
                return $this->jsonResponse($e->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
