<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Meta as MyModel;
use Illuminate\Http\Request;

class MetasController extends ApiController
{
    public function getItems(Request $request) {
        $rules = ['limit' => '', 'search' => '','id'=>'required|exists:metas,id'];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
//            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search))
                $model = $model->Where('meta_content', 'LIKE', "%$request->search%");
            if($request->id)
            $model = $model->where('id', $request->id)->first();
            return parent::success($model);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
}
