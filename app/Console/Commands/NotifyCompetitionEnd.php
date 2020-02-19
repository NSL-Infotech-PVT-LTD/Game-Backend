<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyCompetitionEnd extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify_competition:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Push notification to all enrolled users on end of game';
    protected $title = 'The tournament is over';
    protected $body = 'That’s it! The tournament is over. Results to be announced soon 😱';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {


        $date = new \DateTime();
        $date->modify('-2 hours');
        $formatted_time = $date->format('H:i:s');
//        dd($formatted_time);
        $competitions = \App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->whereTime('start_time', '<', $formatted_time)->get()->pluck('id')->toArray();
        $userIds = \App\CompetitionUser::whereIn('competition_id', $competitions)->get()->pluck('player_id')->toArray();
        
        
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => $this->title, 'body' => $this->body], $userIds, [], 'FCM');
        $this->info('Send successfully');
    }

}
