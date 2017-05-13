<?php

use nstdio\svg\transform\SVGMatrixImpl;
use nstdio\svg\transform\SVGTransform;
use nstdio\svg\transform\SVGTransformImpl;
use nstdio\svg\transform\SVGTransformList;
use nstdio\svg\transform\SVGTransformListImpl;

/**
 * Class SVGTransformListTest
 *
 * @author Edgar Asatryan <nstdio@gmail.com>
 */
class SVGTransformListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SVGTransformList
     */
    private $transformList;


    public function setUp()
    {
        $this->transformList = new SVGTransformListImpl();
        self::assertEmpty($this->transformList);
    }

    public function tearDown()
    {
        $this->transformList->clear();
        self::assertEmpty($this->transformList);
    }

    public function testClear()
    {
        $mock = self::getMock('\nstdio\svg\transform\SVGTransform');

        $this->transformList->appendItem($mock);

        self::assertNotEmpty($this->transformList);

        $this->transformList->clear();

        self::assertEmpty($this->transformList);
    }

    public function testAppendItem()
    {
        $transformList = self::createList();
        $transform = self::createTransform();

        $transformList->appendItem($transform);

        self::assertSame($transform, $this->transformList->appendItem($transform));
        self::assertEmpty($transformList);
        self::assertNotEmpty($this->transformList);
    }

    private static function createList()
    {
        return new SVGTransformListImpl();
    }

    /**
     * @return SVGTransform
     */
    private static function createTransform()
    {
        return new SVGTransformImpl();
    }

    /**
     * @depends testAppendItem
     */
    public function testNumberOfItems()
    {
        self::assertEquals(0, $this->transformList->numberOfItems);

        $count = 50;
        $this->append($count);

        self::assertEquals($count, $this->transformList->numberOfItems);
        self::assertCount($count, $this->transformList);
    }

    private function append($count)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->transformList->appendItem(self::createTransform());
        }
    }

    public function testMagicGetter()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        self::assertNull($this->transformList->a);
        /** @noinspection PhpUndefinedFieldInspection */
        self::assertNull($this->transformList->numberofitems);
    }

    public function testInsetItemBefore_Begin()
    {
        $list = self::createList();
        $newItem = self::createTransform();

        $list->appendItem($newItem);

        self::assertSame($newItem, $this->transformList->insertItemBefore($newItem, 0));
        self::assertEmpty($list);
        self::assertNotEmpty($this->transformList);
        self::assertSame($newItem, $this->transformList->getItem(0));
    }

    public function testInsetItemBefore_SameItem()
    {
        $item1 = self::createTransform();

        $this->transformList->insertItemBefore($item1, 0);
        $this->transformList->insertItemBefore($item1, 1);

        self::assertSame($item1, $this->transformList->getItem(0));
        self::assertEquals(1, $this->transformList->numberOfItems);
    }

    public function testInsetItemBefore_Other()
    {
        $count = 1051;
        for ($i = 0; $i < $count; $i++) {
            $item = self::createTransform();
            self::assertSame($item, $this->transformList->insertItemBefore($item, $i + 1));
            self::assertSame($item, $this->transformList->getItem($i));
        }

        self::assertEquals($count, $this->transformList->numberOfItems);

        $item = self::createTransform();
        $this->transformList->insertItemBefore($item, $count / 2);

        self::assertSame($item, $this->transformList->getItem(($count / 2) - 1));

        self::assertEquals($count + 1, $this->transformList->numberOfItems);
    }

    public function testInsertItemBefore_End()
    {
        $count = 10;
        $this->append($count);

        $item = self::createTransform();

        $this->transformList->insertItemBefore($item, $count + 1);
        self::assertSame($item, $this->transformList->getItem($count));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $index < 0
     */
    public function testInsertItemBefore_NegativeIndex()
    {
        $this->transformList->insertItemBefore(self::createTransform(), -1);
    }

    public function testReplaceItem()
    {
        $item = self::createTransform();

        $this->transformList->appendItem(self::createTransform());
        self::assertSame($item, $this->transformList->replaceItem($item, 0));
    }

    public function testReplaceItem_WithSame()
    {
        $item1 = self::createTransform();
        $item2 = self::createTransform();
        $item3 = self::createTransform();
        $item4 = self::createTransform();

        $this->transformList->appendItem($item1);
        $this->transformList->appendItem($item2);
        $this->transformList->appendItem($item3);
        $this->transformList->appendItem($item4);

        self::assertSame($item4, $this->transformList->replaceItem($item4, 1));

        self::assertSame($item1, $this->transformList->getItem(0));
        self::assertSame($item4, $this->transformList->getItem(1));
        self::assertSame($item3, $this->transformList->getItem(2));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testReplaceItem_InvalidIndex()
    {
        $this->transformList->replaceItem(self::createTransform(), 0);
    }

    public function testRemoveItem()
    {
        $count = 10;
        $this->append($count);

        $item = self::createTransform();
        $this->transformList->replaceItem($item, 3);

        self::assertSame($item, $this->transformList->getItem(3));
        self::assertSame($item, $this->transformList->removeItem(3));
    }

    /**
     * @expectedException InvalidArgumentException
     * @dataProvider invalidIndexProvider
     *
     * @param $data
     */
    public function testRemoveItem_InvalidIndex($data)
    {
        $this->transformList->removeItem($data['index']);
    }

    public function testConsolidate()
    {
        $rotate = self::createTransform();
        $rotate->setRotate(45, 10, 5);

        $translate = self::createTransform();
        $translate->setTranslate(2, 1);

        $scale = self::createTransform();
        $scale->setScale(0.75, 0.75);

        $skewX = self::createTransform();
        $skewX->setSkewX(45);

        $skewY = self::createTransform();
        $skewY->setSkewY(90);

        $this->transformList->appendItem($rotate);
        $this->transformList->appendItem($translate);
        $this->transformList->appendItem($scale);
        $this->transformList->appendItem($skewX);
        $this->transformList->appendItem($skewY);

        self::assertInstanceOf('nstdio\svg\transform\SVGTransformImpl', $this->transformList->consolidate());
        self::assertEquals(1, $this->transformList->numberOfItems);
        self::assertEquals(SVGTransform::SVG_TRANSFORM_MATRIX, $this->transformList->getItem(0)->type());
    }

    public function testConsolidate_EmptyList()
    {
        self::assertNull($this->transformList->consolidate());
    }

    public function testCreateSVGTransformFromMatrix()
    {
        $matrix = SVGMatrixImpl::ofTranslate(1, 1);

        $created = $this->transformList->createSVGTransformFromMatrix($matrix);

        self::assertInstanceOf('nstdio\svg\transform\SVGTransformImpl', $created);

        self::assertEquals($created->matrix()->a, $matrix->a);
        self::assertEquals($created->matrix()->b, $matrix->b);
        self::assertEquals($created->matrix()->c, $matrix->c);
        self::assertEquals($created->matrix()->d, $matrix->d);
        self::assertEquals($created->matrix()->e, $matrix->e);
        self::assertEquals($created->matrix()->f, $matrix->f);
    }

    public function invalidIndexProvider()
    {
        return [
            'negative'      => [
                ['index' => -1],
            ],
            'zero'          => [
                ['index' => 0],
            ],
            'out of bounds' => [
                ['index' => 1],
            ],
        ];
    }
}