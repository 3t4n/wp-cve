<?php

namespace WcMipConnector\Client\BigBuy\Model;

defined('ABSPATH') || exit;

class ShippingOption
{
    /** @var ShippingService */
    public $shippingService;

    /** @var float */
    public $cost;

    /** @var float */
    public $weight;
}
