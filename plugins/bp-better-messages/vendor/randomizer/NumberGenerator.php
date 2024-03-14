<?php

namespace BetterMessages\Randomizer;

use BetterMessages\Randomizer\Exception\InvalidArgumentException;

/**
 * Class for generating random numbers.
 *
 * @author cinam <cinam@hotmail.com>
 */
class NumberGenerator
{
    /**
     * Returns random integer from given range.
     *
     * @param integer $min The minimal value.
     * @param integer $max The maximal value.
     *
     * @return integer
     *
     * @throws InvalidArgumentException If min is greater than max.
     */
    public function getInt($min, $max)
    {
        if ($min > $max) {
            throw new InvalidArgumentException('Min cannot be greater than max');
        }

        return \mt_rand($min, $max);
    }

    /**
     * Returns random float from given range.
     *
     * @param float $min The minimal value.
     * @param float $max The maximal value.
     *
     * @return float
     *
     * @throws InvalidArgumentException If min is greater than max.
     */
    public function getFloat($min, $max)
    {
        if ($min > $max) {
            throw new InvalidArgumentException('Min cannot be greater than max');
        }

        $random01 = \mt_rand() / \mt_getrandmax();

        // [0, 1] -> [min, max]:
        // y = (max - min) * x + min
        return ($max - $min) * $random01 + $min;
    }
}
