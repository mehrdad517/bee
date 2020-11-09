<?php

namespace Modules\Ticket\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Ticket\Entities\Category;
use Modules\Ticket\Entities\Conversation;
use Modules\Ticket\Entities\Ticket;
use Modules\User\Entities\Role;

class TicketController extends Controller
{
    public function index(Request $request)
    {

        $entities = Ticket::with(['createdBy' => function($q) {
            $q->select('id','name');
        }, 'category' => function($q) {
            $q->select('id', 'title');
        }])->where( function ($q) use ($request) {

            // Filter If Request Contain Filter Input
            if ($request->has('filter')) {

                $filter = json_decode($request->get('filter'), true);

                if (isset($filter['id'])) {
                    $q->where('id', '=', $filter['id']);
                }

                if (isset($filter['created_by']) && $filter['created_by'] != -1 and  $filter['created_by'] != "") {
                    $q->where('created_by', '=', $filter['created_by']);
                }


                if (isset($filter['category_id']) &&  $filter['category_id'] != -1 and  $filter['category_id'] != "") {
                    $q->whereIn('category_id', Category::descendantsAndSelf($filter['category_id'])->pluck('id'));
                }

                if (isset($filter['status']) && $filter['status'] != -1) {
                    $q->where('status', $filter['status']);
                }

                if (isset($filter['from_date']) && isset($filter['to_date'])) {
                    $q->where('created_at', '>=', $filter['from_date']);
                    $q->where('created_at', '<=', $filter['to_date']);
                } elseif (isset($filter['from_date'])) {
                    $q->where('created_at', '>=', $filter['from_date']);
                } elseif (isset($filter['to_date'])) {
                    $q->where('created_at', '<=', $filter['to_date']);
                }
            }
        })->orderBy($request->get('sort_field') ?? 'id', $request->get('sort_type') ?? 'desc')
            ->paginate($request->get('limit') ?? 10);

        return response($entities);

    }


    public function store(Request $request)
    {

        $result = Ticket::create([
            'title' => $request->get('title'),
            'category_id' => $request->get('category_id'),
            'created_by' => $request->get('created_by')
        ]);

        if ($result) {
            return response()->json(['status' => true, 'msg' => 'با موفقیت انجام شد.']);
        }

        return response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }



    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     *
     * ticket_id
     */
    public function conversations($id)
    {
        $ticket = Ticket::with(['createdBy', 'conversations' => function($q) {
            $q->with(['createdBy']);
        }, 'category' => function($q) {
            $q->select('id', 'title')->with(['tags']);
        }])->find($id);


        $list = [
            'id' => $ticket->id,
            'title' => $ticket->title,
            'created_by' => [
                'id' => $ticket->createdBy->id,
                'name' => $ticket->createdBy->name,
                'mobile' => $ticket->createdBy->mobile,
            ],
            'created_at' => $ticket->created_at,
            'category' => $ticket->category,
            'conversations' => [],
        ];

        foreach ($ticket->conversations as $key => $conversation) {
            $list['conversations'][$key] = [
                'created_by' => [
                    'id' => $conversation->createdBy->id,
                    'name' => $conversation->createdBy->name,
                    'mobile' => $conversation->createdBy->mobile,
                ],
                'id' => $conversation->id,
                'content' => $conversation->content,
                'is_customer' => $ticket->created_by == $conversation->created_by ? true: false,
                'created_at' => $conversation->created_at,
            ];

        }

        return response($list);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeConversations($id, Request $request)
    {

        $ticket = Ticket::find($id);

        $result = $ticket->conversations()->create([
            'created_by' => Auth::id(),
            'content' => $request->get('content'),
        ]);

        if ($request->has('file') && $request->get('file') != "") {

            $old = 'attachment/' . $request->get('file');

            $new = 'ticket/' . $result->id . '/' . $request->get('file');

            $move = Storage::move($old, $new); // Move Main Image

            if ($move) {

                $result->files()->create([
                    'created_by' => Auth::id(),
                    'file' => $request->get('file'),
                    'collection' => 0,
                    'directory' => 'ticket',
                ]);
            }
        }



        if ($result) {
            return response()->json(['status' => true, 'msg' => 'با موفقیت انجام شد.']);
        }

        return response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);
    }

    /**
     * @param $ticket_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteConversation($ticket_id, $id)
    {
        $status = false;

        $cnv = Conversation::find($id);

        if ( ! in_array(Auth::user()->role_id, Role::where('crud', 1)->pluck('id')->toArray())) {
            if (Auth::id() != $cnv->created_by) {
                return response()->json(['status' => false, 'msg' => 'دسترسی لازم برای حذف این سند را ندارید.']);
            }
        }

        if ($cnv->ticket_id == $ticket_id) {

//            $directory_delete_status = Storage::deleteDirectory("ticket/$cnv->id");
//
//            if ($directory_delete_status) {
//                $cnv->files()->delete();
//            }



            $status = $cnv->delete();
        }

        if ($status) {
            return response()->json(['status' => true, 'msg' => 'با موفقیت انجام شد.']);
        }

        return response()->json(['status' => false, 'msg' => 'خطایی رخ داده است.']);

    }




    public function update($ticket_id, Request $request)
    {

        $res = Ticket::find($ticket_id);

        if ($request->has('category_id')) {

            $res->update([
                'category_id' => $request->get('category_id')
            ]);
        } elseif ($request->has('status')) {
            $res->update([
                'status' => $request->get('status')
            ]);
        }

        if ($res) {
            return response()->json(['status' => true, 'msg' => 'عملیات موفقیا آمیز']);
        }

        return response()->json(['status' => true, 'msg' => 'عملیات موفقیا آمیز']);
    }
}
