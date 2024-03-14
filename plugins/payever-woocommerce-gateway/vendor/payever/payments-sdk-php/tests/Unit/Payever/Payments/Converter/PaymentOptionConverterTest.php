<?php

namespace Payever\Tests\Unit\Payever\Payments\Converter;

use Payever\Sdk\Payments\Converter\PaymentOptionConverter;
use Payever\Sdk\Payments\Http\MessageEntity\ListPaymentOptionsVariantsResultEntity;
use Payever\Tests\Bootstrap\TestCase;
use Payever\Tests\Unit\Payever\Payments\Http\MessageEntity\ListPaymentOptionsVariantsResultEntityTest;

class PaymentOptionConverterTest extends TestCase
{
    public function testConvert()
    {
        $paymentOptionWithVariant = new ListPaymentOptionsVariantsResultEntity(
            ListPaymentOptionsVariantsResultEntityTest::getScheme()
        );

        $result = PaymentOptionConverter::convertPaymentOptionVariants([$paymentOptionWithVariant]);

        $this->assertCount(3, $result);

        foreach ($paymentOptionWithVariant->getVariants() as $variant) {
            $this->assertArrayHasKey($variant->getId(), $result);

            $convertedVariant = $result[$variant->getId()];

            $this->assertEquals($variant->getAcceptFee(), $convertedVariant->getAcceptFee());
            $this->assertEquals($variant->getName(), $convertedVariant->getVariantName());
            $this->assertEquals($variant->getId(), $convertedVariant->getVariantId());
        }
    }
}
