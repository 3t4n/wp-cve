<?php

namespace WcMipConnector\Enum;

defined('ABSPATH') || exit;

class WooCommerceOrderStatus
{
    public const PENDING = 'pending';
    public const ON_HOLD = 'on-hold';
    public const PROCESSING = 'processing';
    public const CANCELLED = 'cancelled';
    public const REFUNDED = 'refund';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';
}