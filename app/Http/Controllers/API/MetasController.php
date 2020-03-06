<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Meta as MyModel;
use Illuminate\Http\Request;
use Validator;
class MetasController extends ApiController {

    public function getMeta(Request $request) {
        // $validator = Validator::make($request->all(), [
        //             'meta_key' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     $errors = parent::formatValidator($validator);
        //     return parent::error($errors, 200);
        // }
         $rules = ['meta_key' => 'required'];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
        $details = MyModel::where('meta_key', $request->input('meta_key'));
        // dd($details->get()->isEmpty());
        if ($details->get()->isEmpty() === false) {
            return parent::success($details->first(), $this->successStatus);
        } else {
            return parent::error('No ' . $request->input('meta_key') . ' Found', 200);
        }
             // return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
