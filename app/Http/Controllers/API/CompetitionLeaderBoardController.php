<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\CompitionLeadBoard as MyModel;

class CompetitionLeaderBoardController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => ''];
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
        $rules = ['competition_id' => 'required|exists:competitions,id',
            'score' => 'required',
        ];

        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $userCheck = MyModel::where('competition_id', $request->competition_id)->where('user_id', \Auth::id())->get();
            
            if ($userCheck->isEmpty() !== true):
//                            dd($userCheck->toArray());

                $id = $userCheck->first()->id;
                $score = $userCheck->first()->score;
                if ($score >= $request->score)
                    return parent::error('Your score is less or equals than current score');

                $count = $userCheck->first()->count;
                if ($count === 2):
                    return parent::error('Your update limit exceeded');
                endif;
                $input = $request->all();
                $updateleader = MyModel::findOrFail($id);
                $input['user_id'] = \Auth::id();
                $input['count'] = '2';
                $updateleader->fill($input);

                $updateleader->save();
                return parent::successCreated(['Message' => 'Updated Successfully', 'updateleader' => $updateleader]);
            else:
//                dd('hello');
                $createleader = new MyModel;
                $input = $request->all();
                $input['user_id'] = \Auth::id();
                $input['count'] = '1';
                $createleader = MyModel::create($input);
                return parent::successCreated(['message' => 'Created Successfully', 'createleader' => $createleader]);
            endif;
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
