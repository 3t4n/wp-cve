<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Fr_Custom_Payment_Gateway_Icon_For_WooCommerce
 * @subpackage Fr_Custom_Payment_Gateway_Icon_For_WooCommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fr_Custom_Payment_Gateway_Icon_For_WooCommerce
 * @subpackage Fr_Custom_Payment_Gateway_Icon_For_WooCommerce/admin
 * @author     Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Custom_Payment_Gateway_Icon_For_WooCommerce_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
        
    /**
     * Method overloading.
     * 
     * @since 1.0.0
     * @param string $name      Name of the method being called.
     * @param array $arguments  An enumerated array containing the 
     *                          parameters passed to the $name'ed method.
     * @return mixed
     */
    public function __call($name, $arguments) {
        if (
            strpos($name, 'add_icon_form_field_to_') === 0 && 
            $id = str_replace('add_icon_form_field_to_', '', $name))
        {
            return $this->add_icon_form_field($id, $arguments[0]);
        }
    }
    
    /**
     * Hook form field modifier.
     * 
     * Hooked on `init` action.
     * 
     * @since 1.0.0
     */
    public function hook_form_fields_modifier() {
        if (!function_exists('WC')) {
            return;
        }
        
        foreach (WC()->payment_gateways()->get_payment_gateway_ids() as $id) {                
            add_filter('woocommerce_settings_api_form_fields_' . $id, array($this, 'add_icon_form_field_to_' . $id));
        }
    }
    
    /**
     * Add icon form field to payment gateway setting page.
     * 
     * Hooked on `woocommerce_settings_api_form_fields_$id`. The dynamic 
     * portion of the hook name, `$id`, refers to the payment gateway ID.
     * 
     * @param   string $id          Payment gateway ID.
     * @param   array $form_fields  Form option fields.
     * @return  array               Modified form option fields.
     */
    public function add_icon_form_field($id, $form_fields) {
        $form_fields['fcpgifw_icon'] = array(
            'title'       => __( 'Icon', 'fr-custom-payment-gateway-icon-for-woocommerce' ),
            'type'        => 'text',
            'description' => __( 'Enter an image URL to change the icon.', 'fr-custom-payment-gateway-icon-for-woocommerce' ),
            'desc_tip'    => true,
            'default'     => '',
        );
        $form_fields['fcpgifw_icon_2x'] = array(
            'title'       => __( 'Icon @2x', 'fr-custom-payment-gateway-icon-for-woocommerce' ),
            'type'        => 'text',
            'description' => __( 'Enter a @2x image URL for retina display.', 'fr-custom-payment-gateway-icon-for-woocommerce' ),
            'desc_tip'    => true,
            'default'     => '',
        );
        
        return $form_fields;
    }

}
