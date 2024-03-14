<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCVariation implements WCObjectInterface
{
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

    /** @var array */
    public $attributes;
}