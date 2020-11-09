<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\Menu;

class MenuController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function index(Request $request)
    {
        return response(Menu::where('deleted', 0)->get()->toTree());
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
            'item' => Menu::select('id', 'title')->find($id),
            'list' => Menu::where('id', '<>' , $id)->get(),
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
            $node = Menu::find($id);
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
                    $node = new Menu([
                        'title' => trim($new),
                        'type' => $frm['type'],
                    ]);
                    $node->appendToNode(Menu::find($item));
                    $node->save();
                }
            }
            return response()->json(['status' => true, 'msg' => 'با موفقیت ایجاد شدند.']);
        } else {
            foreach ($new_node as $new) {
                Menu::create([
                    'title' => $new,
                    'type' => $frm['type'],
                ]);
            }
            return response()->json(['status' => true, 'msg' => 'با موفقیت ایجاد شد.']);
        }
    }

    public function show($id, Request $request) {

        $entity = Menu::find($id);

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


        $result = Menu::find($id);

        $result->update($frm);

        $nodes = Menu::descendantsAndSelf($id);

        foreach ($nodes as $node) {
            $node->status = $frm['status'];
            $node->deleted = $frm['deleted'];
            $node->type = $frm['type'];
            $node->save();
        }

        return response()->json(['status' => true, 'msg' => 'با موفقیت به روز رسانی گردید']);
    }

}
