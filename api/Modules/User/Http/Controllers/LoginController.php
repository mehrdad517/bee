<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Role;
use Lcobucci\JWT\Parser;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ( $validator->fails() ) {
            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }


        // fetch user with domain
        if(Auth::attempt(['mobile' => $request->get('username'), 'password' => $request->get('password')]) || Auth::attempt(['email' => $request->get('username'), 'password' => $request->get('password')])) {

            $user = $request->user();

            if ( ! $user->status ) {
                return response()->json([
                    'status' => false,
                    'msg' => 'کاربر غیرفعال است.'
                ], 200);
            }


            // create token
            $token = $user->createToken('Token Name')->accessToken;


            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => [
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'email' => $user->email,
                    'role' => Role::find($user->role_id),
                    'permissions' => Role::roleHasPermissions($user->role_id, false)
                ],
            ]);

        } else {
            return response()->json([
                'status' => false,
                'msg' => 'نام کاربری و کلمه عبور اشتباه است.'
            ], 200);
        }



    }

    public function logout(Request $request)
    {

        $bearer = $request->bearerToken();
        $id = (new Parser())->parse($bearer)->getHeaders('jti');
        $token = $request->user()->tokens->find($id);
        foreach ($token as $t) {
            $t->revoke();
        }

        return response(['status' => true]);
    }

}
