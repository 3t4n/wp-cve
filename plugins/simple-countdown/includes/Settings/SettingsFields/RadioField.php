<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\FieldBase;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Radio Field.
 */
class RadioField extends FieldBase {


	/**
	 * Get Radio Field HTML.
	 *
	 * @param boolean $return;
	 *
	 * @return mixed
	 */
	public function get_field_html( $return = false ) {
		if ( $return ) {
			ob_start();
		}
        foreach ( $this->field['options'] as $field_option ) :
		?>
		<div class="col d-flex-align-items-center mb-3">
			<div class="input w-100">
				<input type="radio" <?php $this->field_id(); ?> <?php $this->field_classes(); ?> <?php $this->field_name(); ?> value="<?php echo esc_attr( ! empty( $field_option['value'] ) ? $field_option['value'] : '' ); ?>" <?php $this->custom_attributes_html( $field_option ); ?> <?php echo esc_attr( ( empty( $field['value'] ) && ! empty( $field_option['default'] ) ) || ! empty( $field_option['value'] ) && $this->field['value'] === $field_option['value'] ? 'checked=checked' : '' ); ?> >
				<?php if ( ! empty( $field_option['input_suffix'] ) ) : ?>
					<?php echo esc_html( $field_option['input_suffix'] ); ?>
				<?php endif; ?>
				<?php if ( ! empty( $field_option['input_footer'] ) ) : ?>
					<h6 class="small text-muted mt-1 ms-4"><?php echo esc_html( $field_option['input_footer'] ); ?></h6>
				<?php endif; ?>
			</div>
		</div>
        <?php
        endforeach;
		if ( $return ) {
			return ob_get_clean();
		}
	}
}
