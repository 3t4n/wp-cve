<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class AttributeGroupMap
{
    /** @var int */
    public $attributeGroupId;

    /** @var int */
    public $shopAttributeGroupId;

    /** @var int */
    public $version;

    /** @var \DateTime */
    public $dateAdd;

    /** @var \DateTime */
    public $dateUpdate;
}