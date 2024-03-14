<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class ProductMap
{
    /** @var int */
    public $productId;

    /** @var int */
    public $shopProductId;

    /** @var int */
    public $version;

    /** @var int */
    public $imageVersion;

    /** @var \DateTime */
    public $messageVersion;
}