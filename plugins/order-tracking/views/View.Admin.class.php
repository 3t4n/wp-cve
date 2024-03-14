<?php

/**
 * Base class for admin views, enqueues admin files, has functions for displaying editable custom fields.
 *
 * @since 3.0.0
 */
class ewdotpViewAdmin extends ewdotpView {

	/**
	 * Prints custom fields area
	 *
	 * @since 3.0.0
	 */
	public function print_admin_custom_field( $custom_field ) {
		global $ewd_otp_controller;

		$custom_field->field_value = $this->set_custom_field_value( $custom_field );

		$this->custom_field = $custom_field;

		if ( $custom_field->type == 'text' ) { $template = $this->find_template( 'admin-custom-field-text' ); }
		elseif ( $custom_field->type == 'textarea' ) { $template = $this->find_template( 'admin-custom-field-textarea' ); }
		elseif ( $custom_field->type == 'number' ) { $template = $this->find_template( 'admin-custom-field-number' ); }
		elseif ( $custom_field->type == 'select' ) { $template = $this->find_template( 'admin-custom-field-select' ); }
		elseif ( $custom_field->type == 'radio' ) { $template = $this->find_template( 'admin-custom-field-radio' ); }
		elseif ( $custom_field->type == 'checkbox' ) { $template = $this->find_template( 'admin-custom-field-checkbox' ); }
		elseif ( $custom_field->type == 'link' ) { $template = $this->find_template( 'admin-custom-field-link' ); }
		elseif ( $custom_field->type == 'file' ) { $template = $this->find_template( 'admin-custom-field-file' ); }
		elseif ( $custom_field->type == 'image' ) { $template = $this->find_template( 'admin-custom-field-image' ); }
		elseif ( $custom_field->type == 'date' ) { $template = $this->find_template( 'admin-custom-field-date' ); }
		elseif ( $custom_field->type == 'datetime' ) { $template = $this->find_template( 'admin-custom-field-datetime' ); }
		
		if ( $template ) {
			include( $template );
		}
	}

	/**
	 * Returns the value for a specified custom field. Should be overwritten by child class.
	 *
	 * @since 3.0.0
	 */
	public function set_custom_field_value( $custom_field ) {
		
		return null;
	}
}
