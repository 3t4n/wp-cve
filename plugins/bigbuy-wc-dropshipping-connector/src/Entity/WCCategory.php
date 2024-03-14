<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class WCCategory implements WCObjectInterface
{
    /** @var string */
    public $name;

    /** @var string */
    public $slug;

    /** @var int */
    public $id;

    /** @var array */
    public $image;

    /** @var int */
    public $parent;
}