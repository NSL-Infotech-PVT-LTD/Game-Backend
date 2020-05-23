<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\CompetitionUser as MyModel;

class CompetitionUserController extends ApiController {

    public function getMyCompetitionUsers(Request $request) {
        $rules = ['search' => '', 'limit' => '', 'competition_id' => 'required|exists:competitions,id'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = new MyModel;
            if ($model->get()->isEmpty() === true):
                throw new \Exception('Data Not Found');
            endif;
            $model = $model->select('id', 'player_id', 'competition_id', 'score', 'status')->with(['competition', 'player']);
            if (isset($request->competition_id))
                $model = $model->where('competition_id', $request->competition_id);
            $perPage = isset($request->limit) ? $request->limit : 20;            
            $model = $model->Where('state', '1');
            $model = $model->orderBy('score', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function GetMyCompetition(Request $request) {
        $rules = ['search' => '', 'limit' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $GetUserCompetition = MyModel::where('player_id', Auth::id())->get();
            if ($GetUserCompetition->isEmpty() === true):
                throw new \Exception('Data Not Found');
            endif;
//            dd(array_unique($GetUserCompetition->pluck('competition_id')->toArray()));
            $model = \App\Competition::whereIn('id', $GetUserCompetition->pluck('competition_id')->toArray());
            $perPage = isset($request->limit) ? $request->limit : 20;            
            $model = $model->Where('state', '1');
            $model = $model->orderBy('id', 'desc');
            return parent::success($model->paginate($perPage));
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function playCompetitionCreateWithToken(Request $request) {
//        $rules = ['card_number' => 'required', 'card_exp_month' => 'required', 'card_exp_year' => 'required', 'card_cvc' => 'required', 'competition_id' => 'required|exists:competitions,id'];
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
//                if ($model->first()->payment_param_2 != null)
//                    return parent::error('Max Allowance to play this game is reached');
//                if ($model->first() != null)
                $fee = $competition->sequential_fee;
            endif;
            if ($fee == 0):
                $charge = ['id' => 'no_id', 'amount' => $fee];
            else:
//            dd(env('STRIPE_SECRET_KEY'));
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
//            $card = \Stripe\Token::create([
//                        'card' => [
//                            'number' => $request->card_number,
//                            'exp_month' => $request->card_exp_month,
//                            'exp_year' => $request->card_exp_year,
//                            'cvc' => $request->card_cvc,
//                        ],
//            ]);
//            dd($card->id);
                $charge = \Stripe\Charge::create([
                            "amount" => $fee * 100,
                            "currency" => "usd",
                            "source" => $request->token,
                            "description" => $request->competition_id . ' Fees for competition',
                            "shipping[name]" => "Jenny Rosen",
                            "shipping[address][line1]" => "510 Townsend St",
                            "shipping[address][postal_code]" => "510 Townsend St",
                            "shipping[address][city]" => "510 Townsend St",
                            "shipping[address][state]" => "510 Townsend St",
                            "shipping[address][country]" => "US"
                ]);
            endif;
//            $modelsend = null;
            if ($model->isEmpty() != true):
                $modelUpdate = MyModel::findOrFail($model->first()->id);
                $modelUpdate->update(['state' => '0']);
                \App\CompetitionUserPayment::create(['competition_user_id' => $model->first()->id, 'payment' => json_encode($charge)]);
//                $modelUpdate->save();
            else:
                $modelCreate = MyModel::create(['player_id' => Auth::id(), 'competition_id' => $request->competition_id, 'state' => '0']);
                \App\CompetitionUserPayment::create(['competition_user_id' => $modelCreate->id, 'payment' => json_encode($charge)]);
            endif;
            \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => "Enrolled for competition", 'body' => "You're successfully enrolled for competition"], [\Auth::id()], ['target_id' => $request->competition_id, 'target_type' => 'Competition'], 'FCM');
            return parent::successCreated(['message' => 'Thank you for registering for the game', 'data' => \App\Competition::whereId($request->competition_id)->first()]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function playCompetitionCreate(Request $request) {
        $rules = ['card_number' => 'required', 'card_exp_month' => 'required', 'card_exp_year' => 'required', 'card_cvc' => 'required', 'competition_id' => 'required|exists:competitions,id'];
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
//                if ($model->first()->payment_param_2 != null)
//                    return parent::error('Max Allowance to play this game is reached');
//                if ($model->first() != null)
                $fee = $competition->sequential_fee;
            endif;
//            dd(env('STRIPE_SECRET_KEY'));
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $card = \Stripe\Token::create([
                        'card' => [
                            'number' => $request->card_number,
                            'exp_month' => $request->card_exp_month,
                            'exp_year' => $request->card_exp_year,
                            'cvc' => $request->card_cvc,
                        ],
            ]);
//            dd($card->id);
            $charge = \Stripe\Charge::create([
                        "amount" => $fee * 100,
                        "currency" => "usd",
                        "source" => $card->id,
                        "description" => $request->competition_id . ' Fees for competition',
                        "shipping[name]" => "Jenny Rosen",
                        "shipping[address][line1]" => "510 Townsend St",
                        "shipping[address][postal_code]" => "510 Townsend St",
                        "shipping[address][city]" => "510 Townsend St",
                        "shipping[address][state]" => "510 Townsend St",
                        "shipping[address][country]" => "US"
            ]);
//            $modelsend = null;
            if ($model->isEmpty() != true):
                $modelUpdate = MyModel::findOrFail($model->first()->id);
                $modelUpdate->update(['state' => '0']);
                \App\CompetitionUserPayment::create(['competition_user_id' => $model->first()->id, 'payment' => json_encode($charge)]);
//                $modelUpdate->save();
            else:
                $modelCreate = MyModel::create(['player_id' => Auth::id(), 'competition_id' => $request->competition_id, 'state' => '0']);
                \App\CompetitionUserPayment::create(['competition_user_id' => $modelCreate->id, 'payment' => json_encode($charge)]);
            endif;
            \App\Http\Controllers\API\ApiController::pushNotificationsMultipleUsers(['title' => "Enrolled for competition", 'body' => "You're successfully enrolled for competition"], [\Auth::id()], ['target_id' => $request->competition_id, 'target_type' => 'Competition'], 'FCM');
            return parent::successCreated(['message' => 'Thank you for registering for the game', 'data' => \App\Competition::whereId($request->competition_id)->first()]);
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
            $model = MyModel::find($competitionUser->first()->id);
            if ($request->score <= $competitionUser->first()->score)
                ''; //return parent::success('current score is greater than requested score');
            else
                $model->score = $request->score;
            $model->state = '1';
            $model->save();
            return parent::success(['message' => 'Updated Successfully']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
