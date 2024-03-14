<?php

namespace QuadLayers\WOOCCM\Controller;

use QuadLayers\WOOCCM\Plugin as Plugin;

/**
 * Checkout Class
 */
class Checkout {

	protected static $_instance;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wooccm_sections_header', array( $this, 'add_header' ) );
		add_action( 'woocommerce_sections_' . WOOCCM_PREFIX, array( $this, 'add_section' ), 99 );
		add_action( 'woocommerce_settings_save_' . WOOCCM_PREFIX, array( $this, 'save_settings' ) );

		// Force Shipping Address
		// -----------------------------------------------------------------------
		add_action( 'woocommerce_before_checkout_form', array( $this, 'add_inline_scripts' ) );
		add_filter( 'woocommerce_checkout_posted_data', array( $this, 'checkout_force_shipping_address' ) );

		// Note that this overrides the 'Shipping Destination' option in the Woo settings
		add_filter( 'woocommerce_ship_to_different_address_checked', array( $this, 'force_shipping_address' ) );
		// If you have the possibility of virtual-only orders you may want to comment this out
		add_filter( 'woocommerce_cart_needs_shipping_address', array( $this, 'force_shipping_address' ) );
		// Order always has shipping (even with local pickup for example)
		add_filter( 'woocommerce_order_needs_shipping_address', array( $this, 'force_shipping_address' ) );

