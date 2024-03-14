<?php
/**
 * Display notices in admin
 *
 * @package EverAccounting\Admin
 * @version 1.0.2
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Notices Class
 *
 * @package EverAccounting\Admin
 */
class Notices {
	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var Notices
	 */
	protected static $instance = null;

	/**
	 * All notices.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	private $notices = array();

	/**
	 * Array of notices - name => callback.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	private $core_notices = array(
		'install'          => 'install_notice',
		'default_currency' => 'default_currency_notice',
		'tables_missing'   => 'tables_missing_notice',
	);

	/**
	 * Init function
	 *
	 * @since 1.0.2.
	 */
	public static function init() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof \EverAccounting\Admin\Notices ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Notices constructor.
	 *
	 * @since 1.0.2
	 */
	public function __construct() {
		$this->notices = get_option( 'eaccounting_notices', array() );
		add_action( 'admin_init', array( $this, 'hide_notices' ) );
		add_action( 'shutdown', array( $this, 'save_notices' ), 100 );
		if ( current_user_can( 'manage_eaccounting' ) ) {
			add_action( 'admin_print_styles', array( $this, 'maybe_show_notices' ) );
		}
	}

	/**
	 * Add Notice.
	 *
	 * @since 1.1.0
	 *
	 * @param string $message Message.
	 * @param string $type   Type.
	 * @param array  $args  Args.
	 */
	public function add_notice( $message, $type = 'success', $args = array() ) {
		$args                  = wp_parse_args(
			$args,
			array(
				'title'       => '',
				'dismissible' => false,
				'classes'     => array(),
			)
		);
		$notice                = array_merge(
			$args,
			array(
				'message' => $message,
				'type'    => $type,
			)
		);
		$key                   = md5( maybe_serialize( $notice ) );
		$this->notices[ $key ] = $notice;
	}

	/**
	 * Add success message.
	 *
	 * @since 1.1.0
	 *
	 * @param string $message Message.
	 * @param array  $args  Args.
	 */
	public function add_success( $message, $args = array() ) {
		$this->add_notice( $message, 'success', $args );
	}

	/**
	 * Add error message.
	 *
	 * @since 1.1.0
	 *
	 * @param string $message Message.
	 * @param array  $args  Args.
	 */
	public function add_error( $message, $args = array() ) {
		$this->add_notice( $message, 'error', $args );
	}

	/**
	 * Add warning message.
	 *
	 * @since 1.1.0
	 *
	 * @param string $message Message.
	 * @param array  $args  Args.
	 */
	public function add_warning( $message, $args = array() ) {
		$this->add_notice( $message, 'warning', $args );
	}

	/**
	 * Add info message.
	 *
	 * @since 1.1.0
	 *
	 * @param string $message Message.
	 * @param array  $args  Args.
	 */
	public function add_info( $message, $args = array() ) {
		$this->add_notice( $message, 'info', $args );
	}

	/**
	 * Add core notice.
	 *
	 * @since 1.1.0
	 *
	 * @param string $id  Notice ID.
	 */
	public function add_core_notice( $id ) {
		if ( array_key_exists( $id, $this->core_notices ) ) {
			$this->notices[ $id ] = $id;
		}
	}

	/**
	 * Save notices.
	 *
	 * @since 1.1.0
	 */
	public function save_notices() {
		update_option( 'eaccounting_notices', $this->notices );
	}

	/**
	 * Hide notice.
	 *
	 * @since 1.1.0
	 */
	public function hide_notices() {
		$action = filter_input( INPUT_GET, 'eaccounting_hide_notice', FILTER_SANITIZE_STRING );
		$nonce  = filter_input( INPUT_GET, '_ea_notice_nonce', FILTER_SANITIZE_STRING );
		if ( empty( $action ) || empty( $nonce ) ) {
			return;
		}
		if ( wp_verify_nonce( $nonce, 'eaccounting_hide_notice' ) ) {
			$this->remove_notice( sanitize_textarea_field( $action ) );
		}
		wp_safe_redirect( remove_query_arg( array( 'eaccounting_hide_notice', '_ea_notice_nonce' ) ) );
		exit();
	}

	/**
	 * Remove notice.
	 *
	 * @since 1.1.0
	 *
	 * @param string $id Notice ID.
	 */
	public function remove_notice( $id ) {
		if ( array_key_exists( $id, $this->notices ) ) {
			unset( $this->notices[ $id ] );
			$this->save_notices();
		}
	}

