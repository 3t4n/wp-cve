<?php

declare(strict_types=1);

namespace CKPL\Pay\Payment\ReturnDecoder;

use CKPL\Pay\Exception\DecodedReturnException;
use CKPL\Pay\Payment\DecodedReturn\DecodedReturnInterface;

/**
 * Interface ReturnDecoderInterface.
 *
 * @package CKPL\Pay\Payment\ReturnDecoder
 */
interface ReturnDecoderInterface
{
    /**
     * @param string $return
     *
     * @throws DecodedReturnException
     *
     * @return DecodedReturnInterface
     */
    public function decode(string $return): DecodedReturnInterface;
}
