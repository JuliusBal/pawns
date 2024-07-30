<?php

    use App\Http\Middleware\CheckForVPN;
    use Illuminate\Foundation\Application;
    use Illuminate\Foundation\Configuration\Exceptions;
    use Illuminate\Foundation\Configuration\Middleware;
    use Illuminate\Http\Request;

    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__ . '/../routes/web.php',
            commands: __DIR__ . '/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {
            $middleware->statefulApi();
            $middleware->alias([
                'vpn' => CheckForVPN::class,
            ]);
            $middleware->trustProxies(at: '*');
            $middleware->trustProxies(
                headers: Request::HEADER_X_FORWARDED_AWS_ELB
            );
        })
        ->withExceptions(function (Exceptions $exceptions) {
            //
        })->create();
