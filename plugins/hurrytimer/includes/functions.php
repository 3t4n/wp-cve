<?php

use Hurrytimer\Campaign;

if (!function_exists('hurryt_wc_stock_statuses')) {
    /**
     * Returns WooCommerce stock status as array.
     *
     * @return array
     */
    function hurryt_wc_stock_statuses() {
        return [
            [
                'name' => __('In stock', 'hurrytimer'),
                'id' => \Hurrytimer\C::WC_IN_STOCK,
            ],
            [
                'name' => __('Out of stock', 'hurrytimer'),
                'id' => \Hurrytimer\C::WC_OUT_OF_STOCK,
            ],
            [
                'name' => __('On backorder', 'hurrytimer'),
                'id' => \Hurrytimer\C::WC_ON_BACKORDER,
            ],
        ];
    }
}

if (!function_exists('hurryt_wc_conditions')) {

    /**
     * Returns supported conditions.
     *
     * @return array
     */
    function hurryt_wc_conditions() {
        return (new \Hurrytimer\ConditionalLogic())->addRule([
            'key' => 'stock_status',
            'name' => __('Stock status', 'hurrytimer'),
            'operators' => ['==', '!='],
            'values' => hurryt_wc_stock_statuses(),
            'type' => 'string',
        ])->addRule([
            'key' => 'stock_quantity',
            'name' => __('Stock quantity', 'hurrytimer'),
            'operators' => ['==', '!=', '<', '>'],
            'values' => [],
            'type' => 'number',
        ])->addRule([
            'key' => 'shipping_class',
            'name' => __('Shipping class', 'hurrytimer'),
            'operators' => ['==', '!='],
            'values' => array_map(function ($class) {
                return [
                    'name' => $class->name,
                    'id' => $class->term_id,
                ];
            }, \WC_Shipping::instance()->get_shipping_classes()),
        ])
            ->addRule([
                'key' => 'on_sale',
                'name' => __('On sale', 'hurrytimer'),
                'operators' => ['=='],
                'values' => [
                    ['name' => 'Yes', 'id' => 'yes'],
                    ['name' => 'No', 'id' => 'no']
                ],
            ])->get();
    }
}

if (!function_exists('hurryt_tz')) {
    /**
     * Return WP timezone string/offset.
     *
     * @return mixed|string|void
     */
    function hurryt_tz() {
        $tz_string = get_option('timezone_string');
        if (!empty($tz_string)) {
            return $tz_string;
        }
        $offset = get_option('gmt_offset');
        $hours = (int)$offset;
        $minutes = abs(($offset - (int)$offset) * 60);
        $offset = sprintf('%+03d:%02d', $hours, $minutes);

        return $offset;
    }
}

if (!function_exists('hurryt_current_page_id')) {

    /**
     * Get current page ID.
     *
     * @return int|mixed
     */
    function hurryt_current_page_id() {
        $object_id = get_queried_object_id();
        if (!hurryt_is_woocommerce_activated()) {
            return $object_id;
        }
        $wc_ids = [
            'shop' => get_option('woocommerce_shop_page_id'),
            'cart' => get_option('woocommerce_cart_page_id'),
            'checkout' => get_option('woocommerce_checkout_page_id'),
            'checkout_pay' => get_option('woocommerce_pay_page_id'),
            'thanks' => get_option('woocommerce_thanks_page_id'),
            'myaccount' => get_option('woocommerce_myaccount_page_id'),
            'edit_address' => get_option('woocommerce_edit_address_page_id'),
            'view_order' => get_option('woocommerce_view_order_page_id'),
            'terms' => get_option('woocommerce_terms_page_id'),
        ];
        if (is_shop()) {
            $object_id = $wc_ids['shop'];
        } elseif (is_account_page()) {
            $object_id = $wc_ids['myaccount'];
        } elseif (is_checkout_pay_page()) {
            $object_id = $wc_ids['checkout_pay'];
        } elseif (is_checkout()) {
            $object_id = $wc_ids['checkout'];
        } elseif (is_cart()) {
            $object_id = $wc_ids['cart'];
        } elseif (is_view_order_page()) {
            $object_id = $wc_ids['view_order'];
        } elseif (is_view_order_page()) {
            $object_id = $wc_ids['view_order'];
        } elseif (is_view_order_page()) {
            $object_id = $wc_ids['view_order'];
        } elseif (is_view_order_page()) {
            $object_id = $wc_ids['view_order'];
        }

        return $object_id;
    }
}

