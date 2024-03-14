<?php

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class OrderItem
{
    /**
     * Model code
     */
    public static $MODEL = 'item';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var float
     */
    public $price;

    /**
     * @var float
     */
    public $line_price;

    /**
     * @var string
     */
    public $sku;

    /**
     * @var string
     */
    public $requires_shipping;

    /**
     * @var float
     */
    public $taxable;

    /**
     * @var int
     */
    public $product_id;

    /**
     * @var int
     */
    public $variant_id;

    /**
     * @var string
     */
    public $vendor;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $fulfillable_quantity;

    /**
     * @var string
     */
    public $fulfillment_service;

    /**
     * @var string
     */
    public $fulfillment_status;
}
