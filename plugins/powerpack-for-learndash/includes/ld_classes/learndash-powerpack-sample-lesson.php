<?php
/**
 * Change sample lesson lable
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Sample_Lesson', false ) ) {
	/**
	 * LearnDash_PowerPack_Sample_Lesson Class.
	 */
	class LearnDash_PowerPack_Sample_Lesson {
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
		public $text_label = 'learndash_sample_lesson_lable';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'learndash_lesson_attributes', [ $this, 'learndash_lesson_attributes_func' ], 10, 2 );
			}
		}

		/**
		 * Returns the lesson attributes.
		 *
		 * @param array $attributes The attributes for the lesson.
		 * @param array $lesson The lesson.
		 *
		 * @return array The modified attributes array.
		 */
		public function learndash_lesson_attributes_func( $attributes, $lesson ) {
			$get_label_text = $this->get_label_text();

			if ( empty( $get_label_text ) ) {
				return $attributes;
			}
			if ( 'is_sample' === $lesson['sample'] ) {
				$attributes[0]['label'] = $get_label_text;
			}

			// Always return $attributes.
			return $attributes;
		}

		/**
		 * Get the label text.
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
			$ld_type           = esc_html__( 'lesson', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Sample Lesson Label', 'learndash-powerpack' );
			$class_description = esc_html__( 'Change "Sample Lesson" label.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => $this->get_form_input_fields(),
			];
		}

		/**
		 * Get the HTML code to create the input field.
		 *
		 * @return String The HTML code to create the input fiel.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div class=""><?php esc_html_e( 'Sample Lession Label', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Sample_Lesson();
}

