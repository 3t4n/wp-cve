<?php
/**
 * Batch Processing
 *
 * @package Demo Importer Plus
 */

if ( ! class_exists( 'Demo_Importer_plus_Batch_Processing_Importer' ) ) :

	/**
	 * Demo_Importer_plus_Batch_Processing_Importer
	 */
	class Demo_Importer_plus_Batch_Processing_Importer {

		/**
		 * Instance
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
		}

		/**
		 * Import Categories
		 */
		public function import_categories() {
			demo_importer_plus_error_log( 'Requesting Tags' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Requesting Tags', 'no' );

			$api_args     = array(
				'timeout' => 30,
			);
			$tags_request = wp_remote_get( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . 'wp-json/wp/v2/sites-tag/?_fields=id,name,slug', $api_args );
			if ( ! is_wp_error( $tags_request ) && 200 === (int) wp_remote_retrieve_response_code( $tags_request ) ) {
				$tags = json_decode( wp_remote_retrieve_body( $tags_request ), true );

				if ( isset( $tags['code'] ) ) {
					$message = isset( $tags['message'] ) ? $tags['message'] : '';
					if ( ! empty( $message ) ) {
						demo_importer_plus_error_log( 'HTTP Request Error: ' . $message );
					} else {
						demo_importer_plus_error_log( 'HTTP Request Error!' );
					}
				} else {
					update_site_option( 'sites-tags', $tags, 'no' );

					if ( defined( 'WP_CLI' ) ) {
						$this->generate_file( 'sites-tags', $tags );
					}
				}
			}

			demo_importer_plus_error_log( 'Tags Imported Successfully!' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Tags Imported Successfully!', 'no' );
		}

		/**
		 * Generate JSON file.
		 *
		 * @param  string $filename File name.
		 * @param  array  $data     JSON file data.
		 */
		public function generate_file( $filename = '', $data = array() ) {
			if ( defined( 'WP_CLI' ) ) {
				Demo_Importer_Plus::get_instance()->get_filesystem()->put_contents( DEMO_IMPORTER_PLUS_DIR . 'inc/json/' . $filename . '.json', wp_json_encode( $data ) );
			}
		}

		/**
		 * Import Categories
		 */
		public function import_site_categories() {
			demo_importer_plus_error_log( 'Requesting Site Categories' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Requesting Site Categories', 'no' );

			$api_args           = array(
				'timeout' => 30,
			);
			$categories_request = wp_remote_get( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . 'wp-json/wp/v2/categories/?_fields=id,name,slug&per_page=100', $api_args );
			if ( ! is_wp_error( $categories_request ) && 200 === (int) wp_remote_retrieve_response_code( $categories_request ) ) {
				$categories = json_decode( wp_remote_retrieve_body( $categories_request ), true );

				if ( isset( $categories['code'] ) ) {
					$message = isset( $categories['message'] ) ? $categories['message'] : '';
					if ( ! empty( $message ) ) {
						demo_importer_plus_error_log( 'HTTP Request Error: ' . $message );
					} else {
						demo_importer_plus_error_log( 'HTTP Request Error!' );
					}
				} else {
					update_site_option( 'demo-importerplus-sites-categories', $categories, 'no' );

					if ( defined( 'WP_CLI' ) ) {
						$this->generate_file( 'demo-importerplus-sites-categories', $categories );
					}
				}
			}

			demo_importer_plus_error_log( 'Site Categories Imported Successfully!' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Site Categories Imported Successfully!', 'no' );
		}

		/**
		 * Import Block Categories
		 */
		public function import_block_categories() {
			demo_importer_plus_error_log( 'Requesting Block Categories' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Requesting Block Categories', 'no' );

			$api_args     = array(
				'timeout' => 30,
			);
			$tags_request = wp_remote_get( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . 'wp-json/wp/v2/blocks-category/?_fields=id,name,slug&per_page=100&hide_empty=1', $api_args );
			if ( ! is_wp_error( $tags_request ) && 200 === (int) wp_remote_retrieve_response_code( $tags_request ) ) {
				$tags = json_decode( wp_remote_retrieve_body( $tags_request ), true );

				if ( isset( $tags['code'] ) ) {
					$message = isset( $tags['message'] ) ? $tags['message'] : '';
					if ( ! empty( $message ) ) {
						demo_importer_plus_error_log( 'HTTP Request Error: ' . $message );
					} else {
						demo_importer_plus_error_log( 'HTTP Request Error!' );
					}
				} else {
					$categories = array();
					foreach ( $tags as $key => $value ) {
						$categories[ $value['id'] ] = $value;
					}

					update_site_option( 'demo-importer-plus-blocks-categories', $categories, 'no' );

					if ( defined( 'WP_CLI' ) ) {
						$this->generate_file( 'demo-importer-plus-blocks-categories', $categories );
					}
				}
			}

			demo_importer_plus_error_log( 'Block Categories Imported Successfully!' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Categories Imported Successfully!', 'no' );
		}


		/**
		 * Import Page Builders
		 */
		public function set_license_page_builder() {

			demo_importer_plus_error_log( 'Requesting License Page Builders' );

			$url = add_query_arg(
				array(
					'_fields'                  => 'id,name,slug',
					'site_url'                 => get_site_url(),
					'purchase_key'             => Demo_Importer_Plus::get_instance()->get_license_key(),
					'only_allow_page_builders' => 'true',
				),
				DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . 'wp-json/wp/v2/demoimporterplusapi/'
			);

			$api_args = array(
				'timeout' => 30,
			);

			$page_builder_request = wp_remote_get( $url, $api_args );
			if ( ! is_wp_error( $page_builder_request ) && 200 === (int) wp_remote_retrieve_response_code( $page_builder_request ) ) {
				$page_builders = json_decode( wp_remote_retrieve_body( $page_builder_request ), true );
				if ( isset( $page_builders['code'] ) ) {
					$message = isset( $page_builders['message'] ) ? $page_builders['message'] : '';
					if ( ! empty( $message ) ) {
						demo_importer_plus_error_log( 'HTTP Request Error: ' . $message );
					} else {
						demo_importer_plus_error_log( 'HTTP Request Error!' );
					}
				} else {
					// @codingStandardsIgnoreStart
					// Set mini agency page builder.
					$page_builder_slugs = wp_list_pluck( $page_builders, 'slug' );
					if ( in_array( 'elementor', $page_builder_slugs ) && ! in_array( 'beaver-builder', $page_builder_slugs ) ) {
						update_option( 'demo-importerplus-license-page-builder', 'elementor' );
						demo_importer_plus_error_log( 'Set "Elementor" as License Page Builder' );
					} elseif ( in_array( 'beaver-builder', $page_builder_slugs ) && ! in_array( 'elementor', $page_builder_slugs ) ) {
						update_option( 'demo-importerplus-license-page-builder', 'beaver-builder' );
						demo_importer_plus_error_log( 'Set "Beaver Builder" as License Page Builder' );
					} else {
						update_option( 'demo-importerplus-license-page-builder', '' );
						demo_importer_plus_error_log( 'Not Set Any License Page Builder' );
					}
					// @codingStandardsIgnoreEnd
				}
			}
		}

		/**
		 * Import Page Builders
		 */
		public function import_page_builders() {
			demo_importer_plus_error_log( 'Requesting Page Builders' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Requesting Page Builders', 'no' );

			$purchase_key = Demo_Importer_Plus::get_instance()->get_license_key();
			$site_url     = get_site_url();

			$api_args = array(
				'timeout' => 30,
			);

			$page_builder_request = wp_remote_get( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . 'wp-json/wp/v2/site-page-builder/?_fields=id,name,slug&site_url=' . $site_url . '&purchase_key=' . $purchase_key, $api_args );
			if ( ! is_wp_error( $page_builder_request ) && 200 === (int) wp_remote_retrieve_response_code( $page_builder_request ) ) {
				$page_builders = json_decode( wp_remote_retrieve_body( $page_builder_request ), true );

				if ( isset( $page_builders['code'] ) ) {
					$message = isset( $page_builders['message'] ) ? $page_builders['message'] : '';
					if ( ! empty( $message ) ) {
						demo_importer_plus_error_log( 'HTTP Request Error: ' . $message );
					} else {
						demo_importer_plus_error_log( 'HTTP Request Error!' );
					}
				} else {
					update_site_option( 'demo-importer-plus-sites-page-builders', $page_builders, 'no' );

					if ( defined( 'WP_CLI' ) ) {
						$this->generate_file( 'demo-importer-plus-sites-page-builders', $page_builders );
					}
				}
			}

			demo_importer_plus_error_log( 'Page Builders Imported Successfully!' );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Page Builders Imported Successfully!', 'no' );
		}

		/**
		 * Import Blocks
		 *
		 * @param  integer $page Page number.
		 */
		public function import_blocks( $page = 1 ) {

			demo_importer_plus_error_log( 'BLOCK: ---ACTUAL IMPORT ---' );
			$api_args   = array(
				'timeout' => 30,
			);
			$all_blocks = array();
			demo_importer_plus_error_log( 'BLOCK: Requesting ' . $page );
			update_site_option( 'demo-importer-plus-blocks-batch-status-string', 'Requesting for blocks page - ' . $page, 'no' );
			$response = wp_remote_get( DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . '/wp-json/demo-importer-plus-blocks/v1/blocks?per_page=100&page=' . $page, $api_args );
			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
				$demo_importer_plus_blocks = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( isset( $demo_importer_plus_blocks['code'] ) ) {
					$message = isset( $demo_importer_plus_blocks['message'] ) ? $demo_importer_plus_blocks['message'] : '';
					if ( ! empty( $message ) ) {
						demo_importer_plus_error_log( 'HTTP Request Error: ' . $message );
					} else {
						demo_importer_plus_error_log( 'HTTP Request Error!' );
					}
				} else {
					demo_importer_plus_error_log( 'BLOCK: Storing data for page ' . $page . ' in option demo-importer-plus-blocks-' . $page );
					update_site_option( 'demo-importer-plus-blocks-batch-status-string', 'Storing data for page ' . $page . ' in option demo-importer-plus-blocks-' . $page, 'no' );

					update_site_option( 'demo-importer-plus-blocks-' . $page, $demo_importer_plus_blocks, 'no' );

					if ( defined( 'WP_CLI' ) ) {
						$this->generate_file( 'demo-importer-plus-blocks-' . $page, $demo_importer_plus_blocks );
					}
				}
			} else {
				demo_importer_plus_error_log( 'BLOCK: API Error: ' . $response->get_error_message() );
			}

			demo_importer_plus_error_log( 'BLOCK: Complete storing data for blocks ' . $page );
			update_site_option( 'demo-importer-plus-blocks-batch-status-string', 'Complete storing data for page ' . $page, 'no' );
		}

		/**
		 * Import Added page no.
		 *
		 * @param  integer $page Page number.
		 * @return array
		 */
		public function import_sites( $page = 1 ) {
			$api_args        = array(
				'timeout' => 30,
			);
			$sites_and_pages = array();
			demo_importer_plus_error_log( 'Requesting ' . $page );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Requesting ' . $page, 'no' );

			$query_args = apply_filters(
				'demo_importer_plus_import_sites_query_args',
				array(
					'per_page' => 15,
					'page'     => $page,
				)
			);

			$api_url = add_query_arg( $query_args, DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . 'wp-json/demoimporterplusapi/v1/dipa-demos/' );

			$response = wp_remote_get( $api_url, $api_args );
			if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
				$sites_and_pages = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( isset( $sites_and_pages['code'] ) ) {
					$message = isset( $sites_and_pages['message'] ) ? $sites_and_pages['message'] : '';
					if ( ! empty( $message ) ) {
						demo_importer_plus_error_log( 'HTTP Request Error: ' . $message );
					} else {
						demo_importer_plus_error_log( 'HTTP Request Error!' );
					}
				} else {
					demo_importer_plus_error_log( 'Storing data for page ' . $page . ' in option demo-importer-plus-sites-and-pages-page-' . $page );
					update_site_option( 'demo-importer-plus-batch-status-string', 'Storing data for page ' . $page . ' in option demo-importer-plus-sites-and-pages-page-' . $page, 'no' );

					update_site_option( 'demo-importer-plus-sites-and-pages-page-' . $page, $sites_and_pages, 'no' );

					if ( defined( 'WP_CLI' ) ) {
						$this->generate_file( 'demo-importer-plus-sites-and-pages-page-' . $page, $sites_and_pages );
					}
				}
			} else {
				demo_importer_plus_error_log( 'API Error: ' . $response->get_error_message() );
			}

			demo_importer_plus_error_log( 'Complete storing data for page ' . $page );
			update_site_option( 'demo-importer-plus-batch-status-string', 'Complete storing data for page ' . $page, 'no' );

			return $sites_and_pages;
		}
	}

	/**
	 * Starting this by calling 'get_instance()' method
	 */
	Demo_Importer_plus_Batch_Processing_Importer::get_instance();

endif;
