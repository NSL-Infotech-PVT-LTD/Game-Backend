<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Banner as MyModel;
use Illuminate\Http\Request;

class BannersController extends ApiController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function getItems(Request $request) {
        $rules = ['limit' => '', 'search' => ''];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search))
                $model = $model->Where('type', 'LIKE', "%$request->search%");
            $model = $model->Where('state', '1');
            $model = $model->select('id', 'user_id', 'type', 'image')->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
