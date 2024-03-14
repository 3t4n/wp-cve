<?php

namespace Payever\Tests\Unit\Payever\Payments\Http\MessageEntity;

use Payever\Sdk\Payments\Http\MessageEntity\CartItemV3Entity;
use Payever\Tests\Unit\Payever\Core\Http\AbstractMessageEntityTest;

/**
 * Class CartItemEntityTest
 *
 * @see \Payever\Sdk\Payments\Http\MessageEntity\CartItemV3Entity
 *
 * @package Payever\Tests\Unit\Payever\Payments\Http\MessageEntity
 */
class CartItemV3EntityTest extends AbstractMessageEntityTest
{
    protected static $scheme = array(
        'name' => 'stub_name',
        'unit_price' => 100,
        'tax_rate' => 19,
        'quantity' => 1,
        'total_amount' => 119,
        'total_tax_amount' => 19,
        'description' => 'stub_description',
        'category' => 'Goods',
        'image_url' => 'stub',
        'product_url' => 'stub',
        'sku' => 'stub_sku',
        'identifier' => 'stub_sku',
        'brand' => 'brand',
        'attributes' => array(),
    );

    public static function getScheme()
    {
        $scheme = static::$scheme;

        $scheme['attributes'] = AttributesEntityTest::getScheme();

        return $scheme;
    }

    public function getEntity()
    {
        return new CartItemV3Entity();
    }
}
