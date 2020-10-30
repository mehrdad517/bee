<?php

namespace App\Jobs\Order;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderQueueJob;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Queue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $order;

    /**
     * OrderQueueJob constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        /**
         * fetch hierarchy from join as order to user and marketer
         */
        $hierarchy = DB::table('marketer')
            ->where('user_id', $this->order->user_id)
            ->value('hierarchy');

        // explode list of self and parent
        $marketer_queue = array_reverse(explode('/', trim($hierarchy, '/')));

        /**
         * add to queue each marketer
         */
        foreach ($marketer_queue as $key=>$queue) {

            try {
                // set period_id with trigger

                $model = OrderQueueJob::create([
                    'user_id' => $queue,
                    'order_id' => $this->order->id,
                    'level' => $key,
                    'buyer' => $key == 0 ? 1 : 0,
                ]);

                dispatch(new Fire($model))->onQueue($key == 0 ? 'OrderQueueJobBuyer' : 'OrderQueueJobAncestors');

            } catch (\Exception $exception) {

                $logger = new Logger('orderQueuedJob');
                $logger->pushHandler(new StreamHandler(storage_path().'/logs/orderQueuedJob.log'));
                $logger->error($exception->getMessage() . ': with orderId ' . $this->order->id);

                /**
                 * Warning
                 * If you put the queue here again, a loop will be created
                 *
                 * add notification alert sms, email
                 */

                dispatch(new \App\Jobs\Notification\Queue(User::where('role_id', 'programmer')->first(), null, ['token' => $this->order->id, 'template' => 'OrderQueueJobFailed']));


            }

        }

    }
}
