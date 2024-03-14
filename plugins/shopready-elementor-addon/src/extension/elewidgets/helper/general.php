<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * get widgets class list
 *
 * @since 1.0
 * @return array
 */

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;



if (!function_exists('shop_ready_widget_locate_tpl')) {

    /**
     * Locate template.
     *
     * Locate the called template.
     * Search Order:
     * 1. /themes/theme/woo-ready/widgets/$template_name
     * 2. /templates-part/$template_name.
     * @param   string  $template_name          Template to load.
     * @param   string  $string $template_path  Path to templates.
     * @param   string  $default_path           Default path to template files.
     * @return  string                          Path to the template file.
     */
    function shop_ready_widget_locate_tpl($template_name, $template_path = '', $default_path = '')
    {

        if (!$template_path):
            $template_path = 'shop-ready/widgets';
        endif;

        if (!$default_path):
            $default_path = SHOP_READY_ELEWIDGET_PATH . 'widgets/';
        endif;

        $template = locate_template([
            $template_path . $template_name,
            $template_name,
        ]);

        if (!$template):
            $template = $default_path . $template_name;
        endif;

        return apply_filters('shop_ready_widget_locate_tpl', $template, $template_name, $template_path, $default_path);

    }
}

if (!function_exists('shop_ready_widget_template_part')) {

    /**
     * Search for the template and include the file.
     * @param string  $template_name          Template to load.
     * @param array   $args                   Args passed for the template file.
     * @param string  $string $template_path  Path to templates.
     * @param string  $default_path           Default path to template files.
     */
    function shop_ready_widget_template_part($template_name, $args = [], $tempate_path = '', $default_path = '')
    {

        if (is_array($args) && isset($args)):
            extract($args);
        endif;

        $template_file = shop_ready_widget_locate_tpl($template_name, $tempate_path, $default_path);

        if (!file_exists($template_file)):
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.0.0');
            return;

        endif;

        include $template_file;

    }
}

if (!function_exists('shop_ready_widget_base_locate_tpl')) {

    /**
     * Locate template.
     *
     * Locate the called template.
     * Search Order:
     * 1. /themes/theme/woo-ready/widgets/$template_name
     * 2. /templates-part/$template_name.
     * @param   string  $template_name          Template to load.
     * @param   string  $string $template_path  Path to templates.
     * @param   string  $default_path           Default path to template files.
     * @return  string                          Path to the template file.
     */
    function shop_ready_widget_base_locate_tpl($template_name, $template_path = '', $default_path = '')
    {

        if (!$template_path):
            $template_path = 'shop-ready/widgets';
        endif;

        if (!$default_path):
            $default_path = SHOP_READY_ELEWIDGET_PATH;
        endif;

        $template = locate_template([
            $template_path . $template_name,
            $template_name,
        ]);

        if (!$template):
            $template = $default_path . $template_name;
        endif;

        return apply_filters('shop_ready_widget_base_locate_tpl', $template, $template_name, $template_path, $default_path);

    }
}

if (!function_exists('shop_ready_widget_base_template_part')) {

    /**
     * Search for the template and include the file.
     * @param string  $template_name          Template to load.
     * @param array   $args                   Args passed for the template file.
     * @param string  $string $template_path  Path to templates.
     * @param string  $default_path           Default path to template files.
     */
    function shop_ready_widget_base_template_part($template_name, $args = [], $tempate_path = '', $default_path = '')
    {

        if (is_array($args) && isset($args)):
            extract($args);
        endif;

        $template_file = shop_ready_widget_base_locate_tpl($template_name, $tempate_path, $default_path);

        if (!file_exists($template_file)):
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.0.0');
            return;

        endif;

        include $template_file;

    }
}

if (!function_exists('shop_ready_sr_wc_quantity_input')) {

    /**
     * Output the quantity input for add to cart forms.
     *
     * @param  array           $args Args for the input.
     * @param  WC_Product|null $product Product.
     * @param  boolean         $echo Whether to return or echo|string.
     *
     * @return string
     */
    function shop_ready_sr_wc_quantity_input($args = [], $product = null, $echo = true)
    {

        if (is_null($product)) {
            $product = $GLOBALS['product'];
        }

        $defaults = [
            'input_id' => uniqid('quantity_'),
            'input_name' => 'quantity',
            'input_value' => '1',
            'classes' => apply_filters('woocommerce_quantity_input_classes', ['input-text', 'qty', 'text'], $product),
            'max_value' => apply_filters('woocommerce_quantity_input_max', -1, $product),
            'min_value' => apply_filters('woocommerce_quantity_input_min', 0, $product),
            'step' => apply_filters('woocommerce_quantity_input_step', 1, $product),
            'pattern' => apply_filters('woocommerce_quantity_input_pattern', has_filter('woocommerce_stock_amount', 'intval') ? '[0-9]*' : ''),
            'inputmode' => apply_filters('woocommerce_quantity_input_inputmode', has_filter('woocommerce_stock_amount', 'intval') ? 'numeric' : ''),
            'product_name' => $product ? $product->get_title() : '',
            'placeholder' => apply_filters('woocommerce_quantity_input_placeholder', '', $product),
            'item_key' => '',
        ];

        $args = apply_filters('woocommerce_quantity_input_args', wp_parse_args($args, $defaults), $product);

        // Apply sanity to min/max args - min cannot be lower than 0.
        $args['min_value'] = max($args['min_value'], 0);
        $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

        // Max cannot be lower than min if defined.
        if ('' !== $args['max_value'] && $args['max_value'] < $args['min_value']) {
            $args['max_value'] = $args['min_value'];
        }

        ob_start();
        shop_ready_widget_base_template_part('templates/global/quantity-input.php', $args);

        if ($echo) {
            echo ob_get_clean();
        } else {
            return ob_get_clean();
        }
    }
}

