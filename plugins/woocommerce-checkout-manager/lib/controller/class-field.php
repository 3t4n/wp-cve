<?php

namespace QuadLayers\WOOCCM\Controller;

use QuadLayers\WOOCCM\Plugin as Plugin;
use QuadLayers\WOOCCM\Controller\Controller as Controller;
use QuadLayers\WOOCCM\Controller\Field_Billing as Field_Billing;
use QuadLayers\WOOCCM\Controller\Field_Shipping as Field_Shipping;
use QuadLayers\WOOCCM\Controller\Field_Additional as Field_Additional;
use QuadLayers\WOOCCM\View\Frontend\Fields_Handler as Fields_Handler;
use QuadLayers\WOOCCM\View\Frontend\Fields_I18n as Fields_I18n;
use QuadLayers\WOOCCM\View\Frontend\Fields_Register as Fields_Register;
use QuadLayers\WOOCCM\View\Frontend\Fields_Additional as Fields_Additional;
use QuadLayers\WOOCCM\View\Frontend\Fields_Disable as Fields_Disable;
use QuadLayers\WOOCCM\View\Frontend\Fields_Conditional as Fields_Conditional;
use QuadLayers\WOOCCM\View\Frontend\Fields_Filter as Fields_Filter;
use QuadLayers\WOOCCM\View\Frontend\Fields_Validation as Fields_Validation;

/**
 * Field Class
 */
class Field extends Controller {

	protected static $_instance;
	public $billing;

