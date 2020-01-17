<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\CompetitionUser as MyModel;

class CompetitionUserController extends ApiController {

    public function GetMyCompetition(Request $request) {
        $rules = ['search' => '', 'limit' => ''];
        $perPage = isset($request->limit) ? $request->limit : 20;
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            $GetUserCompetition = $model->where('player_id', Auth::id())->get();
            if ($GetUserCompetition->isEmpty() === true):
                throw new \Exception('Data Not Found');
            endif;
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function playCompetitionCreate(Request $request) {
        $rules = ['token' => 'required', 'competition_id' => 'required|exists:competitions,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $competition = \App\Competition::where('id', $request->competition_id)->first();
//            dd('s');
            $model = MyModel::where('competition_id', $request->competition_id)->where('player_id', Auth::id())->get();
            $fee = $competition->fee;
//            dd($model->isEmpty() != true);
            if ($model->isEmpty() != true):
                if ($model->first()->payment_param_2 != null)
                    return parent::error('Max Allowance to play this game is reached');
                if ($model->first()->payment_param_1 != null)
                    $fee = $fee / 2;
            endif;
//            dd(env('STRIPE_SECRET_KEY'));
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $charge = \Stripe\Charge::create([
                        "amount" => $fee * 100,
                        "currency" => "usd",
                        "source" => $request->token,
                        "description" => $request->competition_id . ' Fees for competition',
            ]);
            if ($model->isEmpty() != true):
                $modelUpdate = MyModel::findOrFail($model->first()->id);
                $modelUpdate->update(['payment_param_2' => json_encode($charge)]);
//                $modelUpdate->save();
            else:
                MyModel::create(['player_id' => Auth::id(), 'competition_id' => $request->competition_id, 'payment_param_1' => json_encode($charge)]);
            endif;
               
            return parent::successCreated(['message' => 'Thankyou for registering for the game']); 
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
            
            
            
        }
    }

    public function playCompetitionUpdate(Request $request) {
        $rules = ['competition_id' => 'required|exists:competitions,id', 'score' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            dd(Auth::id());
            $competitionUser = MyModel::where('competition_id', $request->competition_id)->where('player_id', Auth::id())->get();
//            dd($competitionUser->isEmpty());
            if ($competitionUser->isEmpty())
                return parent::error('No competion found for this player');
//            dd($request->score <= $competitionUser->first()->score);
            if ($request->score <= $competitionUser->first()->score)
                return parent::success('current score is greater than requested score');
            $model = MyModel::find($competitionUser->first()->id);
            $model->score = $request->score;
            $model->save();
            return parent::success(['message' => 'Updated Successfully']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }
    
    
    
    
    

}
