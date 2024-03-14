<?php

/**
 * Class to display an order add/edit form in the admin.
 *
 * @since 3.0.0
 */
class ewdotpAdminOrderFormView extends ewdotpViewAdmin {

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 3.0.0
	 */
	public function render() {
		global $ewd_otp_controller;

		ob_start();

		$template = $this->find_template( 'admin-order-form' );
		
		if ( $template ) {
			include( $template );
		}

		$output = ob_get_clean();

		return apply_filters( 'ewd_otp_admin_order_output', $output, $this );
	}

	/**
	 * Returns the value for a specified custom field.
	 *
	 * @since 3.0.0
	 */
	public function set_custom_field_value( $custom_field ) {
		global $ewd_otp_controller;
		
		return ! empty( $this->order->custom_fields[ $custom_field->id ] ) ? $this->order->custom_fields[ $custom_field->id ] : '';
	}
}
