<?php

namespace nstdio\svg\transform;

/**
 * Class MatrixImpl
 *
 * @package nstdio\svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
class SVGMatrixImpl implements SVGMatrix
{
    private static $keys = ['a', 'b', 'c', 'd', 'e', 'f'];
    /**
     * @var array
     */
    private $map;

    public function __construct()
    {
        $this->map = array_combine(self::$keys, array_fill(0, count(self::$keys), 0.0));
    }

    public static function ofTranslate($tx, $ty)
    {
        return self::svgMatrixFromVector([1, 0, 0, 1, $tx, $ty]);
    }

    private static function svgMatrixFromVector(array $vec)
    {
        $matrix = new static();
        $matrix->map = self::mapFromVector($vec);

        return $matrix;
    }

    private static function mapFromVector(array $vec)
    {
        return array_combine(self::$keys, $vec);
    }

    public static function ofSkewX($angle)
    {
        return self::svgMatrixFromVector([1, 0, tan(deg2rad($angle)), 1, 0, 0]);
    }

    public static function ofSkewY($angle)
    {
        return self::svgMatrixFromVector([1, tan(deg2rad($angle)), 0, 1, 0, 0]);
    }

    public static function ofScale($sx, $sy)
    {
        return self::svgMatrixFromVector([$sx, 0, 0, $sy, 0, 0]);
    }

    public static function ofRotate($angle)
    {
        $matrix = new static();

        $rads = deg2rad($angle);
        $cos = cos($rads);
        $sin = sin($rads);

        $matrix->map = self::mapFromVector([$cos, $sin, -$sin, $cos, 0, 0]);

        return $matrix;
    }

    /**
     * Performs matrix multiplication. This matrix is post-multiplied by another matrix, returning the resulting new
     * matrix.
     *
     * @param SVGMatrix $matrix The matrix which is post-multiplied to this matrix.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function multiply(SVGMatrix $matrix)
    {
        return $this->createWithMul(self::vector($matrix));
    }

    private function createWithMul(array $vec)
    {
        $result = new static();

        $result->map = $this->mul($vec);

        return $result;
    }

    private function mul(array $transformVec)
    {
        return self::mapFromVector(
            MatrixUtil::mulVectorsAsMatrices($this->map, $transformVec)
        );
    }

    /**
     * @param SVGMatrix $matrix
     *
     * @return array
     */
    private static function vector(SVGMatrix $matrix)
    {
        $secondMap = [$matrix->a, $matrix->b, $matrix->c, $matrix->d, $matrix->e, $matrix->f];

        return $secondMap;
    }

    /**
     * Returns the inverse matrix.
     *
     * @return SVGMatrix The inverse matrix.
     */
    public function inverse()
    {
        $result = new static();

        $result->map = self::mapFromVector(MatrixUtil::inverseVector($this->map));

        return $result;
    }

    /**
     * Post-multiplies a uniform scale transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $scaleFactor
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function scale($scaleFactor)
    {
        return $this->scaleNonUniform($scaleFactor, $scaleFactor);
    }

    /**
     * Post-multiplies a non-uniform scale transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $scaleFactorX Scale factor in X.
     * @param float $scaleFactorY Scale factor in Y.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function scaleNonUniform($scaleFactorX, $scaleFactorY)
    {
        return $this->createWithMul([$scaleFactorX, 0, 0, $scaleFactorY, 0, 0]);
    }

    /**
     * Post-multiplies a rotation transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $angle Rotation angle.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function rotate($angle)
    {
        $rads = deg2rad($angle);
        $cos = cos($rads);
        $sin = sin($rads);

        return $this->createWithMul([$cos, $sin, -$sin, $cos, 0, 0]);
    }

    /**
     * Post-multiplies a rotation transformation on the current matrix and returns the resulting matrix. The rotation
     * angle is determined by taking (+/-) atan(y/x). The direction of the vector (x, y) determines whether the
     * positive or negative angle value is used.
     *
     * @param float $x The X coordinate of the vector (x,y). Must not be zero.
     * @param float $y The Y coordinate of the vector (x,y). Must not be zero.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function rotateFromVector($x, $y)
    {
        $angle = rad2deg(atan2($y, $x));

        $result = $this->translate($x, $y)
            ->rotate($angle)
            ->translate(-$x, -$y);

        return $result;
    }

    /**
     * Post-multiplies a translation transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $x The distance to translate along the x-axis.
     * @param float $y The distance to translate along the y-axis.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function translate($x, $y)
    {
        return $this->createWithMul([1, 0, 0, 1, $x, $y]);
    }

    /**
     * Post-multiplies the transformation [-1 0 0 1 0 0] and returns the resulting matrix.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function flipX()
    {
        return $this->createWithMul([-1, 0, 0, 1, 0, 0]);
    }

    /**
     * Post-multiplies the transformation [1 0 0 -1 0 0] and returns the resulting matrix.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function flipY()
    {
        return $this->createWithMul([1, 0, 0, -1, 0, 0]);
    }

    /**
     * Post-multiplies a skewX transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $angle Skew angle.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function skewX($angle)
    {
        return $this->createWithMul([1, 0, tan(deg2rad($angle)), 1, 0, 0]);
    }

    /**
     * Post-multiplies a skewY transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $angle Skew angle.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function skewY($angle)
    {
        return $this->createWithMul([1, tan(deg2rad($angle)), 0, 1, 0, 0]);
    }

    public function __get($name)
    {
        if (!in_array($name, self::$keys, true)) {
            return null;
        }

        return $this->map[$name];
    }

    public function __set($name, $value)
    {
        if (in_array($name, self::$keys)) {
            $this->map[$name] = floatval($value);
        }
    }
}