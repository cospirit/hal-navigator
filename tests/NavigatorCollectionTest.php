<?php

namespace Test\CoSpirit\HAL;

use CoSpirit\HAL\NavigatorCollection;

class NavigatorCollectionTest extends \PHPUnit_Framework_TestCase
{
    protected function createNavigatorCollection()
    {
        return new NavigatorCollection([
            ['name' => 'john'],
            ['name' => 'jessie'],
            ['name' => 'jane']
        ]);
    }

    public function testFirst()
    {
        $collection = $this->createNavigatorCollection();

        $nav = $collection->first();

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $nav);
        $this->assertEquals('john', $nav->name);
    }

    public function testLast()
    {
        $collection = $this->createNavigatorCollection();

        $nav = $collection->last();

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $nav);
        $this->assertEquals('jane', $nav->name);
    }

    public function testNext()
    {
        $collection = $this->createNavigatorCollection();

        $nav = $collection->next();

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $nav);
        $this->assertEquals('jessie', $nav->name);

        $nav = $collection->next();

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $nav);
        $this->assertEquals('jane', $nav->name);
    }

    public function testCurrent()
    {
        $collection = $this->createNavigatorCollection();

        $second = $collection->next();

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $second);
        $this->assertEquals('jessie', $second->name);

        $current = $collection->current();

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $current);
        $this->assertEquals('jessie', $current->name);
    }

    public function testGetIterator()
    {
        $collection = $this->createNavigatorCollection();

        $this->assertInstanceOf('CoSpirit\HAL\NavigatorIterator', $collection->getIterator());
    }

    public function testArrayAccess()
    {
        $collection = $this->createNavigatorCollection();

        $this->assertEquals('john', $collection[0]->name);
        $this->assertEquals('jane', $collection[2]->name);
    }
}
