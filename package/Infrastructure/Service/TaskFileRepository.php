<?php

namespace package\Infrastructure\Service;

use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Domain\Service\TaskRepository;
use package\Domain\Model\Entity\Task;

final class TaskFileRepository implements TaskRepository
{
    public function save(Task $task): void
    {
        $filename = self::saveDirectory() . '/' . $task->title()->value() . '.txt';
        file_put_contents($filename, $task->body()->value());
    }

    /**
     * @return Task[]
     */
    public function find(): array
    {
        $tasks = [];
        foreach (glob(self::saveDirectory() . '/*.txt') as $filename) {
            $body = file_get_contents($filename);
            if ($body === false || strlen($body) === 0) {
                // 読めない or サイズ0のファイルは無視
                break;
            }
            $title = basename($filename, '.txt');
            $tasks[] = new Task(new TaskTitle($title), new TaskBody($body));
        }
        return $tasks;
    }

    private static function saveDirectory(): string
    {
        // このソースのあるディレクトリから3階層上がプロジェクトのベースディレクトリ
        $baseDirectory = __DIR__;
        $baseDirectory = dirname($baseDirectory);
        $baseDirectory = dirname($baseDirectory);
        $baseDirectory = dirname($baseDirectory);
        // ベースディレクトリにある"tasks"ディレクトリにタスクを保存する
        return $baseDirectory . '/tasks';
    }

    public function exists(TaskTitle $title): bool
    {
        $filename = self::saveDirectory() . '/' . $title->value() . '.txt';
        return file_exists($filename);
    }
}
