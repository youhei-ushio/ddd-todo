<?php
declare(strict_types=1);

namespace package\Infrastructure\EventSubscriber;

use Exception;
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

        $this->writeErrorLog('slack連携設定:' . var_export($config, true));

        try {
            $client = new Client();
            $response = $client->post('https://slack.com/api/chat.postMessage', [
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF-8',
                    'Authorization' => 'Bearer ' . $config['token'],
                ],
                'json' => [
                    'channel' => $config['channel'],
                    "text" => "タスク [{$event->task()->title()->value()}] が登録されました。"
                ],
            ]);
            $this->writeErrorLog('slack連携結果:' . $response->getBody());
        } catch (Exception $exception) {
            // ログの仕組みがまだ無いのでとりあえず何もしない
            $this->writeErrorLog('slack連携エラー:' . $exception->getMessage());
        }
    }

    private function slackConfigs(): array
    {
        $directory = __DIR__;
        $directory = dirname($directory);
        $directory = dirname($directory);
        $directory = dirname($directory);
        $config = file_get_contents("{$directory}/.slack");
        if ($config === false || $config === '') {
            $this->writeErrorLog('設定エラー:' . $config);
            return [];
        }
        $config = explode(',', trim($config));
        if (count($config) < 2) {
            $this->writeErrorLog('設定エラー:' . $config);
            return [];
        }
        return [
            'token' => $config[0],
            'channel' => $config[1],
        ];
    }

    private function writeErrorLog(string $message)
    {
        $directory = __DIR__;
        $directory = dirname($directory);
        $directory = dirname($directory);
        $directory = dirname($directory);
        file_put_contents("{$directory}/log.txt", $message . PHP_EOL, FILE_APPEND);
    }
}
