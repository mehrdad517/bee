<?php

namespace App\Jobs\Notification;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Entities\Notification;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Queue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $user;

    private $text;

    private $type;

    private $params;

    /**
     * Queue constructor.
     * @param User $user
     * @param null $text
     * @param string $type
     * @param $params
     */
    public function __construct(User $user, $text = null, $params = [], $type = 'sms')
    {
        $this->user = $user;
        $this->type = $type;
        $this->text = $text;
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $model = Notification::create([
                'to' => ($this->type == 'sms') ? $this->user->mobile : $this->user->email,
                'text' => $this->text,
                'type' => $this->type,
            ]);

            if ($model) {
                dispatch(new Fire($model, $this->params))->onQueue('notification');
            }

        } catch (\Exception $exception) {

            $logger = new Logger('notification');
            $logger->pushHandler(new StreamHandler(storage_path().'/logs/notification.log'));
            $logger->error('notification error in file queue.php with message: '. $exception->getMessage());
        }


    }
}

