<?php
/**
 * UpStream_Debug Class
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	return;
}


/**
 * UpStream_Debug Class
 *
 * @since 1.0.0
 */
class UpStream_Debug {

	const FILE = 'debug-upstream.log';

	const PAGE_SLUG = 'upstream_debug_log';

	const ACTION_DELETE_LOG = 'delete_log';

	const ADMIN_PAGE_URL_FRAGMENT = 'admin.php?page=';

	/**
	 * Log file path.
	 *
	 * @var string
	 */
	protected static $path;

	/**
	 * Is initialized.
	 *
	 * @var bool
	 */
	protected static $initialized = false;

	/**
	 * Log messages.
	 *
	 * @var array
	 */
	protected static $messages = array();

	/**
	 * Write the given message into the log file.
	 *
	 * @param string $message Error message.
	 * @param mixed  $trace Error message.
	 */
	public static function write( $message, $trace = null ) {
		if ( ! static::is_enabled() ) {
			return;
		}

		if ( ! static::$initialized ) {
			static::init();
		}

		// Make sure we have a string to write.
		if ( ! is_string( $message ) ) {
			$message = print_r( $message, true );
		}

		// Add the timestamp to the message.
		$message = sprintf( '[%s] %s', gmdate( 'Y-m-d H:i:s T O' ), $message ) . "\n";

		if ( $trace ) {
			$tt       = print_r( $trace, true );
			$message .= $tt . "\n";
		}

		error_log( $message, 3, static::$path );
	}

	/**
	 * Returns true if debug is enabled in the UpStream settings.
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		$option = get_option( 'upstream_general' );

		if ( ! $option ) {
			return false;
		}

		$key = 'debug';

		return array_key_exists( $key, $option )
			&& ! empty( $option[ $key ] )
			&& 1 === (int) $option[ $key ][0];
	}

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		if ( ! static::is_enabled() ) {
			return;
		}

		static::$path = str_replace( '//', '/', WP_CONTENT_DIR . '/' . static::FILE );

		// Admin bar.
		add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar_menu' ), 99 );

		// Admin menu.
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );

		static::$initialized = true;
	}

	/**
	 * Add menu to admin bar
	 *
	 * @since 1.0.0
	 */
	public static function admin_bar_menu() {
		global $wp_admin_bar;

		$args = array(
			'id'    => 'upstream_debug',
			'title' => __( 'UpStream Debug Log', 'upstream' ),
			'href'  => admin_url( static::ADMIN_PAGE_URL_FRAGMENT . static::PAGE_SLUG ),
		);

		$wp_admin_bar->add_menu( $args );
	}

	/**
	 * Admin menu hook
	 *
	 * @since 1.0.0
	 */
	public static function admin_menu() {
		// Admin menu.
		add_submenu_page(
			admin_url( static::ADMIN_PAGE_URL_FRAGMENT . static::PAGE_SLUG ),
			__( 'Debug Log' ),
			__( 'Debug Log' ),
			'activate_plugins',
			'upstream_debug_log',
			array( __CLASS__, 'view_log_page' )
		);
	}

