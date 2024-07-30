<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\UserProfile\UserProfileUpdateRequest;
    use App\Services\UserProfileService;
    use App\Traits\ApiResponse;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\Auth;

    class UserProfileController extends Controller
    {
        use ApiResponse;

        protected UserProfileService $userProfileService;

        public function __construct(UserProfileService $userProfileService)
        {
            $this->userProfileService = $userProfileService;
        }

        public function update(UserProfileUpdateRequest $request): JsonResponse
        {
            try {
                $user = Auth::user();

                if ($this->userProfileService->hasUpdatedProfileToday($user->id)) {
                    return $this->jsonResponse('error', 'You can only update your profile once a day', 429);
                }

                $data = $request->validated();

                $validationErrors = $this->userProfileService->validateUserProfileData($data);

                if ($validationErrors !== null) {
                    return $this->jsonResponse('error', $validationErrors, 400);
                }

                $hasChanges = $this->userProfileService->updateUserProfileAndCheckChanges($user, $data);

                if ($hasChanges) {
                    $this->userProfileService->awardUserProfileUpdatePoints($user);
                    return $this->jsonResponse('success', 'Profile updated and points awarded');
                }

                return $this->jsonResponse('success', 'Profile updated');
            } catch (\Exception $e) {
                return $this->jsonResponse(
                    'error',
                    'There was an error updating the profile: ' . $e->getMessage(),
                    500
                );
            }
        }
    }
