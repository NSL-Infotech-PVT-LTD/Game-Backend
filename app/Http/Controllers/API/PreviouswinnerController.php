<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Previouswinner as MyModel;

class PreviouswinnerController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => '','title' => ''];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->title))
                $model = $model->Where('title', 'LIKE', "%$request->title%");
            $model = $model->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
