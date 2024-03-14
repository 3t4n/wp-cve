<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class AttributeMap
{
    /** @var int */
    public $attributeId;

    /** @var int */
    public $shopAttributeId;

    /** @var string */
    public $isoCode;

    /** @var int */
    public $version;

    /** @var \DateTime */
    public $dateAdd;

    /** @var \DateTime */
    public $dateUpdate;
}