<?php

namespace QuadLayers\WOOCCM\Controller;

use QuadLayers\WOOCCM\Controller\Field as Field;
use QuadLayers\WOOCCM\Plugin as Plugin;
use QuadLayers\WOOCCM\Model\Field_Additional as Field_Additional_Model;
use QuadLayers\WOOCCM\View\Frontend\Fields_Handler as Fields_Handler;

/**
 * Field_Additional Class
 */
class Field_Additional extends Field {

	protected static $_instance;
	public $additional;

	public function __construct() {

		Field_Additional_Model::instance();

		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'add_order_data' ) );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_order_data' ), 10, 2 );
		add_action( 'wooccm_sections_header', array( $this, 'add_header' ) );
		add_action( 'woocommerce_sections_' . WOOCCM_PREFIX, array( $this, 'add_section' ), 99 );
		add_action( 'woocommerce_settings_save_' . WOOCCM_PREFIX, array( $this, 'save_settings' ) );
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function save_order_data( $order_id, $data ) {

		$fields = Plugin::instance()->additional->get_fields();
		if ( count( $fields ) ) {

			$order = wc_get_order( $order_id );

			foreach ( $fields as $field_id => $field ) {

				$key = sprintf( '_%s', $field['key'] );

				if ( ! empty( $data[ $field['key'] ] ) ) {

					$value = $data[ $field['key'] ];
					if ( 'textarea' == $field['type'] ) {
						$order->update_meta_data( $key, wp_kses( $value, false ) );
					} elseif ( is_array( $value ) ) {
						$order->update_meta_data( $key, implode( ',', array_map( 'sanitize_text_field', $value ) ) );
					} else {
						$order->update_meta_data( $key, sanitize_text_field( $value ) );
					}
				}
			}

			$order->save();
		}
	}

	public function save_settings() {

		global $current_section;

		if ( 'additional' == $current_section ) {
			woocommerce_update_options( $this->get_settings() );
		}
	}

	public function get_settings() {

		return array(
			array(
				'desc_tip' => esc_html__( 'Select the position of the additional fields.', 'woocommerce-checkout-manager' ),
				'id'       => 'wooccm_additional_position',
				'type'     => 'select',
				// 'class' => 'chosen_select',
				'options'  => array(
					'before_billing_form' => esc_html__( 'Before billing form', 'woocommerce-checkout-manager' ),
					'after_billing_form'  => esc_html__( 'After billing form', 'woocommerce-checkout-manager' ),
					'before_order_notes'  => esc_html__( 'Before order notes', 'woocommerce-checkout-manager' ),
					'after_order_notes'   => esc_html__( 'After order notes', 'woocommerce-checkout-manager' ),
				),
				'default'  => 'before_order_notes',
			),
		);
	}

	// Admin Order
	// ---------------------------------------------------------------------------

	public function add_order_data( $order ) {

		Fields_Handler::instance();
		$fields = Plugin::instance()->additional->get_fields();
		if ( $fields ) {
			$template = Plugin::instance()->additional->get_template_types();
			$options  = Plugin::instance()->additional->get_option_types();
			$multiple = Plugin::instance()->additional->get_multiple_types();
			?>
			  </div>
			<style>
				#order_data .order_data_column {
				width: 23%;
				}
				#order_data .order_data_column_additional .form-field {
				width: 100%;
				clear: both;
				}
			</style>
			<div class="order_data_column order_data_column_additional">
				<h3>
					<?php esc_html_e( 'Additional', 'woocommerce-checkout-manager' ); ?>
				<a href="#" class="edit_address"><?php esc_html_e( 'Edit', 'woocommerce-checkout-manager' ); ?></a>
				<span>
					<a href="<?php echo esc_url( WOOCCM_PREMIUM_SELL_URL ); ?>" class="load_customer_additional" target="_blank" style="display:none;font-size: 13px;font-weight: 400;">
					<?php esc_html_e( 'This is a premium feature.', 'woocommerce-checkout-manager' ); ?>
					</a>
				</span>
				</h3>
				<div class="address">
					<?php
					foreach ( $fields as $field_id => $field ) {

						$key = sprintf( '_%s', $field['key'] );

						$value = $order->get_meta( $key, true );
						if ( ! $value ) {

							$value = maybe_unserialize( $order->get_meta( sprintf( '%s', $field['name'] ), true ) );

							if ( is_array( $value ) ) {
								$value = implode( ',', $value );
							}

							$order->update_meta_data( $key, $value );
							$order->delete_meta_data( sprintf( '%s', $field['name'] ) );
						}

						if ( $value ) {
							?>
							<p id="<?php echo esc_attr( $field['key'] ); ?>" class="form-field form-field-wide form-field-type-<?php echo esc_attr( $field['type'] ); ?>">
								<strong title="<?php echo esc_attr( sprintf( esc_html__( 'ID: %1$s | Field Type: %2$s', 'woocommerce-checkout-manager' ), $key, esc_html__( 'Generic', 'woocommerce-checkout-manager' ) ) ); ?>">
									<?php printf( '%s', esc_html( $field['label'] ) ? esc_html( $field['label'] ) : sprintf( esc_html__( 'Field %s', 'woocommerce-checkout-manager' ), esc_html( $field_id ) ) ); ?>
								</strong>
									<?php echo esc_html( $value ); ?>
							</p>
							<?php
						}
					}
					$order->save();
					?>
				</div>
				<div class="edit_address">
					<?php
					foreach ( $fields as $field_id => $field ) {

						if ( in_array( $field['type'], $template ) ) {
							continue;
						}

						$key = sprintf( '_%s', $field['key'] );

						$field['id']            = sprintf( '_%s', $field['key'] );
						$field['name']          = $field['key'];
						$field['value']         = null;
						$field['class']         = join( ' ', $field['class'] );
						$field['wrapper_class'] = 'wooccm-premium-field';

						$field['value'] = $order->get_meta( $key, true );
						if ( ! $field['value'] ) {

							$field['value'] = maybe_unserialize( $order->get_meta( sprintf( '%s', $field['name'] ), true ) );

							if ( is_array( $field['value'] ) ) {
								$field['value'] = implode( ',', $field['value'] );
							}
						}

						switch ( $field['type'] ) {
							case 'textarea':
								woocommerce_wp_textarea_input( $field );
								break;
							default:
								$field['type'] = 'text';
								woocommerce_wp_text_input( $field );
								break;
						}
					}
					?>
				</div>
			<?php
		}
	}

	// Admin
	// ---------------------------------------------------------------------------

	public function add_header() {
		global $current_section;
		?>
	  <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=wooccm&section=additional' ) ); ?>" class="<?php echo ( 'additional' == $current_section ? 'current' : '' ); ?>"><?php esc_html_e( 'Additional', 'woocommerce-checkout-manager' ); ?></a> | </li>
		<?php
	}

	public function add_section() {

		global $current_section, $wp_roles, $wp_locale;

		if ( 'additional' == $current_section ) {

			$fields             = Plugin::instance()->additional->get_fields();
			$defaults           = Plugin::instance()->additional->get_defaults();
			$types              = Plugin::instance()->additional->get_types();
			$conditionals       = Plugin::instance()->additional->get_conditional_types();
			$option             = Plugin::instance()->additional->get_option_types();
			$price              = Plugin::instance()->additional->get_price_types();
			$multiple           = Plugin::instance()->additional->get_multiple_types();
			$template           = Plugin::instance()->additional->get_template_types();
			$disabled           = Plugin::instance()->additional->get_disabled_types();
			$product_categories = $this->get_product_categories();
			$settings           = $this->get_settings();

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

			$is_billing_shipping = false;

			include_once WOOCCM_PLUGIN_DIR . 'lib/view/backend/pages/additional.php';
		}
	}
}
