<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\UrlsEntity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class UrlsEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\UrlsEntity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class UrlsEntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'redirect' => 'https://example.com',
        'success' => 'https://example.com',
        'pending' => 'https://example.com',
        'failure' => 'https://example.com',
        'cancel' => 'https://example.com',
        'notification' => 'https://example.com',
    );

    public function getEntity()
    {
        return new UrlsEntity();
    }
}
