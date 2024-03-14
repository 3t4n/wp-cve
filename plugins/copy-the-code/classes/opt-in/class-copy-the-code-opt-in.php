<?php
/**
 * Initialize Plugin
 *
 * @package Copy the Code
 * @since 1.0.0
 */

if ( ! class_exists( 'Copy_The_Code_Opt_In' ) ) :

	/**
	 * Copy the Code
	 *
	 * @since 1.0.0
	 */
	class Copy_The_Code_Opt_In {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class Instance.
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_notices', array( $this, 'welcome' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_copy_the_code_opt_in', array( $this, 'opt_in' ) );
		}

		/**
		 * Opt in.
		 */
		public function opt_in() {
			check_ajax_referer( 'copy-the-code-opt-in', 'security' );

			$opt_in = isset( $_POST['opt_in'] ) ? sanitize_text_field( $_POST['opt_in'] ) : '';
			if ( 'welcome' === $opt_in ) {
				update_option( 'copy_the_code_fresh_user', 'no' );
				update_option( 'copy_the_code_opt_in_welcome', 'yes' );
			}

			wp_send_json_success();
		}

		/**
		 * Enqueue scripts.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {
			wp_register_script( 'copy-the-code-opt-in', COPY_THE_CODE_URI . 'classes/opt-in/opt-in.js', array( 'jquery' ), COPY_THE_CODE_VER, true );
			wp_localize_script(
				'copy-the-code-opt-in',
				'copy_the_code_opt_in',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'copy-the-code-opt-in' ),
				)
			);
		}

		/**
		 * Welcome message.
		 */
		public function welcome() {
			if ( 'no' === get_option( 'copy_the_code_fresh_user' ) ) {
				return;
			}
			if ( get_option( 'copy_the_code_opt_in_welcome' ) ) {
				return;
			}

			if ( ! isset( $_GET['page'] ) ) {
				return;
			}

			if ( 'copy-the-code' !== $_GET['page'] ) {
				return;
			}

			wp_enqueue_script( 'copy-the-code-opt-in' );
			?>
			<div class="copy-the-code-opt-in-notice-welcome notice notice-info is-dismissible">
				<p>
					<?php
					_e( 'Thank you for using "<b>Copy Anything to Clipboard</b>"! 🤩<br/>Please read a quick <a href="https://clipboard.agency/" target="_blank">getting started</a> guide. Also, Don\'t hesitate to <a href="https://clipboard.agency/contact/" target="_blank">contact us</a> for any issue or help.<br/>It\'ll help us to understand more about you which we\'ll help you to grow your business 🚀', 'copy-the-code' );
					?>
				</p>
			</div>
			<?php
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Copy_The_Code_Opt_In::get_instance();

endif;
