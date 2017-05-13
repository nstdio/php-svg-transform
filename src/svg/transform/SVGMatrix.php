<?php
namespace nstdio\svg\transform;

/**
 * Class Matrix
 * Many of SVG's graphics operations utilize 2x3 matrices of the form:
 *
 * [a c e]
 * [b d f]
 *
 * which, when expanded into a 3x3 matrix for the purposes of matrix arithmetic, become:
 *
 * [a c e]
 * [b d f]
 * [0 0 1]
 *
 * @property float $a The a component of the matrix.
 * @property float $b The b component of the matrix.
 * @property float $c The c component of the matrix.
 * @property float $d The d component of the matrix.
 * @property float $e The e component of the matrix.
 * @property float $f The f component of the matrix.
 * @package nstdio\svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface SVGMatrix
{
    /**
     * Performs matrix multiplication. This matrix is post-multiplied by another matrix, returning the resulting new
     * matrix.
     *
     * @param SVGMatrix $matrix The matrix which is post-multiplied to this matrix.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function multiply(SVGMatrix $matrix);

    /**
     * Returns the inverse matrix.
     *
     * @return SVGMatrix The inverse matrix.
     */
    public function inverse();

    /**
     * Post-multiplies a translation transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $x The distance to translate along the x-axis.
     * @param float $y The distance to translate along the y-axis.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function translate($x, $y);

    /**
     * Post-multiplies a uniform scale transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $scaleFactor
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function scale($scaleFactor);

    /**
     * Post-multiplies a non-uniform scale transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $scaleFactorX Scale factor in X.
     * @param float $scaleFactorY Scale factor in Y.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function scaleNonUniform($scaleFactorX, $scaleFactorY);

    /**
     * Post-multiplies a rotation transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $angle Rotation angle.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function rotate($angle);

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
    public function rotateFromVector($x, $y);

    /**
     * Post-multiplies the transformation [-1 0 0 1 0 0] and returns the resulting matrix.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function flipX();

    /**
     * Post-multiplies the transformation [1 0 0 -1 0 0] and returns the resulting matrix.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function flipY();

    /**
     * Post-multiplies a skewX transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $angle Skew angle.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function skewX($angle);

    /**
     * Post-multiplies a skewY transformation on the current matrix and returns the resulting matrix.
     *
     * @param float $angle Skew angle.
     *
     * @return SVGMatrix The resulting matrix.
     */
    public function skewY($angle);
}