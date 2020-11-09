<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Brand;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $result = Brand::select('id', 'title')
            ->where(function ($q) use ($request) {
                if ($request->has('filter')) {
                    $filter = json_decode($request->get('filter'), true);
                    if (isset($filter['id'])) {
                        if ($filter['id']) {
                            $q->where('id', $filter['id']);
                        }
                    }

                    if (isset($filter['title'])) {
                        if ($filter['title']) {
                            $q->where('title', 'like', '%'. $filter['title'] . '%');
                        }
                    }
                }
            })
            ->where('deleted', 0)
            ->orderBy('id','desc')
            ->paginate(50);

        return response($result);
    }


    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg'  =>  $validator->errors()->first(), 'validator' => $validator->errors()]);
        }

        if (Brand::where('title', $request->get('title'))->where('deleted', 0)->exists()) {
            return response()->json(['status' => false, 'msg'  =>  'این برند قبل ثبت شده است.']);
        }



        $result = Brand::create(
            [
                'title' => $request->get('title'),
                'slug' => remove_special_char($request->get('title')),
                'meta_title' => $request->get('meta_title') ?? $request->get('title'),
                'meta_description' => $request->get('meta_description') ?? $request->get('title'),
            ]
        );


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

        $entity = Brand::find($id);

        $list = [
            'id' => $entity->id,
            'title' => $entity->title ?? '',
            'content' => $entity->content ?? '',
            'slug' => $entity->slug ?? '',
            'meta_title' => $entity->meta_title ?? '',
            'meta_description' => $entity->meta_description ?? '',
            'status' => $entity->status ?? '',
        ];

        return response($list);
    }

    public function update($id, Request $request) {

        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }


        $result = Brand::find($id);

        $request->merge(['slug' => remove_special_char($request->get('slug'))]);
        $result->update($request->all());

        if ($result) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id, Request $request)
    {

        $model = Brand::find($id);

        if ($request->get('field') == 'status') {

            $model = $model->update([
                'status' => $model->status ? 0 : 1
            ]);
        }


        if ($request->get('field') == 'deleted') {

            $model = $model->update([
                'deleted' => $model->deleted ? 0 : 1
            ]);
        }

        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }
}
