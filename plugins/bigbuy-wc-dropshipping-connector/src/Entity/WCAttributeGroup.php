<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCAttributeGroup implements WCObjectInterface
{
    /** @var string */
    public $slug;

    /** @var string */
    public $name;

    /** @var string */
    public $type;

    /** @var bool */
    public $variation;

    /** @var bool */
    public $visible;
}