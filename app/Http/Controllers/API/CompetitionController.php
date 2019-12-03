<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Competition as MyModel;

class CompetitionController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => ''];
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
            $model = $model->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
        public function updateleaderboard(Request $request) {
        $rules = ['competition_id' => 'required|exsits.competitions.id',
                   'user_id' => 'required|exsits.users.id',
                   'score' => 'required',
            ];
//        dd($request->all());
        
    }

}
