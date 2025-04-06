<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserLocation;
use App\Services\RandomUserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class FetchRandomUsers extends Command
{
    protected $signature = 'fetch:random-users';
    protected $description = 'Fetch 5 random users and store them in the database';

    protected $randomUserService;

    public function __construct(RandomUserService $randomUserService)
    {
        parent::__construct();
        $this->randomUserService = $randomUserService;
    }

    public function handle()
    {
        

        try {

            $usersData = $this->randomUserService->fetchRandomUsers(5);

            foreach ($usersData as $userData) {
                $user = User::create([
                    'name' => $userData['name']['first'] . ' ' . $userData['name']['last'],
                    'email' => $userData['email'],
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make($userData['login']['password']),
                ]);
    
                UserDetail::create([
                    'user_id' => $user->id,
                    'gender' => $userData['gender'],
                ]);
    
                UserLocation::create([
                    'user_id' => $user->id,
                    'city' => $userData['location']['city'],
                    'country' => $userData['location']['country'],
                ]);
            }
        } catch(Exception $e) {
            return throw new Exception($e->getMessage()); 
        }


        

        $this->info('Successfully fetched and saved 5 random users!');
    }
}
