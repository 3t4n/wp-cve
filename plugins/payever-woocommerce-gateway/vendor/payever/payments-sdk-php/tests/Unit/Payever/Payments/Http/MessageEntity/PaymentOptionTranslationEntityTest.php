<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\PaymentOptionTranslationEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class PaymentOptionTranslationEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\PaymentOptionTranslationEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class PaymentOptionTranslationEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'locale' => 'de',
        'field' => 'stub_field',
        'content' => 'stub_content',
    );

    public function getEntity()
    {
        return new PaymentOptionTranslationEntity();
    }
}
