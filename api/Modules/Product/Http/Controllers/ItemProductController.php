<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\ItemProduct;

class ItemProductController extends Controller
{
    public function index(Request $request)
    {

        $entities = ItemProduct::with(['createdBy', 'products'])
            ->orderBy($request->get('sort_field') ?? 'id', $request->get('sort_type') ?? 'desc')
            ->paginate($request->get('limit') ?? 50);

        return response($entities);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'title' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
            }

            if (count($request->get('products')) == 0) {
                return response()->json(['status' => false, 'msg' => 'هیچ محصولی انتخاب نشده است.']);
            }

            $result = ItemProduct::firstOrCreate([
                'title' => $request->get('title'),
                'status' => $request->get('status'),
                'link' => $request->get('link'),
                'created_by' => Auth::id()
            ]);


            if ($request->has('products')) {

                $result->products()->detach();
                $result->products()->attach($request->get('products'));

            }


            if ($result) {
                return response()->json(['status' => true, 'msg' => 'با موفقیت انجام شد.'], 200);
            }

            return response()->json(['status' => false, 'msg' => 'un success'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'msg' => $exception->getMessage()]);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = ItemProduct::with(['products' => function($q) {
            $q->select('id', 'title as name');
        }])->find($id);


        if ($result) {
            return response([
                'title' => $result->title,
                'status' => $result->status,
                'order' => $result->order,
                'link' => $result->link,
                'products' => $result->products
            ]);
        }

        return response()->json(['status' => false, 'msg' => 'request is invalid'], 200);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }

        if (count($request->get('products')) == 0) {
            return response()->json(['status' => false, 'msg' => 'هیچ محصولی انتخاب نشده است.']);
        }

        $result = ItemProduct::updateOrCreate(['id' => $id], [
            'title' => $request->get('title'),
            'status' => $request->get('status'),
            'link' => $request->get('link'),
        ]);


        $result->products()->sync($request->get('products_id'));



        if ($result) {
            return response()->json(['status' => true, 'msg' => 'عملیات موفقیت امیز بود.'], 200);
        }

        return response()->json(['status' => false, 'msg' => 'un success'], 200);

    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id, Request $request)
    {

        $model = ItemProduct::find($id);

        if ($request->get('field') == 'status') {

            $model = $model->update([
                'status' => $model->status ? 0 : 1
            ]);
        }

        if ($request->get('field') == 'order') {

            $model = $model->update([
                'order' => $request->get('value')
            ]);
        }


        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }
}
