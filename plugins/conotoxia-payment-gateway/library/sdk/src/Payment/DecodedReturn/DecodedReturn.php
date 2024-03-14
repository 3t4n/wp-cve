<?php

declare(strict_types=1);

namespace CKPL\Pay\Payment\DecodedReturn;

/**
 * Class DecodedReturn.
 *
 * @package CKPL\Pay\Payment\DecodedReturn
 */
class DecodedReturn implements DecodedReturnInterface
{
    /**
     * @var string
     */
    protected $paymentId;

    /**
     * @var string
     */
    protected $externalPaymentId;

    /**
     * @var string
     */
    protected $result;

    /**
     * DecodedReturn constructor.
     *
     * @param string $paymentId
     * @param string $externalPaymentId
     * @param string $result
     */
    public function __construct(string $paymentId, string $externalPaymentId, string $result)
    {
        $this->paymentId = $paymentId;
        $this->externalPaymentId = $externalPaymentId;
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    /**
     * @return string
     */
    public function getExternalPaymentId(): string
    {
        return $this->externalPaymentId;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }
}
