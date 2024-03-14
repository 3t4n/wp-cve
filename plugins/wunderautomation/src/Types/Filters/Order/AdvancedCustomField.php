<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseAdvancedCustomField;

/**
 * Class AdvancedCustomField
 */
class AdvancedCustomField extends BaseAdvancedCustomField
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Advanced Custom field', 'wunderauto');
        $this->description = __('Filter object based on value of an order ACF custom field.', 'wunderauto');
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

        $id = $order->get_id();

        if ((int)$id < 1 || empty($this->filterConfig->field)) {
            return false;
        }

        $actualValue = get_field($this->filterConfig->field, $id);
        return $this->evaluateCompare($actualValue);
    }
}
