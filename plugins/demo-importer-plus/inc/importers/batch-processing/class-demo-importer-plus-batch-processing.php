<?php
/**
 * Batch Processing
 *
 * @package Demo Importer Plus
 * @since 1.0.0
 */

if ( ! class_exists( 'Demo_Importer_Plus_Batch_Processing' ) ) :

	/**
	 * Demo_Importer_Plus_Batch_Processing
	 *
	 * @since 1.0.0
	 */
	class Demo_Importer_Plus_Batch_Processing {

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 * @var object Class object.
		 * @access private
		 */
		private static $instance;

		/**
		 * Process All
		 *
		 * @since 1.0.0
		 * @var object Class object.
		 * @access public
		 */
		public static $process_all;

		/**
		 * Last Export Checksums
		 *
		 * @since 1.0.0
		 * @var object Class object.
		 * @access public
		 */
		public $last_export_checksums;

		/**
		 * Sites Importer
		 *
		 * @var object Class object.
		 * @access public
		 */
		public static $process_site_importer;

		/**
		 * Process Single Page
		 *
		 * @var object Class object.
		 * @access public
		 */
		public static $process_single;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
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

			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/helpers/class-demo-importer-plus-image-importer.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/helpers/class-wp-async-request.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/helpers/class-wp-background-process.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/helpers/class-wp-background-process-demo-importer-plus.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/helpers/class-wp-background-process-demo-importer-plus-single.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/helpers/class-wp-background-process-demo-importer-plus-importer.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/class-demo-importer-plus-batch-processing-widgets.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/class-demo-importer-plus-batch-processing-elementor.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/class-demo-importer-plus-batch-processing-misc.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/class-demo-importer-plus-batch-processing-importer.php';

			self::$process_all           = new WP_Background_Process_Demo_Importer();
			self::$process_single        = new WP_Background_Process_Demo_Importer_Single();
			self::$process_site_importer = new WP_Background_Process_Demo_Importer_Site_Importer();

			add_filter( 'demo_importer_plus_image_importer_skip_image', array( $this, 'skip_image' ), 10, 2 );
			add_action( 'demo_importer_plus_import_complete', array( $this, 'start_process' ) );
			add_action( 'demo_importer_plus_process_single', array( $this, 'start_process_single' ) );
			add_action( 'admin_head', array( $this, 'start_importer' ) );
		}

		/**
		 * Start Importer
		 */
		public function start_importer() {

			$current_screen = get_current_screen();
			
			if ( ! is_object( $current_screen ) && null === $current_screen ) {
				return;
			}
			
			if ( 'appearance_page_demo-importer-plus' === $current_screen->id ) {
				$this->import_site_categories();
				$this->process_import();
			}
		}

		public function import_site_categories() {
			Demo_Importer_plus_Batch_Processing_Importer::get_instance()->import_site_categories();
		}

		/**
		 * Process Batch
		 *
		 * @return mixed
		 */
		public function process_batch() {

			$this->log( 'Dispatch the Queue!' );

			self::$process_site_importer->save()->dispatch();

		}

		/**
		 * Log
		 */
		public function log( $message = '' ) {
			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( $message );
			} else {
				demo_importer_plus_error_log( $message );
				update_site_option( 'demo-importer-plus-batch-status-string', $message, 'no' );
			}
		}

		/**
		 * Process Import
		 */
		public function process_import() {

			// Batch is already started? Then return.
			$status = get_site_option( 'demo-importer-plus-batch-status' );
			if ( 'in-process' === $status ) {
				return;
			}

			// Check batch expiry.
			$expired = get_transient( 'demo-importer-plus-import-check' );
			if ( false !== $expired ) {
				return;
			}

			// For 1 week.
			set_transient( 'demo-importer-plus-import-check', 'true', apply_filters( 'demo_importer_plus_sync_check_time', WEEK_IN_SECONDS ) );

			update_site_option( 'demo-importer-plus-batch-status', 'in-process', 'no' );

			// Process batch.
			$this->process_batch();
		}

		/**
		 * Start Single Page Import
		 *
		 * @param  int $page_id Page ID .
		 */
		public function start_process_single( $page_id ) {

			add_filter( 'elementor/files/allow_unfiltered_upload', '__return_true', 9898 );

			$default_page_builder = Demo_Importer_Plus::get_instance()->get_setting( 'page_builder' );

			// Add "elementor" in import [queue].
			if ( 'elementor' === $default_page_builder ) {
				// @todo Remove required `allow_url_fopen` support.
				if ( ini_get( 'allow_url_fopen' ) ) {
					if ( is_plugin_active( 'elementor/elementor.php' ) ) {

						// !important, Clear the cache after images import.
						\Elementor\Plugin::$instance->posts_css_manager->clear_cache();

						$import = new \Elementor\TemplateLibrary\Demo_Importer_Plus_Batch_Processing_Elementor();
						self::$process_single->push_to_queue(
							array(
								'page_id'  => $page_id,
								'instance' => $import,
							)
						);
					}
				} else {
					demo_importer_plus_error_log( 'Couldn\'t not import image due to allow_url_fopen() is disabled!' );
				}
			}

			self::$process_single->save()->dispatch();
		}

		/**
		 * Skip Image from Batch Processing.
		 *
		 * @param  boolean $can_process Batch process image status.
		 * @param  array   $attachment  Batch process image input.
		 * @return boolean
		 */
		public function skip_image( $can_process, $attachment ) {

			if ( isset( $attachment['url'] ) && ! empty( $attachment['url'] ) ) {

				if ( strpos( $attachment['url'], site_url() ) !== false ) {
					return true;
				}

				if (
					strpos( $attachment['url'], DEMO_IMPORTER_PLUS_MAIN_DEMO_URI ) !== false
				) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Start Image Import
		 */
		public function start_process() {

			add_filter( 'elementor/files/allow_unfiltered_upload', '__return_true', 9898 );

			$wxr_id = get_site_option( 'demo_importer_plus_imported_wxr_id', 0 );
			if ( $wxr_id ) {
				wp_delete_attachment( $wxr_id, true );
				demo_importer_plus_error_log( 'Deleted Temporary WXR file ' . $wxr_id );
				delete_option( 'demo_importer_plus_imported_wxr_id' );
				demo_importer_plus_error_log( 'Option `demo_importer_plus_imported_wxr_id` Deleted.' );
			}

			$classes = array();

			$classes[] = Demo_Importer_Plus_Batch_Processing_Widgets::get_instance();

			if ( ini_get( 'allow_url_fopen' ) && is_plugin_active( 'elementor/elementor.php' ) ) {
				$import    = new \Elementor\TemplateLibrary\Demo_Importer_Plus_Batch_Processing_Elementor();
				$classes[] = $import;
			}

			$classes[] = Demo_Importer_Plus_Batch_Processing_Misc::get_instance();

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Batch Process Started..' );
				foreach ( $classes as $key => $class ) {
					if ( method_exists( $class, 'import' ) ) {
						$class->import();
					}
				}
				WP_CLI::line( 'Batch Process Complete!' );
			} else {
				foreach ( $classes as $key => $class ) {
					self::$process_all->push_to_queue( $class );
				}

				self::$process_all->save()->dispatch();
			}

		}

		/**
		 * Get all post id's
		 *
		 * @param  array $post_types Post types.
		 */
		public static function get_pages( $post_types = array() ) {

			if ( $post_types ) {
				$args = array(
					'post_type'      => $post_types,
					'fields'         => 'ids',
					'no_found_rows'  => true,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) :

					return $query->posts;

				endif;
			}

			return null;
		}

		/**
		 * Get Supporting Post Types.
		 *
		 * @param  integer $feature Feature.
		 */
		public static function get_post_types_supporting( $feature ) {
			global $_wp_post_type_features;

			$post_types = array_keys(
				wp_filter_object_list( $_wp_post_type_features, array( $feature => true ) )
			);

			return $post_types;
		}

	}

	/**
	 * Starting this by calling 'get_instance()' method
	 */
	Demo_Importer_Plus_Batch_Processing::get_instance();

endif;
