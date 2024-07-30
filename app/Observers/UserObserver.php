<?php

    namespace App\Observers;

    use App\Models\User;
    use App\Traits\RetrieveIpData;

    class UserObserver
    {
        use RetrieveIpData;

        public function created(User $user): void
        {
            $ip = env('TESTING_IP_ADDRESS') ?? request()->ip();
            $data = $this->getData($ip);

            if (!empty($data[$ip]['country'])) {
                $country = $data[$ip]['country'];
                $user->fill(['country' => $country])->save();
            }
        }
    }
