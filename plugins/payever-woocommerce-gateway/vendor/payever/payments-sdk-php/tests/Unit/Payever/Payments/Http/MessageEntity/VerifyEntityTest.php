<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\VerifyEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class VerifyEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\VerifyEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class VerifyEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'type' => 'type',
        'twoFactor' => 'twoFactor',
    );

    public function getEntity()
    {
        return new VerifyEntity();
    }
}
