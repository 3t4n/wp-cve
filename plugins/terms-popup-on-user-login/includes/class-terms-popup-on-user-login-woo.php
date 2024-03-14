<?php

class TPUL_Woo_Connector {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $version;

    private $is_woo_enabled = false;


    public function __construct( $plugin_name = '', $version = '1.0.0' ) {
		$this->plugin_name = $plugin_name;
        $this->version = $version;

        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            $this->is_woo_enabled = true;
        }
    }

    public function woo_enabled() {
        return $this->is_woo_enabled;
    }

    public function is_woo_product_page() {
        if($this->is_woo_enabled) {
            if ( is_product() ) {
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function is_product_category() {
        if($this->is_woo_enabled) {
            if ( is_product_category() ) {
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function is_cart_page() {
        if($this->is_woo_enabled) {
            if ( is_cart() ) {
                return true;
            }else{
                return false;
            }
        }
        return false;
    }
    public function is_checkout_page() {
        if($this->is_woo_enabled) {
            if ( is_checkout() ) {
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

}

