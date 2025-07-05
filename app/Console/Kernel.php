<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:wasabi')->everyThreeHours();

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        $this->commands([
            \App\Console\Commands\BackupToWasabi::class,
        ]);

        require base_path('routes/console.php');
    }
}
