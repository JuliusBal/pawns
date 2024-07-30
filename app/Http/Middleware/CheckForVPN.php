<?php

    namespace App\Http\Middleware;

    use App\Traits\RetrieveIpData;
    use Closure;
    use Illuminate\Http\Request;

    class CheckForVPN
    {
        use RetrieveIpData;

        /**
         * Handle an incoming request.
         *
         * @param Request $request
         * @param Closure $next
         * @return mixed
         */
        public function handle(Request $request, Closure $next)
        {
            $ip = env('TESTING_IP_ADDRESS') ?? $request->ip();

            $data = $this->getData($ip);
            if ($data[$ip]['proxy'] === 'yes') {
                return response('VPN usage is not allowed.', 403);
            }
            return $next($request);
        }
    }
