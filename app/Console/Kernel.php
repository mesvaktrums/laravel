<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
   

	protected $commands = [
        Commands\CheckPortalUsers::class,
    ];
   protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:portal-users')
                 ->everyMinute()
                 ->description('Check if users have become portal users and delete them from local DB if they have');
    }
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
