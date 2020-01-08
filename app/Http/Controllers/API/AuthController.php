<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use App\User;
use \App\Role;
use Illuminate\Support\Facades\Mail;
use Hash;
use App;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;

class AuthController extends ApiController {

    public $successStatus = 200;

    public function formatValidator($validator) {
        $messages = $validator->getMessageBag();
        foreach ($messages->keys() as $key) {
            $errors[] = $messages->get($key)['0'];
        }
        return $errors[0];
    }

    public function getMetaContent(Request $request) {

        $keyword = $request->get('search');

        if (!empty($keyword)) {
            $metaContent = \App\Metum::where('name', 'LIKE', "%$keyword%")->get();
        } else {
            $metaContent = \App\Metum::get();
        }


        if (!$metaContent->isEmpty()) {
            return parent::success($metaContent, 200);
        } else {
            return parent::error('No Meta Content Found', 500);
        }
    }

// Phase 2 Starts here


    public function login(Request $request) {
        //Validating attributes
        $rules = ['email' => 'required', 'password' => 'required'];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = \App\User::find(Auth::user()->id);
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user;
//            if ($user->status != 1) {
//                return parent::error('Please contact admin to activate your account', 200);
//            }
            // Add user device details for firbase
            parent::addUserDeviceData($user, $request);
            return parent::success($success, $this->successStatus);
        } else {
            return parent::error('Wrong Username or Password', 200);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $rules = ['first_name' => '', 'last_name' => '', 'email' => 'required|email|unique:users', 'password' => 'required', 'c_password' => 'required|same:password', 'mobile' => '', 'country' => '', 'image' => '', 'image_url' => ''];
        $rules = array_merge($this->requiredParams, $rules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        if (isset($request->image))
            $input['image'] = parent::__uploadImage($request->file('image'), public_path(\App\Http\Controllers\Admin\UsersController::$_mediaBasePath));
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;

        $lastId = $user->id;
        $selectClientRole = Role::where('name', 'App-Users')->first();
        $assignRole = DB::table('role_user')->insert(
                ['user_id' => $lastId, 'role_id' => $selectClientRole->id]
        );
        $success['user'] = User::where('id', $user->id)->select('first_name', 'last_name', 'email', 'password', 'status', 'image', 'mobile', 'image_url')->first();
//        dd($user);
        // Add user device details for firbase
        parent::addUserDeviceData($user, $request);
//        if ($user->status != 1) {
//            return parent::error('Please contact admin to activate your account', 200);
//        }
        return parent::success($success, $this->successStatus);
    }

    public function socialRegister(Request $request) {
        $rules = ['first_name' => '', 'last_name' => '', 'email' => 'required','password'=>'', 'mobile' => '', 'social_type' => '', 'social_id' => '', 'social_password' => ''];

        $rules = array_merge($this->requiredParams, $rules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = self::formatValidator($validator);
            return parent::error($errors, 200);
        }
        $input = $request->all();
//        $input['password'] = bcrypt($input['password']);
        $isUser = User::where('email', request('email'));
        if ($isUser->get()->isEmpty() != true) {
            $user = \App\User::find($isUser->first()->id);
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user;
//            if ($user->status != 1) {
//                return parent::error('Please contact admin to activate your account', 200);
//            }
            // Add user device details for firbase
            parent::addUserDeviceData($user, $request);
            return parent::success($success, $this->successStatus);
        }
//        dd($isUser->get()->isEmpty());
         $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;

        $lastId = $user->id;
        $selectClientRole = Role::where('name', 'App-Users')->first();
        $assignRole = DB::table('role_user')->insert(
                ['user_id' => $lastId, 'role_id' => $selectClientRole->id]
        );

        $success['user'] = User::where('id', $user->id)->select('first_name', 'last_name', 'email', 'password', 'status', 'image', 'mobile', 'image_url', 'social_type', 'social_id', 'social_password')->first();
//        dd($user);
        // Add user device details for firbase
        parent::addUserDeviceData($user, $request);
//        if ($user->status != 1) {
//            return parent::error('Please contact admin to activate your account', 200);
//        }
        return parent::successCreated($success, 201);
    }

    public function MyProfile(Request $request) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $model = User::Where('id', Auth::id())->first();
//            dd($model->toArray());
            return parent::success($model);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function ProfileUpdate(Request $request) {
        $user = \App\User::findOrFail(\Auth::id());

        if ($user->get()->isEmpty())
            return parent::error('User Not found');
//        dd($user->hasRole(''));
//        if ($user->hasRole('App-Users') === false)
//            return parent::error('Please use valid auth token');
        $rules = ['first_name' => '', 'last_name' => '', 'email' => '', 'image' => '', 'country' => '', 'mobile' => ''];
        $rules = array_merge($this->requiredParams, $rules);
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
            if (isset($request->image)):
                $input['image'] = parent::__uploadImage($request->file('image'), public_path('uploads/users/image'));
            endif;
            $abc = $request->first_name;

//            $input['password'] = Hash::make($request->password);
            $user->fill($input);
            $user->save();

            // Add user device details for firbase
            parent::addUserDeviceData($user, $request);
            return parent::successCreated(['Message' => 'Updated Successfully', 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function resetPassword(Request $request, Factory $view) {
        //Validating attributes
        $rules = ['email' => 'required'];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), true);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        $view->composer('emails.auth.password', function($view) {
            $view->with([
                'title' => trans('front/password.email-title'),
                'intro' => trans('front/password.email-intro'),
                'link' => trans('front/password.email-link'),
                'expire' => trans('front/password.email-expire'),
                'minutes' => trans('front/password.minutes'),
            ]);
        });
//        dd($request->only('email'));
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject(trans('front/password.reset'));
                });
//        dd($response);
        switch ($response) {
            case Password::RESET_LINK_SENT:
                return parent::successCreated('Password reset link sent please check inbox');
            case Password::INVALID_USER:
                return parent::error(trans($response));
            default :
                return parent::error(trans($response));
                break;
        }
        return parent::error('Something Went');
    }

