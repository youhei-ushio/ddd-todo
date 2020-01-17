<?php
declare(strict_types=1);

namespace package\Domain\Model\ValueObject;

final class TaskSaveDirectory implements SaveDirectory
{
    public function path(): string
    {
        // このソースのあるディレクトリから4階層上がプロジェクトのベースディレクトリ
        $baseDirectory = __DIR__;
        $baseDirectory = dirname($baseDirectory);
        $baseDirectory = dirname($baseDirectory);
        $baseDirectory = dirname($baseDirectory);
        $baseDirectory = dirname($baseDirectory);
        // ベースディレクトリにある"tasks"ディレクトリにタスクを保存する
        return $baseDirectory . '/tasks';
    }
}
