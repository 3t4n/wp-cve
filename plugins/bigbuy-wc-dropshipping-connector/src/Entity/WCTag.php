<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCTag implements WCObjectInterface
{
    /** @var string */
    public $name;

    /** @var string */
    public $slug;
}