    public function getRegisterdUserDetails(Request $request) {
        $rules = [];
        $validateAttributes = parent::validateAttributes($request, 'GET', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
//            dd(\Auth::id());
            $model = \App\User::whereId(\Auth::id());
            return parent::success($model->first());
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function userUpdate(Request $request) {
        $user = \App\User::findOrFail(\Auth::id());
        if ($user->get()->isEmpty())
            return parent::error('User Not found');
        $rules = ['name' => '', 'dob' => '', 'mobile' => '', 'profile_image' => ''];
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
//            $input['password'] = Hash::make($request->password);
            if (isset($request->profile_image)):
                $input['profile_image'] = parent::__uploadImage($request->file('profile_image'), public_path('uploads/user/profile_image'));
            endif;
//            var_dump(json_decode($input['category_id']));
//            dd('s');
            $user->fill($input);
            $user->save();
            return parent::successCreated(['Message' => 'Updated Successfully', 'user' => $user]);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

    public function userUpdatePassword(Request $request) {
        $user = \App\User::findOrFail(\Auth::id());
        if ($user->get()->isEmpty())
            return parent::error('User Not found');
        $rules = ['password' => 'required|confirmed', 'password_confirmation' => ''];
//        dd($request->all());
        $validateAttributes = parent::validateAttributes($request, 'POST', $rules, array_keys($rules), false);
        if ($validateAttributes):
            return $validateAttributes;
        endif;
        try {
            $input = $request->all();
            $input['password'] = Hash::make($request->password);
            $user->fill($input);
            $user->save();
            return parent::successCreated(['Message' => 'Password Updated Successfully']);
        } catch (\Exception $ex) {
            return parent::error($ex->getMessage());
        }
    }

}
