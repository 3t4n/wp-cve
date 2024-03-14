<?php
/**
 * Change complete button border-radius property
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Changing_Complete_Button_Border_Radius_Property', false ) ) {
	/**
	 * LearnDash_PowerPack_Changing_Complete_Button_Border_Radius_Property Class.
	 */
	class LearnDash_PowerPack_Changing_Complete_Button_Border_Radius_Property {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Text label
		 *
		 * @var string
		 */
		public $text_label = 'ld_border_radius';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_action( 'wp_footer', [ $this, 'wp_footer_border_func' ] );
			}
		}

		/**
		 * Returns the style for WP footer.
		 */
		public function wp_footer_border_func() {
			$border = absint( $this->get_label_text() );

			if ( empty( $border ) ) {
				return;
			}
			?>
			<style>
				.ld-status.ld-status-complete {
					border-radius: <?php echo esc_html( $border . 'px' ); ?>;
				}
			</style>
			<?php
		}

		/**
		 * Get label text.
		 *
		 * @return String The label text.
		 */
		public function get_label_text() {
			$get_option = get_option( $this->current_class );
			if ( is_array( $get_option ) || is_object( $get_option ) ) {
				foreach ( $get_option as $key => $data_val ) {
					return $data_val['value'];
				}
			}

			return '';
		}

		/**
		 * Add the class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'button', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Changing Complete button border radius property', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to change border radius of complete button.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => $this->get_form_input_fields(),
			];
		}

		/**
		 * Returns the HTML for the input field.
		 *
		 * @return String The HTML code for create the input field.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div class=""><?php esc_html_e( 'Enter border radius for complete button', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="number" placeholder="" min="0" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Changing_Complete_Button_Border_Radius_Property();
}