if (!function_exists('hurryt_is_woocommerce_activated')) {
    /**
     * Returns true if WC is active.
     *
     * @return bool
     */
    function hurryt_is_woocommerce_activated() {
        return class_exists('woocommerce');
    }
}

if (!function_exists('hurryt_settings')) {
    /**
     * Returns plugin settings array.
     *
     * @return array
     */
    function hurryt_settings() {
        $defaults = ['disable_actions' => 0];

        return wp_parse_args(get_option('hurryt_settings', []), $defaults);
    }
}

if (!function_exists('hurryt_get_campaign')) {
    /**
     * Create a campaign instance with all props.
     * @param $id
     *
     * @return Campaign
     */
    function hurryt_get_campaign($id) {
        $campaign = new Campaign($id);
        $campaign->loadSettings();

        return $campaign;
    }
}

if (!function_exists('hurryt_is_admin_area')) {
    function hurryt_is_admin_area() {
        global $wp;
        wp_parse_str($wp->query_string, $params);

        return is_admin()
            || is_preview()
            || is_customize_preview()
            || hurrytimer_is_elementor_edit_mode()
            || hurrytimer_is_elementor_preview_mode()
            || !empty($params['preview'])
            || !empty($params['fl_builder']);
    }
}

if (!function_exists('hurryt_parse_campaigns')) {

    /**
     * Parse campaigns shortcodes in the given string content.
     */
    function hurryt_parse_campaigns($content) {
        $pattern = get_shortcode_regex();
        $tag = 'hurrytimer';
        $ids = [];
        if (
            preg_match_all('/' . $pattern . '/s', $content, $matches)
            && array_key_exists(2, $matches)
            && in_array($tag, $matches[2])
        ) {
            foreach ((array)$matches[2] as $key => $value) {
                if ($tag === $value) {
                    $ids[] = shortcode_parse_atts($matches[3][$key]);
                }
            }
            $ids = array_map(function ($id) {
                return isset($id['id']) ? absint($id['id']) : null;
            }, $ids);

            return array_filter($ids);
        }

        return [];
    }
}

if (!function_exists('hurryt_count_active_campaigns')) {
    /**
     * Returns published campaigns.
     */
    function hurryt_count_active_campaigns() {

        /** @var object $count */
        $count = wp_count_posts(HURRYT_POST_TYPE);

        return absint($count->publish);
    }
}


/**
 * Return all WooCommerce coupons.
 * @param string $status
 * @access private
 * @internal
 * @return array
 */
function hurryt_get_wc_coupons($status = 'any') {

    return get_posts([
        'post_type' => 'shop_coupon',
        'post_status' => $status,
        'no_found_rows' => true,
        'numberposts' => 30
    ]);
}


function hurryt_wc_coupon_name($coupon_id) {
    if (!function_exists('wc_get_coupon_types')) {
        return '';
    }

    $type_names = wc_get_coupon_types();

    $coupon_type = get_post_meta($coupon_id, 'discount_type', true);

    return isset($type_names[$coupon_type]) ? $type_names[$coupon_type] : '';
}

/**
 * Return the current timezone string.
 * @return string
 */
function hurryt_current_timezone_string() {
    $current_offset = get_option('gmt_offset');
    $tzstring       = get_option('timezone_string');

    if (false !== strpos($tzstring, 'Etc/GMT')) {
        $tzstring = '';
    }

    if (empty($tzstring)) {
        if (0 == $current_offset) {
            $tzstring = 'UTC+0';
        } elseif ($current_offset < 0) {
            $tzstring = 'UTC' . $current_offset;
        } else {
            $tzstring = 'UTC+' . $current_offset;
        }
    }
    return $tzstring;
}


function hurrytimer_is_elementor_edit_mode() {
    try {
        return class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->editor->is_edit_mode();
    } catch (\Exception $e) {
        return false;
    }
}

function hurrytimer_is_elementor_preview_mode() {
    try {
        return class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->preview->is_preview_mode();
    } catch (\Exception $e) {
        return false;
    }
}


function hurrytimer_allow_unfiltered_html() {
	
	$allow_unfiltered_html = current_user_can('unfiltered_html');
	
	/**
	 * Filters whether the current user is allowed to save unfiltered HTML.
	 *
	 * @param	bool allow_unfiltered_html The result.
	 */
	return apply_filters( 'hurrytimer/allow_unfiltered_html', $allow_unfiltered_html );
}