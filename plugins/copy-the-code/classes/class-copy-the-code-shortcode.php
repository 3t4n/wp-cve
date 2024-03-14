<?php
/**
 * Shortcode
 *
 * @package Copy the Code
 * @since 2.2.0
 */

use CopyTheCode\Helpers;

if ( ! class_exists( 'Copy_The_Code_Shortcode' ) ) :

	/**
	 * Shortcode
	 *
	 * @since 2.2.0
	 */
	class Copy_The_Code_Shortcode {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class Instance.
		 * @since 2.2.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 2.2.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_shortcode( 'copy', [ $this, 'shortcode_markup' ] );
			add_shortcode( 'copy_inline', [ $this, 'copy_inline_markup' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 3.1.0
		 */
		public function enqueue_scripts() {
			// Core.
			wp_enqueue_script( 'ctc-clipboard', COPY_THE_CODE_URI . 'assets/js/clipboard.js', [ 'jquery' ], COPY_THE_CODE_VER, true );

			// Shortcode.
			wp_enqueue_style( 'ctc-copy-inline', COPY_THE_CODE_URI . 'assets/css/copy-inline.css', [], COPY_THE_CODE_VER );
			wp_enqueue_script( 'ctc-copy-inline', COPY_THE_CODE_URI . 'assets/js/copy-inline.js', [ 'jquery' ], COPY_THE_CODE_VER, true );
		}

		/**
		 * Copy inline markup
		 *
		 * @since 3.1.0
		 *
		 * @param array $atts Shortcode parameters.
		 * @param mixed $content Shortcode content.
		 *
		 * @return mixed
		 */
		public function copy_inline_markup( $atts = [], $content = '' ) {
			$atts = shortcode_atts(
				[
					'text'    => '',
					'display' => '',
					'hidden'  => '',
					'style'   => '',
					'tooltip' => esc_html__( 'Copied', 'copy-the-code' ),
				],
				$atts
			);

			$text    = $atts['text'] ? $atts['text'] : $content;
			$display = $atts['display'] ? $atts['display'] : $atts['text'];
			$style   = $atts['style'] ? 'ctc-inline-style-' . $atts['style'] : '';
			$hidden  = 'yes' === $atts['hidden'] ? 'ctc-inline-hidden' : '';
			$tooltip = esc_html__( 'Copied', 'copy-the-code' );

			ob_start();
			?>
			<span class="ctc-inline-copy <?php echo esc_attr( $style ); ?>" aria-label="<?php echo esc_attr( $tooltip ); ?>">
				<span class="ctc-inline-copy-text <?php echo esc_attr( $hidden ); ?>"><?php echo esc_html( $display ); ?></span>
				<textarea style="display: none;" class="ctc-inline-copy-textarea" readonly="readonly"><?php echo esc_html( $text ); ?></textarea>
				<span class="ctc-inline-copy-icon" role="button" aria-label="<?php echo esc_attr( $tooltip ); ?>">
					<?php echo Helpers::get_svg_copy_icon(); ?>
					<?php echo Helpers::get_svg_checked_icon(); ?>
				</span>
			</span>
			<?php
			return ob_get_clean();
		}

		/**
		 * Shortcode markup
		 *
		 * @since 2.2.0
		 * @param array $atts Shortcode parameters.
		 * @param mixed $content Shortcode content.
		 * @return mixed
		 */
		public function shortcode_markup( $atts = [], $content = '' ) {
			$atts = apply_filters(
				'copy_the_code_shortcode_atts',
				shortcode_atts(
					[
						'target'      => '',
						'title'       => __( 'Copy to Clipboard', 'copy-the-code' ),
						'text'        => $content,
						'copied-text' => 'Copied to Clipboard',
						'tag'         => 'span',
						'class'       => '',
						'copy-as'     => 'text',
						'content'     => $content,
						'link'        => isset( $atts['link'] ) ? $atts['link'] : '',
						'style'       => '',
						'color'       => '',
						'icon-color'  => '',
					],
					$atts
				)
			);

			$color           = $atts['color'];
			$display_content = wp_kses_post( $atts['text'] );
			if ( 'icon' === $atts['style'] ) {
				$icon_color = ! empty( $atts['icon-color'] ) ? $atts['icon-color'] : '#b5b5b5';

				$display_content = '<svg style="fill: ' . esc_attr( $icon_color ) . '" viewBox="-21 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m186.667969 416c-49.984375 0-90.667969-40.683594-90.667969-90.667969v-218.664062h-37.332031c-32.363281 0-58.667969 26.300781-58.667969 58.664062v288c0 32.363281 26.304688 58.667969 58.667969 58.667969h266.664062c32.363281 0 58.667969-26.304688 58.667969-58.667969v-37.332031zm0 0"></path><path d="m469.332031 58.667969c0-32.40625-26.261719-58.667969-58.664062-58.667969h-224c-32.40625 0-58.667969 26.261719-58.667969 58.667969v266.664062c0 32.40625 26.261719 58.667969 58.667969 58.667969h224c32.402343 0 58.664062-26.261719 58.664062-58.667969zm0 0"></path></svg>';
			}

			return '<' . esc_html( $atts['tag'] ) . ' title="' . esc_attr( $atts['title'] ) . '" class="copy-the-code-shortcode ' . esc_attr( $atts['class'] ) . '" data-target="' . esc_attr( $atts['target'] ) . '" data-button-text="' . esc_attr( $atts['text'] ) . '" data-button-copy-text="' . esc_attr( $atts['copied-text'] ) . '" data-content="' . esc_attr( wp_strip_all_tags( $atts['content'] ) ) . '" data-copy-as="' . esc_attr( $atts['copy-as'] ) . '" data-link="' . esc_attr( $atts['link'] ) . '" style="color: ' . esc_attr( $color ) . '" >' . $display_content . '</' . esc_html( $atts['tag'] ) . '>';
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Copy_The_Code_Shortcode::get_instance();

endif;
