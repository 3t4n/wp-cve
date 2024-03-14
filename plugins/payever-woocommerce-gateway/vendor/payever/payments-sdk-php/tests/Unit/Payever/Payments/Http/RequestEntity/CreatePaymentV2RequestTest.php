<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\RequestEntity;

use Payever\Sdk\Payments\Http\MessageEntity\ChannelEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PaymentDataEntity;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentV2Request;
use PHPUnit\Framework\TestCase;

class CreatePaymentV2RequestTest extends TestCase
{
    public function testMethods()
    {
        $request = new CreatePaymentV2Request();

        $this->assertInstanceOf(
            CreatePaymentV2Request::class,
            $request->setChannel(new ChannelEntity())
        );
        $this->assertInstanceOf(
            ChannelEntity::class,
            $request->getChannel()
        );

        $this->assertInstanceOf(
            CreatePaymentV2Request::class,
            $request->setAmount(100.1)
        );
        $this->assertIsFloat($request->getAmount());

        $this->assertInstanceOf(
            CreatePaymentV2Request::class,
            $request->setPaymentData(['force_redirect' => true])
        );
        $this->assertInstanceOf(
            PaymentDataEntity::class,
            $request->getPaymentData()
        );

        $this->assertInstanceOf(
            CreatePaymentV2Request::class,
            $request->setPaymentData(new PaymentDataEntity())
        );
        $this->assertInstanceOf(
            PaymentDataEntity::class,
            $request->getPaymentData()
        );
        $this->assertInstanceOf(
            CreatePaymentV2Request::class,
            $request->setPaymentData(json_encode(['force_redirect' => true]))
        );
        $this->assertInstanceOf(
            PaymentDataEntity::class,
            $request->getPaymentData()
        );
    }
}
