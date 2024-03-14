<?php

namespace WpifyWooDeps\Wpify\Core\Models;

use WC_Order_Item_Product;
use WC_Order_Item_Shipping;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractWooOrderItemModel;
/**
 * @package Wpify\Core
 * @property WC_Order_Item_Shipping $wc_order_item
 */
class WooOrderItemShippingModel extends AbstractWooOrderItemModel
{
    private $method_id;
    private $instance_id;
    private $method_title;
    public function __construct(WC_Order_Item_Shipping $item)
    {
        $this->set_wc_order_item($item);
    }
    public function setup()
    {
        parent::setup();
        $this->method_id = $this->wc_order_item->get_method_id();
        $this->instance_id = $this->wc_order_item->get_instance_id();
        $this->method_title = $this->wc_order_item->get_method_title();
    }
    /**
     * @return mixed
     */
    public function get_method_id()
    {
        return $this->method_id;
    }
    /**
     * @return mixed
     */
    public function get_instance_id()
    {
        return $this->instance_id;
    }
    /**
     * @return mixed
     */
    public function get_method_title()
    {
        return $this->method_title;
    }
}
