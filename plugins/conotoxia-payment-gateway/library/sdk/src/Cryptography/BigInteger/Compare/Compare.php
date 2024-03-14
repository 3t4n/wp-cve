<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Compare;

use CKPL\Pay\Cryptography\BigInteger\BigIntegerInterface;
use function array_pad;
use function count;

/**
 * Class Compare.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Compare
 */
class Compare implements CompareInterface
{
    /**
     * @var BigIntegerInterface
     */
    protected $first;

    /**
     * @var BigIntegerInterface
     */
    protected $second;

    /**
     * Compare constructor.
     *
     * @param BigIntegerInterface $first
     * @param BigIntegerInterface $second
     */
    public function __construct(BigIntegerInterface $first, BigIntegerInterface $second)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * @return int
     */
    public function perform(): int
    {
        $first = $this->first->getValue();
        $second = $this->second->getValue();

        if (count($first) !== count($second)) {
            $result = (count($first) > count($second)) ? 1 : -1;
        } else {
            $size = count($first);

            $first = array_pad($first, $size, 0);
            $second = array_pad($second, $size, 0);

            for ($i = count($first) - 1; $i >= 0; $i--) {
                if ($first[$i] != $second[$i]) {
                    $result = ($first[$i] > $second[$i]) ? 1 : -1;

                    break;
                }
            }

            $result = $result ?? 0;
        }

        return $result;
    }
}
