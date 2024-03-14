<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ShippingCity
 */
class IsGuest extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Is guest order', 'wunderauto');
        $this->description = __('Filters on the order being a guest order or not', 'wunderauto');
        $this->objects     = ['order'];

        $this->inputType = 'select';
        $this->operators = [];

        $this->compareValues = [
            ['value' => 'yes', 'label' => __('Yes', 'wunderauto')],
            ['value' => 'no', 'label' => __('No', 'wunderauto')],
        ];
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

        $user        = $order->get_user();
        $actualValue = $user === false ? 'yes' : 'no';

        $this->filterConfig->compare = 'eq';

        return $this->evaluateCompare($actualValue);
    }
}
