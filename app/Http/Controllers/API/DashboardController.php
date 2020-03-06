<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;

class DashboardController extends ApiController {

    protected $title = 'testing push';
    protected $body = 'testing push';

    public function testPush(Request $request) {
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => "Enrolled for competition", 'body' => "You're successfully enrolled for competition"], [6], ['target_id' => 1, 'target_type' => 'Competition'], 'FCM');
        return parent::successCreated(['message' => 'Thank you for registering for the game']);
    }

    public function getItems(Request $request) {
        $validateAttributes = parent::validateAttributes($request, 'GET');
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

//            $date = new \DateTime();
//            $date->modify('-2 hours');
//            $formatted_time = $date->format('H:i:s');
////        dd($formatted_time);
//            $competitions = \App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->where('start_time', '>', $formatted_time)->get()->pluck('id')->toArray();
////            dd($competitions);
//            foreach ($competitions as $competition_id):
//                $userIds = \App\CompetitionUser::where('competition_id', $competition_id)->get()->pluck('player_id')->toArray();
////                dd($userIds);
//                \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => $this->title, 'body' => $this->body], array_unique($userIds), ['target_id' => $competition_id, 'target_type' => 'Competition'], 'FCM');
//            endforeach;
            $limit = 50;
            return parent::success(['game' => \App\Game::select('id', 'name', 'image')->Where('state','1')->limit($limit)->orderBy('id', 'desc')->get(), 'competition' => \App\Competition::select('id', 'name', 'image', 'description', 'date', 'start_time', 'fee', 'prize_details', 'sequential_fee', 'game_id', 'competition_category_id', 'state')->whereDate('date', '>=', \Carbon\Carbon::now()->toDateString())->where('hot_competitions', '1')->Where('state','1')->limit($limit)->with(['game', 'category'])->orderBy('id', 'desc')->get(), 'previous_winner' => \App\CompetitionUser::where('status', 'winner')->select('id','score','player_id','competition_id','params')->with(['competition','player'])->limit($limit)->orderBy('id', 'desc')->get(), 'news' => \App\News::select('id', 'title', 'description', 'image')->Where('state','1')->limit($limit)->orderBy('id', 'desc')->get()]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