	/**
	 * View log page
	 *
	 * @since 1.0.0
	 */
	public static function view_log_page() {
		static::handle_actions();

		global $wp_version;

		$is_log_found = file_exists( static::$path );

		// Get all the plugins and versions.
		$plugins      = get_plugins();
		$plugins_data = array();
		$server_data  = wp_unslash( $_SERVER );

		foreach ( $plugins as $plugin => $data ) {
			$plugins_data[ $plugin ] = ( is_plugin_active( $plugin ) ? 'ACTIVATED' : 'deactivated' ) . ' [' . $data['Version'] . ']';
		}

		$user_info = get_userdata( wp_get_current_user()->ID );

		$debug_data = array(
			'php'       => array(
				'version'                   => PHP_VERSION,
				'os'                        => PHP_OS,
				'date_default_timezone_get' => date_default_timezone_get(),
				'date(e)'                   => gmdate( 'e' ),
				'date(T)'                   => gmdate( 'T' ),
				'browser_str'               => sanitize_text_field( $server_data['HTTP_USER_AGENT'] ),
			),
			'wordpress' => array(
				'version'         => $wp_version,
				'date_format'     => get_option( 'date_format' ),
				'time_format'     => get_option( 'time_format' ),
				'timezone_string' => get_option( 'timezone_string' ),
				'gmt_offset'      => get_option( 'gmt_offset' ),
				'plugins'         => $plugins_data,
			),
			'user'      => array(
				'uid'          => $user_info->ID,
				'roles'        => $user_info->roles,
				'username'     => $user_info->user_email,
				'capabilities' => $user_info->allcaps,
			),
			'theme'     => array(
				'template'     => get_template(),
				'template_dir' => get_template_directory(),
			),
		);

		$context = array(
			'label'         => array(
				'title'             => __( 'UpStream Debug Log', 'upstream' ),
				'file_info'         => __( 'File info', 'upstream' ),
				'path'              => __( 'Path', 'upstream' ),
				'log_content'       => __( 'Log content', 'upstream' ),
				'size'              => __( 'Size', 'upstream' ),
				'creation_time'     => __( 'Created on', 'upstream' ),
				'modification_time' => __( 'Modified on', 'upstream' ),
				'delete_file'       => __( 'Delete file', 'upstream' ),
				'debug_data'        => __( 'Debug data', 'upstream' ),
				'log_file'          => __( 'Log File', 'upstream' ),
			),
			'message'       => array(
				'log_not_found'       => __( 'Log file not found.', 'upstream' ),
				'contact_support_tip' => __(
					'If you see any error or look for information regarding UpStream, please don\'t hesitate to contact the support team. E-mail us:',
					'upstream'
				),
				'click_to_delete'     => __(
					'Click to delete the log file. Be careful, this operation can not be undone. ',
					'upstream'
				),
			),
			'contact_email' => 'help@upstreamplugin.com',
			'link_delete'   => admin_url(
				sprintf(
					'admin.php?page=%s&action=%s&_wpnonce=%s',
					static::PAGE_SLUG,
					static::ACTION_DELETE_LOG,
					wp_create_nonce( static::ACTION_DELETE_LOG )
				)
			),
			'is_log_found'  => $is_log_found,
			'file'          => array(
				'path'              => static::$path,
				'size'              => $is_log_found ? round( filesize( static::$path ) / 1024, 2 ) : 0,
				'modification_time' => $is_log_found ? gmdate( 'Y-m-d H:i:s T O', filemtime( static::$path ) ) : '',
				'content'           => $is_log_found ? file_get_contents( static::$path ) : '',
			),
			'debug_data'    => print_r( $debug_data, true ),
			'messages'      => static::$messages,
		);

		?>


		<h1><?php echo esc_html( $context['label']['title'] ); ?></h1>

		<div id="upstream-debug-data">
			<h2><?php echo esc_html( $context['label']['debug_data'] ); ?></h2>
			<textarea style="width:100%; height:400px"><?php echo esc_textarea( $context['debug_data'] ); ?></textarea>
		</div>

		<hr>

		<div id="upstream-debug-log">
			<h2><?php echo esc_html( $context['label']['log_file'] ); ?></h2>

			<p><?php echo esc_html( $context['message']['click_to_delete'] ); ?></p>
			<a class="button button-danger" href="<?php echo esc_attr( $context['link_delete'] ); ?>"><?php echo esc_html( $context['label']['delete_file'] ); ?></a>

			<h3><?php echo esc_html( $context['label']['log_content'] ); ?></h3>
			<textarea style="width:100%; height:400px" id="upstream-debug-log"><?php echo esc_textarea( $context['file']['content'] ); ?></textarea>
		</div>


		<?php

	}

	/**
	 * Handle admin page actions.
	 *
	 * @since 1.0.0
	 */
	protected static function handle_actions() {
		$get_data = wp_unslash( $_GET );

		// Are we on the correct page?
		$page_param = 'page';
		if ( ! array_key_exists( $page_param, $get_data ) || sanitize_text_field( $get_data[ $page_param ] ) !== static::PAGE_SLUG ) {
			return;
		}

		// Do we have an action?
		$action_param = 'action';
		if ( ! array_key_exists( $action_param, $get_data ) || empty( $get_data[ $action_param ] ) ) {
			return;
		}

		$action = preg_replace( '/[^a-z0-9_\-]/i', '', sanitize_text_field( $get_data[ $action_param ] ) );

		// Do we have a nonce?
		$wp_once_param = '_wpnonce';
		if ( ! array_key_exists( $wp_once_param, $get_data ) || empty( $get_data[ $wp_once_param ] ) ) {
			static::$messages[] = __( 'Action nonce not found.', 'upstream' );

			return;
		}

		// Check the nonce.
		if ( ! wp_verify_nonce( sanitize_text_field( $get_data[ $wp_once_param ] ), $action ) ) {
			static::$messages[] = __( 'Invalid action nonce.', 'upstream' );

			return;
		}

		if ( static::ACTION_DELETE_LOG && file_exists( static::$path ) === $action ) {
			unlink( static::$path );
		}

		wp_save_redirect( admin_url( static::ADMIN_PAGE_URL_FRAGMENT . static::PAGE_SLUG ) );
		exit();
	}
}

/**
 * Handle debugging message.
 *
 * @param mixed $s Debuging data.
 */
function up_debug( $s = null ) {
	if ( ! $s ) {
		$fname    = '';
		$bt       = debug_backtrace();
		$bt_count = count( $bt );

		for ( $i = 1; $i < 2 && $i < $bt_count; $i++ ) {
			$fname = $bt[ $i ]['function'];
		}

		UpStream_Debug::write( 'Entering function: ' . $fname );
	} elseif ( is_string( $s ) ) {
		UpStream_Debug::write( $s, debug_backtrace() );
	} elseif ( is_object( $s ) ) {
		if ( $s instanceof \Exception ) {
			UpStream_Debug::write( $s->getMessage(), $s->getTrace() );
		} else {
			UpStream_Debug::write( print_r( $s, true ), debug_backtrace() );
		}
	}
}
