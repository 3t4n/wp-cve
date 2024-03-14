<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class VariationMap
{
    /** @var int */
    public $variationId;

    /** @var int */
    public $shopVariationId;

    /** @var \DateTime */
    public $dateAdd;

    /** @var \DateTime */
    public $dateUpdate;
}