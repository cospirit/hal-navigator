<?php

namespace Test\ArDev\HAL;

use ArDev\HAL\RelCollection;

class RelCollectionTest extends \PHPUnit_Framework_TestCase
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

    public function testMagicAccessor()
    {
        $rels = $this->createRelCollection();

        $this->assertEquals('http://', $rels->self);
        $this->assertEquals('http://', $rels->collectionBikes);
        $this->assertNull($rels->anotherLink);

        $this->setExpectedException('LogicException');
        $rels->badLink;
    }

    public function testGetHref()
    {
        $this->setExpectedException('LogicException');

        $rels = $this->createRelCollection();

        $this->assertEquals('http://', $rels->getHref('self'));
        $rels->getHref('bad-link');
    }
}
