<?php

    namespace Tests\Feature;

    use App\Models\ProfilingQuestion;
    use App\Models\QuestionAnswer;
    use App\Models\Transaction;
    use App\Models\User;
    use App\Models\UserWallet;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class EndpointsTest extends TestCase
    {
        use RefreshDatabase;

        private User $user;
        private UserWallet $userWallet;

        public function setUp(): void
        {
            parent::setUp();
            $this->artisan('db:seed')->assertExitCode(0);
            $this->user = User::factory()->create();
            $this->userWallet = UserWallet::create(['user_id' => $this->user->id, 'balance' => 0]);

            $users = User::factory(4)->create();
            foreach ($users as $user) {
                UserWallet::create(['user_id' => $user->id, 'balance' => rand(0, 100)]);
            }
        }

        // Retrieve Profiling questions
        public function testProfilingQuestionsHasBeenSeeded()
        {
            $response = $this->actingAs($this->user)->get('/profiling-questions');
            $responseData = $response->decodeResponseJson();
            $response->assertJsonFragment([
                'message' => 'success',
            ]);

            $profilingQuestionsCount = ProfilingQuestion::all()->count();
            $dataObjectsCount = count($responseData['data']);
            $this->assertEquals($profilingQuestionsCount, $dataObjectsCount);
        }

        // Retrieve User's wallet
        public function testRetrieveUserWallet()
        {
            $response = $this->actingAs($this->user)->get('/wallet/' . $this->user->id);
            $responseData = $response->decodeResponseJson();

            $data = $responseData['data'];

            $this->assertArrayHasKey('id', $data);
            $this->assertArrayHasKey('user_id', $data);
            $this->assertArrayHasKey('balance', $data);
            $this->assertArrayHasKey('unclaimed_points', $data);
            $this->assertArrayHasKey('pending_balance', $data);

            $this->assertEquals(1, $data['id']);
            $this->assertEquals(1, $data['user_id']);
            $this->assertEquals(0, $data['balance']);
            $this->assertEquals(0, $data['unclaimed_points']);
            $this->assertEquals(0, $data['pending_balance']);

            $response->assertStatus(200);
        }

        // Retrieve All wallets
        public function testRetrieveAllWallets()
        {
            $response = $this->actingAs($this->user)->get('/wallets');
            $responseData = $response->decodeResponseJson();
            $this->assertEquals(5, count($responseData['data']));
        }

        // Update User's Profile
        public function testItCanUpdateUsersProfile()
        {
            $queryParams = [
                'answers' => [
                    ['profiling_question_id' => 1, 'answer' => 'Male'],
                    ['profiling_question_id' => 2, 'answer' => '2000-01-01'],
                ]
            ];

            $updatedQueryParams = [
                'answers' => [
                    ['profiling_question_id' => 1, 'answer' => 'Female'],
                    ['profiling_question_id' => 2, 'answer' => '2001-01-01'],
                ]
            ];

            $wrongParamsForValidation = [
                'answers' => [
                    ['profiling_question_id' => 1, 'answer' => 'Female'],
                    ['profiling_question_id' => 2, 'answer' => 'STRING'],
                ]
            ];

            // First time today, updated profile.
            $response = $this->actingAs($this->user)->put('/users/profile', $queryParams);
            $responseData = $response->decodeResponseJson();
            $this->assertEquals("Profile updated and points awarded", $responseData['data']);

            // Check if user was awarded with 5 points.
            $transaction = Transaction::whereUserId($this->user->id)->first();
            $this->assertEquals(5, $transaction->points);
            $this->assertEquals(0, $transaction->is_claimed);

            // Check if user has ability to claim USD from Points Transactions.
            $claimTransactionResponse = $this->actingAs($this->user)->post(
                sprintf('/transactions/%d/claim', $transaction->id)
            );
            $claimedTransactionResponseData = $claimTransactionResponse->decodeResponseJson();
            $this->assertEquals("Transaction successfully claimed.", $claimedTransactionResponseData['data']);
            $claimedTransaction = Transaction::whereUserId($this->user->id)->first();
            $this->assertEquals(1, $claimedTransaction->is_claimed);

            // Check if user wallet received 0.05 USD from 5 points.
            $userWallet = UserWallet::whereUserId($this->user->id)->first();
            $this->assertEquals(0.05, $userWallet->balance);

            // Trying to update profile second time in the same day.
            $secondResponse = $this->actingAs($this->user)->put('/users/profile', $updatedQueryParams);
            $secondResponseData = $secondResponse->decodeResponseJson();
            $this->assertEquals("You can only update your profile once a day", $secondResponseData['data']);

            // Check if dynamic validation works
            ProfilingQuestion::whereType('date')->first()->fill(['validation' => 'date'])->save();
            Transaction::truncate();
            $wrongValidationResponse = $this->actingAs($this->user)->put('/users/profile', $wrongParamsForValidation);
            $wrongValidationResponseData = $wrongValidationResponse->decodeResponseJson();
            $this->assertEquals(
                "The answer field must be a valid date.",
                $wrongValidationResponseData['data'][0]['answer'][0]
            );
        }
    }
