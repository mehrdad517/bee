<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/





Route::prefix('backend')->group(function () {

    Route::group(['prefix' => '/auth'], function () {

        Route::post('/login', 'LoginController@login');
        Route::get('/logout', 'LoginController@logout')->middleware('auth:api');
        Route::post('/sendVerifyCode', function (Request $request) {

            $validator = \Validator::make($request->all(), [
                'username' => 'required',
            ]);

            if ($validator->fails()) {

                return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            $user = \Modules\User\Entities\User::where('mobile', $request->get('username'))->orWhere('email', $request->get('email'));

            // check user status
            if ($user->exists()) {

                $user = $user->first();

                if (! $user->status) {
                    return Response()->json(['status' => false, 'msg' => 'اکانت شما غیرفعال است']);
                }

                $rand = rand(10000, 99999);

                $token = bcrypt('@#$!~'. rand(1, 100000) .'*()+=' .time() . '@#$%^^&*((#$$$$)__45454&&^^@@@$#md54532515');
                // send sms to user

                try {
                    $verify = new \MahdiMajidzadeh\Kavenegar\KavenegarVerify();
                    $res = $verify->lookup($user->mobile, 'PasswordRecovery', $rand, null, null, 'sms');

                    if (@$res[0]->status) {
                        // to do
                        $user->update([
                            'verify_code' => $rand,
                            'remember_token' => $token
                        ]);

                        return Response()->json(['status' => true, 'msg' => 'کد تایید به موبایل شما ارسال شد.', 'token' => $token]);

                    }
                } catch (Exception $exception) {
                    return Response()->json(['status' => false, 'msg' => $exception->getMessage()]);
                }

            } elseif ($user->count() == 0) {
                return Response()->json(['status' => false, 'msg' => 'این اکانت در سیستم وجود ندارد.']);
            } else {
                return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
            }
        });
        Route::post('/checkVerifyCode', function (Request $request) {

            $validator = \Validator::make($request->all(), [
                'token' => 'required',
                'username' => 'required',
                'verify_code' => 'required',
            ]);

            if ($validator->fails()) {

                return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            $user = \Modules\User\Entities\User::where('mobile', $request->get('username'))
                ->where('remember_token', $request->get('token'))
                ->where('role_id', '<>', \Modules\User\Entities\User::USER_TYPE_GUEST)
                ->where('verify_code', $request->get('verify_code'))
                ->where('status', 1)
            ;

            // check user status
            if ($user->count() == 1) {

                $token = bcrypt('@#$!~'. rand(1, 100000) .'*()+=' .time() . '@#$%^^&*((#$$$$)__45454&&^^@@@$#md54532515');

                $user->update([
                    'mobile_verify' => true,
                    'remember_token' => $token
                ]);

                return Response()->json(['status' => true, 'msg' => 'رمز جدید خود را وارد کنید', 'token' => $token]);

            } elseif ($user->count() == 0) {
                return Response()->json(['status' => false, 'msg' => 'کد وارد شده نادرست است.']);
            } else {
                return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
            }
        });
        Route::post('/passwordRecovery', function (Request $request) {

            $validator = \Validator::make($request->all(), [
                'password' => 'required|min:6',
                'token' => 'required',
                'username' => 'required',
                'verify_code' => 'required',
            ]);

            if ($validator->fails()) {

                return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            $user = \Modules\User\Entities\User::where('mobile', $request->get('username'))
                ->where('remember_token', $request->get('token'))
                ->where('verify_code', $request->get('verify_code'))
                ->where('status', 1)
            ;

            // check user status
            if ($user->count() == 1) {

                $token = bcrypt('@#$!~'. rand(1, 100000) .'*()+=' .time() . '@#$%^^&*((#$$$$)__45454&&^^@@@$#md54532515');

                $user->update([
                    'remember_token' => $token,
                    'password' => bcrypt($request->get('password'))
                ]);

                return Response()->json(['status' => true, 'msg' => 'رمز با موفقیت تغیر کرد.', 'token' => $token]);

            } elseif ($user->count() == 0) {
                return Response()->json(['status' => false, 'msg' => 'به جای فرستادن اسپم کتاب بخوانید.']);
            } else {
                return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
            }
        });

    });

    Route::prefix('users')->middleware('auth:api')->group(function () {

        Route::get('/autocomplete', function (Request $request) {
            $response = \Modules\User\Entities\User::select('id', 'name', 'mobile')
                ->where('name', 'like', '%'.$request->get('term').'%')
                ->orWhere('mobile', 'like', '%'.$request->get('mobile').'%')
                ->where("status", 1)
                ->take(10)
                ->get();
            return response($response);
        });

        Route::group(['prefix' => '/roles'], function () {
            Route::get('/', 'RoleController@index');
            Route::post('/', 'RoleController@store');
            Route::get('/{role}', 'RoleController@show');
            Route::get('/{role}/permissions', 'RoleController@hasPermissions');
            Route::put('/{role}/permissions', 'RoleController@setPermissions');
        });

        Route::get('/', 'UserController@index');
        Route::post('/', 'UserController@store');
        Route::get('/{id}', 'UserController@show');
        Route::put('/{id}', 'UserController@update');
        Route::put('/{id}/password', 'UserController@changePassword');
        Route::put('/{id}/status', 'UserController@changeStatus');


    });
});
