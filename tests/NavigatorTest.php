<?php

namespace Test\CoSpirit\HAL;

use CoSpirit\HAL\Navigator;
use CoSpirit\HAL\NavigatorCollection;
use CoSpirit\HAL\RelCollection;
use PHPUnit\Framework\TestCase;

class NavigatorTest extends TestCase
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
        $this->assertInstanceOf(RelCollection::class, $nav->rels);
        $this->assertInstanceOf(Navigator::class, $nav->feature);
        $this->assertInstanceOf(NavigatorCollection::class, $nav->bikes);
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

        $this->assertInstanceOf(RelCollection::class, $nav->rels);
        $this->assertInstanceOf(RelCollection::class, $nav->getRels());
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

        $this->assertInstanceOf(NavigatorCollection::class, $emptyNav);
        $this->assertEmpty($emptyNav);

        // Simple embedded
        $feature = $nav->getEmbedded('feature');

        $this->assertInstanceOf(Navigator::class, $feature);
        $this->assertNotNull($feature->rels->self);
        $this->assertTrue($feature->beard);
        $this->assertEquals(5, $feature->level);

        // Collection embedded
        $bikes = $nav->getEmbedded('bikes');

        $this->assertInstanceOf(NavigatorCollection::class, $bikes);
        $this->assertCount(2, $bikes);

        $bike = $bikes['0'];

        $this->assertInstanceOf(Navigator::class, $bike);
        $this->assertEquals('Fix cycles', $bike->brand);
        $this->assertInstanceOf(RelCollection::class, $bike->rels);
    }
}
