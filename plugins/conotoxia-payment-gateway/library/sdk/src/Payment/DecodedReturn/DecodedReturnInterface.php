<?php

declare(strict_types=1);

namespace CKPL\Pay\Payment\DecodedReturn;

/**
 * Interface DecodedReturnInterface.
 *
 * @package CKPL\Pay\Payment\DecodedReturn
 */
interface DecodedReturnInterface
{
    /**
     * @return string
     */
    public function getPaymentId(): string;

    /**
     * @return string
     */
    public function getExternalPaymentId(): string;

    /**
     * @return string
     */
    public function getResult(): string;
}
