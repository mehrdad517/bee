<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Blog\Entities\Category;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
|
| frontend api
|
 */
Route::prefix('/')->middleware('access')->group(function () {

    Route::get('/setting', function (Request $request) {

        if (!\Cache::has('setting')) {

            $domain = \Modules\Setting\Entities\Domain::with(['links' => function($q) {
                $q->select('id', 'title', 'value', 'type');
            }])->where('status', 1)->first();


            $domain->introduce = strip_tags($domain->introduce);


            \Cache::put('setting', $domain , 24 * 60);


        }

        return response(\Cache::get('setting'));

    });

    Route::get('/menu', function (Request $request) {

        if (!\Cache::tags(['product_categories', 'menu', 'footer_menu', 'blog_categories'])->has('menu')) {

            \Cache::tags(['menu'])->put('menu', [
                'product_categories' => \Modules\Product\Entities\Category::where('status', 1)->where('deleted', 0)->get()->toTree(),
                'menu' => \Modules\Blog\Entities\Menu::where('status', 1)->where('deleted', 0)->where(['type' => 'header'])->get()->toTree(),
                'footer_menu' => \Modules\Blog\Entities\Menu::where('status', 1)->where('deleted', 0)->where(['type' => 'footer'])->get()->toTree(),
                'blog_categories' => Category::where('status', 1)->where('deleted', 0)->get()->toTree()
            ], 24 * 60);

        }

        return response(\Cache::tags(['menu'])->get('menu'));

    });

    Route::get('/slider', function () {

        if (!\Cache::tags(['slider'])->has('slider')) {

            $result = \Illuminate\Support\Facades\DB::select('call sp_slider');

            \Cache::tags(['slider'])->put('slider', $result, 24 * 60);
        }

        return response(\Cache::tags(['slider'])->get('slider'));
    });

    Route::get('/search', function (Request $request) {


        $q = $request->get('q');
        $type = $request->get('type');

        if (!\Cache::has("search[$type][$q]")) {

            $result = ['contents' => [], 'categories' => [], 'tags' => []];

            $tags = \Modules\Tag\Entities\Tag::where('name', 'like', '%'. $request->get('q') . '%')
                ->take(5)
                ->get();

            switch ($type) {

                case 'site':
                    $products = \Modules\Product\Entities\Product::select('id', 'title', 'slug')
                        ->where('title', 'like', '%'. $q . '%')
                        ->where('status', 1)
                        ->take(5)
                        ->get();

                    $categories = \Modules\Product\Entities\Category::select('id', 'title', 'slug')
                        ->where('title', 'like', '%'. $q . '%')
                        ->where('status', 1)
                        ->take(5)
                        ->get();

                    $result = ['contents' => $products, 'categories' => $categories, 'tags' => $tags];
                    break;

                case 'blog':

                    $blog = \Modules\Blog\Entities\Content::select('id', 'title', 'slug')
                        ->where('title', 'like', '%'. $q . '%')
                        ->where('status', 1)
                        ->take(5)
                        ->get();

                    $categories = Category::select('id', 'title', 'slug')
                        ->where('title', 'like', '%'. $q . '%')
                        ->where('status', 1)
                        ->take(5)
                        ->get();

                    $result = ['contents' => $blog, 'categories' => $categories, 'tags' => $tags];

                    break;
            }

            \Cache::put("search[$type][$q]", $result , 24 * 60);
        }

        return response(\Cache::get("search[$type][$q]"));


    });

    Route::post('/login', function (Request $request) {


        $domain = \Modules\Setting\Entities\Domain::with(['links' => function($q) {
            $q->select('id', 'title', 'value', 'type');
        }])->first();

        if (!$domain->status) {
            return Response()->json(['status' => false, 'message' => 'امکان ورد به سایت توسط مدیر سایت بسته شده است.']);
        }

        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required'
        ]);

        if ( $validator->fails() ) {
            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }


        // fetch user with domain
//        if(Auth::attempt([
//                'mobile' => $request->get('username'),
//                'password' => $request->get('password')
//            ]) || Auth::attempt([
//                'email' => $request->get('username'),
//                'password' => $request->get('password')
//            ]) || Auth::attempt([
//                'nickname' => $request->get('username'),
//                'password' => $request->get('password')
//            ])) {
//
//            $user = $request->user();
            $user = Auth::loginUsingId(1);

            if ( ! $user->status ) {
                return response()->json([
                    'status' => false,
                    'msg' => 'کاربر غیرفعال است.'
                ], 200);
            }


            // create token
            $token = $user->createToken('Token Name')->accessToken;


            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => [
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'email' => $user->email,
                ],
            ]);

