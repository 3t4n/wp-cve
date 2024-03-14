<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\FieldBase;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Select Field.
 */
class SelectField extends FieldBase {


	/**
	 * Get Select Field HTML.
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
		<select <?php $this->field_id(); ?> <?php $this->field_classes(); ?> <?php $this->field_name( $this->field ); ?> <?php $this->custom_attributes_html( $this->field ); ?> <?php echo esc_attr( ! empty( $this->field['multiple'] ) ? 'multiple' : '' ); ?> >
		<?php
		if ( ! empty( $this->field['options'] ) ) :
			foreach ( $this->field['options'] as $value => $label ) :
				?>
			<option <?php selected( $value, $this->field['value'] ); ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php
			endforeach;
		endif;
		?>
		</select>
        <?php
		if ( $return ) {
			return ob_get_clean();
		}
	}

	/**
	 * Get Empty Value.
	 *
	 * @return string
	 */
	public function get_empty_value() {
		return ! empty( $this->field['multiple'] ) ? array() : $this->field['value'];
	}

}
