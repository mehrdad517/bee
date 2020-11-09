<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Product\Entities\PackageType;
use Modules\Product\Entities\Pins;
use Modules\Product\Entities\PriceParameter;
use Modules\Product\Entities\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $entities = Product::with(['brand', 'packageType'])->where(function ($q) use($request) {

            if ($request->has('filter')) {

                $filter = json_decode($request->get('filter'), true);

                if (isset($filter['title'])) {
                    $q->where('title', 'like', '%' . $filter['title'] . '%');
                }

                if (isset($filter['id']) && $filter['id'] > 0) {
                    $q->where('id', '=', $filter['id']);
                }

                if (isset($filter['count']) && $filter['count'] != -1) {
                    if ($filter['count'] == 1) {
                        $q->where('count', '>', 0);
                    } else {
                        $q->where('count', '=', 0);
                    }
                }

                if (isset($filter['discount']) && $filter['discount'] != -1) {
                    if ($filter['discount'] == 1) {
                        $q->where('discount', '>', 0);
                    } else {
                        $q->where('discount', '=', 0);
                    }
                }

                if (isset($filter['status']) && $filter['status'] != -1) {
                    $q->where('status', $filter['status']);
                }

                if (isset($filter['brand_id']) && $filter['brand_id'] != -1) {
                    $q->where('brand_id', $filter['brand_id']);
                }

                if (isset($filter['package_type_id']) && $filter['package_type_id'] != -1) {
                    $q->where('package_type_id', $filter['package_type_id']);
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
        $result = Product::with(['priceParameters'])->find($id);


        if ($result) {

            return response([
                'title' =>  $result->title,
                'heading' =>  $result->heading ?? '',
                'code' =>  $result->code,
                'brand_id' =>  $result->brand_id,
                'package_type_id' =>  $result->package_type_id,
                'brand' => $result->brand,
                'package_type' => $result->packageType,
                'price_parameters' => $result->priceParameters
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

        $result = Product::updateOrCreate(['id' => $id] ,[
            'title' =>  $request->get('title'),
            'heading' =>  $request->get('heading') ?? '',
            'code' =>  $request->get('code'),
            'brand_id' =>  $request->get('brand_id'),
            'package_type_id' =>  $request->get('package_type_id'),
        ]);


        if ($request->has('price_parameters')) {
            $result->priceParameters()->sync($request->get('price_parameters'));
        }


        if ($result) {
            return response()->json(['status' => true, 'msg' => 'عملیات موفقیت امیز بود.', 'model' => $result], 200);
        }

        return response()->json(['status' => false, 'msg' => 'un success'], 200);


    }

    public function initStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'package_type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg'  =>  $validator->errors()->first()]);
        }

        if ($request->get('package_type_id') == -1) {
            return response()->json(['status' => false, 'msg'  =>  'واحد را وارد کنید.']);
        }



        if ($request->get('code') && $request->get('code') != "") {
            $code = Product::where('code', $request->get('code'))->count();
            if ($code > 0) {
                return Response()->json(['status' => false, 'msg' => 'کد قبلا ثبت شده است.']);
            }
        }

        $slug = Product::where('slug', remove_special_char($request->get('title')))->count();
        if ($slug > 0) {
            return Response()->json(['status' => false, 'msg' => 'اسلاگ قبلا ثبت شده است.']);
        }


        $model = Product::firstOrCreate([
            'created_by' => Auth::id(),
            'title' => $request->get('title'),
            'heading' => $request->get('heading'),
            'package_type_id' => $request->get('package_type_id'),
            'brand_id' => $request->get('brand_id'),
            'code' => $request->get('code'),
            'slug' => $request->get('slug') == '' ? remove_special_char($request->get('title')) : remove_special_char($request->get('slug')),
            'meta_title' => $request->get('meta_title') ?? $request->get('title'),
            'meta_description' => $request->get('meta_description') ?? $request->get('title'),
        ]);


        if ($request->has('price_parameters')) {
            $model->priceParameters()->sync($request->get('price_parameters'));
        }

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

        $model = Product::find($id);

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

    // product pins
    public function productPins($id)
    {
        $list = [
            [
                'id' => '',
                'status' => 1,
                'count' => 0,
                'discount' => 0,
                'price' => 0,
                'weight' => 0,
                'price_parameters' => []
            ]
        ];

        // Get All Price Parameters
        $price_parameters = [];

        // Get Product Info
        $product = Product::select('id', 'title', 'package_type_id', 'code', 'count')->with(['packageType', 'pins', 'priceParameters'])->find($id);

        foreach ($product->priceParameters as $parameter) {
            $price_parameters[] = [
                'id' => $parameter->id,
                'title' => $parameter->title,
                'children' => PriceParameter::whereDescendantOf($parameter->id)->get(),
                'selected' => '',
            ];
        }

        $list[0]['price_parameters'] = $price_parameters;


        if (count($product->pins) > 0) { // Check Exist Record
            foreach ($product->pins as $key=>$pins) {
                $list[$key] = [
                    'id' => $pins['id'],
                    'status' => $pins['status'],
                    'count' => $pins['count'],
                    'discount' => $pins['discount'],
                    'price' => $pins['price'],
                    'weight' => $pins['weight']
                ];

                foreach ($product->priceParameters as $k=>$parameter) {

                    $selected = '';
                    foreach ($pins->priceParameters as $item) {
                        $bool = $item->isDescendantOf(PriceParameter::find($parameter['id']));
                        if ($bool) {
                            $selected = [
                                'id' => $item->id,
                                'title' => $item->title
                            ];
                            break;
                        }
                    }

                    $list[$key]['price_parameters'][] = [
                        'id' => $parameter['id'],
                        'title' => $parameter['title'],
                        'children' => PriceParameter::whereDescendantOf($parameter->id)->get(),
                        'selected' => $selected
                    ];
                }
            }
        }

        return response(['product' => $product, 'list' => $list]);
    }

    /*
     * find product
     * if product is bulk change count of product in main product table
     *
     * request has pins records
     * pins record update or create
     * after sync product pins with price parameter
     *
     */
    public function storePins($id, Request $request)
    {

        $product = Product::find($id);

        if ($product->packageType->type == PackageType::IS_BULK) {
            $product->count = $request->get('bulk_counter') ;
            $product->save();
        }

        if ($request->has('pins')) {

            foreach ($request->get('pins') as $pin) {

                try { // for duplicate insert, continue if record repeated

                    $price_parameter = [];
                    $product_pins = Pins::updateOrCreate(['id' => $pin['id'], 'product_id' => $product->id], [
                        'price' => $pin['price'],
                        'product_id' => $product->id,
                        'off_price' => $pin['price'] * (100 - $pin['discount']) / 100,
                        'discount' => $pin['discount'],
                        'weight' => $pin['weight'],
                        'count' => $pin['count'],
                        'status' => $pin['status'],
                    ]);

                    if (isset($pin['price_parameters'])) { // if record not price parameter
                        foreach ($pin['price_parameters'] as $parameter) {
                            if ($parameter['selected']) {
                                if (PriceParameter::where('id', $parameter['selected']['id'])->exists()) {
                                    $price_parameter[] = $parameter['selected']['id'];
                                }
                            } else { // price parameter not selected
                                $product_pins->delete();
                            }
                        }
                    }

                    $product_pins->priceParameters()->sync($price_parameter);
                } catch (\Exception $exception) {
                    continue;
                }



            }
        }


        return response()->json(['status' => true, 'msg' => 'عملیات موفقیت آمیز بود.']);

    }


}
