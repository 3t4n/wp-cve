<?php

namespace WcMipConnector\Client\BigBuy\Model;

defined('ABSPATH') || exit;

class Order
{
    /** @var Delivery */
    public $delivery;

    /** @var Product */
    public $products;
}
