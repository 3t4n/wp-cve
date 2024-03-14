<?php
/**
 * WooCommerce Paygreen Payment Exception Class
 *
 * Extends Exception to provide additional data
 *
 * @since 0.0.0
 */

namespace Paygreen\Module\Exception;

use Exception;

if (!defined('ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Exception extends Exception
{
    /*
     * String sanitized/localized error message.
     *
     * @var string
     */
    protected $localized_message;

    /**
     * @var string
     */
    protected $localized_message_id;

    /**
     * Setup exception
     *
     * @since 0.0.0
     * @param string $error_message Full response
     * @param string $localized_message user-friendly translated error message
     */
    public function __construct(
        $error_message = '',
        $localized_message = '',
        $localized_message_id = ''
    ) {
        $this->localized_message = $localized_message;
        $this->localized_message_id = $localized_message_id;

        parent::__construct($error_message);
    }

    /**
     * Returns the localized message.
     *
     * @since 0.0.0
     * @return string
     */
    public function get_localized_message()
    {
        return $this->localized_message;
    }

    /**
     * Returns the localized message id.
     *
     * @since 1.2.0
     * @return string
     */
    public function get_localized_message_id()
    {
        return $this->localized_message_id;
    }

}
