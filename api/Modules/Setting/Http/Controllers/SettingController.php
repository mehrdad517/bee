<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Domain;

class SettingController extends Controller
{

    public function read(Request $request)
    {

        $setting = Domain::where(['status' => 1])->first();

        if ($setting) {

            if(!$setting->status) {
                return Response(['status' => false, 'msg' => 'دامنه غیرفعال است']);
            }

            $result = [
                'name' => $setting->name,
                'meta_title' => $setting->meta_title,
                'meta_description' => $setting->meta_description,
                'introduce' => $setting->introduce,
                'free_postage' => $setting->free_postage,
                'min_purchase' => $setting->min_purchase,
                'default_post_cost' => $setting->default_post_cost,
                'copy_right' => $setting->copy_right,
                'blog_title' => $setting->blog_title,
                'blog_description' => $setting->blog_description,
                'shop_title' => $setting->shop_title,
                'shop_description' => $setting->shop_description,

            ];


            return Response()->json(['status' => true, 'domain' => $result]);
        }

        return Response(['status' => false, 'msg' => 'دامنه موجود نیست']);
    }

    public function update(Request $request)
    {

        $validator = \Validator::make($request->all(),[
            'name' => 'required',
            'meta_title' => 'required|max:60',
            'meta_description' => 'required|max:255',
            'introduce' => 'required',
            'copy_right' => 'required',
            'blog_title' => 'required',
            'blog_description' => 'required',
            'shop_title' => 'required',
            'shop_description' => 'required',
        ]);

        if ($validator->fails()) {

            return Response()->json(['status' => false, 'msg' => $validator->errors()->first()]);
        }

        $setting = Domain::where(['status' => 1])->first();

        $setting->update([
            'name' => $request->get('name'),
            'meta_title' => $request->get('meta_title'),
            'meta_description' => $request->get('meta_description'),
            'introduce' => $request->get('introduce'),
            'copy_right' => $request->get('copy_right'),
            'blog_title' => $request->get('blog_title'),
            'blog_description' => $request->get('blog_description'),
            'shop_title' => $request->get('shop_title'),
            'shop_description' => $request->get('shop_description'),
            'free_postage' => $request->get('free_postage'),
            'min_purchase' => $request->get('min_purchase'),
            'default_post_cost' => $request->get('default_post_cost'),
        ]);




        if ($setting) {
            return response()->json(['status' => true, 'msg' => 'تغییرات با موفقیت اعمال شد']);
        }

        return response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);


    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     *
     *
     * read domain links
     *
     */
    public function readDomainLinks(Request $request)
    {
        $setting = Domain::where(['status' => 1])->first();

        if ($setting) {

            if(!$setting->status) {
                return Response(['status' => false, 'msg' => 'دامنه غیرفعال است']);
            }

            $result = [
                'app' => [],
                'license' => [],
                'social_media' => [],
                'communication_channels' => []

            ];


            foreach ($setting->app as $app) {
                $result['app'][] = ['app_id' => $app->pivot->link_id, 'value' => $app->pivot->value];
            }

            foreach ($setting->license as $license) {
                $result['license'][] = ['license_id' => $license->pivot->link_id, 'value' => $license->pivot->value];
            }


            foreach ($setting->socialMedia as $social_media) {
                $result['social_media'][] = ['social_media_id' => $social_media->pivot->link_id, 'value' => $social_media->pivot->value];
            }

            foreach ($setting->communicationChannels as $communication_channel) {
                $result['communication_channels'][] = ['communication_channel_id' => $communication_channel->pivot->link_id, 'value' => $communication_channel->pivot->value];
            }

            return Response()->json($result);
        }

        return Response(['status' => false, 'msg' => 'دامنه موجود نیست']);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDomainLinks(Request $request) {


        $setting = Domain::where(['status' => 1])->first();


        try {

            $setting->socialMedia()->detach();
            $setting->communicationChannels()->detach();
            $setting->app()->detach();
            $setting->license()->detach();


            if ($request->has('app')) {
                foreach ($request->get('app') as $item) {

                    if ($item['app_id'] > 0 and $item['value'] != '') {

                        $setting->app()->attach([
                            $item['app_id'] => ['value' => $item['value']],
                        ]);
                    }
                }
            }

            if ($request->has('license')) {

                foreach ($request->get('license') as $item) {

                    if ($item['license_id'] > 0 and $item['value'] != '') {

                        $setting->license()->attach([
                            $item['license_id'] => ['value' => $item['value']],
                        ]);
                    }
                }
            }


            if ($request->has('social_media')) {

                foreach ($request->get('social_media') as $item) {

                    if ($item['social_media_id'] > 0 and $item['value'] != '') {

                        $setting->socialMedia()->attach([
                            $item['social_media_id'] => ['value' => $item['value']],
                        ]);
                    }
                }
            }

            if ($request->has('communication_channel')) {

                foreach ($request->get('communication_channel') as $item) {

                    if ($item['communication_channel_id'] > 0 and $item['value'] != '') {

                        $setting->communicationChannels()->attach([
                            $item['communication_channel_id'] => ['value' => $item['value']]
                        ]);
                    }
                }
            }


            if ($setting) {
                return response()->json(['status' => true, 'msg' => 'تغییرات با موفقیت اعمال شد']);
            }

        }catch (\Exception $exception) {

            return response()->json(['status' => false, 'msg' => $exception->getMessage()]);

        }
    }


    // read boolean data for update redux
    public function readSticky(Request $request)
    {
        $setting = Domain::with(['sticky'])->where(['status' => 1])->first();

        $response = [
            ['title' => 'به روز رسانی', 'name' => 'maintenance_mode', 'value' => isset($setting->sticky->maintenance_mode) ? $setting->sticky->maintenance_mode :  0],
            ['title' => 'ثبت نام', 'name' => 'register', 'value' => isset($setting->sticky->register) ? $setting->sticky->register :  0],
            ['title' => 'پنل کاربر', 'name' => 'user_dashboard', 'value' => isset($setting->sticky->user_dashboard) ? $setting->sticky->user_dashboard :  0],
            ['title' => 'پنل ادمین', 'name' => 'admin_panel', 'value' => isset($setting->sticky->admin_panel) ? $setting->sticky->admin_panel :  0],
            ['title' => 'اپ اندروید', 'name' => 'android', 'value' => isset($setting->sticky->android) ? $setting->sticky->android :  0],
            ['title' => 'اپ IOS', 'name' => 'ios', 'value' => isset($setting->sticky->ios) ? $setting->sticky->ios :  0],
            ['title' => 'نوتیفیکیش خرید', 'name' => 'notify_order', 'value' => isset($setting->sticky->notify_order) ? $setting->sticky->notify_order :  0],
            ['title' => 'نوتیفیکیشن تیکت', 'name' => 'notify_ticket', 'value' => isset($setting->sticky->notify_ticket) ? $setting->sticky->notify_ticket :  0],
            ['title' => 'نوتیفیکیشن ثبت نام', 'name' => 'notify_register', 'value' => isset($setting->sticky->notify_register) ? $setting->sticky->notify_register :  0],
            ['title' => 'سبدخرید', 'name' => 'basket', 'value' => isset($setting->sticky->basket) ? $setting->sticky->basket :  0],
            ['title' => 'پیام ها', 'name' => 'chat', 'value' => isset($setting->sticky->chat) ? $setting->sticky->chat :  0],

        ];

        return response($response);
    }

    public function updateSticky(Request $request)
    {


        $setting = Domain::where(['status' => 1])->first();

        if ($setting) {

            if ($setting->sticky) {

                $setting = $setting->sticky()->update($request->all());
            } else {
                $setting = $setting->sticky()->create($request->all());
            }


            if ($setting) {
                return response()->json(['status' => true, 'msg' => 'تغییرات با موفقیت اعمال شد']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'دامنه نامعتبر است.']);
        }


        return response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);


    }
}
