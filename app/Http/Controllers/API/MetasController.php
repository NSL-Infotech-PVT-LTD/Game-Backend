<?php

namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Meta as MyModel;
use Illuminate\Http\Request;
use Validator;
class MetasController extends ApiController {

    public function getMeta(Request $request) {
        $validator = Validator::make($request->all(), [
                    'meta_key' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = parent::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $details = MyModel::where('meta_key', $request->input('meta_key'))->first();
        //dd($details);
        if (count($details) > 0) {
            return parent::success($details, $this->successStatus);
        } else {
            return parent::error('No ' . $request->input('meta_key') . ' Found', 200);
        }
    }

}
