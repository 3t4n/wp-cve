<?php
/**
 * Contains code for the settings override class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Shipping_Method
 */

namespace Boxtal\BoxtalConnectWoocommerce\Shipping_Method;

use Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point\Controller;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Settings_Override class.
 *
 * Add tag setting to shipping methods.
 */
class Settings_Override {

	/**
	 * Plugin url.
	 *
	 * @var string
	 */
	private $plugin_url;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * List of shipping methods to ignore for settings override
	 *
	 * @var array
	 */
	private $shipping_method_settings_override_ignore = array(
		'boxtal_connect',
		'lpfr-eco_connect',
		'local_pickup',
	);

	/**
	 * Construct function.
	 *
	 * @param array $plugin plugin array.
	 * @void
	 */
	public function __construct( $plugin ) {
		$this->plugin_url     = $plugin['url'];
		$this->plugin_version = $plugin['version'];
	}

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_action( 'admin_enqueue_scripts', array( $this, 'shipping_settings_styles' ) );
		add_filter( 'woocommerce_shipping_methods', array( $this, 'shipping_methods_settings_override' ) );
	}

	/**
	 * Add extra field to shipping methods.
	 *
	 * @param array $shipping_methods wc shipping methods.
	 * @return array
	 */
	public function shipping_methods_settings_override( $shipping_methods ) {
		foreach ( $shipping_methods as $shipping_method => $classname ) {
			if ( ! in_array( $shipping_method, $this->shipping_method_settings_override_ignore, true ) ) {
				if ( 'free_shipping' === $shipping_method ) {
					add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_method, array( $this, 'add_form_field_free' ) );
				} else {
					add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_method, array( $this, 'add_form_field_default' ) );
				}
			}
		}
		return $shipping_methods;
	}

	/**
	 * Enqueue shipping settings scripts
	 *
	 * @param string $hook hook name.
	 * @void
	 */
	public function shipping_settings_styles( $hook ) {
        // phpcs:ignore
		$current_tab = isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ? urldecode( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) : '';
		if ( 'woocommerce_page_wc-settings' === $hook && 'shipping' === $current_tab ) {
			wp_enqueue_style( Branding::$branding_short . '_notices', $this->plugin_url . 'Boxtal/BoxtalConnectWoocommerce/assets/css/shipping-method.css', array(), $this->plugin_version );
		}
	}

	/**
	 * Add custom form fields to shipping methods.
	 *
	 * @param array $form_fields existing form fields.
	 * @return array $form_fields
	 */
	public function add_form_field_free( $form_fields ) {
		return $this->add_form_field( $form_fields, __( 'To use only if you want to offer free shipping through coupons.', 'boxtal-connect' ) );
	}

	/**
	 * Add custom form fields to shipping methods.
	 *
	 * @param array $form_fields existing form fields.
	 * @return array $form_fields
	 */
	public function add_form_field_default( $form_fields ) {
		/* translators: 1) company name */
		return $this->add_form_field( $form_fields, sprintf( __( 'No need to use it if you\'re using %s Connect shipping method, as it\'s available directly when you set it up.', 'boxtal-connect' ), Branding::$company_name ) );
	}

	/**
	 * Add custom form fields to shipping methods.
	 *
	 * @param array $form_fields existing form fields.
	 * @param array $description parcel point field custom description.
	 * @return array $form_fields
	 */
	public function add_form_field( $form_fields, $description ) {
		$network_options = Controller::get_network_options();
		$form_fields[ Branding::$branding_short . '_parcel_point_networks' ] = array(
			/* translators: 1) company name */
			'title'       => sprintf( __( 'Parcel points map display (%s Connect)', 'boxtal-connect' ), Branding::$company_name ),
			'type'        => 'multiselect',
			'description' => $description,
			'options'     => $network_options,
			'default'     => array(),
			'class'       => 'wc-enhanced-select ' . Branding::$branding_short . '-parcel-point-networks-dropdown',
		);
		return $form_fields;
	}
}
