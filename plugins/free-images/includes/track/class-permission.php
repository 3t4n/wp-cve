<?php
/**
 * Ask Permission
 *
 * @since      2.0.0
 * @package    FAL
 * @subpackage FAL\Track\Permission
 * @author     FAL <support@surror.com>
 */

namespace FAL\Track;

defined( 'ABSPATH' ) || exit;

/**
 * Permission class.
 */
class Permission {

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
	public static function get() {
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
		add_action( 'wp_ajax_fal_allow_tracking', array( $this, 'allow_tracking' ) );
		add_action( 'wp_ajax_fal_dont_allow_tracking', array( $this, 'dont_allow_tracking' ) );
	}

	/**
	 * Allow tracking.
	 */
	public function allow_tracking() {
		check_ajax_referer( 'fal-ask-permission', 'security' );

		update_option( 'fal_allow_tracking', 'yes' );

		wp_send_json_success();
	}

	/**
	 * Allow tracking.
	 */
	public function dont_allow_tracking() {
		check_ajax_referer( 'fal-ask-permission', 'security' );

		update_option( 'fal_allow_tracking', 'no' );

		set_transient( 'fal_ask_again', 'no', MONTH_IN_SECONDS );

		wp_send_json_success();
	}

	/**
	 * Enqueue Scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'fal-notice', FAL_URI . 'includes/track/js/permission.js', array( 'jquery' ), FAL_VERSION, true );
		wp_localize_script(
			'fal-notice',
			'fal_notice',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'fal-ask-permission' ),
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

		$allow = get_option( 'fal_allow_tracking', 'no' );
		if ( 'yes' === $allow ) {
			return;
		}

		$transient = get_transient( 'fal_ask_again' );
		if ( 'no' === $transient ) {
			return;
		}
		?>
		<div class="fal-notice notice notice-info is-dismissible">
			<p><?php echo wp_kses_post( __( 'Want to help make our products more awesome? Allow us to collect non-sensitive diagnostic data and usage information.', 'fal' ) ); ?></p>
			<p>
				<a href="#" class="button button-primary fal-allow-tracking"><?php echo esc_html( __( 'Yes! Allow it', 'fal' ) ); ?></a>
				<a href="#" class="button fal-not-allow-tracking"><?php echo esc_html( __( 'No thanks', 'fal' ) ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Add settings field.
	 */
	public function add_settings_field() {
		add_settings_field(
			'fal_allow_tracking',
			__( 'Enable tracking', 'fal' ),
			array( $this, 'settings_field' ),
			'general'
		);
		register_setting( 'general', 'fal_allow_tracking' );
	}

	/**
	 * Settings field.
	 */
	public function settings_field() {
		$value = get_option( 'fal_allow_tracking', 'no' );
		?>
		<label>
			<input type="checkbox" name="fal_allow_tracking" value="yes" <?php checked( $value, 'yes' ); ?> />
			<?php esc_html_e( 'Allow usage of Free Assets Library to be tracked', 'fal' ); ?><br/>
			<p class="description">To opt out, leave this box unticked. Your store remains untracked, and no data will be collected. Read about what usage data is tracked at: <a href="https://surror.com/usage-tracking/" target="_blank">Usage Tracking Documentation</a>.</p>
		</label>
		<?php
	}

}