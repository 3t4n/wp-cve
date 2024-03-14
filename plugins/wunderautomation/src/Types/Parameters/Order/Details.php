<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Emails;
use WC_Order;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Details
 */
class Details extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'email.summary';
        $this->description = __('Outputs order summary in plain text or HTML', 'wunderauto');
        $this->objects     = ['order'];

        $this->dataType         = 'string';
        $this->usesOutputFormat = true;
        $this->outputFormats    = [
            'plain' => __('Plain text', 'wunderauto'),
            'html'  => __('HTML', 'wunderauto'),
        ];
    }

    /**
     * @param WC_Order|null $order
     * @param \stdClass     $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        if (!defined('WC_ABSPATH')) {
            return false;
        }
        include_once WC_ABSPATH . 'includes/class-wc-emails.php';
        WC_Emails::instance();

        if (is_null($order)) {
            return false;
        }

        $outputFormat = isset($modifiers->format) ?
            $modifiers->format :
            'plain';

        ob_start();
        do_action('woocommerce_email_order_details', $order, false, $outputFormat !== 'html');
        return ob_get_clean();
    }
}
