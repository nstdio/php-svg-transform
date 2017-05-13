<?php
namespace nstdio\svg\transform;

use mcordingley\LinearAlgebra\Matrix;

/**
 * Class MatrixUtil
 *
 * @package svg\transform
 * @author  Edgar Asatryan <nstdio@gmail.com>
 */
final class MatrixUtil
{
    private function __construct()
    {
    }

    public static function mulVectorsAsMatrices(array $first, array $second)
    {
        $first = self::matrixFromVector(array_values($first));
        $second = self::matrixFromVector(array_values($second));

        $result = self::mul($first, $second);

        return self::vectorFromMatrix($result);
    }

    /**
     * Creates 3x3 matrix from 6 element input array.
     *
     * @param array $vector
     *
     * @return array
     */
    public static function matrixFromVector(array $vector)
    {
        $vector = array_values($vector);

        return [
            [$vector[0], $vector[2], $vector[4]],
            [$vector[1], $vector[3], $vector[5]],
            [0, 0, 1],
        ];
    }

    /**
     * @param array $first
     * @param array $second
     *
     * @return array
     */
    public static function mul(array $first, array $second)
    {
        $first = new Matrix($first);

        return $first->multiplyMatrix(new Matrix($second))
            ->toArray();
    }

    /**
     * @param array $matrix
     *
     * @return array
     */
    public static function vectorFromMatrix(array $matrix)
    {
        return [
            $matrix[0][0], $matrix[1][0], $matrix[0][1],
            $matrix[1][1], $matrix[0][2], $matrix[1][2],
        ];
    }

    public static function inverseVector($vec)
    {
        $matrix = new Matrix(self::matrixFromVector(array_values($vec)));

        return self::vectorFromMatrix($matrix->inverse()->toArray());
    }
}