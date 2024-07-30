<?php

    namespace App\Traits;

    use Illuminate\Support\Facades\Http;

    trait RetrieveIpData
    {
        /**
         * Gets data from proxycheck.io according to IP address
         *
         * @param string $ip - The IP address to check
         *
         * @return array<string,mixed>
         */
        public function getData(string $ip): array
        {
            $response = Http::get(sprintf('https://proxycheck.io/v2/%s?vpn=1&asn=1', $ip));
            return $response->json();
        }
    }
