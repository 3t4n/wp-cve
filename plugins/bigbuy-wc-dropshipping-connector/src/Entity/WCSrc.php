<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCSrc implements WCObjectInterface
{
    /** @var string */
    public $src;

    /** @var string */
    public $name;

    /** @var string */
    public $alt;

    /** @var int */
    public $position;
}