	public function __construct() {

		Field_Billing::instance();
		Field_Shipping::instance();
		Field_Additional::instance();

		Fields_I18n::instance();

		if ( ! is_admin() ) {
			Fields_Register::instance();
			Fields_Additional::instance();
			Fields_Validation::instance();
			Fields_Disable::instance();
			Fields_Conditional::instance();
			Fields_Handler::instance();
			Fields_Filter::instance();
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_wooccm_load_parent', array( $this, 'ajax_load_parent' ) );
		add_action( 'wp_ajax_wooccm_load_field', array( $this, 'ajax_load_field' ) );
		add_action( 'wp_ajax_wooccm_save_field', array( $this, 'ajax_save_field' ) );
		add_action( 'wp_ajax_wooccm_delete_field', array( $this, 'ajax_delete_field' ) );
		add_action( 'wp_ajax_wooccm_reset_fields', array( $this, 'ajax_reset_fields' ) );
		add_action( 'wp_ajax_wooccm_change_field_attribute', array( $this, 'ajax_change_field_attribute' ) );
		add_action( 'wp_ajax_wooccm_toggle_field_attribute', array( $this, 'ajax_toggle_field_attribute' ) );
		add_action( 'woocommerce_settings_save_' . WOOCCM_PREFIX, array( $this, 'save_field_order' ) );
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function enqueue_scripts() {
		global $current_section;

		// $admin_field = include_once WOOCCM_PLUGIN_DIR . 'assets/backend/js/admin-field.asset.php';

		// wp_register_script( 'wooccm-admin-js', plugins_url( 'assets/backend/js/admin-field.js', WOOCCM_PLUGIN_FILE ), $admin_field['dependencies'], $admin_field['dependencies'], true );

		wp_localize_script(
			'wooccm-admin-js',
			'wooccm_field',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php?section=' . $current_section ),
				'nonce'    => wp_create_nonce( 'wooccm_field' ),
				'args'     => Plugin::instance()->billing->get_args(),
				'message'  => array(
					'remove' => esc_html__( 'Are you sure you want to remove this field?', 'woocommerce-checkout-manager' ),
					'reset'  => esc_html__( 'Are you sure you want to reset this fields?', 'woocommerce-checkout-manager' ),
				),
			)
		);

		if ( isset( $_GET['tab'] ) && WOOCCM_PREFIX === $_GET['tab'] ) {
			wp_enqueue_style( 'media-views' );
			wp_enqueue_script( 'wooccm-admin-js' );
		}
	}

	public function get_product_categories() {
		$args = array(
			'taxonomy'   => 'product_cat',
			'orderby'    => 'id',
			'order'      => 'ASC',
			'hide_empty' => true,
			'fields'     => 'all',
		);

		return get_terms( $args );
	}

	// Ajax
	// ---------------------------------------------------------------------------

	public function ajax_toggle_field_attribute() {
		if (
		current_user_can( 'manage_woocommerce' ) &&
		check_ajax_referer( 'wooccm_field', 'nonce' ) &&
		isset( $_REQUEST['section'] ) &&
		isset( $_REQUEST['field_id'] ) &&
		isset( $_REQUEST['field_attr'] )
		) {

			$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

			if ( isset( Plugin::instance()->$section ) ) {

				$field_id = wc_clean( wp_unslash( $_REQUEST['field_id'] ) );
				$attr     = wc_clean( wp_unslash( $_REQUEST['field_attr'] ) );

				$field = Plugin::instance()->$section->get_field( $field_id );
				if ( $field ) {

					// $value = $field[ $attr ] = ! (bool) @$field[ $attr ];

					$value          = isset( $field[ $attr ] ) ? ! (bool) $field[ $attr ] : false;
					$field[ $attr ] = isset( $field[ $attr ] ) ? ! (bool) $field[ $attr ] : false;

					Plugin::instance()->$section->update_field( $field );

					parent::success_ajax( $value );
				}
			}
		}

		parent::error_reload_page();
	}

	public function ajax_change_field_attribute() {
		if (
		current_user_can( 'manage_woocommerce' ) &&
		check_ajax_referer( 'wooccm_field', 'nonce' ) &&
		isset( $_REQUEST['section'] ) &&
		isset( $_REQUEST['field_id'] ) &&
		isset( $_REQUEST['field_attr'] ) &&
		isset( $_REQUEST['field_value'] )
		) {

			$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

			if ( isset( Plugin::instance()->$section ) ) {

				$field_id = wc_clean( wp_unslash( $_REQUEST['field_id'] ) );
				$attr     = wc_clean( wp_unslash( $_REQUEST['field_attr'] ) );

				$field = Plugin::instance()->$section->get_field( $field_id );
				if ( $field ) {

					$value = wc_clean( wp_unslash( $_REQUEST['field_value'] ) );

					$field[ $attr ] = wc_clean( wp_unslash( $_REQUEST['field_value'] ) );

					$field = Plugin::instance()->$section->update_field( $field );

					parent::success_ajax( $value );
				}
			}
		}

		parent::error_reload_page();
	}

	public function ajax_save_field() {
		if ( isset( $_REQUEST['field_data'] ) && current_user_can( 'manage_woocommerce' ) && check_ajax_referer( 'wooccm_field', 'nonce', false ) ) {

			// phpcs:ignore
			$field_data           = json_decode( wp_unslash( $_REQUEST['field_data'] ), true );
			$field_data_sanitized = wc_clean( $field_data );

			$field_data_sanitized['description'] = wp_kses_post( $field_data['description'] );

			if ( is_array( $field_data_sanitized ) ) {

				$field_data_sanitized = $this->order_options( $field_data_sanitized );
				if ( isset( $field_data_sanitized['id'] ) ) {

					unset( $field_data_sanitized['show_product_selected'] );
					unset( $field_data_sanitized['hide_product_selected'] );

					return parent::success_ajax( $this->save_modal_field( $field_data_sanitized ) );
				} else {
					return parent::success_ajax( $this->add_modal_field( $field_data_sanitized ) );
				}
			}
		}

		return parent::error_reload_page();
	}

	public function order_options( $field ) {
		if ( count( $field['options'] ) < 2 ) {
			return $field;
		}
		usort(
			$field['options'],
			function ( $item1, $item2 ) {
			return intval( $item1['order'] ) <=> intval( $item2['order'] );
			}
		);
		return $field;
	}

	public function ajax_delete_field() {
		if (
		current_user_can( 'manage_woocommerce' ) &&
		check_ajax_referer( 'wooccm_field', 'nonce' ) &&
		isset( $_REQUEST['field_id'] )
		) {

			$field_id = wc_clean( wp_unslash( $_REQUEST['field_id'] ) );

			if ( $this->delete_field( $field_id ) ) {

				parent::success_ajax( $field_id );
			}
		}

		parent::error_reload_page();
	}

	public function ajax_reset_fields() {
		if (
		current_user_can( 'manage_woocommerce' ) &&
		check_ajax_referer( 'wooccm_field', 'nonce' ) &&
		isset( $_REQUEST['section'] )
		) {

			$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

			if ( isset( Plugin::instance()->$section ) ) {

				Plugin::instance()->$section->delete_fields();

				parent::success_ajax();
			}
		}

		parent::error_reload_page();
	}

	public function ajax_load_field() {
		if (
		current_user_can( 'manage_woocommerce' ) &&
		check_ajax_referer( 'wooccm_field', 'nonce' ) &&
		isset( $_REQUEST['field_id'] )
		) {

			$field_id = wc_clean( wp_unslash( $_REQUEST['field_id'] ) );

			$field = $this->get_modal_field( $field_id );
			if ( $field ) {
				parent::success_ajax( $field );
			}

			parent::error_ajax( esc_html__( 'Undefined field id', 'woocommerce-checkout-manager' ) );
		}

		parent::error_reload_page();
	}

	// Modal
	// ---------------------------------------------------------------------------

	public function get_modal_field( $field_id ) {
		if ( array_key_exists( 'section', $_REQUEST ) ) {

			$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

			if ( isset( Plugin::instance()->$section ) ) {
				$fields = Plugin::instance()->$section->get_fields();
				if ( $fields ) {

					if ( isset( $fields[ $field_id ] ) ) {

						$field = $fields[ $field_id ];

						if ( ! empty( $field['show_product'] ) ) {
							$field['show_product_selected'] = array_filter( array_combine( (array) $field['show_product'], array_map( 'get_the_title', (array) $field['show_product'] ) ) );
						} else {
							$field['show_product_selected'] = array();
						}
						if ( ! empty( $field['hide_product'] ) ) {
							$field['hide_product_selected'] = array_filter( array_combine( (array) $field['hide_product'], array_map( 'get_the_title', (array) $field['hide_product'] ) ) );
						} else {
							$field['hide_product_selected'] = array();
						}

						if ( ! empty( $field['conditional_parent_key'] ) && $field['conditional_parent_key'] != $field['key'] ) {

							// $parent_id = @max(array_keys(array_column($fields, 'key'), $field['conditional_parent_key']));
							$parent_id = Plugin::instance()->$section->get_field_id( $fields, 'key', $field['conditional_parent_key'] );

							if ( isset( $fields[ $parent_id ] ) ) {
								$field['parent'] = $fields[ $parent_id ];
							}
						}

						// don't remove empty attr because previus data remain
						// $field = array_filter($field);

						return $field;
					}
				}
			}
		}
	}

	public function ajax_load_parent() {
		if ( ! empty( $_REQUEST['conditional_parent_key'] ) ) {

			$key = wc_clean( wp_unslash( $_REQUEST['conditional_parent_key'] ) );

			if ( array_key_exists( 'section', $_REQUEST ) ) {

				$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

				if ( isset( Plugin::instance()->$section ) ) {

					$fields = Plugin::instance()->$section->get_fields();
					if ( $fields ) {

						$parent_id = Plugin::instance()->$section->get_field_id( $fields, 'key', $key );

						if ( isset( $fields[ $parent_id ] ) ) {
							parent::success_ajax( $fields[ $parent_id ] );
						}
					}
				}
			}
		}
	}

	// Save
	// ---------------------------------------------------------------------------

	public function save_modal_field( $field_data ) {
		if ( array_key_exists( 'section', $_REQUEST ) ) {

			$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

			if ( isset( Plugin::instance()->$section ) ) {

				$field_data = wp_parse_args( $field_data, Plugin::instance()->$section->get_args() );

				/**
				 * Don't override this fields, they are handled trough the interface toggles.
				*/
				unset( $field_data['order'] );
				unset( $field_data['required'] );
				// unset($field_data['position']);
				unset( $field_data['clear'] );
				unset( $field_data['disabled'] );

				return Plugin::instance()->$section->update_field( $field_data );
			}
		}
	}

	public function add_modal_field( $field_data ) {
		if ( array_key_exists( 'section', $_REQUEST ) ) {

			$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

			if ( isset( Plugin::instance()->$section ) ) {

				return Plugin::instance()->$section->add_field( $field_data );
			}
		}
	}

	public function delete_field( $field_id ) {
		if ( array_key_exists( 'section', $_REQUEST ) ) {

			$section = wc_clean( wp_unslash( $_REQUEST['section'] ) );

			if ( isset( Plugin::instance()->$section ) ) {

				return Plugin::instance()->$section->delete_field( $field_id );
			}
		}
	}

	public function save_field_order() {
		global $current_section;
		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( wc_clean( wp_unslash( $_POST['_wpnonce'] ) ), 'woocommerce-settings' ) ) {

			if ( in_array( $current_section, array( 'billing', 'shipping', 'additional' ) ) ) {

				$section = wc_clean( wp_unslash( $current_section ) );

				if ( array_key_exists( 'field_order', $_POST ) ) {

					$field_order = wc_clean( wp_unslash( $_POST['field_order'] ) );

					if ( is_array( $field_order ) && count( $field_order ) > 0 ) {

						if ( isset( Plugin::instance()->$section ) ) {

							$fields = Plugin::instance()->$section->get_fields();

							$loop = 1;

							foreach ( $field_order as $field_id ) {

								if ( isset( $fields[ $field_id ] ) ) {

									$fields[ $field_id ]['order'] = $loop;

									$loop++;
								}
							}

							Plugin::instance()->$section->update_fields( $fields );
						}
					}
				}
			}
		}
	}
}
