<?php

namespace QuadLayers\WOOCCM\View\Frontend;

/**
 * Fields_Additional Class
 */
class Fields_Additional {

	protected static $_instance;

	public function __construct() {

		// Compatibility
		// -----------------------------------------------------------------------
		add_filter( 'default_option_wooccm_additional_position', array( $this, 'position' ) );

		// Additional fields
		// -----------------------------------------------------------------------

		switch ( get_option( 'wooccm_additional_position', 'before_order_notes' ) ) {

			case 'before_billing_form':
				add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'add_additional_fields' ) );
				break;

			case 'after_billing_form':
				add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'add_additional_fields' ) );
				break;

			case 'before_order_notes':
				add_action( 'woocommerce_before_order_notes', array( $this, 'add_additional_fields' ) );
				break;

			case 'after_order_notes':
				add_action( 'woocommerce_after_order_notes', array( $this, 'add_additional_fields' ) );
				break;
		}
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function add_additional_fields( $checkout ) {
		?>
			<div class="wooccm-additional-fields">
				<?php

				$fields = WC()->checkout->get_checkout_fields();

				if ( ! empty( $fields['additional'] ) ) {

					foreach ( $fields['additional'] as $key => $field ) {

						if ( empty( $field['disabled'] ) ) {

							woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
						}
					}
				}
				?>
				<div class="wooccm-clearfix"></div>
			</div>
		<?php
	}

	public function position( $position = 'before_order_notes' ) {

		$options = get_option( 'wccs_settings' );

		if ( ! empty( $options['checkness']['position'] ) ) {

			$positon = sanitize_text_field( $options['checkness']['position'] );

			switch ( $position ) {
				case 'before_shipping_form':
					$position = 'after_billing_form';
					break;

				case 'after_shipping_form':
					$position = 'before_order_notes';
					break;
			}
		}

		return $position;
	}
}
