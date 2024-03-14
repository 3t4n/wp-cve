<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WunderAuto\Types\Parameters\BaseCustomField;

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
        $this->group       = 'order';
        $this->title       = 'customfield';
        $this->description = __('WooCommerce order custom field', 'wunderauto');
        $this->objects     = ['order'];

        $this->customFieldNameCaption = __('Custom field', 'wunderauto');
        $this->customFieldNameDesc    = __('Custom field name (meta key)', 'wunderauto');

        $this->objectType = 'order';
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        $value = get_metadata('post', $order->get_id(), $modifiers->field, true);
        return $this->formatCustomField($value, $order, $modifiers);
    }
}
