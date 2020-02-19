<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyCompetitionAll extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify_competition:all';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $LIVEdescription = 'Send a Push notification to all enrolled users on start of game';
    protected $LIVEtitle = 'Play and win your prize 🕹';
    protected $LIVEbody = 'Time to play! The competition is about to go LIVE. Play and win your prize 🕹';
    protected $ENDdescription = 'Send a Push notification to all enrolled users on end of game';
    protected $ENDtitle = 'The tournament is over';
    protected $ENDbody = 'That’s it! The tournament is over. Results to be announced soon';
    public $BEFOREdescription = 'Send a Push notification to all enrolled users on before of game';
    public $BEFOREtitle = 'The competition is about to go LIVE';
    public $BEFOREbody = '2 hours to go until the competition is LIVE ⏰ Get some practice to have the best chance of winning.';

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
        /* Before 2 hours Start */
        $date = new \DateTime();
        $date->modify('-2 hours');
        $formatted_time = $date->format('H:i:s');
//        dd($formatted_time);
        $competitionTwoHours = \App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->whereTime('start_time', '<', $formatted_time)->get()->pluck('id')->toArray();
        $userIds = \App\CompetitionUser::whereIn('competition_id', $competitionTwoHours)->get()->pluck('player_id')->toArray();
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => $this->BEFOREtitle, 'body' => $this->BEFOREbody], $userIds, [], 'FCM');
        $this->info('Send Before successfully');
        /* Before 2 hours end */
    }

}
