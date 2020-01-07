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
            $model = $model->orderBy('id', 'desc');
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
            return parent::success(MyModel::where('id', $request->id)->first());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