//        }
//        else {
//            return response()->json([
//                'status' => false,
//                'msg' => 'نام کاربری و کلمه عبور اشتباه است.'
//            ], 200);
//        }
    });

    Route::post('/verify', function (Request $request) {

        $validator = \Validator::make($request->all(), [
            'mobile' => 'required|min:11|max:11',
            'code' => 'required',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }

        $token = quickRandom(); // render token

        $user = User::where('mobile', $request->get('mobile'))
            ->where('remember_token', $request->get('token'))
            ->where('verify_code', $request->get('code'))
            ->where('status', 1)
            ->first();

        // check user status
        if ($user) {
            //update access token and remember token
            $user->update([
                'mobile_verify' => true,
                'confirmed_at' => date('Y-m-d H:i:s'),
                'remember_token' => $token
            ]);

            Auth::loginUsingId($user->id);

            $user = $request->user();

            // create access to
            $access = $user->createToken('Token Name');
            if ($access) {
                $token = $access->accessToken;
            }


            return response()->json([
                'status' => true,
                'msg' => 'با موفقیت وارد سایت شدید.',
                'result' => [
                    'token' => $token,
                    'user' => [
                        'name' => $user->name ?? '',
                        'mobile' => $user->mobile,
                    ],
                ],

            ]);


        } else {
            return Response()->json(['status' => false, 'msg' => 'اطلاعات ارسالی نامعتبر است.']);
        }
    });

    Route::get('/brand', function () {

        if (!\Cache::tags(['brand'])->has("brand")) {

            $brands = \Illuminate\Support\Facades\DB::table("brand")
                ->select("id", "slug", "title", "heading")
                ->orderBy(\Illuminate\Support\Facades\DB::raw('RAND()'))
                ->where('status', 1)
                ->take(15)
                ->get();

            \Cache::tags(['brand'])->put("brand", $brands , 1 * 60);
        }

        return response(\Cache::tags(['brand'])->get('brand'));
    });

    Route::group(['prefix' => 'blog'], function() {


        Route::get('/', function (Request $request) {

            $page = $request->get('page') ?? 1;
            $sort = $request->get('sort') ?? 0;
            $limit = $request->get('limit') ?? 10;

            $sort_items = blogSortItems();

            if (!\Cache::tags(['blog', 'blog_content'])->has("blog[$page][$sort][$limit]")) {

                $contents = \Modules\Blog\Entities\Content::select('id', 'title', 'img', 'short_content as content', 'created_at', 'visitor', 'slug')->when(true, function ($q) use ($request, $sort_items, $sort) {
                    if ($request->has('sort')) {
                        if (isset($sort_items[$sort])) {
                            $q->orderBy($sort_items[$sort]['field'], $sort_items[$sort]['type']);
                        }
                    } else {
                        $q->orderBy('id', 'desc');
                    }
                })
                    ->where('status', 1)
                    ->whereHas('categories')
                    ->paginate($limit);

                \Cache::tags(['blog', 'blog_content'])->put("blog[$page][$sort][$limit]", [
                    'category' => [
                        'label' => 'وبلاگ',
                        'slug' => 'blog',
                        'meta_title' => '',
                        'meta_description' => '',
                    ],
                    'contents' => $contents,
                    'sorts' => $sort_items
                ] , 24 * 60);

            }

            return response()->json(['status' => true, 'result' => \Cache::tags(['blog', 'blog_content'])->get("blog[$page][$sort][$limit]")]);
        });

        Route::get('/tag/{tag}', function ($tag, Request $request) {

            $page = $request->get('page') ?? 1;
            $sort = $request->get('sort') ?? 0;
            $limit = $request->get('limit') ?? 10;

            $sort_items = blogSortItems();

            if (!\Cache::tags(['blog', 'blog_tags'])->has("blog[tag][$tag][$page][$sort][$limit]")) {

                $result = \Modules\Tag\Entities\Tag::where('id', $tag)
                    ->firstOrFail();


                $contents = $result->contents()
                    ->when(true, function ($q) use($request, $sort_items, $sort) {
                        if ($request->has('sort')) {
                            if (isset($sort_items[$sort])) {
                                $q->orderBy($sort_items[$sort]['field'], $sort_items[$sort]['type']);
                            }
                        } else {
                            $q->orderBy('id', 'desc');
                        }
                    })
                    ->where('status', 1)
                    ->paginate($limit, ['id', 'title', 'img', 'short_content as content', 'created_at', 'visitor', 'slug']);


                \Cache::tags(['blog', 'blog_tags'])->put("blog[tag][$tag][$page][$sort][$limit]", [
                    'category' => [
                        'label' => $result->name,
                        'slug' => $result->name,
                        'meta_title' => $result->name,
                        'meta_description' => $result->name,
                    ],
                    'contents' => $contents,
                    'sorts' => $sort_items
                ] , 24 * 60);


            }

            return response()->json(['status' => true, 'result' => \Cache::tags(['blog', 'blog_tags'])->get("blog[tag][$tag][$page][$sort][$limit]")]);

        });

        Route::get('/category/{category}', function ($category, Request $request) {

            $page = $request->get('page') ?? 1;
            $sort = $request->get('sort') ?? 0;
            $limit = $request->get('limit') ?? 10;

            $sort_items = blogSortItems();


            if (!\Cache::tags(['blog', 'blog_categories'])->has("blog[category][$category][$page][$sort][$limit]")) {

                $result = Category::select('id', 'title', 'slug', 'meta_title', 'meta_description')
                    ->where(is_numeric($category) ? 'id' : 'slug', $category)
                    ->where('status', 1)
                    ->firstOrFail();

                $contents = $result->contents()
                    ->when(true, function ($q) use($request, $sort_items, $sort) {
                        if ($request->has('sort')) {
                            if (isset($sort_items[$sort])) {
                                $q->orderBy($sort_items[$sort]['field'], $sort_items[$sort]['type']);
                            }
                        } else {
                            $q->orderBy('id', 'desc');
                        }
                    })
                    ->where('status', 1)
                    ->paginate($limit, ['id', 'title', 'img', 'short_content as content', 'created_at', 'visitor', 'slug']);


                \Cache::tags(['blog', 'blog_categories'])->put("blog[category][$category][$page][$sort][$limit]", ['category' => $result, 'contents' => $contents, 'sorts' => $sort_items] , 24 * 60);


            }

            return response()->json(['status' => true, 'result' => \Cache::tags(['blog', 'blog_categories'])->get("blog[category][$category][$page][$sort][$limit]")]);

        });

        Route::get('/content/{id}', function ($id, Request $request)  {

            if (!\Cache::tags(['blog', 'content_id'])->has("content[$id]")) {

                $content = \Modules\Blog\Entities\Content::with(['files' => function($q) {
                    $q->select('fileable_id', 'fileable_type', 'mime_type', DB::raw('fetch_file_address(id) as prefix'), 'file', 'size')
                        ->where('collection', 1)
                        ->orderBy('order', 'asc');
                }, 'products' => function($q) use($request) {
                    $q->select('id', 'title', 'slug', 'price', 'discount', 'count' ,'brand_id', 'img')
                        ->with(['brand' => function($q) {
                            $q->select('id', 'title', 'id');
                        }])
                        ->where('status', 1) // change to true
                        ->orderBy($request->get('sort') ?? 'id', $request->get('type') ?? 'desc')
                        ->get();
                }, 'createdBy' => function($q) {
                    $q->select('id', 'name');
                }, 'tags', 'categories' => function($q) use($id) {
                    $q->select('id', 'title', 'slug')
                        ->where('status', 1)
                        ->with(['contents' => function($q) use($id) {
                            $q->select('id', 'slug', 'title', 'heading', 'created_at', 'img')
                                ->where('status', 1)
                                ->where(is_numeric($id) ? 'id' : 'slug', '<>', $id)
                                ->take(5);
                        }]);
                }])
                    ->where(is_numeric($id) ? 'id' : 'slug', $id)
                    ->where('status', 1)
                    ->firstOrFail();

                \Cache::tags(['blog', 'content_id'])->put("content[$id]", $content , 24 * 60);
            }


            return response(\Cache::tags(['blog', 'content_id'])->get("content[$id]"));
        });

        Route::get('/categories', function () {

            if (!\Cache::tags(['blog', 'blog_categories'])->has('blog_categories')) {

                \Cache::tags(['blog','blog_categories'])->put('blog_categories', [
                    'blog_categories' => Category::where('status', 1)->where('deleted', 0)->get()->toTree(),
                ], 24 * 60);

            }

            return response(\Cache::tags(['blog','blog_categories'])->get('blog_categories'));
        });


    });

    Route::group(['prefix' => 'shop'], function () {


        Route::get('/', function (Request $request) {

            $sort = productSortItems(); // get sort items

            $result = [
                'navigation' => [],
                'tree' => [],
                'brands' => [],
                'sort' => $sort,
            ];

            if (!\Cache::has("shop")) {
                // get children
                $result['tree'] = \Modules\Product\Entities\Category::select('id', 'slug', 'title')->whereNull('parent_id')->get();
                // push result to caching
                \Cache::put("shop", $result , 24 * 60);
            }


            $products = \Modules\Product\Entities\Product::select('id', 'title', 'slug', 'price', 'discount', 'img', 'off_price')
                ->where(function ($q) use ($request) {
                    // stock products
                    if ($request->has('stock')) {
                        if ($request->get('stock') == 1) {
                            $q->where('count', '>', 0); // change to >
                        }
                    }
                })
                ->where('status', 1) // change to true
                ->when(true, function ($q) use($request, $sort) {
                    if ($request->has('sort')) {
                        if (isset($sort[$request->get('sort')])) {
                            $q->orderBy($sort[$request->get('sort')]['field'], $sort[$request->get('sort')]['type']);
                        }
                    } else {
                        $q->orderBy('id', 'desc');
                    }
                })
                ->paginate($request->get('limit') ?? 24);


            return response()->json([
                'cached' => \Cache::get("shop"),
                'products' => $products
            ]);

        });

        Route::get('/brand/{brand}', function ($brand, Request $request) {

            $sort = productSortItems(); // get sort items

            $cache = \Cache::tags(['brand'])->remember("brand[$brand]", 24 * 60, function () use($brand, $sort) {

                $brand = \Modules\Product\Entities\Brand::with(['categories' => function($q) {
                    $q->select('id', 'title', 'slug');
                }])->select('id', 'slug', 'title', 'heading', 'content' ,'meta_title', 'meta_description')
                    ->where(is_numeric($brand) ? 'id': 'slug', $brand)
                    ->firstOrFail();


                $result = [
                    'id' => $brand->id,
                    'slug' => $brand->slug,
                    'label' => $brand->title,
                    'heading' => $brand->heading ?? '',
                    'meta_title' => $brand->meta_title,
                    'meta_description' => $brand->meta_description,
                    'content' => $brand->content,
                    'navigation' => [],
                    'tree' => [],
                    'brands' => [],
                    'attributes' => [],
                    'sort' => $sort,
                ];

                // navigation
                $result['navigation'] =  [
                    [
                        'id' => $brand->id,
                        'slug' => $brand->slug,
                        'title' => $brand->title
                    ]
                ];

                $result['tree'] = $brand->categories;

                return $result;

            });


            $products = \Modules\Product\Entities\Product::select('id', 'title', 'slug', 'price', 'discount', 'img')
                ->where(function ($q) use ($request) {
                    // stock products
                    if ($request->has('stock')) {
                        if ($request->get('stock') == 1) {
                            $q->where('count', '>', 0);
                        }
                    }
                })
                ->where('brand_id', $cache['id'])
                ->where('status', 1) // change to true
                ->when(true, function ($q) use($request, $sort) {
                    if ($request->has('sort')) {
                        if (isset($sort[$request->get('sort')])) {
                            $q->orderBy($sort[$request->get('sort')]['field'], $sort[$request->get('sort')]['type']);
                        }
                    } else {
                        $q->orderBy('id', 'desc');
                    }
                })
                ->paginate($request->get('limit') ?? 24);


            return response()->json([
                'cached' => $cache,
                'products' => $products
            ]);
        });

        // product list
        Route::get('/category/{category}', function ($category, Request $request) {

            $sort = productSortItems(); // get sort items

            $cache = \Cache::tags(['product_categories'])->remember("category[$category]", 24 * 60, function () use($category, $sort) {

                $category = \Modules\Product\Entities\Category::select('id', 'slug', 'title', 'heading' ,'meta_title', 'meta_description', 'content')
                    ->where(is_numeric($category) ? 'id': 'slug', $category)
                    ->firstOrFail();

                $result = [
                    'id' => $category->id,
                    'slug' => $category->title,
                    'label' => $category->label,
                    'heading' => $category->heading ?? '',
                    'meta_title' => $category->meta_title,
                    'meta_description' => $category->meta_description,
                    'content' => $category->content,
                    'navigation' => [],
                    'tree' => [],
                    'brands' => [],
                    'attributes' => [],
                    'sort' => $sort,
                ];


                // navigation
                $result['navigation'] =  \Modules\Product\Entities\Category::select('id', 'slug', 'title')->ancestorsAndSelf($category->id);

                // get children
                $result['tree'] = \Modules\Product\Entities\Category::select('id', 'slug', 'title')->where('parent_id', $category->id)->get();

                // get all brands
                $result['brands'] = $category->brands()->select('id', 'slug', 'title')->where('status', 1)->get();


                return ['result' => $result, 'category' => $category];

            });


            $products = $cache['category']->products()->select('id', 'title', 'slug', 'price', 'discount', 'img')
                ->where(function ($q) use ($request) {
                    // stock products
                    if ($request->has('stock')) {
                        if ($request->get('stock') == 1) {
                            $q->where('count', '>', 0); // change to >
                        }
                    }
                    // brand filter
                    if ($request->has('brands') && $request->get('brands')) {
                        $q->whereIn('brand_id', explode(',', $request->get('brands')));
                    }
                })
                ->where('status', 1) // change to true
                ->when(true, function ($q) use($request, $sort) {
                    if ($request->has('sort')) {
                        if (isset($sort[$request->get('sort')])) {
                            $q->orderBy($sort[$request->get('sort')]['field'], $sort[$request->get('sort')]['type']);
                        }
                    } else {
                        $q->orderBy('id', 'desc');
                    }
                })
                ->paginate($request->get('limit') ?? 24);


            return response()->json([
                'cached' => $cache['result'],
                'products' => $products
            ]);
        });

        // payload home page items
        Route::get('/products/swiper', function (Request $request) {

            if (!\Cache::tags(['item_product'])->has("item_product")) {
                $list = \Modules\Product\Entities\ItemProduct::select('id', 'title', 'link')
                    ->with(['products' => function($q) use($request) {
                        $q->select('id', 'title', 'slug', 'price', 'off_price' ,'discount', 'img')
                            ->where(function ($q) use ($request) {
                                if ($request->has('stock')) {
                                    if ($request->get('stock') == 1) {
                                        $q->where('count', '>', 0);
                                    }
                                }
                            })
                            ->where('status', 1) // change to true
                            ->orderBy($request->get('sort') ?? 'id', $request->get('type') ?? 'desc')
                            ->get();
                    }])
                    ->where('status', 1)
                    ->orderBy('order', 'asc')
                    ->get()
                    ->toArray();

                \Cache::tags(['item_product'])->put("item_product", $list , 24 * 60);
            }


            return response(\Cache::tags(['item_product'])->get("item_product"));
        });

        Route::get('/products/{id}', function ($id, Request $request) {

            $data = \Cache::remember("product[$id]", 24 * 60 , function () use($id) {

                $product = \Illuminate\Support\Facades\DB::select('call sp_frontend_product(?)', [$id]);
                $similar = \Illuminate\Support\Facades\DB::select('call sp_frontend_product_family(?)', [$id]);
                $categories = \Illuminate\Support\Facades\DB::select('call sp_frontend_product_categories(?)', [$id]);

                return [
                    'product' => $product[0],
                    'similar' => $similar,
                    'categories' => $categories,
                ];

            });


            return response($data);

        });

        Route::post('/products/{id}/pins', function ($id, Request $request) {

            $result = [];

            $product_pins = DB::table('product_pins as pp')
                ->select(['pp.id', 'pp.price', 'pp.discount', 'pp.off_price', 'pp.count'])
                ->leftJoin('product_pins_price_parameter as pppp', 'pppp.product_pins_id', '=', 'pp.id')
                ->leftJoin('product as p', 'p.id', '=', 'pp.product_id')
                ->where('pp.status', 1)
                ->where('p.id', $id)
                ->orderBy('pp.id', 'asc')
                ->whereIn('pppp.price_parameter_value', $request->all())
                ->get();

            foreach ($product_pins as $pin) {
                $result[] = [
                    'pins' => $pin,
                    'selected' => DB::select('call sp_frontend_product_pins_price_parameters(?)', [$pin->id])
                ];
            }



            return response(['result' => $result]);

        });

    });

    Route::prefix('card')->middleware('auth:api')->group(function () {

        Route::get('/', function () {
            $card = DB::select('call sp_card(?)', [Auth::user()->id]);
            return response()->json($card);
        });

        Route::post('/', function (Request $request) {
            try {
                $card = DB::select('call sp_card_add(?, ?, ?)', [Auth::user()->id, $request->get('id'), $request->get('count')]);

                return response()->json(['status' => true, 'card' => $card]);
            } catch (Exception $exception) {
                if ($exception instanceof \Illuminate\Database\QueryException) {
                    return response()->json(['status' => false, 'msg' => $exception->getPrevious()->errorInfo[2]]);
                } else {
                    return response()->json(['status' => false, 'msg' => $exception->getMessage()]);
                }
            }
        });

        Route::delete('/{id}', function ($id) {
            try {
                $card = DB::select('call sp_card_remove(?, ?)', [Auth::user()->id, $id]);
                return response()->json(['status' => true, 'card' => $card]);
            } catch (Exception $exception) {
                if ($exception instanceof \Illuminate\Database\QueryException) {
                    return response()->json(['status' => false, 'msg' => $exception->getPrevious()->errorInfo[2]]);
                } else {
                    return response()->json(['status' => false, 'msg' => $exception->getMessage()]);
                }
            }
        });


    });

    Route::prefix('address')->middleware('auth:api')->group(function () {

        Route::get('/', function () {
            $address = DB::select('call sp_address(?)', [Auth::user()->id]);
            return response()->json($address);
        });

        Route::post('/', function (Request $request) {
            try {

                $validator = \Validator::make($request->all(), [
                    'reciver_name' => 'required',
                    'region_id' => 'required',
                    'main' => 'required',
                    'postal_code' => 'required|min:10|max:10',
                    'reciver_mobile' => 'required|min:11|max:11',
                    'reciver_national_code' => 'required|min:10|max:10',
                ]);

                if ( $validator->fails() ) {
                    return Response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }

                $address = DB::select('call sp_address_add(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                    Auth::user()->id,
                    $request->get('region_id'),
                    $request->has('lat') ? $request->get('lat') : null,
                    $request->has('lng') ? $request->get('lng') : null,
                    $request->get('main'),
                    $request->get('postal_code'),
                    $request->get('reciver_name'),
                    $request->get('reciver_mobile'),
                    $request->get('reciver_national_code'),
                ]);
                return response()->json(['status' => true, 'address' => $address]);
            } catch (Exception $exception) {
                return response()->json(['status' => false, 'msg' => $exception->getPrevious()->errorInfo[2]]);
            }
        });

        Route::put('/{id}', function ($id, Request $request) {
            try {
                $address = DB::select('call sp_address_update(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                    $id,
                    Auth::user()->id,
                    $request->get('region_id'),
                    $request->has('lat') ? $request->get('lat') : null,
                    $request->has('lng') ? $request->get('lng') : null,
                    $request->get('main'),
                    $request->get('postal_code'),
                    $request->get('reciver_name'),
                    $request->get('reciver_mobile'),
                    $request->get('reciver_national_code'),
                ]);
                return response()->json(['status' => true, 'address' => $address]);
            } catch (Exception $exception) {
                return response()->json(['status' => false, 'msg' => $exception->getPrevious()->errorInfo[2]]);
            }
        });

        Route::delete('/{id}', function ($id) {
            try {
                $address = DB::select('call sp_address_deleted(?, ?)', [$id, Auth::user()->id]);
                return response()->json(['status' => true, 'address' => $address]);
            } catch (Exception $exception) {
                return response()->json(['status' => false, 'card' => $exception->getPrevious()->errorInfo[2]]);
            }
        });
    });

    Route::prefix('gateway')->group(function () {
        /**
         * gateway pasargad, zarinpal, ....
         * invoice_id
         */
        Route::post('/', function (Request $request) {

            try {

                $order = \Modules\Order\Entities\Order::find($request->get('invoice_id'));

                if ($order->user_id !== Auth::id()) return response(['status' => false, 'msg' => 'این فاکتور متعلق به شما نیست']);
                if (in_array($order->status, [1])) return response(['status' => false, 'msg' => 'این فاکتور پرداخت شده است']);


                Auth::user()->update([
                    'remember_token' => \Illuminate\Support\Facades\Hash::make(quickRandom()),
                ]);

                // create payment
                switch ($request->get('gateway')) {
                    case 'parsian':
                        $gateway = Payment::parsian();
                        $gateway->setAmount($order->total_pay);
                        break;
                    case 'passargad':
                        $gateway = Payment::pasargad();
                        $gateway->setAmount($order->total_pay);
                        break;
                }


                $gateway->setDescription('پرداخت فاکتور');
                $transaction = $gateway->ready();
                $gateway->setCallback(route('gateway_callback', ['uuid' => $transaction->id, 'invoice' => $order->id, 'token' => Auth::user()->getRememberToken()]));

                return $gateway->redirect();


            } catch (Exception $exception) {
                if ($exception instanceof \Illuminate\Database\QueryException) {
                    return response()->json(['status' => false, 'msg' => $exception->getPrevious()->errorInfo[2]]);
                } else {
                    return response()->json(['status' => false, 'msg' => $exception->getMessage()]);
                }
            }
        })->middleware('auth:api');


        Route::any('/callback', function (Request $request) {

            /**
             * get callback parameters from app
             */
            $order_id = $request->get('invoice');
            $uuid = $request->get('uuid');
            $token = $request->get('token');

            $order = \Modules\Order\Entities\Order::find($order_id);
            $user = User::where('id', $order->user_id)->where('remember_token', $token)->first();

            if ( ! $user ) return response(['status' => false, 'msg' => 'درخواست نامعتبر: کاربر پرداخت کننده با سفارش دهنده منطبق نیست.']);

            Auth::user()->update([
                'remember_token' => \Illuminate\Support\Facades\Hash::make(quickRandom())
            ]);

            $transaction = \Modules\Finanical\Entities\Transaction::find($uuid);

            if ($transaction->status == 'success')  return response(['status' => false, 'msg' => 'این فاکتور قبلا پرداخت شده است.']);

            /**
             * get bank parameters
             */
            switch ($transaction->gateway) {
                case 'parsian':
                    $gateway = Payment::parsian();
                    $gateway->setParameter($request->all());
                    break;
                case 'passargad':
                    $gateway = Payment::pasargad();
                    break;
            }

            $result = $gateway->verify($transaction); // return transaction model

            if ($result->status === 'success') {
                $order->status = 1;
                $order->save();
            }

            return redirect()->away(env('WEB_URL') . '/invoice/' . $order_id);

        })->name('gateway_callback');


    });

    Route::prefix('order')->middleware('auth:api')->group(function () {

        Route::get('/{id}', function ($id, Request $request) {
            try {
                $order = DB::select('call sp_order_info(?)', [$id]);
                if ($order[0]->user_id != Auth::id()) {
                    return response()->json(['status' => false, 'msg' => 'این سفارش متعلق به شما نیست.']);
                }
                return response()->json(['status' => true, 'order' => $order[0]]);
            } catch (Exception $exception) {
                if ($exception instanceof \Illuminate\Database\QueryException) {
                    return response()->json(['status' => false, 'msg' => $exception->getPrevious()->errorInfo[2]]);
                } else {
                    return response()->json(['status' => false, 'msg' => $exception->getMessage()]);
                }
            }
        });


        Route::post('/', function (Request $request) {

            $validator = \Validator::make($request->all(), [
                'address_id' => 'required',
            ]);

            if ( $validator->fails() ) {
                return Response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            try {
                $order = DB::select('call sp_card_To_order(?, ?)', [Auth::user()->id, $request->get('address_id')]);
                if (is_array($order) && count($order) > 0) {
                    return response()->json(['status' => true, 'id' => $order[0]->order_id]);
                }
                return response()->json(['status' => false, 'order' => $order]);
            } catch (Exception $exception) {
                if ($exception instanceof \Illuminate\Database\QueryException) {
                    return response()->json(['status' => false, 'msg' => $exception->getPrevious()->errorInfo[2]]);
                } else {
                    return response()->json(['status' => false, 'msg' => $exception->getMessage()]);
                }
            }
        });
    });

    Route::get('/region', function () {

        if (!\Cache::tags(['region'])->has("region")) {

            $regions = \Modules\Region\Entities\Region::get()->toTree();

            \Cache::tags(['region'])->put("region", $regions , 24 * 60);
        }

        return response(\Cache::tags(['region'])->get('region'));
    });




});



