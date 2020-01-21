<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;

class DashboardController extends ApiController {

    public function testPush(Request $request) {
        \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => "Enrolled for competition", 'body' => "You're successfully enrolled for competition"], [\Auth::id()], ['target_id' => 1, 'target_type' => 'Competition'], 'FCM');
        return parent::successCreated(['message' => 'Thank you for registering for the game']);
    }

    public function getItems(Request $request) {
        $validateAttributes = parent::validateAttributes($request, 'GET');
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

//            dd(\App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->get()->pluck('id')->toArray());
//            dd(\App\CompetitionUser::whereIn('competition_id',\App\Competition::whereDate('date', '=', \Carbon\Carbon::now())->get()->pluck('id')->toArray())->get()->pluck('player_id')->toArray());
//            \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => ' Added', 'body' => 'custom'], ['63'], ['target_id' => '222', 'target_type' => 'testing'],'FCM');
            $limit = 50;
            return parent::success(['game' => \App\Game::select('id', 'name', 'image')->limit($limit)->orderBy('id', 'desc')->get(), 'competition' => \App\Competition::select('id', 'name', 'image', 'description', 'date', 'fee', 'prize_details', 'game_id', 'competition_category_id', 'state')->whereDate('date', '=', \Carbon\Carbon::now())->limit($limit)->with(['game', 'category'])->orderBy('id', 'desc')->get(), 'previous_winner' => \App\Previouswinner::select('id', 'title', 'description', 'image', 'state')->limit($limit)->orderBy('id', 'desc')->get(), 'news' => \App\News::select('id', 'title', 'description', 'image')->limit($limit)->orderBy('id', 'desc')->get()]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
