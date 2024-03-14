<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\MyCLabs\Enum\PHPUnit;

use CoffeeCode\MyCLabs\Enum\Enum;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Use this Comparator to get nice output when using PHPUnit assertEquals() with Enums.
 *
 * Add this to your PHPUnit bootstrap PHP file:
 *
 * \SebastianBergmann\Comparator\Factory::getInstance()->register(new \CoffeeCode\MyCLabs\Enum\PHPUnit\Comparator());
 */
final class Comparator extends \SebastianBergmann\Comparator\Comparator
{
    public function accepts($expected, $actual)
    {
        return $expected instanceof Enum && (
                $actual instanceof Enum || $actual === null
            );
    }

    /**
     * @param Enum $expected
     * @param Enum|null $actual
     *
     * @return void
     */
    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        if ($expected->equals($actual)) {
            return;
        }

        throw new ComparisonFailure(
            $expected,
            $actual,
            $this->formatEnum($expected),
            $this->formatEnum($actual),
            false,
            'Failed asserting that two Enums are equal.'
        );
    }

    private function formatEnum(Enum $enum = null)
    {
        if ($enum === null) {
            return "null";
        }

        return get_class($enum)."::{$enum->getKey()}()";
    }
}
