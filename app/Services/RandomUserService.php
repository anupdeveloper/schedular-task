<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RandomUserService
{
    public function fetchRandomUsers($count = 5)
    {
        $response = Http::get('https://randomuser.me/api/', [
            'results' => $count,
        ]);

        return $response->json()['results'];
    }
}
