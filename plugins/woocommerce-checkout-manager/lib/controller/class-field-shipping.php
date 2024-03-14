<?php

namespace QuadLayers\WOOCCM\Controller;

use QuadLayers\WOOCCM\Controller\Field as Field;
use QuadLayers\WOOCCM\Plugin as Plugin;
use QuadLayers\WOOCCM\Model\Field_Shipping as Field_Shipping_Model;

/**
 * Field_Shipping Class
 */
class Field_Shipping extends Field {

	protected static $_instance;
	public $shipping;

	public function __construct() {
		Field_Shipping_Model::instance();

		add_action( 'wooccm_sections_header', array( $this, 'add_header' ) );
		add_action( 'woocommerce_sections_' . WOOCCM_PREFIX, array( $this, 'add_section' ), 99 );
		add_filter( 'woocommerce_admin_shipping_fields', array( $this, 'add_admin_shipping_fields' ), 999 );
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	// Admin
	// ---------------------------------------------------------------------------

	public function add_header() {
		global $current_section;
		?>
	<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=wooccm&section=shipping' ) ); ?>" class="<?php echo ( 'shipping' == $current_section ? 'current' : '' ); ?>"><?php esc_html_e( 'Shipping', 'woocommerce-checkout-manager' ); ?></a> | </li>
		<?php
	}

	public function add_section() {
		global $current_section, $wp_roles, $wp_locale;

		if ( 'shipping' == $current_section ) {

			$fields             = Plugin::instance()->shipping->get_fields();
			$defaults           = Plugin::instance()->shipping->get_defaults();
			$types              = Plugin::instance()->shipping->get_types();
			$conditionals       = Plugin::instance()->shipping->get_conditional_types();
			$option             = Plugin::instance()->shipping->get_option_types();
			$price              = Plugin::instance()->shipping->get_price_types();
			$multiple           = Plugin::instance()->shipping->get_multiple_types();
			$template           = Plugin::instance()->shipping->get_template_types();
			$disabled           = Plugin::instance()->shipping->get_disabled_types();
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

			include_once WOOCCM_PLUGIN_DIR . 'lib/view/backend/pages/shipping.php';
		}
	}

	public function add_admin_shipping_fields( $shipping_fields ) {
		$fields = Plugin::instance()->shipping->get_fields();
		if ( ! $fields ) {
			return $shipping_fields;
		}

		$template = Plugin::instance()->shipping->get_template_types();

		foreach ( $fields as $field_id => $field ) {

			if ( ! isset( $field['name'] ) ) {
				continue;
			}

			if ( isset( $shipping_fields[ $field['name'] ] ) ) {
				continue;
			}

			if ( in_array( $field['name'], $template ) ) {
				continue;
			}

			if ( ! isset( $field['type'] ) || 'textarea' != $field['type'] ) {
				$field['type'] = 'text';
			}

			$shipping_fields[ $field['name'] ]                  = $field;
			$shipping_fields[ $field['name'] ]['id']            = sprintf( '_%s', (string) $field['key'] );
			$shipping_fields[ $field['name'] ]['label']         = $field['label'];
			$shipping_fields[ $field['name'] ]['name']          = $field['key'];
			$shipping_fields[ $field['name'] ]['value']         = null;
			$shipping_fields[ $field['name'] ]['class']         = join( ' ', $field['class'] );
			$shipping_fields[ $field['name'] ]['wrapper_class'] = 'wooccm-premium-field';
		}

		return $shipping_fields;
	}
}
