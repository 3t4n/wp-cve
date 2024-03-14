<?php

declare(strict_types=1);

namespace CKPL\Pay\Payment\ReturnDecoder;

use CKPL\Pay\Exception\DecodedReturnException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Payment\DecodedReturn\DecodedReturn;
use CKPL\Pay\Payment\DecodedReturn\DecodedReturnInterface;
use CKPL\Pay\Security\SecurityManagerInterface;

/**
 * Class ReturnDecoder.
 *
 * @package CKPL\Pay\Payment\ReturnDecoder
 */
class ReturnDecoder implements ReturnDecoderInterface
{
    /**
     * @var SecurityManagerInterface
     */
    protected $securityManager;

    /**
     * ReturnDecoder constructor.
     *
     * @param SecurityManagerInterface $securityManager
     */
    public function __construct(SecurityManagerInterface $securityManager)
    {
        $this->securityManager = $securityManager;
    }

    /**
     * @param string $return
     *
     * @throws DecodedReturnException
     *
     * @return DecodedReturnInterface
     */
    public function decode(string $return): DecodedReturnInterface
    {
        $return = $this->securityManager->decodeResponse($return);
        $payload = $return->getPayload();

        if (!$payload->hasElement('paymentId')) {
            throw new DecodedReturnException('Missing "paymentId" in return.');
        }

        if (!$payload->hasElement('externalPaymentId')) {
            throw new DecodedReturnException('Missing "externalPaymentId" in return.');
        }

        if (!$payload->hasElement('result')) {
            throw new DecodedReturnException('Missing "result" in return.');
        }

        try {
            return new DecodedReturn(
                $payload->expectStringOrNull('paymentId'),
                $payload->expectStringOrNull('externalPaymentId'),
                $payload->expectStringOrNull('result')
            );
        } catch (PayloadException $e) {
            throw new DecodedReturnException('Unable to decode return data.', 0, $e);
        }
    }
}
