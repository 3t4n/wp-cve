<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Events
{
    const AWC_ORDER_APPROVED = 'OrderApproved';

    const AWC_ORDER_AUTHORIZED = 'OrderAuthorized';

    const AWC_ORDER_AUTHORIZED_DELAY = 'OrderAuthorizedWithDelay';

    const AWC_ORDER_BILLET_CREATED = 'OrderBilletCreated';

    const AWC_ORDER_BILLET_OVERDUE = 'OrderBilletOverdue';

    const AWC_ORDER_INTEGRATED = 'OrderIntegrated';

    const AWC_ORDER_PAID = 'OrderPaid';

    const AWC_ORDER_PENDING_INTEGRATION = 'OrderPendingIntegration';

    const AWC_ORDER_REFUND = 'OrderRefund';

    const AWC_PAYMENT_NOT_AUTHORIZED = 'PaymentNotAuthorized';

    const AWC_PAYMENT_NOT_AUTHORIZED_WITH_DELAY = 'PaymentNotAuthorizedWithDelay';

}