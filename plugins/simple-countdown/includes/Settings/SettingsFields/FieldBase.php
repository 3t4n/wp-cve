<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Base;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase\Settings;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Utils\GeneralUtilsTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Utils\NoticeUtilsTrait;

/**
 * Settings Field.
 */
abstract class FieldBase extends Base {
	use GeneralUtilsTrait, NoticeUtilsTrait;

	/**
	 * Settings ID.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Field Data.
	 *
	 * @var array
	 */
	protected $field;

	/**
	 * Field Identifier.
	 *
	 * @var string
	 */
	protected $identifier;

	/**
	 * Default Attributes for Kses.
	 *
	 * @var array
	 */
	protected $default_attrs = array(
		'class',
		'id',
		'name',
		'value',
		'type',
		'selected',
		'checked',
		'placeholder',
	);

	/**
	 * Default Input Fields for Kses.
	 *
	 * @var array
	 */
	protected $default_fields = array(
		'input',
		'select',
		'textarea',
		'option',
	);

	/**
	 * Field Constructor.
	 *
	 * @param array $this->field
	 */
	public function __construct( $id, $field, $apply_hooks = true ) {
		$this->id         = $id;
		$this->field      = $field;
		$this->identifier = wp_generate_uuid4();

		if ( $apply_hooks && method_exists( $this, 'hooks' ) ) {
			$this->hooks();
		}
	}

	/**
	 * Get Field HTML.
	 *
	 * @return string
	 */
	abstract public function get_field_html();

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function _get_field_html() {
		if ( ! empty( $this->field['custom_input'] ) ) {
			do_action( $this->id . '-settings-field-html-' . $this->field['key'], $this->field );
		} else {
			$this->get_field_html();
		}
	}

	/**
	 * Get Custom Attributes HTML.
	 *
	 * @return void
	 */
	protected function custom_attributes_html() {
		$attributes = '';
		if ( ! empty( $this->field['attrs'] ) && is_array( $this->field['attrs'] ) ) {
			foreach ( $this->field['attrs'] as $key => $value ) {
				$attributes .= esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
			}
		}
		if ( ! empty( $attributes ) ) {
			echo wp_kses_post( $attributes );
		}
	}

	/**
	 * Field Name
	 *
	 * @return void
	 */
	protected function field_name() {
		if ( ! empty( $this->field['noname'] ) ) {
			return;
		}
		echo esc_attr( 'name' ) . '="' . esc_attr( ! empty( $this->field['name'] ) ? $this->field['name'] : $this->id . '[' . $this->field['key'] . ']' . ( ! empty( $this->field['subkey'] ) ? '[' . $this->field['subkey'] . ']' : '' ) . ( ! empty( $this->field['multiple'] ) ? '[]' : '' ) ) . '"';
	}

	/**
	 * Field Classes.
	 *
	 * @return void
	 */
	protected function field_classes( $additional_classes = '' ) {
		echo esc_attr( 'class' ) . '="' . ( ! empty( $this->field['classes'] ) ? esc_attr( $this->field['classes'] ) : '' )  . esc_attr( ! empty( $additional_classes ) ? ' ' . $additional_classes . ' ' : '' ) . '"';
	}

	/**
	 * Field ID.
	 *
	 * @return void
	 */
	protected function field_id() {
		echo esc_attr( 'id' ) . '="' . esc_attr( ! empty( $this->field['id'] ) ? $this->field['id'] : $this->field['key'] ) . '"';
	}

