<?php

    namespace App\Http\Controllers;

    use App\Http\Resources\UserWalletResource;
    use App\Models\User;
    use App\Models\UserWallet;
    use App\Traits\ApiResponse;
    use Illuminate\Http\JsonResponse;
    use Symfony\Component\HttpFoundation\Response as ResponseAlias;

    class UserWalletController extends Controller
    {
        use ApiResponse;

        public function index(): JsonResponse
        {
            try {
                return $this->jsonResponse('success', UserWalletResource::collection(UserWallet::paginate(10)));
            } catch (\Exception $e) {
                return $this->jsonResponse($e->getMessage(), [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        public function show(User $user): JsonResponse
        {
            try {
                return $this->jsonResponse('success', new UserWalletResource($user->userWallet));
            } catch (\Exception $e) {
                return $this->jsonResponse($e->getMessage(), [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
