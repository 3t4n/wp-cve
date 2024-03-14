<?php

namespace QuadLayers\WOOCCM\Controller;

use QuadLayers\WOOCCM\Plugin as Plugin;
use QuadLayers\WOOCCM\Controller\Field as Field;
use QuadLayers\WOOCCM\Model\Field_Billing as Field_Billing_Model;

/**
 * Field_Billing Class
 */
class Field_Billing extends Field {

	protected static $_instance;
	public $billing;

	public function __construct() {
		Field_Billing_Model::instance();

		add_action( 'wooccm_sections_header', array( $this, 'add_header' ) );
		add_action( 'woocommerce_sections_' . WOOCCM_PREFIX, array( $this, 'add_section' ), 99 );
		add_filter( 'woocommerce_admin_billing_fields', array( $this, 'add_admin_billing_fields' ), 999 );
		// add_filter('woocommerce_admin_shipping_fields', array($this, 'add_admin_shipping_fields'));
		// add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'add_order_data'));
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	// Admin
	// ---------------------------------------------------------------------------

	public function add_section() {
		global $current_section, $wp_roles, $wp_locale;

		if ( 'billing' == $current_section ) {

			$fields             = Plugin::instance()->billing->get_fields();
			$defaults           = Plugin::instance()->billing->get_defaults();
			$types              = Plugin::instance()->billing->get_types();
			$conditionals       = Plugin::instance()->billing->get_conditional_types();
			$option             = Plugin::instance()->billing->get_option_types();
			$price              = Plugin::instance()->billing->get_price_types();
			$multiple           = Plugin::instance()->billing->get_multiple_types();
			$template           = Plugin::instance()->billing->get_template_types();
			$disabled           = Plugin::instance()->billing->get_disabled_types();
			$product_categories = $this->get_product_categories();

			$product_types = wc_get_product_types();

			// This type cannot setted because it is not added to to cart
			unset( $product_types['external'] );

			// This type cannot setted because it is not added to to cart. It add every child to cart as simple product
			unset( $product_types['grouped'] );

			$product_subtypes_options = array(
				'virtual'              => __( 'Virtual', 'woocommerce-checkout-manager' ),
				'downloadable'         => __( 'Downloadable', 'woocommerce-checkout-manager' ),
				'virtual-downloadable' => __( 'Virtual & Downloadable', 'woocommerce-checkout-manager' ),
			);

			$is_billing_shipping = true;

			include_once WOOCCM_PLUGIN_DIR . 'lib/view/backend/pages/billing.php';
		}
	}

	public function add_header() {
		global $current_section;
		?>
		<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=wooccm&section=billing' ) ); ?>" class="<?php echo ( 'billing' == $current_section ? 'current' : '' ); ?>"><?php esc_html_e( 'Billing', 'woocommerce-checkout-manager' ); ?></a> | </li>
		<?php
	}

	// Admin Order
	// ---------------------------------------------------------------------------

	public function add_admin_billing_fields( $billing_fields ) {
		$fields = Plugin::instance()->billing->get_fields();
		if ( ! $fields ) {
			return $billing_fields;
		}

		$template = Plugin::instance()->billing->get_template_types();

		foreach ( $fields as $field_id => $field ) {

			if ( ! isset( $field['name'] ) ) {
				continue;
			}

			if ( isset( $billing_fields[ $field['name'] ] ) ) {
				continue;
			}

			if ( in_array( $field['name'], $template ) ) {
				continue;
			}

			if ( ! isset( $field['type'] ) || 'textarea' != $field['type'] ) {
				$field['type'] = 'text';
			}

			$billing_fields[ $field['name'] ]                  = $field;
			$billing_fields[ $field['name'] ]['id']            = sprintf( '_%s', (string) $field['key'] );
			$billing_fields[ $field['name'] ]['label']         = $field['label'];
			$billing_fields[ $field['name'] ]['name']          = $field['key'];
			$billing_fields[ $field['name'] ]['value']         = null;
			$billing_fields[ $field['name'] ]['class']         = join( ' ', $field['class'] );
			$billing_fields[ $field['name'] ]['wrapper_class'] = 'wooccm-premium-field';
		}

		return $billing_fields;
	}
}

