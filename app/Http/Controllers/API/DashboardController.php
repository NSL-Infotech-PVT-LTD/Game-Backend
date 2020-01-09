<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;

class DashboardController extends ApiController {

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
            return parent::success(['game' => \App\Game::select('id', 'name', 'image')->limit($limit)->get(), 'competition' => \App\Competition::select('id', 'name', 'image', 'description', 'date', 'fee', 'prize_details', 'game_id', 'competition_category_id', 'state')->whereDate('date', '=', \Carbon\Carbon::now())->limit($limit)->with(['game', 'category'])->get(), 'previous_winner' => \App\Previouswinner::select('id', 'title', 'description', 'image', 'state')->limit($limit)->get(), 'news' => \App\News::select('id', 'title', 'description', 'image')->limit($limit)->get()]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
