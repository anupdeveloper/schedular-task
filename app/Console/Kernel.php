<?php

namespace App\Console;

use App\Console\Commands\FetchRandomUsers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        FetchRandomUsers::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('fetch:random-users')->everyMinute();
    }
}
