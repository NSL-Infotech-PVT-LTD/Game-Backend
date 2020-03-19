<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\NotifyCompetitionBefore::class,
        Commands\NotifyCompetitionStart::class,
        Commands\NotifyCompetitionEnd::class,
        Commands\NotifyCompetitionAll::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // $schedule->command('inspire')
        //          ->hourly();
        
//        $schedule->command('notify_competition:before')->everyMinute();
//        $schedule->command('notify_competition:start')->everyMinute();
//        $schedule->command('notify_competition:end')->everyMinute();

//        $schedule->command('notify_competition:before')->dailyAt('22:00');
//        $schedule->command('notify_competition:start')->dailyAt('00:01');
//        $schedule->command('notify_competition:end')->dailyAt('23:59');
//        
//        $schedule->command('notify_competition:before')->everyMinute();
        $schedule->command('notify_competition:start')->everyMinute();
        $schedule->command('notify_competition:end')->dailyAt('23:59');
//        dd('s');
//        $schedule->command('notify_competition:all')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
