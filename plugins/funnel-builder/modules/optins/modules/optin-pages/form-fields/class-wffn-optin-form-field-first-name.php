<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will control all Optin First Name mapping functionality on optin submission.
 * Class WFFN_Optin_Form_Field_First_Name
 */
if ( ! class_exists( 'WFFN_Optin_Form_Field_First_Name' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Form_Field_First_Name extends WFFN_Optin_Form_Field {

		private static $ins = null;
		public static $slug = WFFN_Optin_Pages::WFOP_FIRST_NAME_FIELD_SLUG;
		public $index = 10;

		/**
		 * WFFN_Optin_Form_Field_First_Name constructor.
		 */
		public function __construct() {
			add_filter( 'wfacp_default_values', [ $this, 'pre_populate_from_get_parameter' ], 10, 2 );
			parent::__construct();
		}

		/**
		 * @return WFFN_Optin_Form_Field_First_Name|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return string
		 */
		public static function get_slug() {
			return self::$slug;
		}

		/**
		 * Return title of this form field
		 */
		public function get_title() {
			return __( 'First Name', 'funnel-builder' );
		}

		public function get_field_output( $field_data ) {
			$field_data  = wp_parse_args( $field_data, $this->get_field_format() );
			$name        = $this->get_prefix() . $this::get_slug();
			$width       = isset( $field_data['width'] ) ? esc_attr( $field_data['width'] ) : '';
			$label       = isset( $field_data['label'] ) ? esc_attr( $field_data['label'] ) : '';
			$placeholder = isset( $field_data['placeholder'] ) ? esc_attr( $field_data['placeholder'] ) : '';
			$required    = isset( $field_data['required'] ) ? esc_attr( $field_data['required'] ) : false;
			$hash        = isset( $field_data['hash_key'] ) ? esc_attr( $field_data['hash_key'] ) : '';
			$value       = $this->get_default_value( $field_data );
			$class       = $this->get_input_class( $field_data );
			?>
			<div class="bwfac_form_sec bwfac_form_field_first_name <?php echo esc_attr( $width ); ?>">
				<?php if ( ! empty( $label ) ) { ?>
					<label for="wfop_id_<?php echo esc_attr( $name ) . '_' . esc_attr( $hash ); ?>"><?php echo esc_html( $label );
						echo ( $required ) ? '<span>*</span>' : ''; ?> </label>
				<?php } ?>
				<div class="wfop_input_cont">
					<input id="wfop_id_<?php echo esc_attr( $name ) . '_' . esc_attr( $hash ); ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $class ); ?>" type="text" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
				</div>
			</div>
			<?php
		}

		public function get_default_value( $field_data ) {
			if ( ! empty( $field_data['default'] ) ) {
				return do_shortcode( $field_data['default'] );
			}

			if( ! WFFN_Common::is_page_builder_editor() && true === apply_filters( 'wffn_optin_default_login_data', true, $field_data )) {
				global $current_user;
				if ( $current_user instanceof WP_User ) {
					return $current_user->first_name;
				}
			}

			return '';
		}

		/**
		 * @param $value
		 * @param $key
		 * @param $field
		 * Default add first name in checkout form
		 *
		 * @return mixed|void
		 */
		public function pre_populate_from_get_parameter( $value, $key ) {
			if ( empty( $key ) ) {
				return $value;
			}

			if ( class_exists( 'BWF_Optin_Tags' ) && in_array( $key, array( 'billing_first_name', 'shipping_first_name' ), true ) ) {
				$optin_tags = BWF_Optin_Tags::get_instance();
				$fnmae      = $optin_tags->get_first_name( array( 'default' ) );

				return empty( $fnmae ) ? $value : $fnmae;
			}

			return $value;
		}

		/**
		 * @return array
		 */
		public function get_field_format() {
			return array(
				'width'       => 'wffn-sm-100',
				'type'        => $this::get_slug(),
				'label'       => __( 'First Name', 'funnel-builder' ),
				'placeholder' => '',
				'required'    => true,
				'InputName'   => $this->get_prefix() . $this::get_slug(),
				'default'     => '',
			);
		}
	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core()->form_fields->register( WFFN_Optin_Form_Field_First_Name::get_instance() );
	}
}
