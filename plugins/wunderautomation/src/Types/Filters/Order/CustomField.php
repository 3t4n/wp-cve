<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseCustomField;

/**
 * Class CustomField
 */
class CustomField extends BaseCustomField
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Custom field', 'wunderauto');
        $this->description = __('Filter object based on value of WooCommerce order custom field.', 'wunderauto');
        $this->objects     = ['order'];
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $order = $this->getObject();
        if (!($order instanceof \WC_Order)) {
            return false;
        }

        $actualValue = get_metadata('post', $order->get_id(), $this->filterConfig->field, true);

        return $this->evaluateCompare($actualValue);
    }
}
