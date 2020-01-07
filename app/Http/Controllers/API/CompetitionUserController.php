<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\CompetitionUser as MyModel;

class CompetitionUserController extends ApiController {

    public function GetMyCompetition(Request $request) {
        $rules = ['search' => '', 'limit' => ''];
        $perPage = isset($request->limit) ? $request->limit : 20;
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            $GetUserCompetition = $model->where('player_id', Auth::id())->get();
            if ($GetUserCompetition->isEmpty() === true):
                throw new \Exception('Data Not Found');
            endif;
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }


}
