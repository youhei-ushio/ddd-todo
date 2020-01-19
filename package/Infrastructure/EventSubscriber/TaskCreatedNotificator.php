<?php
declare(strict_types=1);

namespace package\Infrastructure\EventSubscriber;

use package\Domain\Model\Event\TaskCreated;
use GuzzleHttp\Client;

class TaskCreatedNotificator
{
    public function handle(TaskCreated $event): void
    {
        $config = $this->slackConfigs();
        if (empty($config)) {
            return;
        }

        $client = new Client();
        $response = $client->post('https://slack.com/api/chat.postMessage', [
            'headers' => [
                'Content-Type'  =>  'application/json; charset=UTF-8',
                'Authorization' =>  'Bearer ' . $config['token'],
            ],
            'json' => [
                'channel' => $config['channel'],
                "text" => "タスク [{$event->task()->title()}] が登録されました。"
            ],
        ]);
        echo $response->getBody();
    }

    private function slackConfigs(): array
    {
        $directory = __DIR__;
        $directory = dirname($directory);
        $directory = dirname($directory);
        $directory = dirname($directory);
        $config = file_get_contents("{$directory}/.slack");
        if ($config === false || $config === '') {
            return [];
        }
        $config = explode(',', $config);
        if (count($config) < 2) {
            return [];
        }
        return [
            'token' => $config[0],
            'channel' => $config[1],
        ];
    }
}
