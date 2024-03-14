<?php

namespace WpifyWooDeps\Wpify\Core\Models;

use WC_Order_Item_Product;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractWooOrderItemModel;
/**
 * @package Wpify\Core
 * @property WC_Order_Item_Product $wc_order_item
 */
class WooOrderItemProductModel extends AbstractWooOrderItemModel
{
    private $product_id;
    private $product;
    private $variation_id;
    private $sku;
    public function __construct(WC_Order_Item_Product $item)
    {
        $this->set_wc_order_item($item);
    }
    public function setup()
    {
        parent::setup();
        $this->product_id = $this->wc_order_item->get_product_id();
        $this->variation_id = $this->wc_order_item->get_variation_id();
    }
    /**
     * @return mixed
     */
    public function get_product_id()
    {
        return $this->product_id;
    }
    /**
     * @return mixed
     */
    public function get_variation_id()
    {
        return $this->variation_id;
    }
    public function get_product()
    {
        if ($this->product) {
            return $this->product;
        }
        $this->product = $this->wc_order_item->get_product();
        return $this->product;
    }
    /**
     * @return mixed
     */
    public function get_sku()
    {
        if ($this->sku) {
            return $this->sku;
        }
        if (!$this->product) {
            $this->product = $this->get_product();
        }
        return $this->product->get_sku();
    }
}
