<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\FieldBase;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Repeater Field.
 */
class RepeaterField extends FieldBase {

	/**
	 * Repeater Hooks.
	 *
	 * @return void
	 */
	protected function hooks() {
		add_action( $this->id . '-after-settings-field-' . ( ! empty( $this->field['filter'] ) ? $this->field['filter'] : $this->field['key'] ), array( $this, 'new_repeater_row_btn' ) );
	}

	/**
	 * New Repeater Row Btn.
	 *
	 * @return void
	 */
	public function new_repeater_row_btn() {
		$this->loader_icon( 'big', 'add-repeater-field-item-loader hidden mx-auto', 'width:60px;height:35px;' );
		?>
		<!-- Repeater Add Group Rule Button -->
		<button data-key="<?php echo esc_attr( $this->field['key'] ); ?>" data-action="<?php echo esc_attr( $this->id . '-' . '-get-repeater-item' ); ?>" data-target="<?php echo esc_attr( $this->id . '-' . $this->field['key'] . '-repeater-container' ); ?>" data-count="<?php echo esc_attr( count( $this->field['value'] ) ); ?>" class="my-4 btn btn-primary <?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-add-rule-group' ); ?>"><?php echo esc_html( ! empty( $this->field['repeat_add_label'] ) ? $this->field['repeat_add_label'] : esc_html__( 'Add rule group' ) ); ?></button>
		<?php
	}

	/**
	 * Get Repeater Field HTML.
	 *
	 * @param boolean $return;
	 *
	 * @return mixed
	 */
	public function get_field_html( $return = false ) {
		$settings_field = new Field();
		if ( $return ) {
			ob_start();
		}
		foreach ( $this->field['value'] as $index => $subitem_row ) {
			?>
			<div id="repeater-item-<?php echo esc_attr( $this->field['key'] . '-' . $index ); ?>" class="repeater-item position-relative <?php echo esc_attr( ! empty( $this->field['classes'] ) ? $this->field['classes'] : '' ); ?>">
				<div class="position-absolute top-0 end-0 bg-black" style="border-radius:50%;padding:4px 5px;margin:5px;">
					<button type="button" class="btn-close btn btn-close-white" aria-label="Close" style="opacity:1;"></button>
				</div>
				<div class="container-fluid">
					<div class="row mt-2">
					<?php
					foreach ( $subitem_row as $subitem_key => $subitem_value ) :
						$subitem_field                   = $this->field['default_subitem'][ $subitem_key ];
						$subitem_field['repeater_index'] = $index;
						$subitem_field['name']           = $this->id . '[' . $this->field['key'] . '][' . $index . '][' . $subitem_key . ']';
						$subitem_field['filter']         = $this->field['key'] . '-' . $subitem_key;
						$subitem_field['value']          = $subitem_value;
						$field                           = $settings_field->new_field( $this->id, $subitem_field );
						$field->get_field();
					endforeach
					?>
					</div>
				</div>
			</div>
			<?php
		}
		if ( $return ) {
			return ob_get_clean();
		}
	}

	/**
	 * Get Repeater Field Default Item HTML
	 *
	 * @param index $index
	 * @return string
	 */
	public function get_default_field( $index, $return = false ) {
		$settings_field = new Field();
		if ( $return ) {
			ob_start();
		}
		?>
		<div id="repeater-item-<?php echo esc_attr( $this->field['key'] . '-' . $index ); ?>" <?php $this->field_classes( 'repeater-item position-relative' ); ?> >
			<div class="position-absolute top-0 end-0 bg-black" style="border-radius:50%;padding:4px 5px;margin:5px;">
				<button type="button" class="btn-close btn btn-close-white" aria-label="Close" style="opacity:1;"></button>
			</div>
			<div class="container-fluid">
				<div class="row mt-2">
				<?php
				foreach ( $this->field['default_subitem'] as $subitem_key => $subitem_field ) {
					$subitem_field['key']            = $subitem_key;
					$subitem_field['repeater_index'] = $index;
					$subitem_field['name']           = $this->id . '[' . $this->field['key'] . '][' . $index . '][' . $subitem_key . ']';
					$subitem_field['filter']         = $this->field['key'] . '-' . $subitem_key;
					$subitem_settings_field          = $settings_field->new_field( $this->id, $subitem_field );
					$subitem_settings_field->get_field();
				}
				?>
				</div>
			</div>
		</div>
		<?php
		if ( $return ) {
			return ob_get_clean();
		}
	}

	/**
	 * Sanitize Submitted Repeater Field.
	 *
	 * @param string $key
	 * @param array  $settings
	 * @return mixed
	 */
	public function sanitize_field( $value ) {
		$settings              = array();
		$default_field_subitem = $this->field['default_subitem'];

		// Empty? bail.
		if ( empty( $_POST[ $this->id ][ $this->field['key'] ] ) ) {
			return $settings;
		}
		// Loop over the submitted array for index and sanitize each sub-item.
		foreach ( $_POST[ $this->id ][ $this->field['key'] ] as $item_index => $item_arr ) {
			$subitem    = array();
			$item_index = absint( sanitize_text_field( $item_index ) );
			foreach ( $default_field_subitem as $subitem_key => $subitem_arr ) {
				$posted_key              = array( $this->field['key'], $item_index, $subitem_key );
				$subitem[ $subitem_key ] = Settings::sanitize_submitted_field( $this->id, $posted_key, $default_field_subitem[ $subitem_key ], $default_field_subitem[ $subitem_key ]['value'] );
			}
			$settings[] = $subitem;
		}

		return $settings;
	}

	/**
	 * Get Empty Value.
	 *
	 * @return string
	 */
	public function get_empty_value() {
		return array();
	}
}
