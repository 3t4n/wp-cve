<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mainulhassan.info
 * @since      1.0.0
 *
 * @package    Download_Media_File
 * @subpackage Download_Media_File/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Download_Media_File
 * @subpackage Download_Media_File/includes
 * @author     Mainul Hassan Main <contact@mainulhassan.info>
 */
class Download_Media_File {

	/**
	 * The constructor.
	 */
	private function __construct() {
	}

	/**
	 * Returns an instance of this class.
	 *
	 * @return Download_Media_File
	 */
	public static function instance() {
		// Store the instance locally to avoid private static replication.
		static $instance = null;

		// Only run these methods if they haven't been run previously.
		if ( null === $instance ) {
			$instance = new Download_Media_File();
			$instance->init_hooks();
		}

		return $instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		add_filter( 'media_meta', array( $this, 'add_download_button' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'process_downloading_media_file' ) );
	}

	/**
	 * Adds a download button.
	 *
	 * @param string  $meta The HTML markup containing the media dimensions.
	 * @param WP_Post $post The post object.
	 *
	 * @return string
	 */
	public function add_download_button( $meta, $post ) {
		ob_start();
		?>
        <form method="post" class="download-media-file-form" style="margin: 7px 0 0;">
			<?php wp_nonce_field( 'download_media_file_action', 'download_media_file_nonce_field' ); ?>
            <input type="hidden" name="post_id" value="<?php echo esc_attr( $post->ID ); ?>">
            <input
                type="submit"
                class="button button-primary button-small"
                name="download_media_file"
                value="<?php esc_attr_e( 'Download', 'download-media-file' ); ?>"
            >
        </form>
		<?php
		$html = ob_get_clean();
		$meta .= $html;

		return $meta;
	}

	/**
	 * Process downloading the media file.
	 */
	public function process_downloading_media_file() {
		if ( ! isset( $_POST['download_media_file'] ) ) {
			return;
		}

		// Verify nonce and then proceed.
		if ( check_admin_referer( 'download_media_file_action', 'download_media_file_nonce_field' ) ) {
			if ( isset( $_POST['post_id'] ) && $post_id = absint( $_POST['post_id'] ) ) {

				/**
				 * Hooks to validate other permissions.
				 *
				 * @param int $post_id The file id.
				 */
				do_action( 'download_media_file_validate_permissions', $post_id );

				// Get file full path
				$file_path = get_attached_file( $post_id );

				if ( ! file_exists( $file_path ) ) {
					wp_die( esc_html__( 'File not found', 'download-media-file' ) );
				}

				$ctype     = get_post_mime_type( $post_id );
				$file_name = wp_basename( $file_path );
				$file_size = filesize( $file_path );

				if ( ! ini_get( 'safe_mode' ) ) {
					@set_time_limit( 0 );
				}

				@ini_set( 'magic_quotes_runtime', 0 );

				if ( function_exists( 'apache_setenv' ) ) {
					@apache_setenv( 'no-gzip', 1 );
				}

				@session_write_close();
				@ini_set( 'zlib.output_compression', 'Off' );

				/**
				 * Prevents errors, for example: transfer closed with 3 bytes remaining to read.
				 */
				@ob_end_clean(); // Clear the output buffer.

				if ( ob_get_level() ) {
					$levels = ob_get_level();

					for ( $i = 0; $i < $levels; $i ++ ) {
						@ob_end_clean(); // Zip corruption fix.
					}
				}

				global $is_IE;

				if ( $is_IE && is_ssl() ) {
					// IE bug prevents download via SSL when Cache Control and Pragma no-cache headers set.
					header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
					header( 'Cache-Control: private' );
				} else {
					nocache_headers();
				}

				header( "X-Robots-Tag: noindex, nofollow" );
				header( "Content-Type: " . $ctype );
				header( "Content-Description: File Transfer" );
				header( "Content-Disposition: attachment; filename=\"" . $file_name . "\";" );
				header( "Content-Transfer-Encoding: binary" );
				header( "Content-Length: " . $file_size );

				/**
				 * Hooks to add additional HTTP Header.
				 *
				 * @param int $post_id The file id.
				 */
				do_action( 'download_media_file_set_http_header', $post_id );

				$this->readfile_chunked( $file_path ) or wp_die( esc_html__( 'File not found', 'download-media-file' ) );

				exit;
			}
		}
	}

	/**
	 * Reads file in chunks so big downloads are possible without changing php.ini file.
	 *
	 * @param string  $file     The file path.
	 * @param boolean $retbytes The retbytes.
	 *
	 * @return bool|int
	 */
	public function readfile_chunked( $file, $retbytes = true ) {
		$chunksize = 1024 * 1024;
		$cnt       = 0;

		$handle = @fopen( $file, 'r' );

		if ( $handle === false ) {
			return false;
		}

		while ( ! feof( $handle ) ) {
			$buffer = fread( $handle, $chunksize );
			echo $buffer;
			@ob_flush();
			@flush();

			if ( $retbytes ) {
				$cnt += strlen( $buffer );
			}
		}

		$status = fclose( $handle );

		if ( $retbytes && $status ) {
			return $cnt;
		}

		return $status;
	}

	/**
	 * Loads the plugin's translated strings.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			DOWNLOAD_MEDIA_FILE_SLUG,
			false,
			DOWNLOAD_MEDIA_FILE_PLUGIN_DIR . 'languages'
		);
	}

}
