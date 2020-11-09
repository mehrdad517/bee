<?php

use Illuminate\Http\Request;

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


Route::prefix('backend')->group(function () {

    Route::prefix('fileManager')->middleware('auth:api')->group(function () {

        Route::group(['prefix' => 'attachment'], function () {

            Route::post('/', function (Request $request) { // Get Form Data

                list($baseType, $image) = explode(';', $request->get('file'));
                $file_type = last(explode(':', $baseType));
                list(, $image) = explode(',', $image);
                $image = base64_decode($image);
                $imageName = uniqid() . '.' . last(explode('/', $file_type));

                $path = ($request->get('directory') ?? 'attachment') . '/' . $imageName;


                // Check File Mime Type
//            if (in_array($file_type, ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'])) {
//                // Image Size Larger Than 1MB
//                if ($file->getSize() / 1024 > 1024) {
//                    return response()->json(['status' => false, 'msg' => 'حداکثر حجم فایل 1 مگابایت است']);
//                }
//                //Video Check Mime Type
//            }
//            elseif (in_array($file_type, ['video/mp4', 'video/ogv', 'video/webm', 'video/3gpp'])) {
//                if ($file->getSize() / 1024 > 8388608) {
//                    return response()->json(['status' => false, 'msg' => 'حداکثر حجم فایل 8 مگابایت است']);
//                }
//            }
//            elseif (in_array($file_type, ['application/zip', 'application/x-rar'])) {
//                if ($file->getSize() > 8388608) {
//                    return response()->json(['status' => false, 'msg' => 'حداکثر حجم فایل 8 مگابایت است']);
//                }
//            }
//            elseif (in_array($file_type, ['application/pdf'])) {
//                if ($file->getSize() > 8388608) {
//                    return response()->json(['status' => false, 'msg' => 'حداکثر حجم فایل 8 مگابایت است']);
//                }
//            }
//
//            else { // Other Format is InValid
//                return response()->json(['status' => false, 'msg' => 'فرمت غیر مجاز است.']);
//
//            }


                //With Storage Laravel File System Save File In Attachment Directory
                $status = \Illuminate\Support\Facades\Storage::disk('public')->put($path, $image, 'public');

                if ($status) {
                    // Water Mark
                    if ($request->has('water_mark')) {
                        if ($request->get('water_mark')) {
                            if (in_array($file_type, ['image/gif', 'image/png', 'image/jpg', 'image/jpeg'])) {
                                \Intervention\Image\Facades\Image::make(storage_path('app/public/attachment/' . $imageName))->insert(public_path('logo.png'), 'bottom-right', 15, 15)->save();
                            }
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'msg' => 'ok',
                        'path' => env('APP_URL') . \Illuminate\Support\Facades\Storage::url($path) ,
                        'file' => last(explode('/', $imageName))
                    ]);
                }

            });



            Route::delete('/', function (Request $request) {

                $status = false;
                // File with Address or Http etc ...
                $file = last(explode('/', $request->get('file')));

                $db_file = \Modules\FileManager\Entities\File::where('file', $file)->first();

                if ($db_file) {

                    if ($db_file->created_by != \Illuminate\Support\Facades\Auth::id()) {
                        return response()->json(['status' => false, 'msg' => 'شما نمیتوانید این فایل را حذف کنید.'], 401);
                    }

                    if ($db_file->size) {
                        foreach (json_decode($db_file->size, true) as $size) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($db_file->directory . '/' . $db_file->fileable_id . '/' . $size .  '/' . $file);
                        }
                    }

                    $status = \Illuminate\Support\Facades\Storage::disk('public')->delete($db_file->directory . '/' . $db_file->fileable_id . '/' . $file);
                    if ($status) {
                        $db_file->delete();
                    }

                } else {
                    // Directory Find
                    $status = \Illuminate\Support\Facades\Storage::disk('public')->delete($request->get('directory') . '/' . $file);
                }

                // Status Delete File True Or False
                return response()->json(['status' => $status]);
            });

        });



        Route::group(['prefix' => 'files'], function () {


            Route::get('/{model}/{id}', function ($model, $id, Request $request) {

                switch ($model) {
                    case 'product':
                        $entity = \Modules\Product\Entities\Product::find($id);
                        break;
                    case 'content':
                        $entity = \Modules\Blog\Entities\Content::find($id);
                        break;
                    case 'brand':
                        $entity = \Modules\Product\Entities\Brand::find($id);
                        break;
                    case 'gallery':
                        $entity = \Modules\Gallery\Entities\Gallery::find($id);
                        break;
                }


                $files = [];


                foreach ($entity->files()->orderBy('order', 'asc')->get() as $file) {
                    $files[] = [
                        'percent' => 100, // for react component
                        'file' => $file['file'],
                        'mime_type' => $file['mime_type'],
                        'path' => env('APP_URL') . \Illuminate\Support\Facades\Storage::url('/' . $file['directory'] . '/' . $entity->id . '/' . $file['file']), // image or file address
                        'collection' => $file['collection'],
                        'directory' => 'product',
                        'link' => $file['link'] ?? '',
                        'caption' => $file['caption'] ?? '',
                        'order' => $file['order'],
                    ];
                }


                return response($files);

            });

            Route::post('/{model}/{id}', function ($model, $id, Request $request) {

                switch ($model) {
                    case 'product':
                        $entity = \Modules\Product\Entities\Product::find($id);
                        $dir = $model;
                        $sizes = [500,400,300,200,100,50];
                        $set_img = true;
                        break;
                    case 'content':
                        $entity = \Modules\Blog\Entities\Content::find($id);
                        $dir = 'content';
                        $sizes = [500,400,300,200,100,50];
                        $set_img = true;
                        break;
                    case 'brand':
                        $entity = \Modules\Product\Entities\Brand::find($id);
                        $dir = 'brand';
                        $sizes = [500,400,300,200,100,50];
                        $set_img = true;
                        break;
                    case 'gallery':
                        $entity = \Modules\Gallery\Entities\Gallery::find($id);
                        $dir = 'gallery';
                        $sizes = [500,400,300];
                        $set_img = false;
                        break;
                }


                if ($request->has('files')) {

                    $result = \Modules\FileManager\Entities\File::moveFileFromAttachment($entity, $request->get('files'), $dir, $sizes, $set_img);

                    if ($result['status']) {
                        return response(['status' => true, 'msg' => 'عملیات موفقیت آمیز']);
                    }

                    return response(['status' => false, 'msg' => 'خطایی رخ داده است']);

                } else {
                    return response(['status' => false, 'msg' => 'حداقل یک فایل آپلود کنید']);
                }

            });
        });

    });
});
