<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCDimension implements WCObjectInterface
{
    /** @var float */
    public $length;

    /** @var float */
    public $width;

    /** @var float */
    public $height;
}