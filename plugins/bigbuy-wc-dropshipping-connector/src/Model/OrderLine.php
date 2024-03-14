<?php

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class OrderLine
{
    /* @var string */
    public $ASIN;

    /* @var string */
    public $Reference;

    /* @var Price */
    public $ItemPrice;

    /* @var Price */
    public $ItemTax;

    /* @var string */
    public $OrderItemId;

    /* @var integer */
    public $QuantityOrdered;

    /* @var string */
    public $SellerSKU;

    /* @var Price */
    public $ShippingPrice;

    /* @var Price */
    public $ShippingTax;

    /* @var string */
    public $ItemTitle;
}