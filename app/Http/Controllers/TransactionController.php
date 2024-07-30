<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\Transaction\TransactionStoreRequest;
    use App\Http\Resources\TransactionResource;
    use App\Models\Transaction;
    use App\Models\User;
    use App\Models\UserWallet;
    use App\Services\TransactionService;
    use App\Traits\ApiResponse;
    use Illuminate\Http\JsonResponse;
    use Symfony\Component\HttpFoundation\Response as ResponseAlias;

    class TransactionController extends Controller
    {
        use ApiResponse;

        protected TransactionService $transactionService;

        public function __construct(TransactionService $transactionService)
        {
            $this->transactionService = $transactionService;
        }

        public function index(): JsonResponse
        {
            try {
                return $this->jsonResponse(
                    'success',
                    TransactionResource::collection(User::paginate(10))
                );
            } catch (\Exception $e) {
                return $this->jsonResponse($e->getMessage(), [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        public function userTransactions(User $user): JsonResponse
        {
            try {
                return $this->jsonResponse(
                    'success',
                    new TransactionResource($user->load(['transactions']))
                );
            } catch (\Exception $e) {
                return $this->jsonResponse($e->getMessage(), [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        public function store(TransactionStoreRequest $request, UserWallet $wallet): JsonResponse
        {
            $validated = $request->validated();
            $transaction = $this->transactionService->createTransaction($validated, $wallet);

            return $this->jsonResponse('success', $transaction, 201);
        }

        public function claim(Transaction $transaction): JsonResponse
        {
            try {
                $this->transactionService->claimTransaction($transaction);
                return $this->jsonResponse('success', 'Transaction successfully claimed.', 201);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }
