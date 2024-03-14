<?php
/**
 * Holds main SocialFlow plugin class
 *
 * @package SocialFlow
 */

if ( ! function_exists( 'sf_debug' ) ) {
	/**
	 * Social flow debug
	 *
	 * @param string $msg sf debug message.
	 * @param array  $data sf debug data.
	 */
	function sf_debug( $msg, $data = array() ) {
		SF_Debug::get_instance()->log( $msg, $data, 'debug' );
	}
}

require_once(ABSPATH . 'wp-admin/includes/file.php');

if ( ! function_exists( 'sf_log' ) ) {
	/**
	 * Social flow debug
	 *
	 * @param string $msg sf debug message.
	 * @param array  $data sf debug data.
	 */
	function sf_log( $msg, $data = array() ) {
		SF_Debug::get_instance()->log( $msg, $data, 'post' );
	}
}

if ( ! function_exists( 'sf_log_post' ) ) {
	/**
	 * Social flow log post
	 *
	 * @param string $msg sf debug message.
	 * @param object $post sf debug data.
	 */
	function sf_log_post( $msg, $post ) {
		SF_Debug::get_instance()->log_post( $msg, $post );
	}
}

/**
 * Social flow debug
 *
 * @package SF_Debug
 */
class SF_Debug {

	/**
	 *  Field Instance
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance;
	/**
	 * Is Logged
	 *
	 * @since 1.0
	 * @var bool
	 */
	protected $is_logged = false;
	/**
	 * Use debug
	 *
	 * @since 1.0
	 * @var bool
	 */
	protected $debug = true;
	/**
	 * Folder name
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $folder = 'socialflow';
	/**
	 * Notice
	 *
	 * @since 1.0
	 * @var array
	 */
	protected $notice = array();
	/**
	 * Files
	 *
	 * @since 1.0
	 * @var array
	 */
	protected $files = array(
		'post',
		'debug',
	);
	/**
	 * Logs
	 *
	 * @since 1.0
	 * @var array
	 */
	protected $logs = array();
	/**
	 * Create Add actions
	 *
	 * @since 1.0
	 * @access public
	 */
	protected function __construct() {
		add_action( 'shutdown', array( $this, 'on_wp_die' ) );

		add_action( 'init', array( $this, 'on_init' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}
	/**
	 * Init debug
	 *
	 * @since 1.0
	 * @access public
	 */
	public function on_init() {
		if ( ! $this->debug ) {
			return;
		}

		if ( ! defined( 'SF_DEBUG' ) ) {
			define( 'SF_DEBUG', true );
		}

		$this->test_log_dir();
	}
	/**
	 * Test dir log
	 *
	 * @since 1.0
	 * @access public
	 */
	public function test_log_dir() {
		if ( ! wp_mkdir_p( $this->get_path() ) ) {
			return $this->add_notice( "Log folder doesn't created" );
		}

		if ( ! wp_is_writable( $this->get_path() ) ) {
			return $this->add_notice( 'Log folder is not writable' );
		}

		foreach ( $this->files as $file ) {
			if ( file_exists( $this->get_log_path( $file ) ) ) {
				continue;
			}

			if ( ! $this->write_file( $file, '' ) ) {
				return $this->add_notice( "$file file does not created" );
			}
		}

	}
	/**
	 * Add message for notice
	 *
	 * @since 1.0
	 * @access protected
	 * @param string $msg .
	 */
	protected function add_notice( $msg ) {
		$this->notice = $msg;
	}
	/**
	 * Wp die run
	 *
	 * @since 1.0
	 */
	public function on_wp_die() {
		if ( ! $this->is_logged ) {
			return;
		}

		foreach ( $this->files as $file ) {
			if ( ! isset( $this->logs[ $file ] ) ) {
				continue;
			}

			if ( empty( $this->logs[ $file ] ) ) {
				continue;
			}

			$this->write_file( $file, '' );
			$this->write_file( $file, '--------------' );
			$this->write_file( $file, '' );
		}
	}
	/**
	 * Get instance field
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Log
	 *
	 * @param string $msg .
	 * @param array  $data .
	 * @param string $file .
	 */
	public function log( $msg, $data = array(), $file = 'post' ) {
		if ( ! $this->debug ) {
			return;
		}

		$this->is_logged = true;

		$date = date( 'Y-m-d H:i:s' );

		if ( $msg ) {
			if ( $data ) {
				$msg .= "\n" . print_r( $data, true );
			}

			$msg = "$date: $msg";
		}

		$this->write_file( $file, $msg );
	}

	/**
	 * Log post
	 *
	 * @param string $msg .
	 * @param object $post .
	 */
	public function log_post( $msg, $post ) {
		if ( is_object( $post ) ) {
			if ( 'post' !== $post->post_type ) {
				return;
			}

			if ( in_array( $post->post_status, array( 'new', 'any', 'auto-draft' ), true ) ) {
				return;
			}

			$post_id = $post->ID;
		} else {
			$post_id = $post;
		}

		$this->log( "post_ID: {$post_id} - $msg" );
	}

	/**
	 * Get Path
	 */
	protected function get_path() {
		$dir = wp_upload_dir();

		return "{$dir['basedir']}/{$this->folder}";
	}

	/**
	 * Get log path
	 *
	 * @param string $file .
	 * @return string
	 */
	protected function get_log_path( $file ) {
		return $this->get_path() . "/{$file}.log";
	}
	/**
	 * Write in log file
	 *
	 * @param string $file .
	 * @param string $msg .
	 * @return bool
	 */
	protected function write_file( $file, $msg = '' ) {
		global $wp_filesystem;
		WP_Filesystem();
		if ( $msg ) {
			$this->logs[ $file ][] = $msg;
		}
		$old_mess = $wp_filesystem->get_contents( $this->get_log_path( $file ) );
		$msg      = $old_mess . "$msg\n";
		return $wp_filesystem->put_contents( $this->get_log_path( $file ), $msg );
	}
	/**
	 * Admin notices
	 */
	public function admin_notices() {
		if ( ! $this->notice ) {
			return;
		}
		?>
		<div class="notice notice-warning is-dismissible">
			<p><b><?php esc_html_e( 'Socialflow Debug:' ); ?></b> <?php echo esc_html( $this->notice ); ?> - <i><?php esc_html_e( 'File system permissions error.' ); ?></i></p>
			<p>Log file path:   <?php echo esc_html( str_replace( WP_CONTENT_DIR, '', $this->get_log_path( 'post' ) ) ); ?></p>
			<p>Debug file path: <?php echo esc_html( str_replace( WP_CONTENT_DIR, '', $this->get_log_path( 'debug' ) ) ); ?></p>
		</div>
	<?php
	}
}
if ( is_admin() ) {
	SF_Debug::get_instance();
}
