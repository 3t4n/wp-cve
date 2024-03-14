<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ProductCategories
 */
class ProductCategories extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order products categories', 'wunderauto');
        $this->description = __('Filters based on order products categories', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators   = $this->multiSetOperators();
        $this->inputType   = 'ajaxmultiselect';
        $this->ajaxAction  = 'wa_search_wooproduct_cats';
        $this->nonceName   = 'search_tax_nonce';
        $this->placeholder = 'Search product categories';
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

            $terms = wp_get_post_terms($product->get_id(), 'product_cat');
            if ($terms instanceof \WP_Error) {
                continue;
            }

            $tags        = array_map(
                function ($term) {
                    return trim($term->name);
                },
                $terms
            );
            $actualValue = array_merge($actualValue, $tags);
        }

        return $this->evaluateCompare($actualValue);
    }
}
