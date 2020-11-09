<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Filter;

class FilterController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function index(Request $request)
    {

        if ($request->has('term') && $request->get('term')) {

            $response = Filter::where('deleted', 0)->where(function ($q) use ($request) {
                if ($request->has('term')) {
                    $q->where('title', 'like', '%'. $request->get('term') .'%');
                    $q->orWhere('slug', 'like', '%'. $request->get('term') .'%');
                    $q->orWhere('title', 'like', $request->get('term') .'%');
                    $q->orWhere('title', 'like', '%' . $request->get('term'));
                }
            })->orderBy('title', 'asc')->get();

        } else {

            $response = Filter::where('deleted', 0)->orderBy('title', 'asc')->get()->toTree();

        }


        return response($response);
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
            'item' => Filter::select('id', 'title')->find($id),
            'list' => Filter::where('id', '<>' , $id)->get(),
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
            $node = Filter::find($id);
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
                    $node = new Filter([
                        'title' => trim($new),
                    ]);
                    $node->appendToNode(Filter::find($item));
                    $node->save();
                }
            }
            return response()->json(['status' => true, 'msg' => 'با موفقیت ایجاد شدند.']);
        } else {
            foreach ($new_node as $new) {
                Filter::create([
                    'title' => $new,
                ]);
            }
            return response()->json(['status' => true, 'msg' => 'با موفقیت ایجاد شد.']);
        }
    }

    public function show($id, Request $request) {

        $entity = Filter::find($id);

        return response($entity);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {



        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }


        $result = Filter::find($id);

        $frm = $request->all();

        $result->update([
            'status' => $frm['status'],
            'deleted' => $frm['deleted'],
            'title' => $frm['title'],
            'slug' => $frm['slug'] ? remove_special_char($frm['slug']) : remove_special_char($frm['title']),
            'meta_title' => $frm['meta_title'] ?? $frm['title'],
            'meta_description' => $frm['meta_description'] ?? $frm['title'],
            'content' => $frm['content'],
        ]);

        $nodes = Filter::descendantsAndSelf($id);

        foreach ($nodes as $node) {
            $node->status = $frm['status'];
            $node->deleted = $frm['deleted'];
            $node->save();
        }


        return response()->json(['status' => true, 'msg' => 'با موفقیت به روز رسانی گردید']);
    }
}
