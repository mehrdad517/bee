<?php

namespace Modules\Ticket\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Ticket\Entities\Category;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function index(Request $request)
    {
        return response(Category::where('deleted', 0)->get()->toTree());
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     *
     * fetch all place for replace node
     */
    public function findMapState($id)
    {
        return response([
            'item' => Category::select('id', 'title')->find($id),
            'list' => Category::where('id', '<>' , $id)->get(),
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dispachMove($id, Request $request)
    {
        $parent = $request->get('parent');

        try {
            $node = Category::find($id);
            $node->parent_id = $parent;
            $node->save();

        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }

        return response()->json(['status' => true, 'msg' => 'انتقال با موفقیت انجام شد.']);

    }

    public function store(Request $request)
    {

        $frm = $request->get('form');


        $validator = \Validator::make($frm, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }

        $new_node = explode(',', trim($frm['title'], ','));
        $selected = $request->get('checked');
        if (!empty($selected)) {
            foreach ($selected as $item) {
                foreach ($new_node as $new) {
                    $node = new Category([
                        'title' => trim($new),
                    ]);
                    $node->appendToNode(Category::find($item));
                    $node->save();
                }
            }
            return response()->json(['status' => true, 'msg' => 'با موفقیت ایجاد شدند.']);
        } else {
            foreach ($new_node as $new) {
                Category::create([
                    'title' => $new,
                ]);
            }
            return response()->json(['status' => true, 'msg' => 'با موفقیت ایجاد شد.']);
        }
    }

    public function show($id, Request $request) {

        $entity = Category::with(['tags'])->find($id);

        return response($entity);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {

        $frm = $request->get('form');

        $validator = \Validator::make($frm, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }


        $result = Category::find($id);

        $result->update([
            'status' => $frm['status'],
            'deleted' => $frm['deleted'],
            'title' => $frm['title'],
        ]);

        $nodes = Category::descendantsAndSelf($id);

        foreach ($nodes as $node) {
            $node->status = $frm['status'];
            $node->deleted = $frm['deleted'];
            $node->save();
        }

        $result->tags()->detach();
        if (isset($frm['tags_id'])) {
            foreach ($frm['tags_id'] as $tag) {

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


        return response()->json(['status' => true, 'msg' => 'با موفقیت به روز رسانی گردید']);
    }


    public function document($id, Request $request)
    {
        $result = Category::find($id);

        $result->update([
            'content' => $request->get('content'),
            'faq' => json_encode($request->get('faq'), JSON_UNESCAPED_UNICODE),
        ]);

        return response()->json(['status' => true, 'msg' => 'با موفقیت به روز رسانی گردید']);
    }

}
