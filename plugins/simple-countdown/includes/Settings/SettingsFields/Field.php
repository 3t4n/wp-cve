<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Base;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\{ TextAreaField, TextField, RepeaterField, SelectField, RadioField, CheckboxField };

/**
 * Field Template Class.
 *
 */
class Field extends Base {

	/**
	 * new Field.
	 *
	 * @param string $id
	 * @param array  $field
	 * @return FieldBase|null
	 */
	public static function new_field( $id, $field, $apply_hooks = true ) {
		switch ( $field['type'] ) {
			case 'repeater':
				return new RepeaterField( $id, $field, $apply_hooks );
				break;
			case 'email':
				return new EmailField( $id, $field, $apply_hooks );
				break;
			case 'text':
			case 'url':
			case 'datetime':
			case 'datetime-local':
				return new TextField( $id, $field, $apply_hooks );
				break;
			case 'textarea':
				return new TextAreaField( $id, $field, $apply_hooks );
				break;
			case 'select':
				return new SelectField( $id, $field, $apply_hooks );
				break;
			case 'checkbox':
				return new CheckboxField( $id, $field, $apply_hooks );
				break;
			case 'radio':
				return new RadioField( $id, $field, $apply_hooks );
				break;
		}

		return null;
	}

	/**
	 * Print Field HTML.
	 *
	 * @param array $field
	 * @return string
	 */
	public static function print_field( $field, $full_field = true, $echo = true, $ignore_hide = true ) {
		$id    = $field['base_id'] ?? self::$plugin_info['name'];
		$field = self::new_field( $id, $field );
		if ( is_null( $field ) ) {
			return;
		}

		if ( ! $echo ) {
			return $field->get_field( $full_field, $echo, $ignore_hide );
		}

		$field->get_field( $full_field, $echo, $ignore_hide );
	}

	/**
	 * List Fields HTML.
	 *
	 * @param array $fields
	 * @return void
	 */
	public static function print_fields( $fields, $full_field = true, $echo = true, $ignore_hide = true ) {
		$id = $fields['base_id'] ?? self::$plugin_info['name'];
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					<div class="settings-list row">

						<?php
						foreach ( $fields as $section_name => $section_arr ) :

							if ( ! empty( $section_arr['hide'] ) && ! $ignore_hide ) {
								continue;
							}
						?>

							<!-- Section -->
						<div class="tab-section-wrapper <?php echo esc_attr( 'tab-section-' . $section_name ); ?> col-12 my-3 p-3 bg-white shadow-lg<?php echo esc_attr( ! empty( $section_arr['section_classes'] ) ? ( ' ' . $section_arr['section_classes'] ) : '' ); ?>">

							<?php if ( ! empty( $section_arr['section_title'] ) ) : ?>
								<h4><?php echo esc_html( $section_arr['section_title'] ); ?></h4>
							<?php endif; ?>

							<?php if ( ! empty( $section_arr['section_heading'] ) ) : ?>
								<span><?php echo esc_html( $section_arr['section_heading'] ); ?></span>
							<?php endif; ?>

							<?php do_action( $id . '-before-settings-fields', $fields ); ?>

							<div class="container-fluid border mt-4">
								<?php
								foreach ( $section_arr['settings_list'] as $field_name => $field_arr ) :
									$field_arr = array_merge( array( 'key' => $field_name ), $field_arr );
									Field::print_field( $field_arr, $full_field, $echo, $ignore_hide );
								endforeach;
								?>
							</div>

							<?php do_action( $id . '-after-settings-fields', $fields ); ?>

						</div>

						<?php endforeach; ?>

					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
