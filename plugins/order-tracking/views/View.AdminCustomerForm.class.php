<?php

/**
 * Class to display a customer add/edit form in the admin.
 *
 * @since 3.0.0
 */
class ewdotpAdminCustomerFormView extends ewdotpViewAdmin {

	/**
	 * Render the view and enqueue required stylesheets
	 * @since 3.0.0
	 */
	public function render() {
		global $ewd_otp_controller;

		ob_start();

		$template = $this->find_template( 'admin-customer-form' );
		
		if ( $template ) {
			include( $template );
		}

		$output = ob_get_clean();

		return apply_filters( 'ewd_otp_admin_customer_output', $output, $this );
	}

	/**
	 * Returns the value for a specified custom field.
	 *
	 * @since 3.0.0
	 */
	public function set_custom_field_value( $custom_field ) {
		global $ewd_otp_controller;

		return ! empty( $this->customer->custom_fields[ $custom_field->id ] ) ? $this->customer->custom_fields[ $custom_field->id ] : '';
	}
}
