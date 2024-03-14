<?php

class shipxApi
{

    const API_URL_PRODUCTION_PL = 'http://api-shipx-pl.easypack24.net';

    const API_URL_SANDBOX_PL = 'https://sandbox-api-shipx-pl.easypack24.net';

    const API_URL_PRODUCTION_UK = 'https://api-shipx-uk.easypack24.net/v1/';

    const API_URL_SANDBOX_UK = 'https://sandbox-api-shipx-uk.easypack24.net/v1/';

    const API_GEOWIDGET_URL_PRODUCTION_PL = 'https://api-pl-points.easypack24.net/v1';

    const API_GEOWIDGET_URL_SANDBOX_PL = 'https://sandbox-api-pl-points.easypack24.net/v1';

    const API_GEOWIDGET_URL_PRODUCTION_UK = 'https://api-uk-points.easypack24.net/v1';

    const API_GEOWIDGET_URL_SANDBOX_UK = 'https://sandbox-api-uk-points.easypack24.net/v1';

    const API_GEOWIDGET_URL_PRODUCTION_CSS = 'https://geowidget.easypack24.net/css/easypack.css';

    const API_GEOWIDGET_URL_SANDBOX_CSS = 'https://sandbox-geowidget.easypack24.net/css/easypack.css';

    private $parcel_machine_parcel_sizes;

    public function __construct()
    {
	    $this->set_parcel_machine_parcel_sizes();
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front_scripts'], 75);
        add_action('admin_enqueue_scripts', [$this, 'include_geowidget_settings'], 76);
        add_action('admin_enqueue_scripts', [$this, 'include_geowidget_metabox'], 77);
	    add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts'], 10);
        add_action('wp_footer', [$this, 'frontFooter'], 75);
        add_action('admin_footer', [$this, 'adminFooter'], 77);

        add_action('woocommerce_review_order_after_shipping',
            [$this, 'woocommerce_review_order_after_shipping']);
        add_action('woocommerce_checkout_update_order_meta',
            array($this, 'woocommerce_checkout_update_order_meta'));
    }

    private function set_parcel_machine_parcel_sizes() {
    	$this->parcel_machine_parcel_sizes = [
		    'a' => [
			    'label' => 'Gabaryt A - max 8x38x64cm',
			    'height' => 8,
			    'depth' => 38,
			    'width' => 64,
			    'max_weight' => 25
		    ],
		    'b' => [
			    'label' => 'Gabaryt B - max 19x38x64cm',
			    'height' => 19,
			    'depth' => 38,
			    'width' => 64,
			    'max_weight' => 25
		    ],
		    'c' => [
			    'label' => 'Gabaryt C - max 41x38x64cm',
			    'height' => 41,
			    'depth' => 38,
			    'width' => 64,
			    'max_weight' => 25
		    ]
	    ];
    }

    public function get_parcel_machine_parcel_sizes() {
    	return $this->parcel_machine_parcel_sizes;
    }

    public function include_geowidget_settings()
    {
        if (is_admin()
            && isset($_GET[ 'section' ])
            && 'apaczka' === $_GET[ 'section' ] ) {

            wp_enqueue_script('geowidget-admin',
                WPDesk_Apaczka_Plugin::get_instance()->getPluginUrl()
                . 'assets/js/admin-geowidget-settings.js', array(), '1.3.10');

            wp_enqueue_style('geowidget-4.5-css',
                self::API_GEOWIDGET_URL_PRODUCTION_CSS);
        }
    }

    public function include_geowidget_metabox()
    {
        if (is_admin()
            && isset($_GET[ 'post' ])
            && 'shop_order' === get_post_type($_GET[ 'post' ]) ) {
            wp_enqueue_script('geowidget-admin',
                WPDesk_Apaczka_Plugin::get_instance()->getPluginUrl()
                . 'assets/js/admin-geowidget-metabox.js', array(), '1.3.10');

            wp_enqueue_style('geowidget-4.5-css',
                self::API_GEOWIDGET_URL_PRODUCTION_CSS);
        }
    }

