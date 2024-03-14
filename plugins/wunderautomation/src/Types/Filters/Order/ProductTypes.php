<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ProductTypes
 */
class ProductTypes extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order product types', 'wunderauto');
        $this->description = __('Filter WooCommerce orders product types', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->multiSetOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $productTypes = wc_get_product_types();
        foreach ($productTypes as $key => $type) {
            $this->compareValues[] = ['value' => $key, 'label' => $type];
        }
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

        $actualValue = [];
        foreach ($order->get_items() as $item) {
            if (!($item instanceof \WC_Order_Item_Product)) {
                continue;
            }

            $product = $item->get_product();
            if (!($product instanceof \WC_Product)) {
                continue;
            }

            $actualValue[] = $product->get_type();
        }

        return $this->evaluateCompare($actualValue);
    }
}
