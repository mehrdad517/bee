<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $entities = User::select(['id','name', 'email' ,'mobile', 'created_at', 'role_id', 'status'])->with(['role' => function($q) {
            $q->select('id', 'title');
        }])->where(function ($q) use($request) {

            if ($request->has('filter')) {

                $filter = json_decode($request->get('filter'), true);

                if (isset($filter['name'])) {
                    $q->where('name', 'like', '%' . $filter['name'] . '%');
                }

                if (isset($filter['mobile'])) {
                    $q->where('mobile', 'like', '%' . $filter['mobile'] . '%');
                }


                if (isset($filter['email'])) {
                    $q->where('email', 'like', '%' . $filter['email'] . '%');
                }

                if (isset($filter['role_id']) && $filter['role_id'] != -1) {
                    $q->where('role_id', @$filter['role_id']);
                } else {
                    if (Auth::check()) {
                        if (Auth::user()->role_id != 'programmer') {
                            if (! in_array(Auth::user()->role_id, Role::where('full_access', 1)->pluck('id')->toArray())) {
                                $q->whereNotIn('role_id', Role::where('full_access', 1)->pluck('id')->toArray());
                            }
                            if (Auth::user()->role_id != 'super_admin') {
                                $q->where('role_id', '<>', 'super_admin');
                            }
                            $q->where('role_id', '<>', 'programmer');
                        }
                    }

                }


                if (isset($filter['status']) && $filter['status'] != -1) {
                    $q->where('status', $filter['status']);
                }

            }

        })->orderBy($request->get('sort_field') ?? 'id', $request->get('sort_type') ?? 'desc')
            ->paginate($request->get('limit') ?? 10);

        return response($entities);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required|unique:user|min:11|max:11',
            'email' => 'required|unique:user|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }


        $request->merge(['password' => bcrypt($request->get('password'))]);

        $model = User::create($request->all());

        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id) {
        $entities = User::select(['id', 'name', 'mobile', 'email', 'created_at', 'role_id', 'status'])->with(['role' => function($q) {
            $q->select('id', 'title');
        }])->where('id', $id)->first();

        return response($entities);
    }

    /***
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request) {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'role_id' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }


        if ($request->get('mobile') && $request->get('mobile') != "") {
            $mobile = User::where('mobile', $request->get('mobile'))->where('id', '<>', $id)->count();
            if ($mobile > 0) {
                return Response()->json(['status' => false, 'msg' => 'موبایل قبلا تکرار شده است']);
            }
        }

        if ($request->get('email') && $request->get('email') != "") {
            $email = User::where('email', $request->get('email'))->where('id', '<>', $id)->count();
            if ($email > 0) {
                return Response()->json(['status' => false, 'msg' => 'ایمیل قبلا ثبت شده است.']);
            }
        }

        $model = User::where('id', $id)->update($request->all());

        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword($id, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }

        $model = User::where('id', $id)->update([
            'password' => bcrypt($request->get('password'))
        ]);

        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id, Request $request)
    {

        $model = User::find($id);
        $model = $model->update([
            'status' => $model->status ? 0 : 1
        ]);

        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }
}
