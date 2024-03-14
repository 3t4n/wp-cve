<?php

if( ! class_exists('EstoRequest')) {
    require_once('Request.php');
}
if( ! class_exists('WC_Esto_Payment')) {
    require_once('Payment.php');
}

class WC_Esto_Calculator {

    private static $plugin_url;
    private static $plugin_dir;
    private static $plugin_title = 'ESTO Product Calculator';
    private static $plugin_slug = 'esto-calculator-settings';
    private static $esto_option_key = 'esto-calculator-settings';
    private $esto_calc_settings;
    private $shopId;
    private static $is_current_billing_country_disabled = false;

    const MIN_PRICE_DEFAULT = 30;
    const MAX_PRICE_DEFAULT = 10000;

    public function __construct()
    {
        global $esto_plugin_dir, $esto_plugin_url;

        self::$plugin_url = $esto_plugin_url;
        self::$plugin_dir = $esto_plugin_dir;

        $this->esto_calc_settings = get_option(self::$esto_option_key);

        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_script'));

        if ( $this->get_setting( 'enable_calc' ) ) {
            add_action('wp_head', array($this, 'wp_head'));
            add_action('woocommerce_single_product_summary', array(&$this, 'display_calculator'), 8);

            if ( apply_filters( 'woo_esto_show_monthly_payment_on_archive_pages', $this->get_setting( 'show_monthly_payment_on_archive_pages' ) ) ) {
                add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'display_calculator_for_archive' ], 15 );
            }
        }
    }

    public static function is_current_billing_country_disabled() {
        $payment_settings = get_option( 'woocommerce_esto_settings', null );
        if ( $payment_settings && isset( $payment_settings['disabled_countries'] ) ) {
            $disabled_countries = $payment_settings['disabled_countries'];

            if ( ! empty( $disabled_countries ) ) {

                $customer = WC()->customer;
                if ( $customer ) {

                    if ( method_exists( WC()->customer, 'get_billing_country' ) ) {
                        $country = WC()->customer->get_billing_country();
                    }
                    else {
                        $country = WC()->customer->get_country();
                    }

                    if ( in_array( $country, $disabled_countries ) ) {
                        self::$is_current_billing_country_disabled = true;
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function display_calculator_for_archive() {
        // this check is to prevent showing calculator in a random related product on single product page when main product does not qualify
        if ( ! is_single() ) {
            $this->display_calculator();
        }
    }

    public function display_calculator()
    {
        if ( self::$is_current_billing_country_disabled || self::is_current_billing_country_disabled() ) {
            return;
        }

        // foreach(WC()->payment_gateways()->payment_gateways() as $gateway)
        // {
        //     if($gateway->id === 'esto')
        //     {
        //         $this->shopId = $gateway->get_option('shop_id');
        //     }
        // }

        global $product;

        if ( ! $product ) {
            return;
        }

        $this->shopId = esto_get_api_field( 'shop_id' );

        if ( method_exists( $product, 'get_type' ) && $product->get_type() == 'variable' && method_exists( $product, 'get_variation_price' ) ) {
            $price = $product->get_variation_price( 'min', true );
        }
        elseif ( function_exists( 'wc_get_price_to_display' ) ) {
            $price = wc_get_price_to_display( $product );
        }
        else {
            $price = $product->get_price();
        }

        $estoMonthlyPayment = false;
        $period_months = false;

        $minimum_price_from_settings = $this->get_setting( 'minimum_price' );
        $maximum_price_from_settings = $this->get_setting( 'maximum_price' );
        $min_price = $minimum_price_from_settings ? $minimum_price_from_settings : self::MIN_PRICE_DEFAULT;
        $max_price = $maximum_price_from_settings ? $maximum_price_from_settings : self::MAX_PRICE_DEFAULT;

        if ( $price > 0 && $price >= (float)$min_price && $price <= (float)$max_price )
        {
            $show_esto_3 = $this->get_setting( 'show_esto_3' );

            if ( $show_esto_3 ) {
                $estoMonthlyPayment = $price / 3;

                if ( function_exists( 'wc_price' ) ) {
                    $estoMonthlyPayment = wc_price( $estoMonthlyPayment );
                }
                elseif ( function_exists( 'wc_format_decimal' ) ) {
                    $estoMonthlyPayment = wc_format_decimal( $estoMonthlyPayment, '' );
                }
                elseif ( function_exists( 'woocommerce_format_decimal' ) ) {
                    $estoMonthlyPayment = woocommerce_format_decimal( $estoMonthlyPayment, '' );
                }
            }
            else {
                $res = $this->get_product_price_from_api( $product );

                $estoMonthlyPayment = ($res && isset($res->monthly_payment)) ? $res->monthly_payment : null;
                if ( function_exists( 'wc_price' ) ) {
                    $estoMonthlyPayment = wc_price( $estoMonthlyPayment );
                }

                $period_months = ( $res && isset( $res->period_months ) ) ? $res->period_months : false;
            }

            if ( $estoMonthlyPayment ) {
                // works for both wpml and polylang
                $current_language = apply_filters( 'wpml_current_language', false );
                $calc_text = $this->get_setting( $current_language ? 'calc_text_' . $current_language : 'calc_text' );

                if ( is_single() ) {

                    $logoSrc = null;
                    $logo_width = 110;
                    $logo_height = 0;

                    if( ! empty($this->get_setting('esto_calc_logo'))) {
                        $image_attributes = wp_get_attachment_image_src($this->get_setting('esto_calc_logo'), 'full');
                        $logoSrc = $image_attributes[0];
                        $logo_width = $image_attributes[1];
                        $logo_height = $image_attributes[2];
                    }

                    if ( ! $current_language ) {
                        $current_language = substr( get_locale(), 0, 2 );
                    }

                    if ( $current_language ) {
                        // default logos are url's, but media uploader uses ids, which means we have to check if stored value is url or id.
                        $logo_id = $this->get_setting( 'calculator_logo_url_' . $current_language );

                        if ( $logo_id ) {
                            if ( is_numeric( $logo_id ) ) {
                                $logo_attachment = wp_get_attachment_image_src( $logo_id, 'full' );
                                if ( ! empty( $logo_attachment ) ) {
                                    $logoSrc = $logo_attachment[0];
                                    $logo_width = $logo_attachment[1];
                                    $logo_height = $logo_attachment[2];
                                }
                            }
                            else {
                                $logoSrc = $logo_id;
                            }
                        }
                    }

                    // works for both wpml and polylang
                    $current_language = apply_filters( 'wpml_current_language', false );
                    if ( $current_language ) {
                        $logoUrl = $this->get_setting( 'calc_url_' . $current_language );
                    }

                    if ( empty( $logoUrl ) ) {
                        $logoUrl = $this->get_setting( 'calc_url' );
                    }

                    $logoUrl = apply_filters( 'esto_product_page_calculator_url', $logoUrl, $this );

                    if ( $override_logo_width = $this->get_setting( 'calc_logo_width' ) ) {
                        $logo_width = $override_logo_width;
                    }

                    if ( $override_logo_height = $this->get_setting( 'calc_logo_height' ) ) {
                        $logo_height = $override_logo_height;
                    }

                    require_once self::$plugin_dir . 'assets/view/calculator.php';
                }
                else {
                    include self::$plugin_dir . 'assets/view/calculator-archive.php';
                }
            }
        }
    }

    public function get_product_price_from_api( $product ) {

        $product_id = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;

        $country = esto_get_country();
        if ( ! in_array( $country, ['ee', 'lv', 'lt'] ) ) {
            $country = 'ee';
        }

        $transient_name = 'woo_esto_product_' . $product_id . '_monthly_payment_' . $country;
        $transient_name_params = 'woo_esto_product_' . $product_id . '_monthly_payment_params_' . $country;

        $res = get_transient( $transient_name );
        $params = get_transient( $transient_name_params );

        if ( function_exists( 'wc_get_price_to_display' ) ) {
            $price = wc_get_price_to_display( $product );
        }
        else {
            $price = $product->get_price();
        }

        if ( ! $res || ! $params || ! isset( $params['amount'] ) || $params['amount'] != $price ) {

            $res = $this->restApi( 'payments', array(
                'amount' => $price,
                'shop_id' => $this->shopId,
            ) );

            if ( $res && isset( $res->monthly_payment ) ) {
                set_transient( $transient_name, $res, WEEK_IN_SECONDS );
                set_transient( $transient_name_params, ['amount' => $price], WEEK_IN_SECONDS );
            }
        }

        return $res;
    }

    private function getPeriods($amount)
    {
        $data = array('amount' => $amount);
        $response = $this->restApi('periods', $data);

        return $response->periods;
    }

    public function wp_head()
    {
        wp_enqueue_script('jquery');

        ?>
        <style type="text/css">
            .monthly_payment {
                font-size: 12px;
            }
            .products .product .esto_calculator {
                margin-bottom: 16px;
            }
        </style>
        <?php
    }

    public function admin_menu()
    {
        $wc_page = 'woocommerce';
        add_submenu_page($wc_page, self::$plugin_title, self::$plugin_title, "install_plugins", self::$plugin_slug,
            array($this, "calculator_setting_page"));
    }

    public function admin_script()
    {
        if(is_admin()) {
            wp_enqueue_media();

            wp_enqueue_style('esto-admin', self::$plugin_url . "assets/css/admin.css");
        }
    }

    /**
     * Image Uploader
     */
    public function esto_image_uploader($optionName) {
        $srcName = $this->get_setting($optionName);
        $default_image = 'https://via.placeholder.com/115x115';

        if ( ! empty($srcName)) {
            $image_attributes = wp_get_attachment_image_src($srcName, 'full');
            $src = $image_attributes[0];
            $value = $srcName;
        } else {
            $src = $default_image;
            $value = '';
        }

        $this->esto_calc_settings['logo_src'] = $src;
        $this->esto_calc_settings['logo_value'] = $value;
    }

    public function calculator_setting_page()
    {
        /* Save calculator setting */
        if ( isset( $_POST[self::$plugin_slug] ) && check_admin_referer( 'esto_calculator_settings') ) {
            $this->saveSetting();
        }

        /* Include admin calculator settings file */
        $this->esto_image_uploader('esto_calc_logo');
        include_once self::$plugin_dir . "assets/view/calculator-settings.php";
    }

    public function saveSetting()
    {
        $arrayRemove = array(self::$plugin_slug, "btn-esto-submit");
        $saveData = array();
        foreach ($_POST as $key => $value):
            if (in_array($key, $arrayRemove))
                continue;
            $saveData[$key] = $value;
        endforeach;
        $this->esto_calc_settings = $saveData;
        update_option(self::$esto_option_key, $saveData);
    }

    public function get_setting($key)
    {
        if (!$key || $key == "")
            return;

        if (!isset($this->esto_calc_settings[$key]))
            return;

        return $this->esto_calc_settings[$key];
    }

    public function restApi($service, $data = array(), $method = 'GET')
    {
        $url = esto_get_api_url() . 'v2/calculate/' . $service;

        if($method == 'GET') {
            $url .= '?' . http_build_query($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if($this->shopId)
        {
            $data['shop_id'] = $this->shopId;
        }

        $data = json_encode($data);

        switch ($method) {
            case 'GET':
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        $response = curl_exec($ch);
        $data = json_decode($response);

        return $data;
    }

}
