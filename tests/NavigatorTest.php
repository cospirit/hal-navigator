<?php

namespace Test\CoSpirit\HAL;

use CoSpirit\HAL\Navigator;
use CoSpirit\HAL\RelCollection;

class NavigatorTest extends \PHPUnit_Framework_TestCase
{
    protected static function getHalJson()
    {
        return json_decode(file_get_contents(__DIR__.'/hal.json'), true);
    }

    protected function createNavigator($content = null)
    {
        return new Navigator($content ?: self::getHalJson());
    }

    public function testMagicGetter()
    {
        $nav = $this->createNavigator();

        $this->assertEquals('John', $nav->firstname);
        $this->assertInstanceOf('CoSpirit\HAL\RelCollection', $nav->rels);
        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $nav->feature);
        $this->assertInstanceOf('CoSpirit\HAL\NavigatorCollection', $nav->bikes);
    }

    public function testWrongMagicGetter()
    {
        $nav = $this->createNavigator();

        $this->assertNull($nav->age);
    }

    public function testMagicIsser()
    {
        $nav = $this->createNavigator();

        $this->assertTrue(isset($nav->firstname));
        $this->assertTrue(isset($nav->age));
        $this->assertNull($nav->age);
    }

    public function testAll()
    {
        $nav = $this->createNavigator();

        $all = $nav->all();

        $this->assertEquals('John', $all['firstname']);
        $this->assertFalse(isset($all['_links']));
        $this->assertFalse(isset($all['_embedded']));
    }

    public function testGetRels()
    {
        $nav = $this->createNavigator();

        $this->assertInstanceOf('CoSpirit\HAL\RelCollection', $nav->rels);
        $this->assertInstanceOf('CoSpirit\HAL\RelCollection', $nav->getRels());
    }

    public function testIsEmbeddedExists()
    {
        $nav = $this->createNavigator();

        $this->assertTrue($nav->isEmbeddedExists('feature'));
        $this->assertFalse($nav->isEmbeddedExists('shirts'));
    }

    public function testGetEmbedded()
    {
        $nav = $this->createNavigator();

        // empty Navigator
        $emptyNav = $nav->getEmbedded('shirts');

        $this->assertInstanceOf('CoSpirit\HAL\NavigatorCollection', $emptyNav);
        $this->assertEmpty($emptyNav);

        // Simple embedded
        $feature = $nav->getEmbedded('feature');

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $feature);
        $this->assertNotNull($feature->rels->self);
        $this->assertTrue($feature->beard);
        $this->assertEquals(5, $feature->level);

        // Collection embedded
        $bikes = $nav->getEmbedded('bikes');

        $this->assertInstanceOf('CoSpirit\HAL\NavigatorCollection', $bikes);
        $this->assertCount(2, $bikes);

        $bike = $bikes['0'];

        $this->assertInstanceOf('CoSpirit\HAL\Navigator', $bike);
        $this->assertEquals('Fix cycles', $bike->brand);
        $this->assertInstanceOf('CoSpirit\HAL\RelCollection', $bike->rels);
    }
}
