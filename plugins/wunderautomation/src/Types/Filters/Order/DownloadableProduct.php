<?php

namespace WunderAuto\Types\Filters\Order;

use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class DownloadableProduct
 */
class DownloadableProduct extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Downloadable product', 'wunderauto');
        $this->description = __('Filter WooCommerce based on downloadable products', 'wunderauto');
        $this->objects     = ['order'];

        $this->inputType = 'select';
        $this->operators = [];

        $this->compareValues = [
            ['value' => 'any', 'label' => __('One or more downloadable products in order', 'wunderauto')],
            ['value' => 'none', 'label' => __('No downloadable products in order', 'wunderauto')],
            ['value' => 'all', 'label' => __('Only downloadable products in order', 'wunderauto')],
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

        $total        = 0;
        $downloadable = 0;
        $items        = $order->get_items();

        foreach ($items as $id => $item) {
            if (!($item instanceof \WC_Order_Item_Product)) {
                continue;
            }

            $total++;
            $product = $item->get_product();
            if ($product instanceof \WC_Product) {
                $downloadable += $product->is_downloadable() ? 1 : 0;
            }
        }

        if ($this->filterConfig->value == 'none') {
            return $downloadable == 0;
        }

        if ($this->filterConfig->value == 'any') {
            return $downloadable > 0;
        }

        if ($this->filterConfig->value == 'all') {
            return $total > 0 && $downloadable == $total;
        }

        return false;
    }
}
