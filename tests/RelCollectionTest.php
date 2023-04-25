<?php

namespace Test\CoSpirit\HAL;

use CoSpirit\HAL\RelCollection;
use LogicException;
use PHPUnit\Framework\TestCase;

class RelCollectionTest extends TestCase
{
    protected function createRelCollection()
    {
        return new RelCollection([
            'self' => ['href' => 'http://'],
            'collection-bikes' => ['href' => 'http://'],
            'bad-link' => 'http://'
        ]);
    }

    public function testAll()
    {
        $rels = $this->createRelCollection();

        $this->assertCount(3, $rels->all());
    }

    public function testMagicGetter()
    {
        $rels = $this->createRelCollection();

        $this->assertEquals('http://', $rels->self);
        $this->assertEquals('http://', $rels->collectionBikes);
        $this->assertNull($rels->anotherLink);

        $this->expectException(LogicException::class);
        $rels->badLink;
    }

    public function testMagicIsser()
    {
        $rels = $this->createRelCollection();

        $this->assertTrue(isset($rels->self));
        $this->assertTrue(isset($rels->anotherLink));
        $this->assertNull($rels->anotherLink);
    }

    public function testGetHref()
    {
        $this->expectException(LogicException::class);

        $rels = $this->createRelCollection();

        $this->assertEquals('http://', $rels->getHref('self'));
        $rels->getHref('bad-link');
    }
}
