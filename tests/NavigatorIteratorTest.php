<?php

namespace Test\CoSpirit\HAL;

use CoSpirit\HAL\Navigator;
use CoSpirit\HAL\NavigatorIterator;
use PHPUnit\Framework\TestCase;

class NavigatorIteratorTest extends TestCase
{
    public function testForeach()
    {
        $iterator = new NavigatorIterator([
            ['name' => 'john'],
            ['name' => 'jane'],
        ]);

        $count = 0;

        foreach ($iterator as $nav) {
            $this->assertInstanceOf(Navigator::class, $nav);
            $count++;
        }

        $this->assertEquals('jane', $nav->name);

        $this->assertEquals(2, $count);
    }
}
