<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Add;

/**
 * Interface AddInterface.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Add
 */
interface AddInterface
{
    /**
     * @return array
     */
    public function perform(): array;
}
