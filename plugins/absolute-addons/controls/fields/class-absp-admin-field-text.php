<?php
/**
 *
 *
 * @package Package
 * @author Name <email>
 * @version
 * @since
 * @license
 */

namespace AbsoluteAddons\Controls\Fields;

use AbsoluteAddons\Controls\ABSP_Admin_Field_Base;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

class ABSP_Admin_Field_Text extends ABSP_Admin_Field_Base {

	public function render() {

		$type = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';

		wp_kses_post_e( $this->field_before() );

		echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . esc_attr( $this->field_attributes() ) . ' />';

		wp_kses_post_e( $this->field_after() );

	}

}

// End of file class-absp-admin-field-base.php.
