<?php

declare(strict_types=1);

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class OrderHealthReport
{
    /** @var int|null */
    public $OrderNotMappedCount;

    /** @var int|null */
    public $OrderMappedCount;
}