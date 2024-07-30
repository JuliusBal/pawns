<?php

    namespace Tests\Feature;

    use App\Models\User;
    use App\Models\UserWallet;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class RoutesTest extends TestCase
    {
        use RefreshDatabase;

        private User $user;
        private UserWallet $userWallet;

        public function setUp(): void
        {
            parent::setUp();

            $this->user = User::factory()->create();
            $this->userWallet = UserWallet::create(['user_id' => $this->user->id, 'balance' => 0]);
        }

        public function testCanAccessWebsiteAddRoute()
        {
            $response = $this->actingAs($this->user)->get('/dashboard');
            $response->assertStatus(200);
        }

        public function testCanAccessProfilingQuestionsRoute()
        {
            $response = $this->actingAs($this->user)->get('/profiling-questions');
            $response->assertStatus(200);
        }

        public function testCanAccessUserWalletRoute()
        {
            $response = $this->actingAs($this->user)->get('/wallet/' . $this->user->id);
            $response->assertStatus(200);
        }

        public function testCanAccessAllWalletsRoute()
        {
            $response = $this->actingAs($this->user)->get('/wallets');
            $response->assertStatus(200);
        }

        public function testCanAccessUsersTransactionsRoute()
        {
            $response = $this->actingAs($this->user)->get('/transactions');
            $response->assertStatus(200);
        }

        public function testCanAccessSingleUserTransactionsRoute()
        {
            $response = $this->actingAs($this->user)->get('/transaction/' . $this->user->id);
            $response->assertStatus(200);
        }
    }
