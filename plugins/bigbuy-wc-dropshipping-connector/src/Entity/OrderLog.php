<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class OrderLog
{
    /** @var int */
    public $orderId;

    /** @var \DateTime */
    public $dateAdd;

    /** @var \DateTime */
    public $dateUpdate;
}