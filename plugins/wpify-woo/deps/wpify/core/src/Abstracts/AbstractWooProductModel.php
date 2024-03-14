<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\ComposePress\Core\Exception\Plugin;
use Exception;
use WC_Order_Item_Fee;
use WC_Order_Item_Product;
use WC_Order_Item_Shipping;
use WC_Product;
use WpifyWooDeps\Wpify\Core\Interfaces\CustomFieldsFactoryInterface;
use WpifyWooDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractWooProductModel extends AbstractPostTypeModel implements PostTypeModelInterface
{
    /**
     * @var \WC_Product
     */
    private $wc_product;
    private $weight;
    /**
     * @return mixed
     */
    public function __construct($product, $post_type)
    {
        $this->post_type = $post_type;
        if (\is_a($product, WC_Product::class)) {
            $this->wc_product = $product;
        } elseif (\is_numeric($product)) {
            $this->wc_product = wc_get_product($product);
        }
        if ($this->wc_product) {
            $this->id = $this->wc_product->get_id();
        }
    }
    public function get_post()
    {
        return get_post($this->id);
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
     * @param string $unit
     *
     * @return mixed
     */
    public function get_weight(string $unit = 'kg')
    {
        if ($this->weight) {
            return $this->weight;
        }
        $wc_weight_unit = get_option('woocommerce_weight_unit');
        $this->weight = $this->get_wc_product()->get_weight();
        if ($wc_weight_unit === 'g' && $unit === 'kg') {
            $this->weight = $this->weight / 1000;
        }
        if ($wc_weight_unit === 'kg' && $unit === 'g') {
            $this->weight = $this->weight * 1000;
        }
        return $this->weight;
    }
    /**
     * @return mixed
     */
    public function get_wc_product()
    {
        return $this->wc_product;
    }
}
