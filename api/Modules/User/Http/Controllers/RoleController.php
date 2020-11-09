<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\Role;

class RoleController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function index(Request $request)
    {
        $result = Role::select('id', 'title')
            ->where(function ($q) {
                if (Auth::check()) {
                    if (Auth::user()->role_id != 'programmer') {

                        if (! in_array(Auth::user()->role_id, Role::where('full_access', 1)->pluck('id')->toArray())) {
                            $q->whereNotIn('id', Role::where('full_access', 1)->pluck('id')->toArray());
                        }

                        if (Auth::user()->role_id != 'super_admin') {
                            $q->where('id', '<>', 'super_admin');
                        }

                        $q->where('id', '<>', 'programmer');
                    }
                }
            })
            ->orderBy('id','asc')
            ->get();


        return response($result);
    }

    public function show($id) {
        return response(Role::find($id));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $frm = $request->get('form');
        $validator = \Validator::make($frm, [
            'id' => 'required',
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg'  =>  $validator->errors()->first(), 'validator' => $validator->errors()]);
        }


        $result = Role::updateOrCreate(
            ['id' => $frm['id']],
            ['crud' => $frm['crud'], 'title' => $frm['title']]
        );


        if ($result) {
            return response()->json(['status' => true, 'result' => $result], 200);
        }

        return response()->json(['status' => false, 'msg' => 'un success'], 200);
    }

    /**
     * @param $role
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     *
     *
     * fetch all role with permission checked
     */
    public function hasPermissions($role, Request $request)
    {

        $list = Role::roleHasPermissions($role, $request->get('map') ?? false);

        return response($list);
    }


    public function setPermissions($role, Request $request)
    {
        $permissions = [];

        $role = Role::find($role);

        $role->permissions()->detach();

        foreach ($request->get('permissions') as $item) {
            foreach ($item['actions'] as $value) {
                if ($value['access']) {
                    $permissions[] = $value['id'];
                }
            }
        }

        $role->permissions()->attach($permissions);


        return response()->json(['status' => true, 'msg' => 'موفقیت آمیز']);
    }
}
