<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Attribute;

class AttributeController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     *
     *
     */
    public function index(Request $request)
    {

        $result = Attribute::select('id', 'title')
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


        if (Attribute::where('title', $request->get('title'))->where('deleted', 0)->first()) {
            return response()->json(['status' => false, 'msg' => 'این ویژگی قبلا ثبت شده است.'], 200);
        }


//        if (Attribute::where('slug', remove_special_char($request->get('title')))->exists()) {
//            return response()->json(['status' => false, 'msg'  =>  'این عنوان قبل ثبت شده است.']);
//        }


        $result = Attribute::create([
            'title' => $request->get('title'),
            'slug' => $request->get('slug') ? remove_special_char($request->get('slug')) : remove_special_char($request->get('title')),
            'meta_title' => $request->get('meta_title') ?? $request->get('title'),
            'meta_description' => $request->get('meta_description') ?? $request->get('title'),
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

        $entity = Attribute::find($id);

        $list = [
            'id' => $entity->id,
            'title' => $entity->title ?? '',
            'heading' => $entity->heading ?? '',
            'content' => $entity->content ?? '',
            'slug' => $entity->slug ?? '',
            'meta_title' => $entity->meta_title ?? '',
            'meta_description' => $entity->meta_description ?? '',
            'status' => $entity->status ?? '',
            'has_link' => $entity->has_link,
            'tags' => $entity->tags
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

//        if ($request->get('slug')) {
//            $slug = Attribute::where('slug', remove_special_char($request->get('slug')))->where('id', '<>', $id)->count();
//            if ($slug > 0) {
//                return Response()->json(['status' => false, 'msg' => 'اسلاگ قبلا ثبت شده است.']);
//            }
//        }

        $result = Attribute::where('id', $id)->first();

        $request->merge(['slug' => remove_special_char($request->get('slug'))]);
        $result->update($request->except('tags', 'tags_id'));

        $result->tags()->detach();
        if ($request->has('tags_id')) {
            foreach ($request->get('tags_id') as $tag) {

                if (!is_numeric($tag)) {
                    $try_check = \Modules\Tag\Entities\Tag::where('name', trim($tag))->first();
                    if ($try_check) {
                        $tag = $try_check->id;
                    } else {
                        $tag = \Modules\Tag\Entities\Tag::create(['name' => $tag])->id;
                    }
                }


                $result->tags()->attach($tag);
            }
        }

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

        $model = Attribute::find($id);

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
