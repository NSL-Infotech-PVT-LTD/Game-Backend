<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Competition as MyModel;

class CompetitionController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['category_id' => '', 'limit' => '', 'search' => ''];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search))
                $model = $model->Where('name', 'LIKE', "%$request->search%");
            if (isset($request->category_id))
                $model = $model->Where('competition_category_id', "%$request->category_id%");
            $model = $model->whereDate('date', '>=', \Carbon\Carbon::now());
            $model = $model->Where('state', '1');
            $model = $model->select('id', 'image', 'description', 'name', 'date', 'fee', 'sequential_fee', 'prize_details', 'game_id', 'competition_category_id')->with(['game', 'category'])->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function getItem(Request $request) {
        $rules = ['id' => 'required|exists:competitions'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            return parent::success(MyModel::select('id', 'image', 'description', 'name', 'date', 'fee', 'sequential_fee', 'prize_details', 'game_id', 'competition_category_id')->with(['game', 'category'])->where('id', $request->id)->first());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function CheckStatusCompetition(Request $request) {

        $rules = ['id' => 'required|exists:competitions,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {

            if (MyModel::where('id', $request->id)->whereDate('date', '=', \Carbon\Carbon::now())->get()->isEmpty() != true)
                return parent::success(['message' => 'Ready To Go']);
            else
                return parent::error('Not Started Yet');
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
