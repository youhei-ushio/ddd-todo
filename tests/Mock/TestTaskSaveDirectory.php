<?php

namespace Tests\Mock;

use package\Domain\Model\ValueObject\SaveDirectory;

final class TestTaskSaveDirectory implements SaveDirectory
{
    public function path(): string
    {
        // このソースのあるディレクトリから2階層上がプロジェクトのベースディレクトリ
        $baseDirectory = __DIR__;
        $baseDirectory = dirname($baseDirectory);
        $baseDirectory = dirname($baseDirectory);
        // ベースディレクトリにある"test_tasks"ディレクトリにテスト用タスクを保存する
        return $baseDirectory . '/test_tasks';
    }
}
