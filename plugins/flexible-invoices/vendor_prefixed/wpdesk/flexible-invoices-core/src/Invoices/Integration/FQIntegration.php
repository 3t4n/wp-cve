<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use WC_Order_Item;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin;
/**
 * Integration with Flexible Quantity
 */
class FQIntegration
{
    /**
     * @var WC_Order_Item
     */
    private $order_item;
    /**
     * @var array
     */
    private $item_meta;
    /**
     * @var string
     */
    private $domain;
    public function __construct(\WC_Order_Item $item)
    {
        $this->order_item = $item;
        $this->item_meta = !empty($this->order_item->get_meta('_fq_measurement_data')) ? (array) $item->get_meta('_fq_measurement_data') : [];
        $this->domain = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::is_fq_pro_addon_enabled() ? 'flexible-quantity' : 'flexible-quantity-measurement-price-calculator-for-woocommerce';
    }
    public function get_item_unit($default = 'szt') : string
    {
        if (empty($this->item_meta)) {
            return $default;
        }
        $measurement_unit = $this->item_meta['_measurement_needed_unit'] ?? '';
        $measurement_needed = $this->item_meta['_measurement_needed'] ?? 0;
        $measurement_qty = $this->item_meta['_quantity'] ?? 0;
        $fq_quantity = (float) $measurement_needed * (float) $measurement_qty;
        if ($fq_quantity === $this->order_item->get_quantity() && $measurement_unit) {
            return \__($measurement_unit, $this->domain);
        }
        return $default;
    }
}
