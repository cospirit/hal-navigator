<?php

namespace Test\CoSpirit\HAL;

use CoSpirit\HAL\NavigatorIterator;

class NavigatorIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testForeach()
    {
        $iterator = new NavigatorIterator([
            ['name' => 'john'],
            ['name' => 'jane'],
        ]);

        $count = 0;

        foreach ($iterator as $nav) {
            $this->assertInstanceOf('CoSpirit\HAL\Navigator', $nav);
            $count++;
        }

        $this->assertEquals('jane', $nav->name);

        $this->assertEquals(2, $count);
    }
}
