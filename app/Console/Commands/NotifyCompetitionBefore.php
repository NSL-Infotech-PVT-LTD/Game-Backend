<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyCompetitionBefore extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify_competition:before';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Send a Push notification to all enrolled users on before of game';
    public $title = 'The competition is about to go LIVE';
    public $body = '2 hours to go until the competition is LIVE ⏰ Get some practice to have the best chance of winning.';

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
//        $userIds = \App\CompetitionUser::whereIn('competition_id', \App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->get()->pluck('id')->toArray())->get()->pluck('player_id')->toArray();
//        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => $this->title, 'body' => $this->body], $userIds, [], 'FCM');
//        $this->info('Send successfully');
        
        
        
        $date = new \DateTime();
        $date->modify('-2 hours');
        $formatted_time = $date->format('H:i:s');
//        dd($formatted_time);
        $competitionTwoHours = \App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->whereTime('start_time', '<', $formatted_time)->get()->pluck('id')->toArray();
        $userIds = \App\CompetitionUser::whereIn('competition_id', $competitionTwoHours)->get()->pluck('player_id')->toArray();
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => $this->title, 'body' => $this->body], $userIds, [], 'FCM');
        $this->info('Send Before successfully');
    }

}
