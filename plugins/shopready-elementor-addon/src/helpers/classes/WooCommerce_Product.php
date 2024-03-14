<?php
namespace Shop_Ready\helpers\classes;

/**
 * WooCommerce Helper Utilities Class
 * Product Details page related methods
 * @since 1.0
 * @author quomodosoft.com
 */

class WooCommerce_Product
{
    /**
     * The singleton instance
     */
    static private $instance = null;
    static private $product = null;

    /**
     * No initialization allowed
     */
    private function __construct()
    {

    }

    /**
     * No cloning allowed
     */
    private function __clone()
    {
    }

    static public function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Product backorder Check
     * @param $product object
     * @return bool  
     */
    public static function wready_product_is_on_backorder($product)
    {


        // To be sure, If we don't get the product object
        if (!is_a($product, 'WC_Product')) {
            // Try to get an instance of the WC_Product object from Post id
            $product = wc_get_product($product);
        }



        if ('onbackorder' === $product->get_stock_status()) {
            return true;
        }
        $qty_in_cart = self::product_in_cart($product->get_id());
        return $product->managing_stock() && $product->backorders_allowed() && ($product->get_stock_quantity() - $qty_in_cart) < 0;
    }

    public static function stock_graph_value($product)
    {

        $progress = 0;

        $fill = '0%';
        $in_stock = $product->get_stock_quantity();
        $total_sales = $product->get_total_sales();

        if (!Wready_Utils::has_enough_stock($product) || !$product->is_in_stock()) {
            $fill = '100%';
            $progress = 100;
        } else {

            $total_product = (int) ($in_stock + $total_sales);
            $fragment = ($total_sales / $total_product);

            $fill = number_format($fragment * 100, 0) . '%';
            $progress = number_format($fragment * 100, 0);
        }


        return $fill;
    }


    /**
     * Cart single product info
     * @since 1.0
     * @param product_id
     * @return mix Product QTY | line_subtotal || line_subtotal_tax || line_total || line_tax | line_tax_data
     */
    public static function product_in_cart($product_id, $col = 'quantity')
    {

        try {

            if (is_null(WC()->cart)) {
                return 0;
            }

            if (WC()->cart->is_empty()) {
                return 0;
            }

            if ($cart_id = WC()->cart->find_product_in_cart(WC()->cart->generate_cart_id($product_id))) {

                $cart = WC()->cart->get_cart();
                return $cart[$cart_id][$col];
            }

            return 0;

        } catch (\Error $e) {

            return 0;
        }

    }

    public static function has_enough_stock($product)
    {
        $product_in_cart = (int) self::product_in_cart($product->get_id());
        return $product->has_enough_stock($product_in_cart);
    }