	/**
	 * Settings Field HTML.
	 *
	 * @param array $this->field
	 * @return void|string
	 */
	public function add_field( $echo = true ) {
		if ( ! $echo ) {
			ob_start();
		}

		$is_inline = ( ! isset( $this->field['inline'] ) || true === $this->field['inline'] );
		?>
		<?php $this->settings_field_wrapper_start(); ?>

		<!-- Field Label -->
		<?php if ( ! empty( $this->field['input_label'] ) ) : ?>
			<div class="<?php echo esc_attr( $is_inline ? 'col-lg-3' : 'col-12' ); ?> d-flex flex-column <?php echo esc_attr( ! empty( $this->field['center_label'] ) ? 'align-items-center' : 'align-items-start' ); ?> mb-4 mb-md-0">
				<h6 class="<?php echo esc_attr( ! empty( $this->field['input_label_classes'] ) ? $this->field['input_label_classes'] : '' ); ?>"><?php echo wp_kses_post( $this->field['input_label'] ); ?></h6>
				<!-- Field Label subheading -->
				<?php if ( ! empty( $this->field['input_label_subheading'] ) ) : ?>
				<p class="text-small text-muted d-block mt-2 <?php echo esc_attr( $this->field['input_label_subheading_classes'] ?? '' ); ?>"><?php echo wp_kses_post( $this->field['input_label_subheading'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- Field input -->
		<div class="<?php echo esc_attr( ! empty( $this->field['input_label'] ) ? ( $is_inline ? 'col-lg-9' : 'col-12' ) : 'col-12' ); ?> d-flex flex-column align-items-start">
			<div class="input w-100 <?php echo esc_attr( 'repeater' === $this->field['type'] ? $this->id . '-' . $this->field['key'] . '-repeater-container' : '' ); ?>">
				<!-- Input Heading -->
				<?php if ( ! empty( $this->field['input_heading'] ) ) : ?>
					<h6 class="text-muted"><?php echo wp_kses_post( $this->field['input_heading'] ); ?></h6>
				<?php endif; ?>

				<!-- Input  -->
				<?php
				$this->_get_field_html();
				do_action( $this->id . '-just-after-settings-field-' . $this->field['key'], $this->field );
				?>

				<!-- Input Suffix -->
				<?php if ( ! empty( $this->field['input_suffix'] ) ) : ?>
					<small class="text-muted"><?php echo wp_kses_post( $this->field['input_suffix'] ); ?></small>
				<?php endif; ?>

				<!-- Input Footer -->
				<?php if ( ! empty( $this->field['input_footer'] ) ) : ?>
					<small class="small text-muted mt-1 d-block"><?php echo wp_kses_post( $this->field['input_footer'] ); ?></small>
				<?php endif; ?>
			</div>
		</div>
		<?php
		do_action( $this->id . '-after-settings-field-' . ( ! empty( $this->field['filter'] ) ? $this->field['filter'] : $this->field['key'] ), $this->field );

		$this->settings_field_wrapper_end();

		if ( ! $echo ) {
			return ob_get_clean();
		}
	}

	/**
	 * Settings Field Row Wrapper Start.
	 *
	 * @return void
	 */
	protected function settings_field_wrapper_start() {
		do_action( $this->id . '-before-settings-field', $this->field );
		$collapse_source = ! empty( $this->field['collapse'] ) ? $this->field['collapse']['collapse_source'] : false;
		if ( $collapse_source ) {
			$collapse_value = $this->field['collapse']['collapse_value'];
		}
		?>
	   <div
	   		class="settings-field-wrapper<?php echo esc_attr( ! empty( $collapse_source ) ? ' collapse ' : '' ); ?><?php echo esc_attr( ! empty( $this->field['wrapper_padding'] ) ? $this->field['wrapper_padding'] : ' py-4' ); ?><?php echo esc_attr( ! empty( $this->field['wrapper_margin'] ) ? $this->field['wrapper_margin'] : ' my-4' ); ?> <?php echo esc_attr( ! empty( $this->field['wrapper_classes'] ) ? $this->field['wrapper_classes'] : '' ); ?>" <?php $this->custom_attributes_html( ! empty( $this->field['wrapper_attrs'] ) ? $this->field['wrapper_attrs'] : '' ); ?>
			<?php echo esc_attr( ! empty( $collapse_source ) ? 'data-collapse-source' : '' ) . ( ! empty( $collapse_source ) ? '="' : '' ) . ( ! empty( $collapse_source ) ? esc_attr(  $collapse_source ) : '' ) . ( ! empty( $collapse_source ) ? '"' : '' ); ?>
			<?php echo esc_attr( ! empty( $collapse_value ) ? 'data-collapse-value' : '' ) . ( ! empty( $collapse_value ) ? '="' : '' ) . ( ! empty( $collapse_value ) ? esc_attr(  $collapse_value ) : '' ) . ( ! empty( $collapse_value ) ? '"' : '' ); ?>
		>
		   <div class="container-fluid <?php echo esc_attr( $this->field['container_classes'] ?? '' ); ?>">
			   <div class="row">
				   <?php if ( ! empty( $this->field['field_heading'] ) ) : ?>
				   <div class="col-12">
					   <h4 class="field-heading <?php echo esc_attr( $this->field['field_heading_classes'] ?? '' ); ?>"><?php echo wp_kses_post( $this->field['field_heading'] ); ?></h4>
				   </div>
				   <?php endif; ?>
			   <?php
	}

	/**
	 * Settings Field Row Wrapper End.
	 *
	 * @return void
	 */
	protected function settings_field_wrapper_end() {
		?>
				</div>
			</div>
		</div>
		<?php
		do_action( $this->id . '-after-settings-field', $this->field );
	}

	/**
	 * Get Field HTML.
	 *
	 * @param array   $this->fieldget_field
	 * @param boolean $full_field
	 * @return boolean
	 */
	public function get_field( $full_field = true, $echo = true, $ignore_hide = false ) {
		if ( $ignore_hide ) {
			unset( $this->field['hide'] );
		}

		if ( ! empty( $this->field['hide'] ) ) {
			return;
		}

		if ( ! empty( $this->field['custom'] ) ) {
			if ( ! $echo ) {
				ob_start();
			}
			do_action( $this->id . '-' . $this->field['filter'] . '-settings-field-custom', $this->field );
			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		if ( ! $echo ) {
			ob_start();
		}

		if ( $full_field ) {
			$this->add_field( $echo );
		} else {
			$this->get_field_html( ! $echo );
		}

		if ( ! $echo ) {
			return ob_get_clean();
		}
	}

	/**
	 * Kses Settings Fields.
	 *
	 * @param string $html
	 * @param array  $this->field
	 * @return string
	 */
	protected function kses_field( $html ) {
		$attrs = array();
		if ( ! empty( $this->field['attrs'] ) ) {
			$attrs = array_keys( $this->field['attrs'] );
		}

		if ( ! empty( $this->field['options'] ) ) {
			$attrs = array_unique(
				array_merge(
					...array_map(
						function( $field_attr ) {
							return array_keys( $field_attr );
						},
						array_values( array_column( $this->field['options'], 'attrs' ) )
					)
				)
			);
		}

		$default_attrs       = $this->default_attrs;
		$this->default_attrs = array_merge( $this->default_attrs, $attrs );
		add_filter( 'wp_kses_allowed_html', array( $this, 'allow_inputs_in_kses' ), 100, 2 );
		$html = wp_kses_post( $html );
		remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_inputs_in_kses' ), 100 );
		$this->default_attrs = $default_attrs;
		return $html;
	}

	/**
	 * Allow input fields for Kses.
	 *
	 * @param array  $allowed_tags
	 * @param string $context
	 * @return array
	 */
	public function allow_inputs_in_kses( $allowed_tags, $context ) {
		foreach ( $this->default_fields as $this->field ) {
			$allowed_tags[ $this->field ] = $this->default_attrs;
			foreach ( $allowed_tags[ $this->field ] as $attr ) {
				$allowed_tags[ $this->field ][ $attr ] = array();
			}
		}
		return $allowed_tags;
	}

	/**
	 * Sanitize Field.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function sanitize_field( $value ) {
		return is_array( $value ) ? self::deep_sanitize_field( $value ) : sanitize_text_field( $value );
	}

	/**
	 * Get Empty Value.
	 *
	 * @return string
	 */
	public function get_empty_value() {
		return '';
	}

	/**
	 * Get Default Value.
	 *
	 * @return string
	 */
	public function get_default_value() {
		return isset( $this->field['value'] ) ? $this->field['value'] : $this->get_empty_value();
	}

	/**
	 * Get Field Type Name.
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->field['type'];
	}
}
