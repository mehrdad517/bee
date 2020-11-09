<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\PackageType;

class PackageTypeController extends Controller
{
    public function index(Request $request)
    {
        $result = PackageType::select('id', 'title')
            ->orderBy('id','desc')
            ->paginate(50);

        return response($result);
    }


    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg'  =>  $validator->errors()->first(), 'validator' => $validator->errors()]);
        }


        $result = PackageType::create([
            'title' => $request->get('title'),
            'type' => $request->get('type'),
        ]);


        if ($result) {
            return response()->json(['status' => true, 'result' => $result], 200);
        }

        return response()->json(['status' => false, 'msg' => 'un success'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id) {

        $entity = PackageType::find($id);

        $list = [
            'id' => $entity->id,
            'title' => $entity->title ?? '',
            'type' => $entity->type ?? '',
        ];

        return response($list);
    }

    public function update($id, Request $request) {

        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ], [
            'title.required' => 'عنوان را وارد نکرده اید.',
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }

        $result = PackageType::where('id', $id)->first();

        $result->update($request->all());


        if ($result) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }

}
