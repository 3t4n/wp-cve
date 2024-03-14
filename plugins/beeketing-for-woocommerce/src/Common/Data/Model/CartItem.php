<?php

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class CartItem
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
     * @var int
     */
    public $variant_id;

    /**
     * @var string
     */
    public $variant_title;

    /**
     * @var int
     */
    public $product_id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $product_title;

    /**
     * @var float
     */
    public $price;

    /**
     * @var float
     */
    public $line_price;

    /**
     * @var int
     */
    public $quantity;

    /**
     * @var string
     */
    public $sku;

    /**
     * @var string
     */
    public $handle;

    /**
     * @var string
     */
    public $image;

    /**
     * @var string
     */
    public $url;
}
