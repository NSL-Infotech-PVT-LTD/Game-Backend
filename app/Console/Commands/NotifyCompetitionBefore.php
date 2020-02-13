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
    protected $description = 'Send a Push notification to all enrolled users on before of game';
    protected $title = 'The competition is about to go LIVE';
    protected $body = '2 hours to go until the competition is LIVE ⏰ Get some practice to have the best chance of winning.';

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
        $userIds = \App\CompetitionUser::whereIn('competition_id', \App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->get()->pluck('id')->toArray())->get()->pluck('player_id')->toArray();
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => $this->title, 'body' => $this->body], $userIds, [], 'FCM');
        $this->info('Send successfully');
    }

}
