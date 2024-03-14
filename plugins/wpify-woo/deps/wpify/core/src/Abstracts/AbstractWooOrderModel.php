<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use Exception;
use ReflectionException;
use WC_Order_Item_Fee;
use WC_Order_Item_Product;
use WC_Order_Item_Shipping;
use WC_Order;
use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
use WpifyWooDeps\Wpify\Core\Interfaces\CustomFieldsFactoryInterface;
use WpifyWooDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
use WpifyWooDeps\Wpify\Core\Models\WooOrderItemModel;
use WpifyWooDeps\Wpify\Core\Models\WooOrderItemProductModel;
use WpifyWooDeps\Wpify\Core\Models\WooOrderItemShippingModel;
/**
 * @package Wpify\Core
 */
abstract class AbstractWooOrderModel extends AbstractComponent implements PostTypeModelInterface
{
    /**
     * Disable auto init by default
     * @var bool
     */
    protected $auto_init = \false;
    /**
     * @var int
     */
    private $id;
    /**
     * @var AbstractPostType $post_type
     */
    private $post_type;
    private $wc_order;
    private $weight;
    /**
     * @return mixed
     */
    public function __construct($order, $post_type)
    {
        $this->post_type = $post_type;
        if (\is_a($order, WC_Order::class)) {
            $this->wc_order = $order;
        } elseif (\is_numeric($order)) {
            $this->wc_order = wc_get_order($order);
        }
        if ($this->wc_order) {
            $this->id = $this->wc_order->get_id();
        }
    }
    public function get_post()
    {
        return get_post($this->id);
    }
    /**
     * Get order items
     * @return WooOrderItemModel[]
     * @throws PluginException
     * @throws ReflectionException
     */
    public function get_items() : array
    {
        $items = array();
        $line_items = $this->get_line_items();
        if (!empty($line_items)) {
            $items = \array_merge($items, $line_items);
        }
        $shipping = $this->get_shipping_items();
        if (!empty($shipping)) {
            $items = \array_merge($items, $shipping);
        }
        $fees = $this->get_fee_items();
        if (!empty($fees)) {
            $items = \array_merge($items, $fees);
        }
        return $items;
    }
    /**
     * Get order Line items
     * @return array
     * @throws PluginException
     * @throws ReflectionException
     */
    public function get_line_items() : array
    {
        $items = array();
        foreach ($this->wc_order->get_items() as $key => $item) {
            /** @var $item WC_Order_Item_Product */
            /** @var WooOrderItemProductModel $order_item */
            $order_item = $this->plugin->create_component(WooOrderItemProductModel::class, ['item' => $item]);
            $order_item->init();
            $items[] = $order_item;
        }
        return $items;
    }
    /**
     * Get order shipping items
     * @return array
     * @throws ReflectionException
     * @throws PluginException
     */
    public function get_shipping_items() : array
    {
        $items = array();
        foreach ($this->get_wc_order()->get_items('shipping') as $key => $item) {
            /** @var $item WC_Order_Item_Shipping */
            /** @var WooOrderItemModel $order_item */
            $order_item = $this->plugin->create_component(WooOrderItemShippingModel::class, ['item' => $item]);
            $order_item->init();
            $items[] = $order_item;
        }
        return $items;
    }
    /**
     * @return mixed
     */
    public function get_wc_order()
    {
        return $this->wc_order;
    }
    /**
     * Get order fee items
     * @return array
     * @throws PluginException
     * @throws ReflectionException
     */
    public function get_fee_items() : array
    {
        /** @var $item WC_Order_Item_Fee */
        $items = array();
        foreach ($this->get_wc_order()->get_items('fee') as $key => $item) {
            /** @var WooOrderItemModel $order_item */
            $order_item = $this->plugin->create_component(WooOrderItemModel::class, ['item' => $item]);
            $order_item->init();
            $items[] = $order_item;
        }
        return $items;
    }
    /**
     * Get custom field value
     *
     * @param $field
     *
     * @return mixed
     * @throws Exception
     */
    public function get_custom_field($field)
    {
        $factory = $this->get_custom_fields_factory();
        if (!$factory) {
            throw new Exception(__('You need to set custom fields factory to register and retrieve custom fields', 'wpify'));
        }
        return $factory->get_field($this, $field);
    }
    /**
     * @return CustomFieldsFactoryInterface|false
     */
    private function get_custom_fields_factory()
    {
        return $this->post_type->get_custom_fields_factory();
    }
    /**
     * @return int|null
     */
    public function get_id()
    {
        return $this->id ?? null;
    }
    /**
     * Get custom field value
     *
     * @param $field
     * @param $value
     *
     * @return mixed
     * @throws Exception
     */
    public function save_custom_field($field, $value)
    {
        $factory = $this->get_custom_fields_factory();
        if (!$factory) {
            throw new Exception(__('You need to set custom fields factory to register and save custom fields', 'wpify'));
        }
        return $factory->save_field($this, $field, $value);
    }
    /**
     * Get Post type for the current model
     * @return AbstractPostType
     */
    public function get_post_type() : AbstractPostType
    {
        return $this->post_type;
    }
    /**
     * @param AbstractPostType $post_type
     */
    public function set_post_type(AbstractPostType $post_type) : void
    {
        $this->post_type = $post_type;
    }
    /**
     * @return mixed
     */
    public function get_weight(string $unit = 'kg')
    {
        if ($this->weight) {
            return $this->weight;
        }
        $wc_weight_unit = get_option('woocommerce_weight_unit');
        $this->weight = 0;
        foreach ($this->get_line_items() as $item) {
            /** @var $item WooOrderItemProductModel */
            $prod = $item->get_product();
            if (!empty($prod) && \method_exists($prod, 'get_weight')) {
                if ($prod->get_weight()) {
                    $this->weight += $prod->get_weight() * $item->get_quantity();
                }
            }
        }
        if ($wc_weight_unit === 'g' && $unit === 'kg') {
            $this->weight = $this->weight / 1000;
        }
        if ($wc_weight_unit === 'kg' && $unit === 'g') {
            $this->weight = $this->weight * 1000;
        }
        return $this->weight;
    }
    /**
     * @param string|[] $shipping_method_id Expects ID in method_id:instance_id format
     */
    public function has_shipping_method($shipping_method_ids)
    {
        $methods = [];
        foreach ($this->get_shipping_items() as $item) {
            $methods[] = \sprintf('%s:%s', $item->get_method_id(), $item->get_instance_id());
        }
        if (\is_array($shipping_method_ids)) {
            $found = \false;
            foreach ($methods as $method) {
                if (\in_array($method, $shipping_method_ids)) {
                    $found = \true;
                    break;
                }
            }
            return $found;
        }
        return \in_array($shipping_method_ids, $methods);
    }
}
