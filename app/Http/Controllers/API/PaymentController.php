<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Previouswinner as MyModel;

class PaymentController extends ApiController {

    public function getItems(Request $request) {
        $rules = ['search' => '', 'limit' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $cards = \Stripe\Customer::allSources(
                            \Auth::user()->stripe_id,
                            ['object' => 'card', 'limit' => 20]
            );
            return parent::success($cards);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function store(Request $request) {
        $rules = ['card_number' => 'required', 'card_exp_month' => 'required', 'card_exp_year' => 'required', 'card_cvc' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $stripeCard = \Stripe\Token::create([
                        'card' => [
                            'number' => $request->card_number,
                            'exp_month' => $request->card_exp_month,
                            'exp_year' => $request->card_exp_year,
                            'cvc' => $request->card_cvc,
                        ],
            ]);
            $card = \Stripe\Customer::createSource(
                            \Auth::user()->stripe_id,
                            ['source' => $stripeCard->id]
            );
            return parent::successCreated(['message' => 'Created Successfully', 'card' => $card]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function delete(Request $request) {
        $rules = ['card_id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $card = \Stripe\Customer::deleteSource(\Auth::user()->stripe_id, $request->card_id);
            return parent::successCreated(['message' => 'Deleted Successfully', 'card' => $card]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function defaultCard(Request $request) {
        $rules = ['card_id' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $customer = \Stripe\Customer::retrieve(\Auth::user()->stripe_id);
            $customer->default_source = $request->card_id;
            $customer->save();
            return parent::successCreated(['message' => 'Updated Successfully']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
