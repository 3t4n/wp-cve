<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Products
 */
class Products extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order products', 'wunderauto');
        $this->description = __('Filters based on order products', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators   = $this->multiSetOperators();
        $this->inputType   = 'ajaxmultiselect';
        $this->ajaxAction  = 'wa_search_wooproducts';
        $this->nonceName   = 'search_products_nonce';
        $this->placeholder = 'Search products';
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

        $items = $order->get_items();

        $actualValue = [];
        foreach ($items as $id => $item) {
            if (!($item instanceof \WC_Order_Item_Product)) {
                continue;
            }

            $product = $item->get_product();
            if (!($product instanceof \WC_Product)) {
                continue;
            }

            $actualValue[] = $product->get_id();
        }

        return $this->evaluateCompare($actualValue);
    }
}
