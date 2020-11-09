<?php

namespace Modules\Gallery\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Gallery\Entities\Gallery;

class GalleryController extends Controller
{

    public function index(Request $request)
    {

        $entities = Gallery::with(['createdBy'])->orderBy($request->get('sort_field') ?? 'id', $request->get('sort_type') ?? 'desc')
            ->paginate($request->get('limit') ?? 10);

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



            $result = Gallery::create([
                'title' => $request->get('title'),
                'status' => $request->get('status'),
                'is_slider' => $request->get('is_slider'),
                'show_in' => $request->get('show_in'),
                'created_by' => Auth::id()
            ]);


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
    public function show($id, Request $request)
    {
        $result = Gallery::find($id);

        if ($result) {
            return response([
                'title' => $result->title,
                'status' => $result->status,
                'is_slider' => $result->is_slider,
                'show_in' => $result->show_in,
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



        $result = Gallery::updateOrCreate(['id' => $id], [
            'title' => $request->get('title'),
            'status' => $request->get('status'),
            'is_slider' => $request->get('is_slider'),
            'show_in' => $request->get('show_in'),
        ]);



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

        $model = Gallery::find($id);

        if ($request->get('field') == 'status') {

            $model = $model->update([
                'status' => $model->status ? 0 : 1
            ]);
        } elseif($request->get('field') == 'is_slider') {
            $model = $model->update([
                'is_slider' => $model->is_slider ? 0 : 1
            ]);
        }

        if ($model) {

            return Response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);
        }
        return Response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }
}
