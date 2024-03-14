<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCAttribute implements WCObjectInterface
{
    /** @var string */
    public $slug;

    /** @var string */
    public $name;
}