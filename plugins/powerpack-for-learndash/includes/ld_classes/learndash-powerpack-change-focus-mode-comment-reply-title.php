<?php
/**
 * Change focus mode comment reply title
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Change_Focus_Mode_Comment_Reply_Title', false ) ) {
	/**
	 * LearnDash_PowerPack_Change_Focus_Mode_Comment_Reply_Title Class.
	 */
	class LearnDash_PowerPack_Change_Focus_Mode_Comment_Reply_Title {
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
		public $text_label = 'learndash_focus_mode_comment_reply_title';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter(
					'learndash_focus_mode_comment_form_args',
					[ $this, 'learndash_focus_mode_comment_form_args_func' ]
				);
			}
		}

		/**
		 * Get the comment arguments.
		 *
		 * @param array $comment_arguments The comment.
		 *
		 * @return array The modified comment arguments.
		 */
		public function learndash_focus_mode_comment_form_args_func( $comment_arguments ) {
			$get_label_text = $this->get_label_text();
			if ( empty( $get_label_text ) ) {
				return $comment_arguments;
			}
			$comment_arguments['title_reply'] = $get_label_text;

			// Always return $attributes.
			return $comment_arguments;
		}

		/**
		 * Get the text for the label.
		 *
		 * @return array The data value.
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
		 * Add class details.
		 *
		 * @return array Class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'comment', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Focus mode reply title', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to change Focus Mode comment reply title.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => $this->get_form_input_fields(),
			];
		}

		/**
		 * Get the HTML of the input field arguments.
		 *
		 * @return String The HTML to create the input field.
		 */
		public function get_form_input_fields() {
			$get_label_text = $this->get_label_text();
			ob_start();
			?>
			<div class=""><?php esc_html_e( 'Title', 'learndash-powerpack' ); ?></div>
			<div class="">
				<input type="text" placeholder="" class="" value="<?php echo esc_html( $get_label_text ); ?>" name="<?php echo esc_html( $this->text_label ); ?>" data-type="text">
			</div>
			<?php
			$html_options = ob_get_clean();

			return $html_options;
		}
	}

	new LearnDash_PowerPack_Change_Focus_Mode_Comment_Reply_Title();
}

