<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Order_Status
{
    const AWC_PENDING = 'pending';

    const AWC_FAILED = 'failed';

    const AWC_PROCESSING = 'processing';

    const AWC_COMPLETED = 'completed';

    const AWC_ON_HOLD = 'on-hold';

    const AWC_CANCELLED = 'cancelled';

    const AWC_REFUNDED = 'refunded';
}