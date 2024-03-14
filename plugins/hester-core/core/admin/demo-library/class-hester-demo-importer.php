<?php
/**
 * Hester Demo Library. Install a copy of a Hester demo to your website.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Demo Importer Class.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Demo_Importer {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Demo ID.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $demo_id;

	/**
	 * Upload folder URI.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $demo_upload_uri;

	/**
	 * Upload folder path.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $demo_upload_path;

	/**
	 * Remote path.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $remote = 'https://assets.peregrine-themes.com/demos/';

	/**
	 * Main Hester Demo Importer Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Demo_Importer
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Demo_Importer ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Main importer function.
		add_action( 'wp_ajax_hester_core_import_step', array( $this, 'import_demo_step' ) );

		// Remap WPForms IDs.
		add_filter( 'wp_import_post_data_raw', array( $this, 'map_wpforms_ids' ) );
	}

	/**
	 * The main importer function.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function import_demo_step() {

		$hester_nonce =  hester_core()->theme_name . '_nonce';

		// Nonce check.
		check_ajax_referer( $hester_nonce );

		// Permission check.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission to import a demo.', 'hester-core' ), 'import_error' );
		}

		// Current import step.
		$import_step = isset( $_POST['import_step'] ) ? sanitize_text_field( wp_unslash( $_POST['import_step'] ) ) : '';

		if ( empty( $import_step ) ) {
			wp_send_json_error( esc_html__( 'Import step not specified.', 'hester-core' ), 'import_error' );
		}

		if ( ! method_exists( $this, $import_step ) ) {
			/* translators: %s is import step. */
			wp_send_json_error( sprintf( esc_html__( 'Missing import step function: %s', 'hester-core' ), $import_step ), 'import_error' );
		}

		// Setup demo import data.
		$this->before_import_step();

		$args = array();

		if ( 'import_content' === $import_step ) {
			$args = array(
				// should we import attachments?
				isset( $_POST['attachments'] ) ? (bool) $_POST['attachments'] : false,
			);
		}

		// Call import step function.
		$response = call_user_func_array( array( $this, $import_step ), $args );

		// Check for errors.
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message(), $response->get_error_code() );
		}

		// Finished step.
		wp_send_json_success();
	}

	/**
	 * Set up demo data.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	private function before_import_step() {

		$hester_nonce =  hester_core()->theme_name . '_nonce';

		// Nonce check.
		check_ajax_referer( $hester_nonce );

		// Demo ID required.
		if ( ! isset( $_POST['demo_id'] ) ) {
			wp_send_json_error( __( 'Missing Demo ID', 'hester-core' ), 'import_error' );
		}

		// Set up variables.
		$demo_id = sanitize_text_field( wp_unslash( $_POST['demo_id'] ) );

		$config = $this->configure_paths( $demo_id );

		if ( is_wp_error( $config ) ) {
			wp_send_json_error( $config->get_error_message(), 'import_error' );
		}

		// Increase PHP limits.
		if ( function_exists( 'ini_get' ) ) {
			if ( 300 > ini_get( 'max_execution_time' ) ) {
				@ini_set( 'max_execution_time', 300 ); // phpcs:ignore WordPress.PHP.IniSet.max_execution_time_Blacklisted
			}
			if ( 512 > intval( ini_get( 'memory_limit' ) ) ) {
				@ini_set( 'memory_limit', '512M' ); // phpcs:ignore WordPress.PHP.IniSet.memory_limit_Blacklisted
			}
		}
	}

	/**
	 * Demo import started.
	 *
	 * @since 1.0.0
	 */
	public function import_started() {
		do_action( 'hester_core_demo_import_start' );
	}

	/**
	 * Demo import completed.
	 *
	 * @since 1.0.0
	 */
	public function import_completed() {
		do_action( 'hester_core_demo_import_end' );
	}

	/**
	 * Main customizer importer method.
	 *
	 * @since 1.0.0
	 */
	public function import_customizer() {

		// Check if helper class exists.
		if ( ! class_exists( 'Hester_Customizer_Import_Export' ) ) {

			$class_customizer_import = plugin_dir_path( __FILE__ ) . 'importers/class-customizer-import-export.php';

			if ( file_exists( $class_customizer_import ) ) {
				require_once $class_customizer_import;
			} else {
				return new WP_Error( 'error', esc_html__( 'Can not retrieve class-customizer-import-export.php', 'hester-core' ) );
			}
		}

		// Get contents to import.
		$content = $this->get_import_file_contents( 'customizer.json' );

		// Check for errors.
		if ( is_wp_error( $content ) ) {
			return $content;
		}

		// Decode data.
		$content = json_decode( $content, true );

		// Import Customizer data.
		Hester_Customizer_Import_Export::import( $content );
	}

	public function get_import_file_contents( $filename, $zip = false ) {

		$filepath = $this->demo_upload_path . $filename;

		// Check if file is older than 1 hour and delete to redownload.
		if ( file_exists( $filepath ) && ( time() - filemtime( $filepath ) > 60 * 60 ) ) {
			wp_delete_file( $filepath );
		}

		if ( true === $zip && file_exists( $filepath . '.zip' ) && ( time() - filemtime( $filepath . '.zip' ) > 60 * 60 ) ) {
			wp_delete_file( $filepath . '.zip' );
		}

		// Check if we need to download the import file.
		if ( ! file_exists( $filepath ) ) {

			if ( true === $zip ) {

				// Check if we need to download the zipped import file.
				if ( ! file_exists( $filepath . '.zip' ) ) {

					// Download file.
					$zip = $this->download_file( $filename . '.zip' );

					// Check for errors.
					if ( is_wp_error( $zip ) ) {
						return $zip;
					} elseif ( ! file_exists( $zip ) ) {
						/* translators: %s is import file name. */
						return new WP_Error( 'error', sprintf( esc_html__( 'Import file “%s” not found.', 'hester-core' ), $filename . '.zip' ) );
					}
				}

				// Unzip the archive.
				$unzip = $this->unzip_file( $filepath . '.zip', dirname( $filepath ) );

				// Check for errors.
				if ( is_wp_error( $unzip ) ) {
					return $unzip;
				}
			} else {

				// Download file.
				$filepath = $this->download_file( $filename );
			}

			if ( is_wp_error( $filepath ) ) {
				return $filepath;
			} elseif ( ! file_exists( $filepath ) ) {
				/* translators: %s is import file name. */
				return new WP_Error( 'error', sprintf( esc_html__( 'Import file “%s” not found.', 'hester-core' ), $filename ) );
			}
		}

		global $wp_filesystem;

		// Check if the the global filesystem isn't setup yet.
		if ( is_null( $wp_filesystem ) ) {
			WP_Filesystem();
		}

		// Get file contents.
		$content = $wp_filesystem->get_contents( $filepath );

		if ( ! $content ) {
			/* translators: %s is import file name. */
			return new WP_Error( 'error', sprintf( esc_html__( 'Import file “%s” is empty.', 'hester-core' ), $filename ) );
		}

		return $content;
	}

	/**
	 * Main content importer method.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function import_content( $attachments = false ) {

		$filename = 'content.xml';
		$filepath = $this->demo_upload_path . $filename;

		// Get contents to import.
		$content = $this->get_import_file_contents( $filename, true );

		// Check for errors.
		if ( is_wp_error( $content ) ) {
			return $content;
		}

		// Before content import.
		$this->before_content_import();

		// Start import.
		$import = $this->process_import_content( $filepath, $attachments );

		if ( is_wp_error( $import ) ) {
			return $import;
		}

		// After content import.
		$this->after_content_import();
	}

	/**
	 * Main widgets importer method.
	 *
	 * @since 1.0.0
	 */
	public function import_widgets() {

		// Check if helper class exists.
		if ( ! class_exists( 'Hester_Widgets_Import_Export' ) ) {

			$class_widgets_import = plugin_dir_path( __FILE__ ) . 'importers/class-widgets-import-export.php';

			if ( file_exists( $class_widgets_import ) ) {
				require_once $class_widgets_import;
			} else {
				return new WP_Error( 'error', esc_html__( 'Can not retrieve class-widgets-import-export.php', 'hester-core' ) );
			}
		}

		// Get contents to import.
		$content = $this->get_import_file_contents( 'widgets.json' );

		// Check for errors.
		if ( is_wp_error( $content ) ) {
			return $content;
		}

		// Decode data.
		$content = json_decode( $content, true );

		// Import widgets data.
		$results = Hester_Widgets_Import_Export::import( $content );
	}

	/**
	 * Import XML.
	 *
	 * @since 1.0.0
	 * @param string $xml_file          Path to XML file.
	 * @param bool   $fetch_attachments Download attachments.
	 */
	public function process_import_content( $xml_file, $fetch_attachments = false ) {

		// Make sure importers constant is defined.
		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		// Import file location.
		$import_file = ABSPATH . 'wp-admin/includes/import.php';

		// Include import file.
		if ( ! file_exists( $import_file ) ) {
			return;
		}

		// Include import file.
		require_once $import_file;

		// Define error var.
		$importer_error = false;

		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

			if ( file_exists( $class_wp_importer ) ) {
				require_once $class_wp_importer;
			} else {
				$importer_error = __( 'Can not retrieve class-wp-importer.php', 'hester-core' );
			}
		}

		if ( ! class_exists( 'Hester_Core_WP_Import' ) ) {

			$class_wp_import = plugin_dir_path( __FILE__ ) . 'importers/class-wordpress-importer.php';

			if ( file_exists( $class_wp_import ) ) {
				require_once $class_wp_import;
			} else {
				$importer_error = __( 'Can not retrieve wordpress-importer.php', 'hester-core' );
			}
		}

		$result = false;

		// Display error.
		if ( $importer_error ) {
			return new WP_Error( 'xml_import_error', $importer_error );
		} else {

			// No error, lets import things...
			if ( ! is_file( $xml_file ) ) {
				$importer_error = __( 'Sample data file appears corrupt or can not be accessed.', 'hester-core' );
				return new WP_Error( 'xml_import_error', $importer_error );
			} else {

				$importer = new Hester_Core_WP_Import();

				$importer->fetch_attachments = $fetch_attachments;

				ob_start();
				$importer->import( $xml_file );
				$result = ob_get_clean();
			}
		}

		return $result;
	}

	/**
	 * Before Import XML.
	 *
	 * @since 1.0.0
	 */
	private function before_content_import() {

		// Delete the default post and page.
		$sample_page      = get_page_by_path( 'sample-page', OBJECT, 'page' );
		$hello_world_post = get_page_by_path( 'hello-world', OBJECT, 'post' );

		if ( ! is_null( $sample_page ) ) {
			wp_delete_post( $sample_page->ID, true );
		}

		if ( ! is_null( $hello_world_post ) ) {
			wp_delete_post( $hello_world_post->ID, true );
		}

		// File to import.
		$filename = 'options.json';
		$filepath = $this->demo_upload_path . $filename;

		// Check if we need to download the import file.
		if ( ! file_exists( $filepath ) ) {

			$filepath = $this->download_file( $filename );

			if ( is_wp_error( $filepath ) ) {
				wp_send_json_error( esc_html__( 'Options import failed.', 'hester-core' ) . ' ' . $filepath->get_error_message(), 'options_import_error' );
			} elseif ( ! file_exists( $filepath ) ) {
				/* translators: %s is import file name. */
				wp_send_json_error( sprintf( esc_html__( 'Options import failed. Import file “%s” not found.', 'hester-core' ), $filename ), 'options_import_error' );
			}
		}

		global $wp_filesystem;

		// Check if the the global filesystem isn't setup yet.
		if ( is_null( $wp_filesystem ) ) {
			WP_Filesystem();
		}

		// Get file contents.
		$content = $wp_filesystem->get_contents( $filepath );

		if ( ! $content ) {
			wp_send_json_error( esc_html__( 'Options empty.', 'hester-core' ), 'options_import_error' );
		}

		// Check if helper class exists.
		if ( ! class_exists( 'Hester_Options_Import_Export' ) ) {

			$class_options_import = plugin_dir_path( __FILE__ ) . 'importers/class-options-import-export.php';

			if ( file_exists( $class_options_import ) ) {
				require_once $class_options_import;
			} else {
				wp_send_json_error( esc_html__( 'Can not retrieve class-options-import-export.php', 'hester-core' ), 'options_import_error' );
			}
		}

		// Decode data.
		$content = json_decode( $content, true );

		if ( isset( $content['menus'] ) && ! empty( $content['menus'] ) ) {
			foreach ( $content['menus'] as $slug => $name ) {
				wp_delete_nav_menu( $slug );
			}
		}
	}

	/**
	 * After Import XML.
	 *
	 * @since 1.0.0
	 */
	private function after_content_import() {
	}

	/**
	 * Import site options.
	 *
	 * @since 1.0.0
	 */
	public function import_options() {

		// Check if helper class exists.
		if ( ! class_exists( 'Hester_Options_Import_Export' ) ) {

			$class_options_import = plugin_dir_path( __FILE__ ) . 'importers/class-options-import-export.php';

			if ( file_exists( $class_options_import ) ) {
				require_once $class_options_import;
			} else {
				return new WP_Error( 'error', esc_html__( 'Can not retrieve class-options-import-export.php', 'hester-core' ) );
			}
		}

		// Get contents to import.
		$content = $this->get_import_file_contents( 'options.json' );

		// Check for errors.
		if ( is_wp_error( $content ) ) {
			return $content;
		}

		// Decode data.
		$content = json_decode( $content, true );

		// Import options data.
		$results = Hester_Options_Import_Export::instance()->import( $content );

		// Generate Dynamic styles.
		$hester_dynamic_styles =  hester_core()->theme_name . '_dynamic_styles';
		if ( function_exists( $hester_dynamic_styles ) ) {
			$hester_dynamic_styles()->update_dynamic_file();
		}
	}

	/**
	 * Import WPForms.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function import_wpforms() {

		// Get contents to import.
		$content = $this->get_import_file_contents( 'wpforms.json' );

		// Check for errors.
		if ( is_wp_error( $content ) ) {
			return $content;
		}

		// Decode data.
		$forms = json_decode( $content, true );

		if ( ! empty( $forms ) ) {

			$ids = array();

			foreach ( $forms as $form ) {

				$title  = ! empty( $form['settings']['form_title'] ) ? $form['settings']['form_title'] : '';
				$desc   = ! empty( $form['settings']['form_desc'] ) ? $form['settings']['form_desc'] : '';
				$new_id = post_exists( $title );

				if ( ! $new_id ) {
					$new_id = wp_insert_post(
						array(
							'post_title'   => $title,
							'post_status'  => 'publish',
							'post_type'    => 'wpforms',
							'post_excerpt' => $desc,
						)
					);
				}

				if ( $new_id ) {

					$ids[ $form['id'] ] = $new_id;

					$form['id'] = $new_id;
					wp_update_post(
						array(
							'ID'           => $new_id,
							'post_content' => empty( $form ) ? false : wp_slash( wp_json_encode( $form ) ),
						)
					);
				}
			}

			update_option( 'hester_wpforms_imported_ids', $ids );
		}
	}

	/**
	 * Download file from remote.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filename File name to download.
	 * @param array  $args {
	 *     Optional.
	 *
	 *     @type string $demo_id Demo ID. Used to create folder name.
	 *     @type string $path    Path to the folder where the file will be downloaded.
	 *     @type string $remote  Remote URL to the folder where the file is located.
	 * }
	 */
	private function download_file( $filename, $args = array() ) {

		// Default args.
		$defaults = array(
			'demo_id' => $this->demo_id,
			'path'    => $this->demo_upload_path,
			'remote'  => $this->remote,
		);

		$args = wp_parse_args( $args, $defaults );

		// Build filepath.
		$filepath = $args['path'] . $filename;

		if ( ! file_exists( $filepath ) || 0 === filesize( $filepath ) ) {

			// Download file.
			$download_url = trailingslashit( $args['remote'] ) . $args['demo_id'] . '/' . $filename;
			return $this->download( $download_url, $filepath );
		}

		// Return filepath.
		return $filepath;
	}

	public function download( $from, $to ) {

		$content = $this->get_remote_file_content( $from );

		if ( ! empty( $content ) ) {

			// Gives us access to the download_url() and wp_handle_sideload() functions.
			require_once ABSPATH . 'wp-admin/includes/file.php';

			global $wp_filesystem;

			// Check if the the global filesystem isn't setup yet.
			if ( is_null( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			// Make sure path exists.
			if ( ! file_exists( dirname( $to ) ) ) {
				wp_mkdir_p( dirname( $to ) );
			}

			if ( $wp_filesystem->put_contents( $to, $content ) ) {
				return $to;
			}
		}

		return new WP_Error(
			'error',
			sprintf(
				/* translators: %1$s is remote file name */
				__( 'Could not download remote file: %1$s', 'hester-core' ),
				$from
			)
		);
	}

	/**
	 * Return contents of a remote file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $remote Full path and filename of a remote file.
	 */
	private function get_remote_file_content( $remote ) {

		// Request remote file content.
		$response = wp_remote_get(
			$remote,
			array(
				'timeout' => 300,
			)
		);

		// Response code.
		$code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $code ) {
			return new WP_Error(
				'error',
				sprintf(
					/* translators: 1: is remote file name, 2: is error code. */
					__( 'Could not download remote file: %1$s. Error code: %2$s', 'hester-core' ),
					basename( $remote ),
					$code
				)
			);
		}

		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Unzips a specified ZIP file to a location.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file Full path and filename of ZIP archive.
	 * @param string $to   Full path on the filesystem to extract archive to.
	 */
	private function unzip_file( $file, $to ) {

		// Gives us access to the download_url() and wp_handle_sideload() functions.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		global $wp_filesystem;

		// Check if the the global filesystem isn't setup yet.
		if ( is_null( $wp_filesystem ) ) {
			WP_Filesystem();
		}

		$return = unzip_file( $file, $to );

		wp_delete_file( $file );

		return $return;
	}

	/**
	 * Activate plugin.
	 *
	 * @since 1.0.0
	 */
	public function activate_plugin() {

		$plugin = array();

		if ( isset( $_POST['plugin'], $_POST['plugin']['name'], $_POST['plugin']['slug'] ) ) {
			$plugin = array(
				'name' => sanitize_text_field( wp_unslash( $_POST['plugin']['name'] ) ),
				'slug' => sanitize_text_field( wp_unslash( $_POST['plugin']['slug'] ) ),
			);
		}

		// Validate plugin data.
		if ( empty( $plugin ) || ! is_array( $plugin ) || ! isset( $plugin['slug'] ) || ! isset( $plugin['name'] ) ) {
			wp_send_json_error( esc_html__( 'Plugin activation error', 'hester-core' ), 'activate_plugin_error' );
		}

		$hester_plugin_utilities =  hester_core()->theme_name . '_plugin_utilities';

		// Check if helper class exists.
		if ( ! function_exists( $hester_plugin_utilities ) ) {
			wp_send_json_error( esc_html__( 'Hester theme not active', 'hester-core' ), 'activate_plugin_error' );
		}

		// Activate plugin.
		$response = $hester_plugin_utilities()->activate_plugin( $plugin['slug'] );

		return $response;
	}

	/**
	 * Downloads an image from the specified URL.
	 *
	 * Taken from the core media_sideload_image() function and
	 * modified to return an array of data instead of html.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The image file path.
	 * @return array An array of image data.
	 */
	public function sideload_image( $file ) {
		$data = new stdClass();

		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		if ( ! empty( $file ) ) {

			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|svg|gif|png)\b/i', $file, $matches );
			$file_array         = array();
			$file_array['name'] = basename( $matches[0] );

			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			// Check if image exists in media library to prevent duplicates.
			$id = $this->media_image_exists( $file_array['name'] );

			if ( false === $id ) {
				// Do the validation and storage stuff.
				$id = media_handle_sideload( $file_array, 0 );
			}

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				unlink( $file_array['tmp_name'] );
				return $id;
			}

			// Build the object to return.
			$meta                = wp_get_attachment_metadata( $id );
			$data->attachment_id = $id;
			$data->url           = wp_get_attachment_url( $id );
			$data->thumbnail_url = wp_get_attachment_thumb_url( $id );
			$data->height        = $meta['height'];
			$data->width         = $meta['width'];
		}

		return $data;
	}

	/**
	 * Checks to see whether a string is an image url or not.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The string to check.
	 * @return bool Whether the string is an image url or not.
	 */
	public function is_image_url( $string = '' ) {
		if ( is_string( $string ) ) {

			if ( preg_match( '/\.(jpg|jpeg|svg|png|gif)/i', $string ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks to see whether a file exists in the uploads folder.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filename Filename to check.
	 * @return bool Whether the file exists or not.
	 */
	public function media_image_exists( $filename ) {

		$filename = preg_replace( '/[_-]\d+x\d+(?=\.[a-z]{3,4}$)/i', '', $filename );
		$filename = pathinfo( $filename, PATHINFO_FILENAME );

		$args = array(
			'posts_per_page' => 1,
			'post_type'      => 'attachment',
			'name'           => trim( $filename ),
		);

		$get_attachment = new WP_Query( $args );

		if ( ! $get_attachment || ! isset( $get_attachment->posts, $get_attachment->posts[0] ) ) {
			return false;
		}

		$post = $get_attachment->posts[0];

		return $post->ID;
	}

	/**
	 * Remap WPForms ids used in shortcodes.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object.
	 * @return WP_Post
	 */
	public function map_wpforms_ids( $post ) {

		$imported_ids = get_option( 'hester_wpforms_imported_ids', array() );

		if ( ! empty( $imported_ids ) ) {
			// Replace ID's.
			foreach ( $imported_ids as $old_id => $new_id ) {
				$post['post_content'] = str_replace( '[wpforms id="' . $old_id, '[wpforms id="' . $new_id, $post['post_content'] );
				$post['post_content'] = str_replace( '<!-- wp:wpforms/form-selector {"formId":"' . $old_id, '<!-- wp:wpforms/form-selector {"formId":"' . $new_id, $post['post_content'] );
			}
		}

		return $post;
	}

	/**
	 * Configure class variables.
	 *
	 * @since 1.0.0
	 *
	 * @param string $demo_id Demo ID.
	 * @return void|WP_Error
	 */
	public function configure_paths( $demo_id ) {

		// Get upload dir.
		$upload_dir = wp_upload_dir();

		// Check upload folder permission.
		if ( ! wp_is_writable( trailingslashit( $upload_dir['basedir'] ) ) ) {
			return new WP_Error( 'error', __( 'Upload folder not writable.', 'hester-core' ) );
		}

		$this->demo_id          = $demo_id;
		$this->demo_upload_uri  = trailingslashit( $upload_dir['baseurl'] ) . hester_core()->theme_name. '/' . $demo_id . '/';
		$this->demo_upload_path = trailingslashit( $upload_dir['basedir'] ) . hester_core()->theme_name. '/' . $demo_id . '/';
		// Create theme folder.
		if ( ! file_exists( $this->demo_upload_path ) ) {
			wp_mkdir_p( $this->demo_upload_path );
		}
	}
}

/**
 * The function which returns the one Hester_Demo_Importer instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $hester_demo_importer = hester_demo_importer(); ?>
 *
 * @since 1.0.0
 *
 * @return object
 */
function hester_demo_importer() {
	return Hester_Demo_Importer::instance();
}

hester_demo_importer();
