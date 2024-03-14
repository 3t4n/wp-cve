<?php
/**
 * Created by PhpStorm.
 * User: quaninte
 * Date: 8/14/18
 * Time: 11:54 AM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Variant
{

    /**
     * Model code
     */
    public static $MODEL = 'variant';
    public static $MODELS = 'variants';

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $product_id;

    /**
     * @var string
     */
    public $barcode;

    /**
     * @var int
     */
    public $image_id;

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
    public $price_compare;

    /**
     * @var string
     */
    public $option1;

    /**
     * @var string
     */
    public $option2;

    /**
     * @var string
     */
    public $option3;

    /**
     * @var string
     */
    public $grams;

    /**
     * @var string
     */
    public $position;

    /**
     * @var string
     */
    public $sku;

    /**
     * @var string
     */
    public $inventory_management;

    /**
     * @var string
     */
    public $inventory_policy;

    /**
     * @var int
     */
    public $inventory_quantity;

    /**
     * @var string
     */
    public $fulfillment_service;

    /**
     * @var string
     */
    public $weight;

    /**
     * @var string
     */
    public $weight_unit;

    /**
     * @var string
     */
    public $requires_shipping;

    /**
     * @var bool
     */
    public $taxable;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var boolean
     */
    public $in_stock;

    /**
     * @var array
     */
    public $attributes = [];
}