		// Order Hooks
		add_action( 'woocommerce_checkout_fields', array( $this, 'order_notes' ) );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'add_checkout_form_before_message' ) );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'add_checkout_form_after_message' ) );
		add_action( 'woocommerce_enable_order_notes_field', array( $this, 'remove_order_notes' ) );

		// Compatibility
		// -----------------------------------------------------------------------
		add_filter( 'default_option_wooccm_checkout_force_shipping_address', array( $this, 'additional_info' ) );
		add_filter( 'default_option_wooccm_checkout_force_create_account', array( $this, 'auto_create_wccm_account' ) );
		add_filter( 'default_option_wooccm_checkout_remove_order_notes', array( $this, 'notesenable' ) );
		add_filter( 'default_option_wooccm_checkout_order_notes_label', array( $this, 'noteslabel' ) );
		add_filter( 'default_option_wooccm_checkout_order_notes_placeholder', array( $this, 'notesplaceholder' ) );
		add_filter( 'default_option_wooccm_checkout_checkout_form_before_message', array( $this, 'text1' ) );
		add_filter( 'default_option_wooccm_checkout_checkout_form_after_message', array( $this, 'text2' ) );
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function enqueue_scripts() {
		if ( is_checkout() || is_account_page() ) {

			Plugin::instance()->register_scripts();

			$i18n = substr( get_user_locale(), 0, 2 );

			wp_enqueue_style( 'wooccm-checkout-css' );

			// Colorpicker
			// ---------------------------------------------------------------------
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			// Farbtastic
			// ---------------------------------------------------------------------
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );

			// Dashicons
			// ---------------------------------------------------------------------
			wp_enqueue_style( 'dashicons' );

			// Checkout
			// ---------------------------------------------------------------------
			wp_enqueue_script( 'wooccm-frontend-js' );
		}
	}

	public function checkout_force_shipping_address( $posted_data ) {

		if ( get_option( 'wooccm_checkout_force_shipping_address', 'no' ) === 'yes' ) {

			$posted_data['ship_to_different_address'] = true;

			return $posted_data;
		}

		return $posted_data;
	}

	public function force_shipping_address( $value ) {

		if ( get_option( 'wooccm_checkout_force_shipping_address', 'no' ) === 'yes' ) {
			return true;
		}

		return $value;
	}

	public function add_inline_scripts() {
		if ( get_option( 'wooccm_checkout_force_shipping_address', 'no' ) === 'yes' ) {
			?>
				<style>
					#ship-to-different-address {
						pointer-events: none!important;
					}
					#ship-to-different-address-checkbox {
						display: none;
					}
					.woocommerce-shipping-fields .shipping_address {
					height: auto !important;
					display: block !important;
					}
				</style>
			<?php
		}

		if ( get_option( 'wooccm_checkout_force_create_account', 'no' ) === 'yes' ) {
			?>
	  <style>
		div.create-account {
		  display: block !important;
		}

		p.create-account {
		  display: none !important;
		}
	  </style>
	  <script>
		jQuery(document).ready(function(e) {
		  jQuery("input#createaccount").prop('checked', 'checked');
		});
	  </script>
			<?php
		}
	}

	// Frontend
	// -------------------------------------------------------------------------
	public function order_notes( $fields ) {
		$options = get_option( 'wccs_settings' );

		$label = get_option( 'wooccm_checkout_order_notes_label', false );
		if ( $label ) {
			$fields['order']['order_comments']['label'] = $label;
		}

		$placeholder = get_option( 'wooccm_checkout_order_notes_placeholder', false );
		if ( $placeholder ) {
			$fields['order']['order_comments']['placeholder'] = $placeholder;
		}

		if ( get_option( 'wooccm_checkout_remove_order_notes', 'no' ) === 'yes' ) {
			unset( $fields['order']['order_comments'] );
		}

		return $fields;
	}

	public function remove_order_notes( $value ) {
		if ( get_option( 'wooccm_checkout_remove_order_notes', 'no' ) === 'yes' ) {
			return false;
		}

		return $value;
	}

	public function add_checkout_form_before_message( $param ) {
		$text = get_option( 'wooccm_checkout_checkout_form_before_message', false );
		if ( $text ) {

			wc_get_template(
				'notices/notice.php',
				array(
					'messages' => array_filter( (array) $text ),
					'notices'  => array(
						0 => array(
							'notice' => $text,
						),
					),
				)
			);
		}
	}

	public function add_checkout_form_after_message( $param ) {
		$text = get_option( 'wooccm_checkout_checkout_form_after_message', false );
		if ( $text ) {

			wc_get_template(
				'notices/notice.php',
				array(
					'messages' => array_filter( (array) $text ),
					'notices'  => array(
						0 => array(
							'notice' => $text,
						),
					),
				)
			);
		}
	}

	// Admin
	// ---------------------------------------------------------------------------

	public function get_settings() {
		return array(
			array(
				'type' => 'title',
				'id'   => 'section_title',
			),
			array(
				'name'     => esc_html__( 'Force shipping address', 'woocommerce-checkout-manager' ),
				'desc_tip' => esc_html__( 'Force show shipping checkout fields.', 'woocommerce-checkout-manager' ),
				'id'       => 'wooccm_checkout_force_shipping_address',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'yes' => esc_html__( 'Yes', 'woocommerce-checkout-manager' ),
					'no'  => esc_html__( 'No', 'woocommerce-checkout-manager' ),
				),
				'default'  => 'no',
			),
			array(
				'name'     => esc_html__( 'Force create an account', 'woocommerce-checkout-manager' ),
				'desc_tip' => esc_html__( 'Force create an account for guests users.', 'woocommerce-checkout-manager' ),
				'id'       => 'wooccm_checkout_force_create_account',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'yes' => esc_html__( 'Yes', 'woocommerce-checkout-manager' ),
					'no'  => esc_html__( 'No', 'woocommerce-checkout-manager' ),
				),
				'default'  => 'no',
			),
			array(
				'name'     => esc_html__( 'Remove order notes', 'woocommerce-checkout-manager' ),
				'desc_tip' => esc_html__( 'Remove order notes from checkout page.', 'woocommerce-checkout-manager' ),
				'id'       => 'wooccm_checkout_remove_order_notes',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'yes' => esc_html__( 'Yes', 'woocommerce-checkout-manager' ),
					'no'  => esc_html__( 'No', 'woocommerce-checkout-manager' ),
				),
				'default'  => 'no',
			),
			array(
				'name'        => esc_html__( 'Order notes label', 'woocommerce-checkout-manager' ),
				'desc_tip'    => esc_html__( 'Add custom title for the custom fields table in the thankyou page.', 'woocommerce-checkout-manager' ),
				'id'          => 'wooccm_checkout_order_notes_label',
				'type'        => 'text',
				'placeholder' => esc_attr__( 'Order notes', 'woocommerce-checkout-manager' ),
			),
			array(
				'name'        => esc_html__( 'Order notes placeholder', 'woocommerce-checkout-manager' ),
				'desc_tip'    => esc_html__( 'Add custom title for the custom fields table in the thankyou page.', 'woocommerce-checkout-manager' ),
				'id'          => 'wooccm_checkout_order_notes_placeholder',
				'type'        => 'text',
				'placeholder' => esc_attr__( 'Notes about your order, e.g. special notes for delivery.', 'woocommerce-checkout-manager' ),
			),
			array(
				'name'        => esc_html__( 'Add message before checkout', 'woocommerce-checkout-manager' ),
				'desc_tip'    => esc_html__( 'Add custom title for the custom fields table in the thankyou page.', 'woocommerce-checkout-manager' ),
				'id'          => 'wooccm_checkout_checkout_form_before_message',
				'type'        => 'textarea',
				'placeholder' => '',
			),
			array(
				'name'        => esc_html__( 'Add message after checkout', 'woocommerce-checkout-manager' ),
				'desc_tip'    => esc_html__( 'Add custom title for the custom fields table in the thankyou page.', 'woocommerce-checkout-manager' ),
				'id'          => 'wooccm_checkout_checkout_form_after_message',
				'type'        => 'textarea',
				'placeholder' => '',
			),
			// thankyou
			// -------------------------------------------------------------------------
			// array(
			// 'name' => esc_html__('Add thankyou custom fields', 'woocommerce-checkout-manager'),
			// 'desc_tip' => esc_html__('Show the selected fields in the thankyou page.', 'woocommerce-checkout-manager'),
			// 'id' => 'wooccm_checkout_thankyou_custom_fields',
			// 'type' => 'select',
			// 'class' => 'chosen_select',
			// 'options' => array(
			// 'yes' => esc_html__('Yes', 'woocommerce-checkout-manager'),
			// 'no' => esc_html__('No', 'woocommerce-checkout-manager'),
			// ),
			// 'default' => 'no',
			// ),
			// array(
			// 'name' => esc_html__('Add thankyou custom fields title', 'woocommerce-checkout-manager'),
			// 'desc_tip' => esc_html__('Add custom title for the custom fields table in the thankyou page.', 'woocommerce-checkout-manager'),
			// 'id' => 'wooccm_checkout_thankyou_custom_fields_text',
			// 'type' => 'text',
			// 'placeholder' => esc_html__('Checkout extra', 'woocommerce-checkout-manager')
			// ),
			// upload
			// -------------------------------------------------------------------------
			// array(
			// 'name' => esc_html__('Add upload files limit', 'woocommerce-checkout-manager'),
			// 'desc_tip' => esc_html__('Add custom title for the custom fields table in the thankyou page.', 'woocommerce-checkout-manager'),
			// 'id' => 'wooccm_checkout_upload_files_limit',
			// 'type' => 'number',
			// 'placeholder' => 4
			// ),
			// array(
			// 'name' => esc_html__('Add upload files types', 'woocommerce-checkout-manager'),
			// 'desc_tip' => esc_html__('Add custom title for the custom fields table in the thankyou page.', 'woocommerce-checkout-manager'),
			// 'id' => 'wooccm_checkout_upload_files_types',
			// 'type' => 'text',
			// 'placeholder' => 'jpg,gif,png'
			// ),
			array(
				'type' => 'sectionend',
				'id'   => 'section_end',
			),
		);
	}

	public function add_header() {
		global $current_section;
		?>
	<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=wooccm&section' ) ); ?>" class="<?php echo ( '' == $current_section ? 'current' : '' ); ?>"><?php esc_html_e( 'Checkout', 'woocommerce-checkout-manager' ); ?></a> | </li>
		<?php
	}

	public function add_section() {
		global $current_section;

		if ( '' == $current_section ) {

			$settings = $this->get_settings();

			include_once WOOCCM_PLUGIN_DIR . 'lib/view/backend/pages/checkout.php';
		}
	}

	public function save_settings() {
		global $current_section;

		if ( '' == $current_section ) {

			woocommerce_update_options( $this->get_settings() );
		}
	}

	// Compatibility
	// ---------------------------------------------------------------------------

	public function additional_info( $value ) {
		$options = get_option( 'wccs_settings' );

		if ( ! empty( $options['checkness']['additional_info'] ) ) {
			return 'yes';
		}

		if ( ! empty( $options['checkness']['show_shipping_fields'] ) ) {
			return 'yes';
		}

		return $value;
	}

	public function auto_create_wccm_account( $value ) {
		$options = get_option( 'wccs_settings' );

		if ( ! empty( $options['checkness']['auto_create_wccm_account'] ) ) {
			return 'yes';
		}

		return $value;
	}

	public function notesenable( $value ) {
		$options = get_option( 'wccs_settings' );

		if ( ! empty( $options['checkness']['notesenable'] ) ) {
			return 'yes';
		}

		return $value;
	}

	public function noteslabel( $value ) {
		$options = get_option( 'wccs_settings' );

		$text = isset( $options['checkness']['noteslabel'] ) ? $options['checkness']['noteslabel'] : false;
		if ( $text ) {
			return $text;
		}

		return $value;
	}

	public function notesplaceholder( $value ) {
		$options = get_option( 'wccs_settings' );

		$text = isset( $options['checkness']['notesplaceholder'] ) ? $options['checkness']['notesplaceholder'] : false;
		if ( $text ) {
			return $text;
		}

		return $value;
	}

	public function text1( $value ) {
		$options = get_option( 'wccs_settings' );

		$text = isset( $options['checkness']['text1'] ) ? $options['checkness']['text1'] : false;
		if ( $text ) {
			return $text;
		}

		return $value;
	}

	public function text2( $value ) {
		$options = get_option( 'wccs_settings' );

		$text = isset( $options['checkness']['text2'] ) ? $options['checkness']['text2'] : false;
		if ( $text ) {
			return $text;
		}

		return $value;
	}
}