function shop_ready_sr_get_cart_remove_url($cart_item_key)
{
    $cart_page_url = wc_get_checkout_url();
    return $cart_page_url ? wp_nonce_url(add_query_arg('remove_item', $cart_item_key, $cart_page_url), 'woocommerce-cart') : '';
}

/**
 * Get shipping methods.
 */
function shop_ready_sr_cart_totals_shipping_html($settings = [])
{
    $packages = WC()->shipping()->get_packages();
    $first = true;

    foreach ($packages as $i => $package) {
        $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
        $product_names = [];

        if (count($packages) > 1) {
            foreach ($package['contents'] as $item_id => $values) {
                $product_names[$item_id] = $values['data']->get_name() . ' &times;' . $values['quantity'];
            }
            $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
        }

        shop_ready_widget_template_part(
            'checkout/template-part/cart-shipping.php',
            [
                'package' => $package,
                'available_methods' => $package['rates'],
                'show_package_details' => count($packages) > 1,
                'show_shipping_calculator' => is_cart() && apply_filters('woocommerce_shipping_show_shipping_calculator', $first, $i, $package),
                'package_details' => implode(', ', $product_names),

                'package_name' => apply_filters('woocommerce_shipping_package_name', (($i + 1) > 1) ? sprintf(_x('Shipping %d', 'shipping packages', 'shopready-elementor-addon'), ($i + 1)) : _x('Shipping', 'shipping packages', 'shopready-elementor-addon'), $i, $package),
                'index' => $i,
                'chosen_method' => $chosen_method,
                'formatted_destination' => WC()->countries->get_formatted_address($package['destination'], ', '),
                'has_calculated_shipping' => WC()->customer->has_calculated_shipping(),
                'settings' => $settings,
            ]
        );

        $first = false;
    }

}

if (!function_exists('shop_ready_get_terms_list')):

    function shop_ready_get_terms_list($taxonomy = 'category', $key = 'term_id')
    {
        $options = [];
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);

        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->{$key}] = $term->name;
            }
        }

        return $options;
    }

endif;

if (!function_exists('shop_ready_attr_to_shortcide')):

    function shop_ready_attr_to_shortcide(array $attr_array)
    {
        $html_attr = '';

        foreach ($attr_array as $attr_name => $attr_val) {
            if (($attr_val === false) || empty($attr_val)) {
                continue;
            }

            if (is_array($attr_val)) {
                $html_attr .= $attr_name . '="' . implode(",", $attr_val) . '" ';
            } else {
                $html_attr .= $attr_name . '="' . $attr_val . '" ';
            }

        }

        return $html_attr;
    }

endif;

if (!function_exists('shop_ready_get_product_sku')):

    function shop_ready_get_product_sku()
    {

        $query = new \WC_Product_Query([
            //'type' => "simple",
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        $products = $query->get_products();

        $product_skus = [];

        foreach ($products as $product) {
            if ($product->get_sku()) {
                $product_skus[$product->get_sku()] = $product->get_sku();
            } else {
                continue;
            }

        }
        return $product_skus;
    }

endif;

if (!function_exists('shop_ready_get_product_attributes')):

    function shop_ready_get_product_attributes()
    {

        $products_attributes = [];
        $products_attribute = wc_get_attribute_taxonomies();
        foreach ($products_attribute as $attribute) {
            $products_attributes[$attribute->attribute_name] = $attribute->attribute_label;

        }

        return $products_attributes;

    }

endif;

if (!function_exists('shop_ready_get_product_attribute_terms')):

    function shop_ready_get_product_attribute_terms($attribute_name)
    {

        $products_attribute_terms = '';
        $products_attribute_terms = get_terms('pa_' . $attribute_name);
        return $products_attribute_terms;

    }

endif;

if (!function_exists('woo_ready_get_product_currency_options')):

    function woo_ready_get_product_currency_options()
    {

        $currency_code_options = get_woocommerce_currencies();

        foreach ($currency_code_options as $code => $name) {
            $currency_code_options[$code] = $name . ' (' . get_woocommerce_currency_symbol($code) . ')';
        }

        return $currency_code_options;
    }

endif;


if (!function_exists('shop_ready_disable_currency_on_checkout')):

    function shop_ready_disable_currency_on_checkout()
    {

        return ('yes' === WReady_Helper::get_global_setting('woo_ready_disable_currency_in_checkout') && is_checkout());

    }

endif;

if (!function_exists('shop_ready_disable_currency_on_cart')):

    function shop_ready_disable_currency_on_cart()
    {

        return ('yes' === WReady_Helper::get_global_setting('woo_ready_disable_currency_in_cart') && is_cart());

    }

endif;