    public function enqueue_admin_scripts()
    {
	    if ( ! function_exists( 'get_plugin_data' ) ) {
		    require_once ABSPATH . 'wp-admin/includes/plugin.php';
	    }

	    $plugin_data = get_plugin_data( WPDesk_Apaczka_Plugin::get_instance()->getPluginFilePath() );

	    wp_enqueue_script( 'apaczka-admin-js',
		    WPDesk_Apaczka_Plugin::get_instance()->getPluginUrl()
		    . 'assets/js/admin.js', array( 'jquery' ), $plugin_data['Version'] );

	    wp_localize_script(
		    'apaczka-admin-js',
		    'parcel_machine_parcel_sizes',
		    $this->parcel_machine_parcel_sizes
	    );
    }

    public function enqueue_front_scripts()
    {
        if (true === is_checkout()){
            wp_enqueue_style('woocommerce-apaczka-front',
                WPDesk_Apaczka_Plugin::get_instance()->getPluginUrl()
                . 'assets/css/admin.css', array(), '1.3.10');

            wp_enqueue_script('woocommerce-apaczka-front',
                WPDesk_Apaczka_Plugin::get_instance()->getPluginUrl()
                . 'assets/js/front.js', array(), '1.3.10');

            wp_enqueue_style('geowidget-4.5-css',
                self::API_GEOWIDGET_URL_PRODUCTION_CSS);
        }
    }

    /**
     * @param int $order_id
     */
    public function woocommerce_checkout_update_order_meta($order_id)
    {
        if ( isset($_POST[ 'parcel_machine_id' ] ) ) {
            update_post_meta($order_id, '_parcel_machine_id',
                esc_attr($_POST[ 'parcel_machine_id' ]));
            update_post_meta($order_id, '_is_parcel_locker', true);
        }
	
	    $parcel_machine_address = array();
	    $parcel_machine_address['city'] = isset( $_POST[ 'parcel_machine_city'] ) ? sanitize_text_field( $_POST[ 'parcel_machine_city' ] ) : '';
	    $parcel_machine_address['street'] = isset( $_POST[ 'parcel_machine_street'] ) ? sanitize_text_field( $_POST[ 'parcel_machine_street' ] ) : '';
	    $parcel_machine_address['building_number'] = isset( $_POST[ 'parcel_machine_building_number'] ) ? sanitize_text_field( $_POST[ 'parcel_machine_building_number' ] ) : '';
	    $parcel_machine_address['post_code'] = isset( $_POST[ 'parcel_machine_post_code'] ) ? sanitize_text_field( $_POST[ 'parcel_machine_post_code' ] ) : '';
	    $parcel_machine_address['machine_province'] = isset( $_POST[ 'parcel_machine_province'] ) ? sanitize_text_field( $_POST[ 'parcel_machine_province' ] ) : '';
	    
		update_post_meta($order_id, '_parcel_machine_address', $parcel_machine_address);
    }


    /**
     * @throws \Exception
     */
    public function woocommerce_review_order_after_shipping()
    {
        $selected_method_in_cart
            = flexible_shipping_method_selected_in_cart('apaczka');

        $selected_method_in_cart_cod
            = flexible_shipping_method_selected_in_cart('apaczka_cod');

        if ( false === $selected_method_in_cart
            && false == $selected_method_in_cart_cod
        ) {
            return;
        }

        $method = new WPDesk_Apaczka_Shipping();

        $service = $method->get_option('service');
        if ('PACZKOMAT' !== $service) {
            return;
        }

        $args[ 'parcel_machine_id' ]
            = WC()->session->get('parcel_machine_id');
        $args[ 'geowidget_src' ] = self::API_GEOWIDGET_URL_PRODUCTION_PL;


        wc_get_template('geowidget-review-order-after-shipping.php',
            $args, '',
            WPDesk_Apaczka_Plugin::get_instance()->getPluginDirectory()
            . DIRECTORY_SEPARATOR
            . 'templates/');

    }



    public
    function frontFooter()
    {
        if (true === is_checkout()){
            //echo '<script async src="https://geowidget.easypack24.net/js/sdk-for-javascript.js"></script>';
            echo '<script async src="' . WPDesk_Apaczka_Plugin::get_instance()->getPluginUrl()
                . "assets/js/sdk-for-javascript.js" . '"></script>';
        }

    }
    public
    function adminFooter()
    {
        //echo '<script async src="https://geowidget.easypack24.net/js/sdk-for-javascript.js"></script>';
        echo '<script async src="' . WPDesk_Apaczka_Plugin::get_instance()->getPluginUrl()
            . "assets/js/sdk-for-javascript.js" . '"></script>';

    }

}