	/**
	 * Check if we need to print message.
	 *
	 * @since 1.1.0
	 */
	public function maybe_show_notices() {
		$notices = $this->notices;

		if ( empty( $notices ) ) {
			return;
		}

		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$show_on_screens = array(
			'dashboard',
			'plugins',
		);

		// Notices should only show on EverAccounting screens, the main dashboard, and on the plugins screen.
		if ( ! in_array( $screen_id, eaccounting_get_screen_ids(), true ) && ! in_array( $screen_id, $show_on_screens, true ) ) {
			return;
		}
		foreach ( array_keys( $notices ) as $notice ) {
			if ( ! empty( $this->core_notices[ $notice ] ) ) {
				add_action( 'admin_notices', array( $this, $this->core_notices[ $notice ] ) );
			} else {
				add_action( 'admin_notices', array( $this, 'output_custom_notices' ) );
			}
		}
	}

	/**
	 * Output any stored custom notices.
	 *
	 * @since 1.1.0
	 */
	public function output_custom_notices() {
		if ( ! empty( $this->notices ) ) {
			foreach ( $this->notices as $id => $notice ) {
				if ( array_key_exists( $id, $this->core_notices ) ) {
					continue;
				}
				$dismissible = false;
				$classes     = array( 'ea-admin-notice', 'notice' );
				if ( true === $notice['dismissible'] ) {
					$classes[]   = 'is-dismissible';
					$dismissible = true;
				} else {
					unset( $this->notices[ $id ] );
				}
				$classes[] = 'notice-' . $notice['type'];
				$classes   = array_merge( $classes, $notice['classes'] );
				$classes   = implode( ' ', array_map( 'sanitize_html_class', $classes ) );
				$url       = wp_nonce_url( add_query_arg( 'eaccounting_hide_notice', $id ), 'eaccounting_hide_notice', '_ea_notice_nonce' );
				?>
				<div id="message" class="<?php echo esc_attr( $classes ); ?>">
					<?php if ( $dismissible ) : ?>
						<a class="ea-dismiss-notice notice-dismiss" href="<?php echo esc_url( $url ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'wp-ever-accounting' ); ?></span>
						</a>
					<?php endif; ?>
					<?php echo wp_kses_post( wpautop( $notice['message'] ) ); ?>
				</div>
				<?php
			}
		}
	}

	/**
	 * If we have just installed, show a message with the installation pages button.
	 *
	 * @since 1.1.0
	 */
	public function install_notice() {
		?>
		<div id="message" class="updated ea-admin-notice">
			<p><?php esc_html_e( '<strong>Welcome to Ever Accounting</strong> &#8211; You&lsquo;re almost done :)', 'wp-ever-accounting' ); ?></p>
			<p class="submit">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=ea-setup' ) ); ?>" class="button-primary">
					<?php esc_html_e( 'Run the Setup Wizard', 'wp-ever-accounting' ); ?>
				</a>
				<a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'eaccounting_hide_notice', 'install' ), 'eaccounting_hide_notice', '_ea_notice_nonce' ) ); ?>">
					<?php esc_html_e( 'Skip setup', 'wp-ever-accounting' ); ?>
				</a>
			</p>
		</div>
		<?php
	}


	/**
	 * Notice about base tables missing.
	 *
	 * @since 1.1.0
	 */
	public function tables_missing_notice() {
		$missing_tables = get_option( 'eaccounting_schema_missing_tables' );
		?>
		<div id="message" class="error ea-admin-notice">
			<p>
				<strong><?php esc_html_e( 'Database tables missing', 'wp-ever-accounting' ); ?></strong>
			</p>
			<p>
				<?php
				echo wp_kses_post(
					sprintf(
					/* translators: %1$s table names */
						__( 'One or more tables required for Ever Accounting to function are missing, some features may not work as expected. Missing tables: %1$s.', 'wp-ever-accounting' ),
						esc_html( implode( ', ', $missing_tables ) )
					)
				);
				?>
			</p>
		</div>
		<?php
	}
}

/**
 * wrapper for admin notice.
 *
 * @since 1.1.0
 * @return \EverAccounting\Admin\Notices|null
 */
function eaccounting_admin_notices() {
	return \EverAccounting\Admin\Notices::init();
}

eaccounting_admin_notices();
