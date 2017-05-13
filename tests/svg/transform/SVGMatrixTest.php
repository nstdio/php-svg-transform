<?php
use nstdio\svg\transform\SVGMatrix;
use nstdio\svg\transform\SVGMatrixImpl;

/**
 * Class MatrixTest
 *
 * @package svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SVGMatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SVGMatrix
     */
    private $matrix;

    public function setUp()
    {
        $this->matrix = new SVGMatrixImpl();
    }

    public function testMatrixInitialValue()
    {
        self::assertEquals(0, $this->matrix->a);
        self::assertEquals(0, $this->matrix->b);
        self::assertEquals(0, $this->matrix->c);
        self::assertEquals(0, $this->matrix->d);
        self::assertEquals(0, $this->matrix->e);
        self::assertEquals(0, $this->matrix->f);
    }

    public function testMagic()
    {
        $this->matrix->a = 1;
        $this->matrix->b = 1;
        $this->matrix->c = 1;
        $this->matrix->d = 1;
        $this->matrix->e = 1;
        $this->matrix->f = 1;
        $this->matrix->l = 1;

        self::assertEquals(1, $this->matrix->a);
        self::assertEquals(1, $this->matrix->b);
        self::assertEquals(1, $this->matrix->c);
        self::assertEquals(1, $this->matrix->d);
        self::assertEquals(1, $this->matrix->e);
        self::assertEquals(1, $this->matrix->f);
        self::assertEquals(null, $this->matrix->l);
    }

    public function testMul()
    {
        $this->matrix->a = 1;
        $this->matrix->c = 0;
        $this->matrix->e = 25;
        $this->matrix->b = 0;
        $this->matrix->d = 1;
        $this->matrix->f = 10;

        $second = new SVGMatrixImpl();

        $second->a = 1;
        $second->c = 0;
        $second->e = 30;
        $second->b = 0;
        $second->d = 1;
        $second->f = 20;

        $result = $this->matrix->multiply($second);

        self::assertEquals(1, $result->a);
        self::assertEquals(0, $result->c);
        self::assertEquals(55, $result->e);
        self::assertEquals(0, $result->b);
        self::assertEquals(1, $result->d);
        self::assertEquals(30, $result->f);
    }

    public function testTranslate()
    {
        $this->matrix->a = 1;
        $this->matrix->b = 0;
        $this->matrix->c = 0;
        $this->matrix->d = 1;

        $tx = 10;
        $ty = -1;
        $matrix = $this->matrix->translate($tx, $ty);

        self::assertEquals(1, $matrix->a);
        self::assertEquals(0, $matrix->b);
        self::assertEquals(0, $matrix->c);
        self::assertEquals(1, $matrix->d);
        self::assertEquals($tx, $matrix->e);
        self::assertEquals($ty, $matrix->f);
    }

    public function testRotateFromVector()
    {
        self::assertEquals(45, rad2deg(atan2(5, 5)));
        self::assertEquals(26.5651, rad2deg(atan2(2.5, 5)), '', 0.4);
    }
}