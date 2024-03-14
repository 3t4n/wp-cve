<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCBrand implements WCObjectInterface
{
    /** @var string */
    public $name;

    /** @var string */
    public $slug;

    /** @var array */
    public $image;
}