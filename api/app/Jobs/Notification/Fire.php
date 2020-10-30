<?php

namespace App\Jobs\Notification;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Entities\Notification;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Fire implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $notification;

    private $params;

    /**
     * Fire constructor.
     * @param Notification $notification
     * @param array $params
     */
    public function __construct(Notification $notification, $params = [])
    {
        $this->notification = $notification;
        $this->params = $params;
    }


    /**
     *
     */
    public function handle()
    {
        switch ($this->notification->type) {
            case 'sms':
                // send sms with kavenegar
                try {

                    $client = new Client();
                    $response = $client->get("https://api.kavenegar.com/v1/".env('KAVENEGAR_API_KEY')."/verify/lookup.json", [
                        "query" => [
                            "receptor" => $this->notification->to,
                            "template" => $this->params['template'],
                            "token" => $this->params['token'],
                            "token2" => isset($this->params['token2']) ? $this->params['token2'] : "",
                            "token3" => isset($this->params['token3']) ? $this->params['token3'] : "",
                        ]
                    ]);
                    if ($response->getStatusCode() == 200) {

                        $body = json_decode($response->getBody()->getContents());

                        if ($body->return->status == 200) {

                            $this->notification->update([
                                'status' => 'done',
                                'text' => $body->entries[0]->message
                            ]);

                        } else {
                            $this->notification->update([
                                'status' => 'failed',
                                'err' => $body->return->message
                            ]);
                        }
                    } else {
                        $this->notification->update([
                            'status' => 'failed',
                            'err' => "client status code is :" . $response->getStatusCode()
                        ]);
                    }
                } catch (\Exception $exception) {

                    $logger = new Logger('notification');
                    $logger->pushHandler(new StreamHandler(storage_path().'/logs/notification.log'));
                    $logger->error('notificationId error' . $this->notification->id . ' with message ' . $exception->getMessage());

                    $this->notification->update([
                        'status' => 'failed',
                        'err' => $exception->getMessage()
                    ]);
                }
                break;
            case 'email':
                try {
                    // send email with mail info
                    $this->notification->update([
                        'status' => 'failed',
                        'err' => 'سرویس ارسال ایمیل غیرفعال است.'
                    ]);

                } catch (\Exception $exception) {

                    $logger = new Logger('notification');
                    $logger->pushHandler(new StreamHandler(storage_path().'/logs/notification.log'));
                    $logger->error('notificationId error' . $this->notification->id . ' with message ' . $exception->getMessage());

                    $this->notification->update([
                        'status' => 'failed',
                        'err' => $exception->getMessage()
                    ]);
                }

                break;
            default:
                $logger = new Logger('notification');
                $logger->pushHandler(new StreamHandler(storage_path().'/logs/notification.log'));
                $logger->error('notificationId missing type error:' . $this->notification->id);

                $this->notification->update([
                    'status' => 'failed',
                    'err' => "notificationId missing type"
                ]);

                break;

        }
    }
}
