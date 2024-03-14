<?php
/**
 * Demo Importer Plus Page
 *
 * @since 1.0.0
 * @package Demo Importer Plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Demo_Importer_Plus' ) ) {

	/**
	 * Demo Impoter Plus Admin Settings
	 */
	class Demo_Importer_Plus {

		/**
		 * API URL which is used to get the response from.
		 *
		 * @since  1.0.0
		 * @var (String) URL
		 */
		public $api_url = DEMO_IMPORTER_PLUS_MAIN_DEMO_URI;

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Initiator
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

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_setup_theme', array( $this, 'init_admin_settings' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ), 99 );
		}

		/**
		 * Admin settings init
		 */
		public function init_admin_settings() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		}

		/**
		 * Enqueue admin scripts.
		 *
		 * @param  string $hook Current hook name.
		 * @return void
		 */
		public function admin_enqueue( $hook = '' ) {
			$min     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '-' . DEMO_IMPORTER_PLUS_VER;
			$dist    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : 'dist/';

			if ( 'appearance_page_demo-importer-plus' !== $hook ) {
				return;
			}

			global $is_IE, $is_edge;

			if ( $is_IE || $is_edge ) {
				wp_enqueue_script( 'demo-importer-plus-eventsource', DEMO_IMPORTER_PLUS_URI . 'assets/admin/js/eventsource.min.js', array( 'jquery', 'wp-util', 'updates' ), DEMO_IMPORTER_PLUS_VER, true );
			}

			// Fetch.
			wp_register_script( 'demo-importer-plus-fetch', DEMO_IMPORTER_PLUS_URI . 'assets/admin/js/fetch.umd.js', array( 'jquery' ), DEMO_IMPORTER_PLUS_VER, true );

			// API.
			wp_register_script( 'demo-importer-plus-api', DEMO_IMPORTER_PLUS_URI . 'assets/admin/js/demo-importer-plus-api.js', array( 'jquery', 'demo-importer-plus-fetch' ), DEMO_IMPORTER_PLUS_VER, true );

			// Admin Page.
			wp_enqueue_style( 'demo-importer-plus-admin', DEMO_IMPORTER_PLUS_URI . "assets/admin/{$dist}css/demo-importer-plus-admin{$version}{$min}.css", DEMO_IMPORTER_PLUS_VER, true );

			wp_style_add_data( 'demo-importer-plus-admin', 'rtl', 'replace' );

			wp_enqueue_script( 'infinite-scroll', 'https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.js', [], '4.1.0', true );

			wp_register_script( 'demo-importer-plus-admin-page', DEMO_IMPORTER_PLUS_URI . 'assets/admin/js/demo-importer-plus-admin.js', array( 'jquery', 'wp-util', 'updates', 'jquery-ui-autocomplete', 'demo-importer-plus-api', 'infinite-scroll' ), DEMO_IMPORTER_PLUS_VER, true );

			$data = $this->get_local_vars();

			wp_localize_script( 'demo-importer-plus-admin-page', 'demoImporterVars', $data );

			wp_enqueue_script( 'demo-importer-plus-admin-page' );
		}

		/**
		 * Get Local vars
		 *
		 * @return void
		 */
		public function get_local_vars() {
			$default_page_builder = $this->get_setting( 'page_builder' );
			$data                 = apply_filters(
				'demo_importer_plus_localize_vars',
				array(
					'debug'                              => defined( 'WP_DEBUG' ) ? true : false,
					'ajaxurl'                            => esc_url( admin_url( 'admin-ajax.php' ) ),
					'siteURL'                            => site_url(),
					'isPro'                              => apply_filters( 'demo_importer_plus_is_pro', false ),
					'getProText'                         => apply_filters( 'demo_importer_plus_get_pro_text', __( 'Get Pro', 'demo-importer-plus' ) ),
					'activateLicenseTxt'                 => apply_filters( 'demo_importer_plus_activate_license_text', __( 'Activate License', 'demo-importer-plus' ) ),
					'getProURL'                          => apply_filters( 'demo_importer_plus_get_pro_url', 'https://rishitheme.com/pricing' ),
					'proLicenseActive'                   => apply_filters( 'demo_importer_plus_pro_active', false ),
					'licensePageURL'                     => apply_filters( 'demo_importer_plus_license_page', admin_url( 'themes.php?page=rishi-dashboard' ) ),
					'_ajax_nonce'                        => wp_create_nonce( 'demo-importer-plus' ),
					'requiredPlugins'                    => array(),
					'syncLibraryStart'                   => '<span class="message">' . esc_html__( 'Syncing template library in the background. The process can take anywhere between 2 to 3 minutes. We will notify you once done.', 'demo-importer-plus' ) . '</span>',
					'xmlRequiredFilesMissing'            => __( 'Some of the files required during the import process are missing.<br/><br/>Please try again after some time.', 'demo-importer-plus' ),
					'importFailedMessageDueToDebug'      => __( '<p>WordPress debug mode is currently enabled on your website. This has interrupted the import process..</p><p>Kindly disable debug mode and try importing Starter Template again.</p><p>You can add the following code into the wp-config.php file to disable debug mode.</p><p><code>define(\'WP_DEBUG\', false);</code></p>', 'demo-importer-plus' ),
					/* translators: %s is a documentation link. */
					'importFailedMessage'                => sprintf( __( '<p>Your website is facing a temporary issue in connecting the template server.</p><p>Read <a href="%s" target="_blank">article</a> to resolve the issue and continue importing template.</p>', 'demo-importer-plus' ), esc_url( 'https://rishitheme.com/docs/how-to-resolve-demo-import-issue/' ) ),
					/* translators: %s is a documentation link. */
					'importFailedRequiredPluginsMessage' => sprintf( __( '<p>Your website is facing a temporary issue in connecting the template server.</p><p>Read <a href="%s" target="_blank">article</a> to resolve the issue and continue importing template.</p>', 'demo-importer-plus' ), esc_url( 'https://rishitheme.com/docs/how-to-resolve-demo-import-issue/' ) ),

					'strings'                            => array(
						/* translators: %s are white label strings. */
						'warningBeforeCloseWindow' => __( 'Warning! Import process is not complete. Don\'t close the window until import process complete. Do you still want to leave the window?', 'demo-importer-plus' ),
						'viewSite'                 => __( 'Done! View Site', 'demo-importer-plus' ),
						/* translators: %s is a template name */
						'importSingleTemplate'     => __( 'Import "%s" Template', 'demo-importer-plus' ),
					),
					'log'                                => array(
						'bulkInstall'  => __( 'Installing Required Plugins..', 'demo-importer-plus' ),
						/* translators: %s are white label strings. */
						'themeInstall' => __( 'Installing Theme..', 'demo-importer-plus' ),
					),
					'default_page_builder'               => $default_page_builder,
					'default_page_builder_sites'         => [],
					'demoAPIURL'                         => DEMO_IMPORTER_PLUS_MAIN_DEMO_URI,
					'allowedDemos'					   	 => DEMO_IMPORTER_PLUS_MAIN_DEMO_ID,
					'categories'                         => array(),
					'page-builders'                      => array(),

					'ApiURL'                             => $this->api_url,
					'category_slug'                      => 'demo-importer-plus-category',
					'page_builder'                       => 'demo-importer-plus-page-builder',
					'cpt_slug'                           => 'demo-importer-plus',
					'dismiss'                            => __( 'Dismiss this notice.', 'demo-importer-plus' ),
					'compatibilities'                    => $this->get_compatibilities(),
					'compatibilities_data'               => $this->get_compatibilities_data(),
					'headings'                           => array(
						'subscription' => esc_html__( 'One Last Step..', 'demo-importer-plus' ),
						'site_import'  => esc_html__( 'Your Selected Website is Being Imported.', 'demo-importer-plus' ),
						'page_import'  => esc_html__( 'Your Selected Template is Being Imported.', 'demo-importer-plus' ),
					),
				)
			);

			return $data;
		}

		/**
		 * Import Compatibility Errors
		 */
		public function get_compatibilities_data() {
			return array(
				'xmlreader'            => array(
					'title'   => esc_html__( 'XMLReader Support Missing', 'demo-importer-plus' ),
					/* translators: %s doc link. */
					'tooltip' => '<p>' . esc_html__( 'You\'re close to importing the template. To complete the process, enable XMLReader support on your website..', 'demo-importer-plus' ) . '</p><p>' . sprintf( __( 'Read an article <a href="%s" target="_blank">here</a> to resolve the issue.', 'demo-importer-plus' ), 'https://rishidemos.com/' ) . '</p>',
				),
				'curl'                 => array(
					'title'   => esc_html__( 'cURL Support Missing', 'demo-importer-plus' ),
					/* translators: %s doc link. */
					'tooltip' => '<p>' . esc_html__( 'To run a smooth import, kindly enable cURL support on your website.', 'demo-importer-plus' ) . '</p><p>' . sprintf( __( 'Read an article <a href="%s" target="_blank">here</a> to resolve the issue.', 'demo-importer-plus' ), 'https://rishidemos.com/' ) . '</p>',
				),
				'wp-debug'             => array(
					'title'   => esc_html__( 'Disable Debug Mode', 'demo-importer-plus' ),
					/* translators: %s doc link. */
					'tooltip' => '<p>' . esc_html__( 'WordPress debug mode is currently enabled on your website. With this, any errors from third-party plugins might affect the import process.', 'demo-importer-plus' ) . '</p><p>' . esc_html__( 'Kindly disable it to continue importing the Starter Template. To do so, you can add the following code into the wp-config.php file.', 'demo-importer-plus' ) . '</p><p><code>define(\'WP_DEBUG\', false);</code></p><p>' . sprintf( __( 'Read an article <a href="%s" target="_blank">here</a> to resolve the issue.', 'demo-importer-plus' ), 'https://rishidemos.com/' ) . '</p>',
				),
				'update-available'     => array(
					'title'   => esc_html__( 'Update Plugin', 'demo-importer-plus' ),
					/* translators: %s update page link. */
					'tooltip' => '<p>' . esc_html__( 'Updates are available for plugins used in this starter template.', 'demo-importer-plus' ) . '</p>##LIST##<p>' . sprintf( __( 'Kindly <a href="%s" target="_blank">update</a> them for a successful import. Skipping this step might break the template design/feature.', 'demo-importer-plus' ), esc_url( network_admin_url( 'update-core.php' ) ) ) . '</p>',
				),
				'third-party-required' => array(
					'title'   => esc_html__( 'Required Plugins Missing', 'demo-importer-plus' ),
					'tooltip' => '<p>' . esc_html__( 'This starter template requires premium plugins. As these are third party premium plugins, you\'ll need to purchase, install and activate them first.', 'demo-importer-plus' ) . '</p>',
				),
				'dynamic-page'         => array(
					'title'   => esc_html__( 'Dynamic Page', 'demo-importer-plus' ),
					'tooltip' => '<p>' . esc_html__( 'The page template you are about to import contains a dynamic widget/module. Please note this dynamic data will not be available with the imported page.', 'demo-importer-plus' ) . '</p><p>' . esc_html__( 'You will need to add it manually on the page.', 'demo-importer-plus' ) . '</p><p>' . esc_html__( 'This dynamic content will be available when you import the entire site.', 'demo-importer-plus' ) . '</p>',
				),
			);
		}

		/**
		 * Get an instance of WP_Filesystem_Direct.
		 */
		public static function get_filesystem() {
			global $wp_filesystem;

			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();

			return $wp_filesystem;
		}

		/**
		 * Getter for $api_url
		 */
		public function get_api_url() {
			return $this->api_url;
		}

		/**
		 * Get API site Option.
		 */
		public function get_api_option( $option ) {
			return get_site_option( $option, array() );
		}

		/**
		 * Get all compatibilities
		 */
		public function get_compatibilities() {

			$data = $this->get_compatibilities_data();

			$compatibilities = array(
				'errors'   => array(),
				'warnings' => array(),
			);

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$compatibilities['warnings']['wp-debug'] = $data['wp-debug'];
			}

			if ( ! class_exists( 'XMLReader' ) ) {
				$compatibilities['errors']['xmlreader'] = $data['xmlreader'];
			}

			if ( ! function_exists( 'curl_version' ) ) {
				$compatibilities['errors']['curl'] = $data['curl'];
			}

			return $compatibilities;
		}

		/**
		 * Downloads an image from the specified URL.
		 *
		 * @param string $file The image file path.
		 */
		public static function sideload_image( $file ) {
			$data = new stdClass();

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';
			}

			if ( ! empty( $file ) ) {

				preg_match( '/[^\?]+\.(jpe?g|jpe|svg|gif|png)\b/i', $file, $matches );
				$file_array         = array();
				$file_array['name'] = basename( $matches[0] );

				$file_array['tmp_name'] = download_url( $file );

				if ( is_wp_error( $file_array['tmp_name'] ) ) {
					return $file_array['tmp_name'];
				}

				$id = media_handle_sideload( $file_array, 0 );

				if ( is_wp_error( $id ) ) {
					unlink( $file_array['tmp_name'] );
					return $id;
				}

				$meta                = wp_get_attachment_metadata( $id );
				$data->attachment_id = $id;
				$data->url           = wp_get_attachment_url( $id );
				$data->thumbnail_url = wp_get_attachment_thumb_url( $id );
				$data->height        = isset( $meta['height'] ) ? $meta['height'] : '';
				$data->width         = isset( $meta['width'] ) ? $meta['width'] : '';
			}

			return $data;
		}

		/**
		 * Add main menu
		 */
		public function add_admin_menu() {
			$page_title = apply_filters( 'Demo_Importer_Plus_menu_page_title', esc_html__( 'Demo Importer Plus', 'demo-importer-plus' ) );

			$page = add_theme_page( $page_title, $page_title, 'manage_options', 'demo-importer-plus', array( $this, 'menu_callback' ) );
		}

		/**
		 * Menu callback
		 */
		public function menu_callback() {
			include DEMO_IMPORTER_PLUS_DIR . '/admin/partials/menu-page.php';
		}

		/**
		 * Get theme install, active or inactive status.
		 */
		public function get_theme_status( $theme_name = '' ) {

			$theme = wp_get_theme();

			// Theme installed and activate.
			if ( $theme_name === $theme->name || $theme_name === $theme->parent_theme ) {
				return 'installed-and-active';
			}

			// Theme installed but not activate.
			foreach ( (array) wp_get_themes() as $theme_dir => $theme ) {
				if ( $theme_name === $theme->name || $theme_name === $theme->parent_theme ) {
					return 'installed-but-inactive';
				}
			}

			return 'not-installed';
		}

		/**
		 * Page Builder List
		 *
		 * @return array
		 */
		public function get_page_builders() {
			return array(
				'elementor' => array(
					'slug'      => 'elementor',
					'name'      => esc_html__( 'Elementor', 'demo-importer-plus' ),
					'image_url' => DEMO_IMPORTER_PLUS_URI . 'assets/images/elementor.jpg',
				),
			);
		}

		/**
		 * Get Page Builders Images
		 *
		 * @param  string $slug Page Builder Slug.
		 * @return array page builders.
		 */
		public function get_page_builder_image( $slug ) {

			$image = '';

			switch ( $slug ) {

				case 'elementor':
					$image = DEMO_IMPORTER_PLUS_URI . 'assets/images/elementor.jpg';
					break;
			}

			return $image;
		}

		/**
		 * Get single setting value
		 *
		 * @param  string $key      Setting key.
		 * @param  mixed  $defaults Setting value.
		 */
		public function get_setting( $key = '', $defaults = '' ) {

			$settings = $this->get_settings();

			if ( empty( $settings ) ) {
				return $defaults;
			}

			if ( array_key_exists( $key, $settings ) ) {
				return $settings[ $key ];
			}

			return $defaults;
		}

		/**
		 * Get Settings
		 *
		 * @return array Stored settings.
		 */
		public function get_settings() {

			$defaults = array(
				'page_builder' => 'elementor',
			);

			$stored_data = get_option( 'demo_importer_plus_settings', $defaults );

			return wp_parse_args( $stored_data, $defaults );
		}

		/**
		 * Get Page Builder Sites
		 *
		 * @param  string $default_page_builder default page builder slug.
		 */
		public function get_sites_by_page_builder( $default_page_builder = 'elementor' ) {
			$sites_and_pages            = $this->get_all_sites();
			$current_page_builder_sites = array_filter(
				$sites_and_pages,
				function( $site ) use ( $default_page_builder ) {
					if ( isset( $site['site_page_builder'] ) ) {
						return $site['site_page_builder'] === $default_page_builder;
					}
				}
			);

			return $current_page_builder_sites;
		}

		/**
		 * Get all sites
		 */
		public function get_all_sites() {
			$all_sites_pages = array();
			$sorted_sites    = array();
			$allowed_sites   = array();
			if ( defined( 'DEMO_IMPORTER_PLUS_MAIN_DEMO_ID' ) && ! empty( DEMO_IMPORTER_PLUS_MAIN_DEMO_ID ) ) {
				$allowed_sites = DEMO_IMPORTER_PLUS_MAIN_DEMO_ID;
			}

			$sites_and_pages = get_transient( 'demo_imprt_all_sites_data' );
			delete_transient( 'demo_imprt_all_sites_data' );

			if ( ! empty( $sites_and_pages ) ) {
				return $sites_and_pages;
			}

			if ( ! empty( $allowed_sites ) ) {
				$sites_and_pages = wp_remote_get( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . '/wp-json/demoimporterplusapi/v1/dipa-demos/?ids=' . implode( ',', $allowed_sites ) );
			} else {
				$sites_and_pages = wp_remote_get( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . '/wp-json/demoimporterplusapi/v1/dipa-demos/' );
			}

			if ( ! is_wp_error( $sites_and_pages ) ) {
				$sites_and_pages = json_decode( wp_remote_retrieve_body( $sites_and_pages ), true );
				$sites_pages     = isset( $sites_and_pages['data'] ) ? $sites_and_pages['data'] : array();

				if ( ! empty( $sites_pages ) ) {
					foreach ( $sites_pages as $site_id => $site ) {
						if ( empty( $allowed_sites ) ) {
							$site['order']                  = $site_id;
							$all_sites_pages[ $site['id'] ] = $site;
						} else {
							foreach ( $allowed_sites as $key => $value ) {
								if ( $value == $site['id'] ) {
									$site['order']                  = $key;
									$all_sites_pages[ $site['id'] ] = $site;
								}
							}
						}
					}
				}
				usort(
					$all_sites_pages,
					function ( $a, $b ) {
						return $a['order'] - $b['order'];
					}
				);

				foreach ( $all_sites_pages as $key => $sorted_site ) {
					$sorted_sites[ $sorted_site['id'] ] = $sorted_site;
				}

				if ( ! empty( $sorted_sites ) ) {
					set_transient( 'demo_imprt_all_sites_data', $sorted_sites, WEEK_IN_SECONDS );
				}
			}
			return $sorted_sites;
		}
	}

	Demo_Importer_Plus::get_instance();

}
