<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Entities\Category;
use Modules\Blog\Entities\Content;
use phpDocumentor\Reflection\DocBlock\Tag;

class ContentController extends Controller
{
    public function index(Request $request)
    {

        $entities = Content::with(['createdBy' => function($q) {
            $q->select('id','name');
        }])->where( function ($q) use ($request) {

            // Filter If Request Contain Filter Input
            if ($request->has('filter')) {

                $filter = json_decode($request->get('filter'), true);

                if (isset($filter['id'])) {
                    $q->where('id', '=', $filter['id']);
                }

                if (isset($filter['created_by']) && $filter['created_by'] != -1 and  $filter['created_by'] != "") {
                    $q->where('created_by', '=', $filter['created_by']);
                }


                if (isset($filter['category_id']) &&  $filter['category_id'] != -1 and  $filter['category_id'] != "") {
                    $q->whereIn('category_id', Category::descendantsAndSelf($filter['category_id'])->pluck('id'));
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function initShow($id, Request $request)
    {
        $result = Content::find($id);

        if ($result) {

            return response([
                'title' =>  $result->title,
                'heading' =>  $result->heading ?? '',
            ]);
        }

        return response()->json(['status' => false, 'msg' => 'request is invalid'], 200);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initUpdate($id, Request $request)
    {

        $result = Content::updateOrCreate(['id' => $id] ,[
            'title' =>  $request->get('title'),
            'heading' =>  $request->get('heading') ?? '',
        ]);


        if ($result) {
            return response()->json(['status' => true, 'msg' => 'عملیات موفقیت امیز بود.', 'model' => $result], 200);
        }

        return response()->json(['status' => false, 'msg' => 'un success'], 200);


    }

    public function initStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg'  =>  $validator->errors()->first()]);
        }


        $slug = Content::where('slug', remove_special_char($request->get('title')))->count();
        if ($slug > 0) {
            return Response()->json(['status' => false, 'msg' => 'اسلاگ قبلا ثبت شده است.']);
        }

        $model = Content::firstOrCreate([
            'created_by' => Auth::id(),
            'title' => $request->get('title'),
            'heading' => $request->get('heading'),
            'slug' => $request->get('slug') == '' ? remove_special_char($request->get('title')) : remove_special_char($request->get('slug')),
            'meta_title' => $request->get('meta_title') ?? $request->get('title'),
            'meta_description' => $request->get('meta_description') ?? $request->get('title'),
        ]);


        if ($model) {
            return response(['status' => true, 'model' => $model]);
        }

    }




    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id, Request $request)
    {

        $model = Content::find($id);

        if ($request->get('field') == 'status') {

            $model = $model->update([
                'status' => $model->status ? 0 : 1
            ]);
        }

        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }
}
