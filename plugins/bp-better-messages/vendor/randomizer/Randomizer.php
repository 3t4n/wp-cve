<?php

namespace BetterMessages\Randomizer;

use BetterMessages\Randomizer\Exception\InvalidArgumentException;

/**
 * Class for getting random values.
 *
 * @author cinam <cinam@hotmail.com>
 */
class Randomizer
{
    /**
     * @var NumberGenerator
     */
    protected $generator;

    /**
     * The constructor.
     *
     * @param NumberGenerator $generator Number generator.
     */
    public function __construct($numberGenerator)
    {
        $this->generator = $numberGenerator;
    }

    /**
     * Returns random boolean using given probability of "true".
     *
     * @param float $probability Probability of result "true".
     *                           Must be between 0 and 1.
     *                           Defaults to 0.5.
     *
     * @return boolean
     *
     * @throws InvalidArgumentException for invalid probability.
     */
    public function getBoolean($probability = 0.5)
    {
        if ((\is_int($probability) || \is_float($probability)) === false
                || $probability < 0
                || $probability > 1
        ) {
            throw new InvalidArgumentException('Invalid probability');
        }

        if ($probability == 0) {
            $result = false;
        } else {
            $random = $this->generator->getFloat(0, 1);
            $result = ($probability >= $random);
        }

        return $result;
    }

    /**
     * Returns key of the input array based on the "powers" of array values.
     *
     * @param array $powers Array of non-negative float values.
     *                      Each key's "power" means likeness of this key
     *                      being returned.
     *
     * @return mixed A key of the input array.
     *
     * @throws InvalidArgumentException for empty input or invalid power values.
     */
    public function getArrayKeyByPowers(array $powers)
    {
        if (empty($powers)) {
            throw new InvalidArgumentException('Empty powers set');
        }

        $powersSum = 0;
        foreach ($powers as $power) {
            if ($power < 0) {
                throw new InvalidArgumentException('Negative power found');
            }

            $powersSum += $power;
        }

        if ($powersSum <= 0) {
            throw new InvalidArgumentException('The sum of powers must be positive');
        }

        $randomValue = $this->generator->getFloat(0, $powersSum);
        $currentSum = 0;
        $result = null;
        foreach ($powers as $key => $power) {
            $currentSum += $power;
            if ($currentSum >= $randomValue) {
                $result = $key;
                break;
            }
        }

        return $key;
    }

    /**
     * Returns an array value based on the "powers" of array values.
     * Arrays must be not empty and of equal sizes.
     *
     * @param array $values Array of values to be returned.
     * @param array $powers Array of non-negative float values.
     *                      Each key's "power" means likeness of
     *                      the corresponding array value being returned.
     *
     * @return mixed One of the values of the "values" array.
     */
    public function getValueByPowers(array $values, array $powers)
    {
        if (empty($values)
                || empty($powers)
                || \count($values) !== \count($powers)
        ) {
            throw new InvalidArgumentException('Empty parameter or count not equal');
        }

        // reindex arrays
        $values = \array_values($values);
        $powers = \array_values($powers);

        $rolledIndex = $this->getArrayKeyByPowers($powers);

        return $values[$rolledIndex];
    }

    /**
     * Returns an array value with equal probabilities.
     * The input array must not be empty.
     *
     * @param array $values Array of values to be returned.
     *
     * @return mixed One of the values of the input array.
     */
    public function getArrayValue(array $values)
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Empty parameter');
        }

        // reindex array
        $values = \array_values($values);

        return $values[$this->generator->getInt(0, count($values) - 1)];
    }
}
