<?php

namespace App\Jobs\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Entities\OrderQueueJob;

class Fire implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    private $orderQueueJob;

    /**
     * Fire constructor.
     * @param OrderQueueJob $orderQueueJob
     */
    public function __construct(OrderQueueJob $orderQueueJob)
    {
        $this->orderQueueJob = $orderQueueJob;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
