<?php
/**
 * WooCommerce Paygreen Payment Forbidden Access Exception Class
 *
 * Extends WC_Paygreen_Payment_Exception to provide additional data
 *
 * @since 0.0.0
 */

namespace Paygreen\Module\Exception;

if (!defined('ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Forbidden_Access_Exception extends WC_Paygreen_Payment_Exception
{
}
