<?php

namespace nstdio\svg\transform;

/**
 * Interface SVGTransform
 * SVGTransform is the interface for one of the component transformations within an TransformList; thus, an
 * SVGTransform object corresponds to a single component (e.g., 'scale(…)' or 'matrix(…)') within a ‘transform’
 * attribute specification.
 *
 * @package nstdio\svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
interface SVGTransform extends Observable
{
    /**
     * The unit type is not one of predefined types. It is invalid to attempt to define a new value of this type or to
     * attempt to switch an existing value to this type.
     */
    const SVG_TRANSFORM_UNKNOWN = 0;

    /**
     * A 'matrix(…)' transformation.
     */
    const SVG_TRANSFORM_MATRIX = 1;

    /**
     * A 'translate(…)' transformation.
     */
    const SVG_TRANSFORM_TRANSLATE = 2;

    /**
     * A 'scale(…)' transformation.
     */
    const SVG_TRANSFORM_SCALE = 3;

    /**
     * A 'rotate(…)' transformation.
     */
    const SVG_TRANSFORM_ROTATE = 4;

    /**
     * A 'skewX(…)' transformation.
     */
    const SVG_TRANSFORM_SKEWX = 5;

    /**
     * A 'skewY(…)' transformation.
     */
    const SVG_TRANSFORM_SKEWY = 6;

    /**
     * The type of the value as specified by one of the SVG_TRANSFORM_* constants defined on this interface.
     *
     * @return int One of the SVG_TRANSFORM_* constants.
     */
    public function type();

    /**
     * The matrix that represents this transformation. The matrix object is live, meaning that any changes made to the
     * SVGTransform object are immediately reflected in the matrix object and vice versa. In case the matrix object is
     * changed directly (i.e., without using the methods on the Transform interface itself) then the type of the
     * Transform changes to SVG_TRANSFORM_MATRIX.
     *      - For SVG_TRANSFORM_MATRIX, the matrix contains the a, b, c, d, e, f values supplied by the user.
     *      - For SVG_TRANSFORM_TRANSLATE, e and f represent the translation amounts (a=1, b=0, c=0 and d=1).
     *      - For SVG_TRANSFORM_SCALE, a and d represent the scale amounts (b=0, c=0, e=0 and f=0).
     *      - For SVG_TRANSFORM_SKEWX and SVG_TRANSFORM_SKEWY, a, b, c and d represent the matrix which will result in
     *      the given skew (e=0 and f=0).
     *      - For SVG_TRANSFORM_ROTATE, a, b, c, d, e and f together represent the matrix which will result in the
     *      given rotation. When the rotation is around the center point (0, 0), e and f will be zero.
     *
     * @return SVGMatrix
     */
    public function matrix();

    /**
     * A convenience method for SVG_TRANSFORM_ROTATE, SVG_TRANSFORM_SKEWX and SVG_TRANSFORM_SKEWY. It holds the
     * angle that was specified.
     * For SVG_TRANSFORM_MATRIX, SVG_TRANSFORM_TRANSLATE and SVG_TRANSFORM_SCALE, angle will be zero.
     *
     * @return float
     */
    public function angle();

    /**
     * Sets the transform type to SVG_TRANSFORM_MATRIX, with parameter matrix defining the new transformation. The
     * values from the parameter matrix are copied, the matrix parameter does not replace Transform::matrix.
     *
     * @param SVGMatrix $matrix The new matrix for the transformation.
     *
     * @return void
     */
    public function setMatrix(SVGMatrix $matrix);

    /**
     * Sets the transform type to SVG_TRANSFORM_TRANSLATE, with parameters tx and ty defining the translation amounts.
     *
     * @param float $tx The translation amount in X.
     * @param float $ty The translation amount in Y.
     *
     * @return void
     */
    public function setTranslate($tx, $ty);

    /**
     * Sets the transform type to SVG_TRANSFORM_SCALE, with parameters sx and sy defining the scale amounts.
     *
     * @param float $sx The scale amount in X.
     * @param float $sy The scale amount in Y.
     *
     * @return void
     */
    public function setScale($sx, $sy);

    /**
     * Sets the transform type to SVG_TRANSFORM_ROTATE, with parameter angle defining the rotation angle and parameters
     * cx and cy defining the optional center of rotation.
     *
     * @param float $angle The rotation angle.
     * @param float $cx    The x coordinate of center of rotation.
     * @param float $cy    The y coordinate of center of rotation.
     *
     * @return void
     */
    public function setRotate($angle, $cx, $cy);

    /**
     * Sets the transform type to SVG_TRANSFORM_SKEWX, with parameter angle defining the amount of skew.
     *
     * @param float $angle The skew angle.
     *
     * @return void
     */
    public function setSkewX($angle);

    /**
     * Sets the transform type to SVG_TRANSFORM_SKEWY, with parameter angle defining the amount of skew.
     *
     * @param float $angle The skew angle.
     *
     * @return void
     */
    public function setSkewY($angle);
}