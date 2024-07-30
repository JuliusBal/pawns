<?php

    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\ProfilingQuestionController;
    use App\Http\Controllers\TransactionController;
    use App\Http\Controllers\UserProfileController;
    use App\Http\Controllers\UserWalletController;
    use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified', 'vpn'])->name('dashboard');

    Route::middleware(['auth', 'vpn'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/profiling-questions', [ProfilingQuestionController::class, 'index']);
        Route::post('/profiling-questions', [ProfilingQuestionController::class, 'store']);
        Route::get('/wallet/{user}', [UserWalletController::class, 'show']);
        Route::get('/wallets', [UserWalletController::class, 'index']);
        Route::post('/wallets/{wallet}/transactions', [TransactionController::class, 'store']);

        Route::get('/transaction/{user}', [TransactionController::class, 'userTransactions']);
        Route::get('/transactions', [TransactionController::class, 'index']);

        Route::put('/users/profile', [UserProfileController::class, 'update']);
        Route::post('/transactions/{transaction}/claim', [TransactionController::class, 'claim']);
    });

    require __DIR__ . '/auth.php';
