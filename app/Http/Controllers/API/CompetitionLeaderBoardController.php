<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\CompitionLeadBoard as MyModel;

class CompetitionLeaderBoardController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => '', 'limit' => '', 'name' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            $perPage = isset($request->limit) ? $request->limit : 20;
            if (isset($request->search))
                $model = $model->Where('name', 'LIKE', "%$request->name%");
            if (isset($request->name))
                $model = $model->Where('name', 'LIKE', "%$request->name%");
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
            $userCheck = MyModel::where('competition_id', $request->competition_id)->where('created_by', \Auth::id())->get();

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

    public function UserCompetition(Request $request) {
        $rules = ['search' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $UserCompetition = MyModel::where('created_by', \Auth::id())->with('get_competition')->get();
//            dd($UserCompetition);
            if ($UserCompetition->isEmpty() === true):
                throw new \Exception('No Competition...');
            endif;
            return parent::success($UserCompetition);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function GetLeaderBoardById(Request $request) {
        $rules = ['search' => '','id'=>'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            if(isset($request->id))
            $GetCompetition = MyModel::where('id',$request->id)->get();
            if ($GetCompetition->isEmpty() === true):   
                throw new \Exception('User Does Not exist');
            endif;
            return parent::success($GetCompetition);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
