<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\FieldBase;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Textarea Field.
 */
class TextAreaField extends FieldBase {


	/**
	 * Get Textarea Field HTML.
	 *
	 * @param boolean $return;
	 *
	 * @return mixed
	 */
	public function get_field_html( $return = false ) {
		if ( $return ) {
			ob_start();
		}
		?>
		<textarea <?php $this->field_id(); ?> <?php $this->field_classes( 'large-text' ); ?> <?php $this->field_name( $this->field ); ?> <?php $this->custom_attributes_html( $this->field ); ?> ><?php echo ( ( ! empty( $this->field['html_allowed'] ) && $this->field['html_allowed'] ) ? wp_kses_post( $this->field['value'] ) : esc_html( $this->field['value'] ) ); ?></textarea>
		<?php
		if ( $return ) {
			return ob_get_clean();
		}
	}

		/**
	 * Sanitize Field.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function sanitize_field($value)
	{
		return ( isset( $this->field['html_allowed'] ) ? wp_kses_post( $value ) : sanitize_textarea_field( $value ) );
	}

}
