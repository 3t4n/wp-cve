<?php

namespace ShopWP\Render\Cart;

use ShopWP\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Defaults
{
    public $plugin_settings;
    public $Render_Attributes;

    public function __construct($plugin_settings, $Render_Attributes)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Render_Attributes = $Render_Attributes;
    }

    public function cart_icon($attrs = []) {
        return array_replace([
            'icon' => $this->Render_Attributes->attr($attrs, 'icon', false),
            'type' => $this->Render_Attributes->attr($attrs, 'type', 'inline'),
             'show_counter' => $this->Render_Attributes->attr(
                $attrs,
                'show_counter',
                true
            ),
        ], $attrs);
    }

    public function cart($attrs = []) {
        return array_replace([], $attrs);
    }

    public function all_attrs($attrs = [])
    {
        // TODO: Remove below hook in 6.0
        // TODO: Remove the below isset calls in 6.0
        $settings = apply_filters('shopwp_cart_default_payload_settings', [
            'icon' => $this->Render_Attributes->attr($attrs, 'icon', false),
            'type' => $this->Render_Attributes->attr($attrs, 'type', 'inline'),
            'show_counter' => $this->Render_Attributes->attr(
                $attrs,
                'show_counter',
                true
            ),
            'data_type' => $this->Render_Attributes->attr(
                $attrs,
                'data_type',
                false
            ),
            'icon_color' => $this->Render_Attributes->attr(
                $attrs,
                'icon_color',
                isset($this->plugin_settings['general']['cart_icon_color']) ? $this->plugin_settings['general']['cart_icon_color'] : '#000'
            ),
            'background_color' => $this->Render_Attributes->attr(
                $attrs,
                'icon_color',
                isset($this->plugin_settings['general']['cart_icon_background_color']) ? $this->plugin_settings['general']['cart_icon_background_color'] : '#000'
            ),
            'counter_background_color' => $this->Render_Attributes->attr(
                $attrs,
                'icon_color',
                isset($this->plugin_settings['general']['cart_counter_background_color']) ? $this->plugin_settings['general']['cart_counter_background_color'] : '#6ae06a'
            ),
            'counter_text_color' => $this->Render_Attributes->attr(
                $attrs,
                'icon_color',
                isset($this->plugin_settings['general']['cart_counter_text_color']) ? $this->plugin_settings['general']['cart_counter_text_color'] : '#FFF'
            ),
            'show_inventory_levels' => $this->Render_Attributes->attr(
                $attrs,
                'show_inventory_levels',
                true
            ),
            'left_in_stock_threshold' => $this->Render_Attributes->attr($attrs, 'left_in_stock_threshold', 10),
            'cart_title' => $this->Render_Attributes->attr(
                $attrs,
                'cart_title',
                __('Shopping cart', 'shopwp')
            ),
            'checkout_text' => $this->Render_Attributes->attr(
                $attrs,
                'checkout_text',
                __('Begin checkout', 'shopwp')
            ),
            'updating_text' => $this->Render_Attributes->attr(
                $attrs,
                'updating_text',
                __('Updating cart', 'shopwp')
            ),
            'checkout_failed_message' => $this->Render_Attributes->attr(
                $attrs,
                'checkout_failed_message',
                __('Unable to checkout. Please reload the page and try again.', 'shopwp')
            ),
            'lineitem_remove_text' => $this->Render_Attributes->attr(
                $attrs,
                'lineitem_remove_text',
                __('Remove', 'shopwp')
            ),
            'lineitem_sale_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'lineitem_sale_label_text',
                __('Sale!', 'shopwp')
            ),
            'lineitems_disable_link' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_disable_link',
                false
            ),
            'lineitems_link_target' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_link_target',
                '_self'
            ),
            'lineitems_max_quantity' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_max_quantity',
                false
            ),
            'lineitems_min_quantity' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_min_quantity',
                false
            ),
            'lineitems_quantity_step' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_quantity_step',
                false
            ),
            'notes_label' => $this->Render_Attributes->attr(
                $attrs,
                'notes_label',
                __('Checkout notes', 'shopwp')
            ),
            'notes_placeholder' => $this->Render_Attributes->attr(
                $attrs,
                'notes_placeholder',
                $this->plugin_settings['general'][
                    'cart_notes_placeholder'
                ]
            ),
            'empty_cart_text' => $this->Render_Attributes->attr(
                $attrs,
                'empty_cart_text',
                __('Your cart is empty', 'shopwp')
            ),
            'subtotal_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'subtotal_label_text',
                __('Subtotal:', 'shopwp')
            ),
            'show_cart_close_icon' => $this->Render_Attributes->attr(
                $attrs,
                'show_cart_close_icon',
                true
            ),
            'show_cart_title' => $this->Render_Attributes->attr(
                $attrs,
                'show_cart_title',
                true
            ),
        ]);

        return apply_filters('shopwp_cart_default_settings', $settings);
    }
}
