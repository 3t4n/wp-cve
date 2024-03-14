<?php

/**
 *
 * Function code for the matrix multiplication operation
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 *
 *Modified by GravityKit on 08-March-2024 using Strauss.
 *@see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\Matrix;

use GFExcel\Vendor\Matrix\Operators\Multiplication;

/**
 * Multiplies two or more matrices
 *
 * @param array<int, mixed> $matrixValues The matrices to multiply
 * @return Matrix
 * @throws Exception
 */
function multiply(...$matrixValues)
{
    if (count($matrixValues) < 2) {
        throw new Exception('Multiplication operation requires at least 2 arguments');
    }

    $matrix = array_shift($matrixValues);

    if (is_array($matrix)) {
        $matrix = new Matrix($matrix);
    }
    if (!$matrix instanceof Matrix) {
        throw new Exception('Multiplication arguments must be Matrix or array');
    }

    $result = new Multiplication($matrix);

    foreach ($matrixValues as $matrix) {
        $result->execute($matrix);
    }

    return $result->result();
}