    public static function date_time_field($field)
    {
        global $thepostid, $post;

        $thepostid = empty($thepostid) ? $post->ID : $thepostid;
        $field['class'] = isset($field['class']) ? $field['class'] : 'checkbox';
        $field['style'] = isset($field['style']) ? $field['style'] : '';
        $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
        $field['value'] = isset($field['value']) ? $field['value'] : esc_html(get_post_meta($thepostid, $field['id'], true));
        $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
        $field['desc_tip'] = isset($field['desc_tip']) ? $field['desc_tip'] : false;


        $custom_attributes = array();

        if (!empty($field['custom_attributes']) && is_array($field['custom_attributes'])) {

            foreach ($field['custom_attributes'] as $attribute => $value) {
                $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($value) . '"';
            }
        }

        echo wp_kses_post('<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '">
                <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>');

        if (!empty($field['description']) && false !== $field['desc_tip']) {
            echo wp_kses_post(wc_help_tip($field['description']));
        }

        echo wp_kses_post('<input type="datetime-local" class="' . esc_attr($field['class']) . '" style="' . wp_kses_post($field['style']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '"  ' . implode(' ', $custom_attributes) . '/> ');

        if (!empty($field['description']) && false === $field['desc_tip']) {
            echo wp_kses_post('<span class="description">' . wp_kses_post($field['description']) . '</span>');
        }

        echo wp_kses_post('</p>');
    }

    public static function time_field($field)
    {
        global $thepostid, $post;

        $thepostid = empty($thepostid) ? $post->ID : $thepostid;
        $field['class'] = isset($field['class']) ? $field['class'] : 'checkbox';
        $field['style'] = isset($field['style']) ? $field['style'] : '';
        $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
        $field['value'] = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);

        $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
        $field['desc_tip'] = isset($field['desc_tip']) ? $field['desc_tip'] : false;


        $custom_attributes = array();

        if (!empty($field['custom_attributes']) && is_array($field['custom_attributes'])) {

            foreach ($field['custom_attributes'] as $attribute => $value) {
                $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($value) . '"';
            }
        }

        echo wp_kses_post('<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '">
                <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>');

        if (!empty($field['description']) && false !== $field['desc_tip']) {
            echo wp_kses_post(wc_help_tip($field['description']));
        }

        echo wp_kses_post('<input type="time" class="' . esc_attr($field['class']) . '" style="' . esc_attr($field['style']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '"  ' . implode(' ', $custom_attributes) . '/> ');

        if (!empty($field['description']) && false === $field['desc_tip']) {
            echo wp_kses_post('<span class="description">' . wp_kses_post($field['description']) . '</span>');
        }

        echo wp_kses_post('</p>');
    }

    public static function text_field($field)
    {
        global $thepostid, $post;

        $thepostid = empty($thepostid) ? $post->ID : $thepostid;
        $field['class'] = isset($field['class']) ? $field['class'] : 'checkbox';
        $field['style'] = isset($field['style']) ? $field['style'] : '';
        $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
        $field['value'] = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);

        $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
        $field['desc_tip'] = isset($field['desc_tip']) ? $field['desc_tip'] : false;


        $custom_attributes = array();

        if (!empty($field['custom_attributes']) && is_array($field['custom_attributes'])) {

            foreach ($field['custom_attributes'] as $attribute => $value) {
                $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($value) . '"';
            }
        }

        echo wp_kses_post('<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '">
                <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>');

        if (!empty($field['description']) && false !== $field['desc_tip']) {
            echo wp_kses_post(wc_help_tip($field['description']));
        }

        echo wp_kses_post('<input type="text" class="' . esc_attr($field['class']) . '" style="' . esc_attr($field['style']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '"  ' . implode(' ', $custom_attributes) . '/> ');

        if (!empty($field['description']) && false === $field['desc_tip']) {
            echo wp_kses_post('<span class="description">' . esc_html($field['description']) . '</span>');
        }

        echo wp_kses_post('</p>');
    }

    public static function date_field($field)
    {
        global $thepostid, $post;

        $thepostid = empty($thepostid) ? $post->ID : $thepostid;
        $field['class'] = isset($field['class']) ? $field['class'] : 'checkbox';
        $field['style'] = isset($field['style']) ? $field['style'] : '';
        $field['wrapper_class'] = isset($field['wrapper_class']) ? $field['wrapper_class'] : '';
        $field['value'] = isset($field['value']) ? $field['value'] : get_post_meta($thepostid, $field['id'], true);

        $field['name'] = isset($field['name']) ? $field['name'] : $field['id'];
        $field['desc_tip'] = isset($field['desc_tip']) ? $field['desc_tip'] : false;


        $custom_attributes = array();

        if (!empty($field['custom_attributes']) && is_array($field['custom_attributes'])) {

            foreach ($field['custom_attributes'] as $attribute => $value) {
                $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($value) . '"';
            }
        }

        echo wp_kses_post('<p class="form-field ' . esc_attr($field['id']) . '_field ' . esc_attr($field['wrapper_class']) . '">
                <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>');

        if (!empty($field['description']) && false !== $field['desc_tip']) {
            echo wp_kses_post(wc_help_tip($field['description']));
        }

        echo wp_kses_post('<input type="date" class="' . esc_attr($field['class']) . '" style="' . wp_kses_post($field['style']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '"  ' . implode(' ', $custom_attributes) . '/> ');

        if (!empty($field['description']) && false === $field['desc_tip']) {
            echo wp_kses_post('<span class="description">' . esc_html($field['description']) . '</span>');
        }

        echo wp_kses_post('</p>');
    }

}