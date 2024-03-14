<?php
/**
 * Ask Permission
 *
 * @package Copy the Code
 */

if ( ! class_exists( 'Copy_The_Code_Ask_Permission' ) ) :

	/**
	 * Ask Permission
	 */
	class Copy_The_Code_Ask_Permission {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class Instance.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
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
			add_action( 'admin_init', array( $this, 'add_settings_field' ) );
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_copy_the_code_allow_tracking', array( $this, 'allow_tracking' ) );
			add_action( 'wp_ajax_copy_the_code_dont_allow_tracking', array( $this, 'dont_allow_tracking' ) );
		}

		/**
		 * Allow tracking.
		 */
		public function allow_tracking() {
			check_ajax_referer( 'copy-the-code-ask-permission', 'security' );

			update_option( 'copy_the_code_allow_tracking', 'yes' );

			wp_send_json_success();
		}

		/**
		 * Allow tracking.
		 */
		public function dont_allow_tracking() {
			check_ajax_referer( 'copy-the-code-ask-permission', 'security' );

			update_option( 'copy_the_code_allow_tracking', 'no' );

			set_transient( 'copy_the_code_ask_again', 'no', MONTH_IN_SECONDS );

			wp_send_json_success();
		}

		/**
		 * Enqueue Scripts
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'copy-the-code-notice', COPY_THE_CODE_URI . 'assets/js/notice.js', array( 'jquery' ), COPY_THE_CODE_VER, true );
			wp_localize_script(
				'copy-the-code-notice',
				'copy_the_code_notice',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'copy-the-code-ask-permission' ),
				)
			);
		}

		/**
		 * Add notice.
		 */
		public function admin_notice() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$allow = get_option( 'copy_the_code_allow_tracking', 'no' );
			if ( 'yes' === $allow ) {
				return;
			}

			$transient = get_transient( 'copy_the_code_ask_again' );
			if ( 'no' === $transient ) {
				return;
			}
			?>
			<div class="copy-the-code-notice notice notice-info is-dismissible">
				<p><?php echo wp_kses_post( __( 'Want to help make our products more awesome? Allow us to collect non-sensitive diagnostic data and usage information.', 'copy-the-code' ) ); ?></p>
				<p>
					<a href="#" class="button button-primary copy-the-code-allow-tracking"><?php echo esc_html( __( 'Yes! Allow it', 'copy-the-code' ) ); ?></a>
					<a href="#" class="button copy-the-code-not-allow-tracking"><?php echo esc_html( __( 'No thanks', 'copy-the-code' ) ); ?></a>
				</p>
			</div>
			<?php
		}

		/**
		 * Add settings field.
		 */
		public function add_settings_field() {
			add_settings_field(
				'copy_the_code_allow_tracking',
				__( 'Enable tracking', 'copy-the-code' ),
				array( $this, 'settings_field' ),
				'general'
			);
			register_setting( 'general', 'copy_the_code_allow_tracking' );
		}

		/**
		 * Settings field.
		 */
		public function settings_field() {
			$value = get_option( 'copy_the_code_allow_tracking', 'no' );
			?>
			<label>
				<input type="checkbox" name="copy_the_code_allow_tracking" value="yes" <?php checked( $value, 'yes' ); ?> />
				<?php _e( 'Allow usage of Copy the Code to be tracked', 'copy-the-code' ); ?><br/>
				<p class="description">To opt out, leave this box unticked. Your store remains untracked, and no data will be collected. Read about what usage data is tracked at: <a href="https://surror.com/usage-tracking/" target="_blank">Usage Tracking Documentation</a>.</p>
			</label>
			<?php
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Copy_The_Code_Ask_Permission::get_instance();

endif;
