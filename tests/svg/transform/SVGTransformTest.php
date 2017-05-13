<?php
use nstdio\svg\transform\SVGTransform;
use nstdio\svg\transform\SVGTransformImpl;

/**
 * Class SVGTransformTest
 *
 * @package svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SVGTransformTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $transform = new SVGTransformImpl();

        $tx = 1;
        $ty = 5;
        $transform->setTranslate($tx, $ty);

        self::assertEquals(SVGTransform::SVG_TRANSFORM_TRANSLATE, $transform->type());

        self::assertEquals(1, $transform->matrix()->a);
        self::assertEquals(0, $transform->matrix()->b);
        self::assertEquals(0, $transform->matrix()->c);
        self::assertEquals(1, $transform->matrix()->d);
        self::assertEquals($tx, $transform->matrix()->e);
        self::assertEquals($ty, $transform->matrix()->f);
    }
}