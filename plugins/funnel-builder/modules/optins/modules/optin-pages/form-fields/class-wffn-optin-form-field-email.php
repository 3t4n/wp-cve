<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will control all Optin Email mapping functionality on optin submission.
 * Class WFFN_Optin_Form_Field_Email
 */
if ( ! class_exists( 'WFFN_Optin_Form_Field_Email' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Form_Field_Email extends WFFN_Optin_Form_Field {

		private static $ins = null;
		public static $slug = WFFN_Optin_Pages::WFOP_EMAIL_FIELD_SLUG;

		public $index = 30;

		/**
		 * WFFN_Optin_Form_Field_Email constructor.
		 */
		public function __construct() {
			add_filter( 'wfacp_default_values', [ $this, 'pre_populate_from_get_parameter' ], 10, 2 );
			parent::__construct();
		}

		/**
		 * @return WFFN_Optin_Form_Field_Email|null
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
			return __( 'Email', 'funnel-builder' );
		}

		public function get_field_output( $field_data ) {
			$field_data = wp_parse_args( $field_data, $this->get_field_format() );

			$name        = $this->get_prefix() . $this::get_slug();
			$width       = isset( $field_data['width'] ) ? esc_attr( $field_data['width'] ) : '';
			$label       = isset( $field_data['label'] ) ? esc_attr( $field_data['label'] ) : '';
			$placeholder = isset( $field_data['placeholder'] ) ? esc_attr( $field_data['placeholder'] ) : '';
			$required    = isset( $field_data['required'] ) ? esc_attr( $field_data['required'] ) : false;
			$hash        = isset( $field_data['hash_key'] ) ? esc_attr( $field_data['hash_key'] ) : '';
			$value       = $this->get_default_value( $field_data );
			$class       = $this->get_input_class( $field_data );
			?>
			<div class="bwfac_form_sec bwfac_form_field_email <?php echo esc_attr( $width ); ?>">
				<?php if ( ! empty( $label ) ) { ?>
					<label for="wfop_id_<?php echo esc_attr( $name ) . '_' . esc_attr( $hash ); ?>"><?php echo esc_html( $label );
						echo ( $required ) ? '<span>*</span>' : ''; ?> </label>
				<?php } ?>
				<div class="wfop_input_cont">
					<input id="wfop_id_<?php echo esc_attr( $name ) . '_' . esc_attr( $hash ); ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $class ) ?>" type="text" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
				</div>
			</div>
			<?php
		}

		public function get_default_value( $field_data ) {
			if ( ! empty( $field_data['default'] ) ) {
				return do_shortcode( $field_data['default'] );
			}

			if ( ! WFFN_Common::is_page_builder_editor() && true === apply_filters( 'wffn_optin_default_login_data', true, $field_data ) ) {
				global $current_user;
				if ( $current_user instanceof WP_User ) {
					return $current_user->user_email;
				}
			}

			return '';
		}

		/**
		 * @param $value
		 * @param $key
		 * @param $field
		 * Default add email in checkout form
		 *
		 * @return mixed|void
		 */
		public function pre_populate_from_get_parameter( $value, $key ) {

			if ( 'billing_email' === $key && class_exists( 'BWF_Optin_Tags' ) ) {
				$optin_tags = BWF_Optin_Tags::get_instance();
				$email      = $optin_tags->get_email( array( 'default' ) );

				return empty( $email ) ? $value : $email;
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
				'label'       => __( 'Email', 'funnel-builder' ),
				'placeholder' => '',
				'required'    => true,
				'InputName'   => $this->get_prefix() . $this::get_slug(),
				'default'     => '',
			);
		}

		public function get_sanitized_value( $data, $field ) {
			return isset( $data[ $field['InputName'] ] ) ? wffn_clean( $data[ $field['InputName'] ] ) : '';
		}
	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core()->form_fields->register( WFFN_Optin_Form_Field_Email::get_instance() );
	}
}
