<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyCompetitionStart extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify_competition:start';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Send a Push notification to all enrolled users on start of game';
    public $title = 'Play and win your prize 🕹';
    public $body = 'Time to play! The competition is about to go LIVE. Play and win your prize 🕹';

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
