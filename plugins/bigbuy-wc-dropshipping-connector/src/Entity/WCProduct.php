<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCProduct implements WCObjectInterface
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $sku;

    /** @var integer */
    public $price;

    /** @var string */
    public $regular_price;

    /** @var integer */
    public $sale_price;

    /** @var int */
    public $manage_stock;

    /** @var int */
    public $stock_quantity;

    /** @var boolean */
    public $in_stock;

    /** @var integer */
    public $weight;

    /** @var string */
    public $status;

    /** @var string */
    public $catalog_visibility;

    /** @var WCDimension */
    public $dimensions;

    /** @var WCSrc[] */
    public $images;

    /** @var array */
    public $categories;

    /** @var int */
    public $variations;

    /** @var array */
    public $attributes;

    /** @var string */
    public $type;

    /** @var int */
    public $id;

    /** @var array */
    public $tags;

    /** @var array */
    public $brands;

    /** @var string */
    public $tax_class;
}