<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class VirtualProduct
 */
class VirtualProduct extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Virtual product', 'wunderauto');
        $this->description = __('Filter WooCommerce order based on if it contains virtual products', 'wunderauto');
        $this->objects     = ['order'];

        $this->inputType = 'select';
        $this->operators = [];

        $this->compareValues = [
            ['value' => 'none', 'label' => __('No virtual products in order', 'wunderauto')],
            ['value' => 'any', 'label' => __('One or more virtual products in order', 'wunderauto')],
            ['value' => 'all', 'label' => __('Only virtual products in order', 'wunderauto')],
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

        $total   = 0;
        $virtual = 0;
        $items   = $order->get_items();

        foreach ($items as $id => $item) {
            $total++;

            if (!($item instanceof \WC_Order_Item_Product)) {
                continue;
            }

            $product = $item->get_product();
            if (!($product instanceof \WC_Product)) {
                continue;
            }

            $virtual += $product->is_virtual() ? 1 : 0;
        }

        if ($this->filterConfig->value == 'none') {
            return $virtual == 0;
        }

        if ($this->filterConfig->value == 'any') {
            return $virtual > 0;
        }

        if ($this->filterConfig->value == 'all') {
            return $total > 0 && $virtual == $total;
        }

        return false;
    }
}
