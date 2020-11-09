<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Filter;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function index(Request $request)
    {
        return response(Category::where('deleted', 0)->defaultOrder()->get()->toTree());
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

        $entity = Category::find($id);

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


        $result = Category::find($id);

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

        $nodes = Category::descendantsAndSelf($id);

        foreach ($nodes as $node) {
            $node->status = $frm['status'];
            $node->deleted = $frm['deleted'];
            $node->save();
        }


        return response()->json(['status' => true, 'msg' => 'با موفقیت به روز رسانی گردید']);
    }


    public function filters($id, $title, Request $request) {

        $list = [];
        switch ($title) {
            case 'brands':

                $list['data'] = DB::select('call sp_product_category_assignable_brands_with_checked(?, ?, ?)', [$id, $request->get('page'), $request->get('title') ?? '']);

                $list['total'] = $request->get('title') == '' ? Brand::count() : count($list['data']);

                break;
            case 'attributes':

                $list['data'] = DB::select('call sp_product_category_assignable_attributes_with_checked(?, ?, ?)', [$id, $request->get('page'), $request->get('title') ?? '']);

                $list['total'] = $request->get('title') == '' ? Attribute::count() : count($list['data']);
                break;
            case 'filters':

                $list['data'] = DB::select('call sp_product_category_assignable_filters_with_checked(?, ?, ?)', [$id, $request->get('page'), $request->get('title') ?? '']);

                $list['total'] = $request->get('title') == '' ? Filter::get()->toTree()->count() : count($list['data']);
                break;
        }

        return response($list);
    }



    public function selectedFilters($id, $title) {
        $model = Category::find($id);
        switch ($title) {
            case 'brands':
                return response($model->brands);
                break;
            case 'attributes':
                return response($model->attributes);
                break;
            case 'filters':
                return response($model->filters);
                break;
        }
    }

    /**
     * @param $id
     * @param $title
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function storeFilters($id, $title, Request $request) {
        switch ($title) {
            case 'brands':
                $entities = Category::descendantsAndSelf($id);
                foreach ($entities as $entity) {
                    $entity->brands()->attach([$request->get('id') => ['type' => 'brand']]);
                }

                return response(['status' => true, 'msg' => 'ok']);
                break;
            case 'attributes':
                $entities = Category::descendantsAndSelf($id);
                foreach ($entities as $entity) {
                    $entity->attributes()->attach([$request->get('id') => ['type' => 'attribute']]);
                }
                return response(['status' => true, 'msg' => 'ok']);
                break;
            case 'filters':
                $entities = Category::descendantsAndSelf($id);
                foreach ($entities as $entity) {
                    $entity->filters()->attach([$request->get('id') => ['type' => 'filter']]);
                }
                return response(['status' => true, 'msg' => 'ok']);
                break;
        }
    }

    public function removeFilters($id, $title, Request $request) {

        switch ($title) {
            case 'brands':
                $entities = Category::descendantsAndSelf($id);
                foreach ($entities as $entity) {
                    DB::table('product_category_assignable')
                        ->where('category_id', $entity->id)
                        ->where('assignable_id', $request->get('id'))
                        ->where('type', 'brand')
                        ->delete();
                }

                return response(['status' => true, 'msg' => 'ok']);
                break;
            case 'attributes':
                $entities = Category::descendantsAndSelf($id);
                foreach ($entities as $entity) {
                    DB::table('product_category_assignable')
                        ->where('category_id', $entity->id)
                        ->where('assignable_id', $request->get('id'))
                        ->where('type', 'attribute')
                        ->delete();
                }
                return response(['status' => true, 'msg' => 'ok']);
                break;
            case 'filters':
                $entities = Category::descendantsAndSelf($id);
                foreach ($entities as $entity) {
                    DB::table('product_category_assignable')
                        ->where('category_id', $entity->id)
                        ->where('assignable_id', $request->get('id'))
                        ->where('type', 'filter')
                        ->delete();
                }
                return response(['status' => true, 'msg' => 'ok']);
                break;
        }
    }

}
