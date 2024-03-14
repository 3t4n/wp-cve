<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package hreflang-manager-lite
 */

/**
 * This class should be used to work with the public side of WordPress.
 */
class Daexthrmal_Public {

	/**
	 * The instance of this class.
	 *
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * The instance of the shared class.
	 *
	 * @var Daexthrmal_Shared|null
	 */
	private $shared = null;

	/**
	 * Constructor.
	 */
	private function __construct() {

		// assign an instance of the plugin info.
		$this->shared = Daexthrmal_Shared::get_instance();

		// write in front-end head.
		add_action( 'wp_head', array( $this, 'set_hreflang' ) );

		// write in the get_footer hook.
		add_action( 'get_footer', array( $this, 'generate_log' ) );

		// enqueue styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Create an instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Write the connections in the 'head' section of the page
	 */
	public function set_hreflang() {

		// retrive a numeric array with the connections.
		$hreflang_output = $this->shared->generate_hreflang_output();

		// echo the connections in the head of the document.
		if ( false !== $hreflang_output ) {
			foreach ( $hreflang_output as $single_connection ) {
				echo $single_connection;
			}
		}
	}

	/**
	 * Write the log with the connections.
	 */
	public function generate_log() {

		// don't show the log if the current user is not the administrator or if the log in not enabled.
		if ( ! current_user_can( 'manage_options' ) || intval( get_option( $this->shared->get( 'slug' ) . '_show_log' ), 10 ) !== 1 ) {
			return;
		}

		// retrive a numeric array with the connections.
		$hreflang_output = $this->shared->generate_hreflang_output();

		// echo the connections in the head of the document.
		if ( false !== $hreflang_output ) { ?>

			<div id="da-hm-log-container">
				<p id="da-hm-log-heading"><?php esc_html_e( 'The following lines have been added in the HEAD section of this page', 'hreflang-manager-lite' ); ?>
					:</p>
				<?php
				foreach ( $hreflang_output as $key => $single_connection ) {
					echo '<p>' . esc_html( $single_connection ) . '</p>';
				}
				?>
			</div>

			<?php
		}
	}

	/**
	 * Enqueue styles.
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		// enqueue the style used to show the log if the current user has the edit_posts capability and if the log is enabled.
		if ( current_user_can( 'manage_options' ) && ( intval( get_option( $this->shared->get( 'slug' ) . '_show_log' ), 10 ) === 1 ) ) {
			wp_enqueue_style( $this->shared->get( 'slug' ) . '-general', $this->shared->get( 'url' ) . 'public/assets/css/general.css', array(), $this->shared->get( 'ver' ) );
		}
	}
}