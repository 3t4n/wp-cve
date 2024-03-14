<?php

namespace WcMipConnector\Client\BigBuy\Model;

defined('ABSPATH') || exit;

class ShippingService
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $delay;

    /** @var string */
    public $transportMethod;

    /** @var string */
    public $serviceName;
}
