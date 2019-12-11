<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Competition as MyModel;

class CompetitionController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => '', 'category_id' => '', 'limit' => '', 'name' => ''];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->name))
                $model = $model->Where('name', 'LIKE', "%$request->name%");
            if (isset($request->category_id))
                $model = $model->Where('competition_category_id', "%$request->category_id%");
            $model = $model->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