Route::get('/init', function (Request $request) {



//
//    $list = [];
//    $lists = \Illuminate\Support\Facades\DB::table('product_temp')->get()->toArray();
//
//
//
//        foreach ($lists as $key=>$item) {
//
//            try {
//
//            $c = collect($item);
//
//            $model = \Modules\Product\Entities\Product::updateOrCreate(['title' => $c['product_name']],[
//                'title' => $c['product_name'],
//                'slug' => remove_special_char(trim($c['product_name'])),
//                'meta_title' => 'مشخصات،قیمت و خرید آنلاین ' . $c['product_name'] . ' | دیجی عطار',
//                'meta_description' => $c['abstract'] ,
//                'content' => $c['description'],
//                'package_type_id' => 16,
//                'brand_id' => 1,
//                'short_content' => $c['abstract'],
//                'heading' => $c['other_names'] ? substr($c['other_names'], 0, 255) : null,
//            ]);
//
//            $model->attributes()->detach();
//
//            $fasl = \Modules\Tag\Entities\Tag::where('name', 'like', '%'.$c['harvest_season'].'%')->first();
//            if ($fasl && $c['harvest_season'] != "") {
//                $model->attributes()->attach([30 => [
//                    'tag_id' => $fasl ? $fasl->id : null
//                ]]);
//            }
//
//            $fasl = \Modules\Tag\Entities\Tag::where('name', 'like', '%'.$c['section'].'%')->first();
//            if ($fasl && $c['section'] != "") {
//                $model->attributes()->attach([31 => [
//                    'tag_id' => $fasl ? $fasl->id : null
//                ]]);
//            }
//
//            $fasl = \Modules\Tag\Entities\Tag::where('name', 'like', '%'.$c['mezaj'].'%')->first();
//            if ($fasl && $c['mezaj'] != "") {
//                $model->attributes()->attach([ 29 => [
//                    'tag_id' => $fasl ? $fasl->id : null
//                ]]);
//            }
//
//
//            $model->attributes()->attach([ 37 => [
//                'value' => trim(str_replace('"', "", $c['how_to_use']))
//            ]]);
//
//            $model->attributes()->attach([ 38 => [
//                'value' => trim(str_replace('"', "", $c['health_benefits']))
//            ]]);
//
//            $model->attributes()->attach([ 36 => [
//                'value' => trim(str_replace('"', "", $c['warning']))
//            ]]);
//
//            $model->attributes()->attach([ 33 => [
//                'value' => trim(str_replace('"', "", str_replace("\\", "", $c['english_common_name'])))
//            ]]);
//
//
//            $model->attributes()->attach([ 32 => [
//                'value' => trim(str_replace('"', "", $c['scientific_name']))
//            ]]);
//
//            $model->attributes()->attach([ 34 => [
//                'value' => trim(str_replace('"', "", $c['other_names']))
//            ]]);
//
//            $model->attributes()->attach([ 35 => [
//                'value' => trim(str_replace('"', "", $c['family_name']))
//            ]]);
//
//
//            $model->categories()->detach();
//            $model->categories()->attach([923 => [
//                'is_main' => 1
//            ]]);
//
//            $model->priceParameters()->detach();
//            $model->priceParameters()->attach([1]);
//
//
////            if ($c['diseases']) {
////                $model->filters()->detach();
////                $categories_result = [];
////                foreach (explode('،', trim($c['diseases'])) as $item) {
////                    $x = \Modules\Product\Entities\Filter::where('title', 'like', '%'. trim($item) .'%')->first();
////
////                    if ($x) {
////
////                        $ancestors = \Modules\Product\Entities\Filter::ancestorsAndSelf($x->id);
////
////                        foreach ($ancestors as $key=>$ancestor) {
////
////                            $is_main = 0;
////
////                            if (count($ancestors) == $key + 1 && count($ancestors) > 1) {
////                                $is_main = 1;
////                            }
////
////                            $categories_result[$ancestor->id] = [
////                                'is_main' => $is_main,
////                                'filter_id' => $ancestor->id
////                            ];
////                        }
////
////
////                    }
////                }
////
////                $model->filters()->attach($categories_result);
////
////
////            }
//            } catch (Exception $exception) {
//                \Illuminate\Support\Facades\Log::info(json_encode(collect($item), JSON_UNESCAPED_UNICODE ));
//                \Illuminate\Support\Facades\Log::info($exception->getMessage());
//            }
//        }
//
//
//    dd($list);


    $routeCollection = \Route::getRoutes()->get();
    // Get all Routes
    foreach ($routeCollection as $key => $route) {

        $action = $route->getAction();

        if (is_array($action['middleware'])) {

            if (in_array('auth:api', $action['middleware'])) {


                // For Routes That Define In Controller
                if (isset($action['controller'])) {
                    // explode action and get single controller
                    $explode_path = explode('@', @$action['controller']); // expload controller, App\Http\Controllers\Backend\AnbarController@index

                    // check controller is valid
                    if (count($explode_path) == 2) {

                        // get action name
                        preg_match_all('/(?:^|[A-Z])[a-z]+/',$explode_path[1],$action_name); // create array that contain 0 index and more tha one key, getAttribute get Attribute or Get Attribute


                        // expload action and get last part , OrderController => 0, Order => 1
                        preg_match('/(.*)Controller/', last(explode("\\", $explode_path[0])), $matches);
                        preg_match_all('/(?:^|[A-Z])[a-z]+/',$matches[1],$controller_name); // create array that contain 0 index and more tha one key, ProductCategory product category or Product Category

                        // get module name
                        preg_match('/\w+\\\\(.*)\\\\Http/', $explode_path[0], $module_container);
                        preg_match_all('/(?:^|[A-Z])[a-z]+/',$module_container[1],$module_name);



                        // join array Product Category Or get Attribute
                        $final[mb_strtolower(join('_', $module_name[0]))][mb_strtolower(join('_', $module_name[0])) . '_' .mb_strtolower(join('_', $controller_name[0]))][] = [
                            'key' => mb_strtolower(join('_', $module_name[0])) . '_' .mb_strtolower(join('_', $controller_name[0])) . '_' . mb_strtolower(join('_', $action_name[0])),
                            'action' => trans('permissions.' . mb_strtolower(join(' ', $action_name[0]))),
                            'method' => $route->methods[0],
                            'url' => '/'.$route->uri,
                        ];
                    }
                }
            }

        }

    }


    foreach ($final as $key=>$value) {
        foreach ($value as $k=>$item) {
            foreach ($item as $i=>$f) {
                \Modules\User\Entities\Permission::updateOrCreate(['id' => $f['key']],[
                    'id' => $f['key'],
                    'url' => $f['url'],
                    'method' => $f['method'],
                    'title' => str_replace('_', ' ', $f['key']),
                    'parent' => $key
                ]);
            }

        }
    }

    return $final;
});
