<?php

namespace package\Infrastructure\Service;

use package\Application\Model\ValueObject\PageNumber;
use package\Application\Model\ValueObject\RowsPerPage;
use package\Domain\Model\ValueObject\SaveDirectory;
use package\Domain\Model\ValueObject\TaskBody;
use package\Domain\Model\ValueObject\TaskTitle;
use package\Domain\Service\TaskRepository;
use package\Domain\Model\Entity\Task;

final class TaskFileRepository implements TaskRepository
{
    public function __construct(SaveDirectory $saveDirectory)
    {
        $this->saveDirectory = $saveDirectory->path();
    }

    public function save(Task $task): void
    {
        $filename = $this->saveDirectory . "/{$task->title()->value()}.txt";
        file_put_contents($filename, $task->body()->value());
    }

    /**
     *
     * @param RowsPerPage|null $limit
     * @param PageNumber|null $pageNumber
     * @return Task[]
     */
    public function find(?RowsPerPage $limit, ?PageNumber $pageNumber): array
    {
        $tasks = [];
        $files = glob($this->saveDirectory . '/*.txt');
        $filesCount = count($files);

        $page = 1;
        if ($pageNumber !== null) {
            $page = $pageNumber->value();
        }
        $maxCount = $filesCount;
        if ($limit !== null) {
            $maxCount = $limit->value();
        }
        $offset = $maxCount * ($page - 1);

        for ($index = 0; $index < $maxCount; ++$index) {
            if (($index + $offset) >= $filesCount) {
                break;
            }
            $filename = $files[$index + $offset];
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

    public function count(): int
    {
        $count = 0;
        foreach (glob($this->saveDirectory . '/*.txt') as $filename) {
            $body = file_get_contents($filename);
            if ($body === false || strlen($body) === 0) {
                // 読めない or サイズ0のファイルは無視
                break;
            }
            $count++;
        }
        return $count;
    }

    public function exists(TaskTitle $title): bool
    {
        $filename = $this->saveDirectory . "/{$title->value()}.txt";
        return file_exists($filename);
    }

    private $saveDirectory;
}
