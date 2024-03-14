<?php

class Express_Checkout_Utility {

    public static function is_ssl_enable() {
        if (is_ssl() || get_option('woocommerce_force_ssl_checkout') == 'yes') {
            return true;
        } else {
            return false;
        }
    }

    public function express_checkout_is_available() {
        $is_enable = $this->express_checkout_get_option('enabled');
        if (isset($is_enable) && $is_enable == 'yes') {
            return true;
        } else {
            return false;
        }
    }

    public function express_checkout_get_option($option_name) {
        $woocommerce_express_checkout_settings = get_option('woocommerce_express_checkout_settings');
        if (isset($woocommerce_express_checkout_settings[$option_name]) && !empty($woocommerce_express_checkout_settings[$option_name])) {
            return $woocommerce_express_checkout_settings[$option_name];
        } else {
            return false;
        }
    }

    public function ex_express_checkout_button() {
        global $post, $product;
        $display_style = 'display: none;';
        if (!$this->express_checkout_is_available()) {
            return;
        }
        if (is_checkout()) {
            if ('no' == $this->express_checkout_get_option('show_on_checkout')) {
                return;
            }
        }
        if (is_cart()) {
            if ('no' == $this->express_checkout_get_option('show_on_cart_page')) {
                return;
            }
        }
        if (is_product()) {
            if ($this->ex_get_product_type($post->ID)) {
                $display_style = 'display: block;';
            }
            if ('no' == $this->express_checkout_get_option('show_on_product_page')) {
                return;
            }
        }

        if (WC()->cart->needs_payment() || is_product()) {
            $_product = wc_get_product($post->ID);

            if ($_product == true) {
                if ($_product->is_type('simple') && (version_compare(WC_VERSION, '3.0', '<') == false)) {
                    ?>
                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" />
                    <?php
                }
            }
            $ex_button_output = '';
            $ex_button_link = $this->ex_get_checkout_url('ex_set_express_checkout');
            if ('image' === $this->express_checkout_get_option('checkout_button_style')) {
                if ($this->ex_get_locale() == 'en_US') {
                    $image_path = EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/images/dynamic-image/' . $this->ex_get_locale() . '.png';
                } else {
                    $image_path = EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/images/dynamic-image/' . $this->ex_get_locale() . '.gif';
                }

                $ex_button_output .= '<div class="express_checkout_button"><a href="' . $ex_button_link . '" class="single_add_to_cart_button paypal_checkout_button paypal-express-checkout-button ex_clearfix">';
                $ex_button_output .= '<input type="image" class="single_add_to_cart_button" src="' . $image_path . '" style="clear: both; margin: 3px 0px 6px 0; border: none; padding: 0;" align="top" alt="' . __('Check out with PayPal', 'express-checkout') . '" />';
                $ex_button_output .= "</a></div>";
            } else {
                $ex_button_output .= '<a class="single_add_to_cart_button paypal_checkout_button paypal-express-checkout-button button alt" href="' . $ex_button_link . '">' . __('Check out with PayPal &rarr;', 'express-checkout') . '</a>';
            }
            if ($this->ex_show_paypal_credit()) {
                $ex_button_output .= '<div class="express_checkout_button_cradit_card"><a href="' . esc_url(add_query_arg('use_bml', 'true', $ex_button_link)) . '" class="single_add_to_cart_button paypal_checkout_button ex_clearfix">';
                $ex_button_output .= '<input type="image" data-action="' . esc_url($ex_button_link) . '"  class="single_add_to_cart_button" src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_bml_SM.png" width="145" height="32" style="width: 145px; height: 32px; float: right; clear: both; border: none; padding: 0; margin: 0;" align="top" alt="' . __('Check out with PayPal', 'express-checkout') . '" />';
                $ex_button_output .= '</a>';
                $ex_button_output .= '</div>';
            }
            if (is_checkout()) {
                $ex_button_output = '<div class="col2-set" id="customer_details"><div class="express_checkout_button_chekout_page"><div id="express_checkout_button_chekout_page" >' . $ex_button_output . '</div><div id="express_checkout_button_text" >' . $this->express_checkout_get_option('skip_text') . '</div></div></div>';
            }
            if (is_product()) {
                $ex_button_output = '<div id="express_checkout_button_product_page" style="' . $display_style . '">' . $ex_button_output . '</div>';
            }
            echo apply_filters('wc_ex_button', $ex_button_output, $ex_button_link);
        }
    }

    public function ex_get_product_type($ID) {

        $result = FALSE;
        $product = wc_get_product($ID);
        if ($product->is_type('simple') || $product->is_type('variable')) {
            $result = TRUE;
        }
        return $result;
    }

    public function ex_show_paypal_credit() {
        $show_paypal_credit = ($this->express_checkout_get_option('show_paypal_credit')) ? $this->express_checkout_get_option('show_paypal_credit') : 'no';
        $is_us = false;
        if ($show_paypal_credit == 'yes') {
            if (substr(get_option("woocommerce_default_country"), 0, 2) == 'US' || substr(get_option("woocommerce_default_country"), 0, 2) == 'GB') {
                $is_us = true;
            }
        }
        return $is_us;
    }

    public function ex_get_checkout_url($action) {
        return add_query_arg('action', $action, WC()->api_request_url('Express_Checkout_Gateway'));
    }

    public function ex_get_locale() {
        $locale = ($this->express_checkout_get_option('use_wp_locale_code')) ? $this->express_checkout_get_option('use_wp_locale_code') : get_locale();
        $safe_locales = array(
            'en_US',
            'de_DE',
            'en_AU',
            'nl_NL',
            'fr_FR',
            'zh_XC',
            'es_XC',
            'zh_CN',
            'fr_XC',
            'en_GB',
            'it_IT',
            'pl_PL',
            'ja_JP',
        );

        if ($locale != '') {
            $locale = substr(get_locale(), 0, 5);
        }
        if (!in_array($locale, $safe_locales)) {
            $locale = 'en_US';
        }
        return apply_filters('wc_ex_button_language', $locale);
    }

    public function ex_is_express_checkout() {
        return isset(WC()->session->paypal_express_checkout);
    }

    public function ex_notice_count($notice_type = '') {
        if (function_exists('wc_notice_count')) {
            return wc_notice_count($notice_type);
        }
        return 0;
    }

    public function ex_redirect_after_checkout() {
        if (!$this->ex_is_express_checkout()) {
            $args = array(
                'result' => 'success',
                'redirect' => $this->ex_get_checkout_url('ex_set_express_checkout'),
            );
            if (isset($_POST['terms']) && wc_get_page_id('terms') > 0) {
                WC()->session->paypal_express_terms = 1;
            }
            if (is_ajax()) {
                if ($this->ex_is_version_gte_2_4()) {
                    wp_send_json($args);
                } else {
                    echo json_encode($args);
                }
            } else {
                wp_redirect($args['redirect']);
            }
            exit;
        }
    }

    public function ex_is_version_gte_2_4() {
        return $this->ex_get_version() && version_compare($this->ex_get_version(), '2.4', '>=');
    }

    public function ex_get_version() {
        return defined('WC_VERSION') && WC_VERSION ? WC_VERSION : null;
    }

}
