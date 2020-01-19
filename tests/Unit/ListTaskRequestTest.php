<?php
declare(strict_types=1);

namespace Tests\Unit;

use package\Application\Service\ListTasksRequest;
use PHPUnit\Framework\TestCase;

final class ListTaskRequestTest extends TestCase
{
    // 有効な値
    public function testValidValue()
    {
        $request = new ListTasksRequest(10, 1);
        $this->assertEquals(10, $request->limit()->value());
        $this->assertEquals(1, $request->page()->value());
    }

    // 無効な件数がデフォルト値になること
    public function testInvalidLimit()
    {
        $request = new ListTasksRequest(-100, 1);
        $this->assertEquals(10, $request->limit()->value());
    }

    // 無効なページがデフォルト値になること
    public function testInvalidPage()
    {
        $request = new ListTasksRequest(10, -100);
        $this->assertEquals(1, $request->page()->value());
    }
}
