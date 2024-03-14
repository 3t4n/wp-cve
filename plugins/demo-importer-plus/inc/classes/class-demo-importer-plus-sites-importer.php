<?php
/**
 * Demo importer Plus Importer
 *
 * @since  1.0.0
 * @package Demo importer Plus
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Demo_Importer_Site_Importer' ) ) {

	/**
	 * Demo importer Plus Importer
	 */
	class Demo_Importer_Site_Importer {

		public static $term_mappings = array();

		/**
		 * Instance
		 *
		 * @since  1.0.0
		 * @var (Object) Class object
		 */
		public static $instance = null;

		/**
		 * Set Instance
		 *
		 * @return object Class object.
		 * @since  1.0.0
		 *
		 */
		public static function get_instance () {
			if ( !isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 */
		public function __construct () {

			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/classes/class-demo-importer-plus-sites-importer-log.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/class-demo-importer-plus-sites-helper.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/class-demo-importer-plus-widget-importer.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/class-demo-importer-plus-customizer-import.php';
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/class-demo-importer-plus-site-options-import.php';

			// Import AJAX.
			add_action( 'wp_ajax_demo-importer-plus-import-contactforms', array( $this, 'import_contactforms' ) );
			add_action( 'wp_ajax_demo-importer-plus-import-customizer-settings', array( $this, 'import_customizer_settings' ) );
			add_action( 'wp_ajax_demo-importer-plus-import-prepare-xml', array( $this, 'prepare_xml_data' ) );
			add_action( 'wp_ajax_demo-importer-plus-import-options', array( $this, 'import_options' ) );
			add_action( 'wp_ajax_demo-importer-plus-import-widgets', array( $this, 'import_widgets' ) );
			add_action( 'wp_ajax_demo-importer-plus-import-end', array( $this, 'import_end' ) );

			// Hooks in AJAX.
			add_action( 'init', array( $this, 'load_importer' ) );

			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/batch-processing/class-demo-importer-plus-batch-processing.php';

			add_action( 'demo_importer_plus_sites_image_import_complete', array( $this, 'after_batch_complete' ) );

			// Reset Customizer Data.
			add_action( 'wp_ajax_demo-importer-plus-reset-customizer-data', array( $this, 'reset_customizer_data' ) );
			add_action( 'wp_ajax_demo-importer-plus-reset-site-options', array( $this, 'reset_site_options' ) );
			add_action( 'wp_ajax_demo-importer-plus-reset-widgets-data', array( $this, 'reset_widgets_data' ) );

			// Reset Post & Terms.
			add_action( 'wp_ajax_demo-importer-plus-sites-delete-posts', array( $this, 'delete_imported_posts' ) );
			add_action( 'wp_ajax_demo-importer-plus-sites-delete-contact-form7', array( $this, 'delete_imported_contact_form7' ) );
			add_action( 'wp_ajax_demo-importer-plus-sites-delete-terms', array( $this, 'delete_imported_terms' ) );

			add_action( 'demo_importer_plus_sites_import_complete', array( $this, 'map_wptravelengine_posttype_packages' ) );

			if ( version_compare( get_bloginfo( 'version' ), '5.1.0', '>=' ) ) {
				add_filter( 'http_request_timeout', array( $this, 'set_timeout_for_images' ), 10, 2 );
			}
		}

		/**
		 * Set the timeout for the HTTP request by request URL.
		 *
		 * @param int $timeout_value Time in seconds. Default 5.
		 * @param string $url The request URL.
		 */
		public function set_timeout_for_images ( $timeout_value, $url ) {

			if ( strpos( $url, DEMO_IMPORTER_PLUS_MAIN_DEMO_URI ) === false ) {
				return $timeout_value;
			}

			// Check is image URL of type jpg|png|gif|jpeg.
			if ( Demo_Importer_Plus_Sites_Image_Importer::get_instance()->is_image_url( $url ) ) {
				$timeout_value = 300;
			}

			return $timeout_value;
		}

		/**
		 * Load WordPress WXR importer.
		 */
		public function load_importer () {
			require_once DEMO_IMPORTER_PLUS_DIR . 'inc/importers/wxr-importer/class-demo-importer-plus-wxr-importer.php';
		}

		/**
		 * Import Customizer Settings.
		 *
		 * @param array $customizer_data Customizer Data.
		 */
		public function import_customizer_settings ( $customizer_data = array() ) {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {

				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$demo_id = ( isset( $_POST[ 'demo_id' ] ) ) ? (int)$_POST[ 'demo_id' ] : 0;

			if ( !$demo_id ) {
				wp_send_json_error( __( 'Requires Demo ID.', 'demo-importer-plus' ) );
			}

			$demo_data = \KraftPlugins\DemoImporterPlus\DemoAPI::fetch( $demo_id );

			$customizer_data = ( isset( $demo_data->data[ 'customizer-data' ] ) ) ? (array)wp_unslash( $demo_data->data[ 'customizer-data' ] ) : $customizer_data;

			if ( !empty( $customizer_data ) ) {

				Demo_Importer_Plus_Sites_Importer_Log::add( 'Imported Customizer Settings ' . wp_json_encode( $customizer_data ) );

				// Set meta for tracking the post.
				demo_importer_plus_error_log( 'Customizer Data ' . wp_json_encode( $customizer_data ) );

				update_option( '_demo_importer_plus_sites_old_customizer_data', $customizer_data );

				Demo_Importer_Customizer_Import::instance()->import( $customizer_data );

				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'Imported Customizer Settings!' );
				} else {
					if ( wp_doing_ajax() ) {
						wp_send_json_success( $customizer_data );
					}
				}
			} else {
				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'Customizer data is empty!' );
				} else {
					if ( wp_doing_ajax() ) {
						wp_send_json_error( __( 'Customizer data is empty!', 'demo-importer-plus' ) );
					}
				}
			}

		}

		/**
		 * Prepare XML Data.
		 */
		public function prepare_xml_data () {

			// Verify Nonce.
			check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

			if ( !current_user_can( 'customize' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
			}

			if ( !class_exists( 'XMLReader' ) ) {
				wp_send_json_error( __( 'If XMLReader is not available, it imports all other settings and only skips XML import. This creates an incomplete website. We should bail early and not import anything if this is not present.', 'demo-importer-plus' ) );
			}

			$wxr_url = ( isset( $_REQUEST[ 'wxr_url' ] ) ) ? urldecode( esc_url_raw( $_REQUEST[ 'wxr_url' ] ) ) : '';
			if ( isset( $wxr_url ) ) {

				Demo_Importer_Plus_Sites_Importer_Log::add( 'Importing from XML ' . $wxr_url );

				$overrides = array(
					'wp_handle_sideload' => 'upload',
				);

				// Download XML file.
				$xml_path = Demo_Importer_Plus_Sites_Helper::download_file( $wxr_url, $overrides );

				if ( $xml_path[ 'success' ] ) {

					$post = array(
						'post_title'     => basename( $wxr_url ),
						'guid'           => $xml_path[ 'data' ][ 'url' ],
						'post_mime_type' => $xml_path[ 'data' ][ 'type' ],
					);

					demo_importer_plus_error_log( wp_json_encode( $post ) );
					demo_importer_plus_error_log( wp_json_encode( $xml_path ) );

					$post_id = wp_insert_attachment( $post, $xml_path[ 'data' ][ 'file' ] );

					demo_importer_plus_error_log( wp_json_encode( $post_id ) );

					if ( is_wp_error( $post_id ) ) {
						wp_send_json_error( __( 'There was an error downloading the XML file.', 'demo-importer-plus' ) );
					} else {

						update_option( 'demo_importer_plus_imported_wxr_id', $post_id );
						$attachment_metadata = wp_generate_attachment_metadata( $post_id, $xml_path[ 'data' ][ 'file' ] );
						wp_update_attachment_metadata( $post_id, $attachment_metadata );
						$data = Demo_Importer_Plus_WXR_Importer::instance()->get_xml_data( $xml_path[ 'data' ][ 'file' ], $post_id );
						$data[ 'xml' ] = $xml_path[ 'data' ];
						wp_send_json_success( $data );
					}
				} else {
					wp_send_json_error( $xml_path[ 'data' ] );
				}
			} else {
				wp_send_json_error( __( 'Invalid site XML file!', 'demo-importer-plus' ) );
			}

		}

		/**
		 * Import WP Forms
		 */
		public function import_contactforms () {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$forms = ( isset( $_REQUEST[ 'contact_forms' ] ) ) ? demo_importer_plus_clean_vars( $_REQUEST[ 'contact_forms' ] ) : array();
			$ids_mapping = array();

			if ( !empty( $forms ) ) {

				foreach ( $forms as $form_id => $form ) {

					$title = !empty( $form[ 'title' ] ) ? sanitize_text_field( $form[ 'title' ] ) : '';
					$content = !empty( $form[ 'content' ] ) ? wp_kses_post( $form[ 'content' ] ) : '';
					$type = !empty( $form[ 'type' ] ) ? sanitize_text_field( $form[ 'type' ] ) : '';
					$status = !empty( $form[ 'status' ] ) ? sanitize_text_field( $form[ 'status' ] ) : '';

					$new_id = post_exists( $title );

					if ( !$new_id ) {
						$new_id = wp_insert_post(
							array(
								'post_title'   => $title,
								'post_status'  => $status,
								'post_type'    => $type,
								'post_content' => $content,
							)
						);

						if ( defined( 'WP_CLI' ) ) {
							WP_CLI::line( 'Imported Form ' . $title );
						}

						// Set meta for tracking the post .
						Demo_Importer_Plus_Sites_Importer_Log::add( 'Inserted WP Form ' . $new_id );
					}

					if ( $new_id ) {

						// ID mapping .
						$ids_mapping[ $form_id ] = $new_id;

						wp_update_post(
							array(
								'ID'           => $new_id,
								'post_content' => $content,
							)
						);
					}
				}
			}
			update_option( 'demo_importer_plus_cf7_ids_mapping', $ids_mapping, 'no' );

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'WP Forms Imported.' );
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_success( $ids_mapping );
				}
			}
		}

		/**
		 * Import Options.
		 *
		 * @param array $options_data Site Options.
		 */
		public function import_options ( $options_data = array() ) {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {

				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$demo_id = ( isset( $_POST[ 'demo_id' ] ) ) ? (int)$_POST[ 'demo_id' ] : 0;

			if ( !$demo_id ) {
				wp_send_json_error( __( 'Requires Demo ID.', 'demo-importer-plus' ) );
			}

			$demo_data = \KraftPlugins\DemoImporterPlus\DemoAPI::fetch( $demo_id );

			$options_data = ( isset( $demo_data->data[ 'site-option' ] ) ) ? (array)wp_unslash( $demo_data->data[ 'site-option' ] ) : $options_data;

			if ( !empty( $options_data ) ) {

				if ( is_array( $options_data ) ) {
					Demo_Importer_Plus_Sites_Importer_Log::add( 'Imported - Site Options ' . wp_json_encode( $options_data ) );
					update_option( '_demo_importer_plus_old_site_options', $options_data );
				}

				$options_importer = Demo_Importer_Plus_Site_Options_Import::instance();
				$options_importer->import_options( $options_data );
				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'Imported Site Options!' );
				} else {
					if ( wp_doing_ajax() ) {
						wp_send_json_success( $options_data );
					}
				}
			} else {
				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'Site options are empty!' );
				} else {
					if ( wp_doing_ajax() ) {
						wp_send_json_error( __( 'Site options are empty!', 'demo-importer-plus' ) );
					}
				}
			}

		}

		/**
		 * Import Widgets.
		 *
		 * @param string $widgets_data Widgets Data.
		 */
		public function import_widgets ( $widgets_data = '' ) {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {

				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$demo_id = ( isset( $_POST[ 'demo_id' ] ) ) ? (int)$_POST[ 'demo_id' ] : 0;

			if ( !$demo_id ) {
				wp_send_json_error( __( 'Requires Demo ID.', 'demo-importer-plus' ) );
			}

			$demo_data = \KraftPlugins\DemoImporterPlus\DemoAPI::fetch( $demo_id );


			$widgets_data = ( isset( $demo_data->data[ 'widgets-data' ] ) ) ? json_decode( $demo_data->data[ 'widgets-data' ] ) : (object)$widgets_data;

			Demo_Importer_Plus_Sites_Importer_Log::add( 'Imported - Widgets ' . wp_json_encode( $widgets_data ) );

			if ( !empty( $widgets_data ) ) {

				$widgets_importer = Demo_Importer_Plus_Widget_Importer::instance();
				$status = $widgets_importer->import_widgets_data( $widgets_data );

				$sidebars_widgets = get_option( 'sidebars_widgets', array() );
				update_option( '_demo_importer_plus_old_widgets_data', $sidebars_widgets, 'no' );

				Demo_Importer_Plus_Sites_Importer_Log::add( 'Imported - Widgets ' . wp_json_encode( $sidebars_widgets ) );

				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'Widget Imported!' );
				} else {
					if ( wp_doing_ajax() ) {
						wp_send_json_success( $widgets_data );
					}
				}
			} else {
				if ( defined( 'WP_CLI' ) ) {
					WP_CLI::line( 'Widget data is empty!' );
				} else {
					if ( wp_doing_ajax() ) {
						wp_send_json_error( __( 'Widget data is empty!', 'demo-importer-plus' ) );
					}
				}
			}

		}

		/**
		 * Map WP Travel Engine Post Types.
		 */
		public function map_wptravelengine_posttype_packages ( $demo_data ) {
			// Map post data.
			$post_mappings = get_option( '_demo_importer_posts_mapping', array() );
			if ( !empty( $post_mappings[ 'trip-packages' ] ) ) {
				$trip_packages = $post_mappings[ 'trip-packages' ];

				if ( isset( $post_mappings[ 'trip' ] ) ) {
					$trips = $post_mappings[ 'trip' ];
					foreach ( $trips as $original_trip_id => $trip_id ) {
						$package_ids = get_post_meta( $trip_id, 'packages_ids', true );
						$new_package_ids = array();
						if ( is_array( $package_ids ) ) {
							foreach ( $package_ids as $package_id ) {
								if ( !empty( $trip_packages[ $package_id ] ) ) {
									$new_package_id = (int)$trip_packages[ $package_id ];

									update_post_meta( $new_package_id, 'trip_ID', $trip_id );
									$new_package_ids[] = $new_package_id;
								}
							}
						}
						update_post_meta( $trip_id, 'packages_ids', $new_package_ids );
					}
				}
			}

			global $wpdb;

			// Map terms.
			$terms_mappings = get_option( '_demo_importer_terms_mapping', array() );
			if ( !empty( $terms_mappings[ 'trip-packages-categories' ] ) ) {
				$category_terms = $terms_mappings[ 'trip-packages-categories' ];

				$query = "SELECT * FROM `{$wpdb->postmeta}` WHERE `meta_key` = 'package-categories'";

				$primary_pricing_category = get_option( 'primary_pricing_category', 0 );

				if ( isset( $category_terms[ $primary_pricing_category ] ) ) {
					update_option( 'primary_pricing_category', $category_terms[ $primary_pricing_category ] );
				}

				$rows = $wpdb->get_results( $query );
				if ( $rows ) {
					foreach ( $rows as $row ) {
						$package_categories = maybe_unserialize( $row->meta_value );
						$new_package_categories = array();
						if ( is_array( $package_categories ) ) {
							foreach ( $package_categories as $key => $value ) {
								if ( is_array( $value ) ) {
									foreach ( $value as $k => $v ) {
										if ( 'c_ids' === $key ) {
											$new_package_categories[ $key ][ $category_terms[ $k ] ] = $category_terms[ $k ];
										} else {
											if ( !empty( $category_terms[ $k ] ) ) {
												$new_package_categories[ $key ][ $category_terms[ $k ] ] = $v;
											}
										}
									}
								}
							}
						}
						update_post_meta( $row->post_id, 'package-categories', $new_package_categories );
					}
				}
				unset( $rows );
			}

			if ( !empty( $post_mappings[ 'media' ] ) ) {
				$media_ids = $post_mappings[ 'media' ];
				$query = "SELECT `term_id`, `meta_value` FROM `{$wpdb->termmeta}` WHERE `meta_key` = 'category-image-id'";
				$rows = $wpdb->get_results( $query );
				if ( $rows ) {
					foreach ( $rows as $row ) {
						if ( !empty( $media_ids[ $row->term_id ] ) ) {
							update_term_meta( $row->term_id, 'category-image-id', $media_ids[ $row->term_id ] );
						}
					}
				}
			}

			// Update Elementor Widgets
			$query = "SELECT `post_id`, `meta_value` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '%wptravelengine-destinations%' || `meta_value` LIKE '%wptravelengine-activities%'";
			$rows = $wpdb->get_results( $query );
			if ( $rows ) {
				if ( isset( $terms_mappings[ 'destination' ] ) ) {
					static::$term_mappings = static::$term_mappings + $terms_mappings[ 'destination' ];
				}
				if ( isset( $terms_mappings[ 'activities' ] ) ) {
					static::$term_mappings = static::$term_mappings + $terms_mappings[ 'activities' ];
				}
				if ( isset( $terms_mappings[ 'trip_types' ] ) ) {
					static::$term_mappings = static::$term_mappings + $terms_mappings[ 'trip_types' ];
				}
				foreach ( $rows as $row ) {

					$_raw_data = json_decode( $row->meta_value, true );

					if ( is_array( $_raw_data ) ) {
						foreach ( $_raw_data as &$raw_data ) {
							static::update_post_ids_wte_elementor_widget( $raw_data );
						}
					}

					update_post_meta( $row->post_id, '_elementor_data', wp_slash( json_encode( $_raw_data ) ) );
				}
			}

		}

		public static function update_post_ids_wte_elementor_widget ( &$raw ) {
			if ( isset( $raw[ 'elements' ] ) ) {
				foreach ( $raw[ 'elements' ] as &$element ) {
					if ( 'widget' == $element[ 'elType' ] ) {
						if ( in_array( $element[ 'widgetType' ], [ 'wptravelengine-destinations', 'wptravelengine-activities' ] ) && isset( $element[ 'settings' ][ 'listItems' ] ) ) {
							if ( is_array( $element[ 'settings' ][ 'listItems' ] ) ) {
								$item_ids = static::$term_mappings;
								$new_ids = array_map(
									function ( $_id ) use ( $item_ids ) {
										return isset( $item_ids[ $_id ] ) ? $item_ids[ $_id ] : $_id;
									}, $element[ 'settings' ][ 'listItems' ]
								);
								$element[ 'settings' ][ 'listItems' ] = $new_ids;
							}
						}
					} else {
						static::update_post_ids_wte_elementor_widget( $element );
					}
				}
			}
		}

		/**
		 * Import End.
		 */
		public function import_end () {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$demo_data = get_option( 'demo_importer_plus_sites_import_data', array() );

			do_action( 'demo_importer_plus_sites_import_complete', $demo_data );

			update_option( 'demo_importer_plus_sites_import_complete', 'yes' );

			if ( wp_doing_ajax() ) {
				wp_send_json_success();
			}
		}

		/**
		 * Get single demo.
		 *
		 * @param (String) $demo_api_uri API URL of a demo.
		 */
		public static function get_single_demo ( $demo_api_uri ) {

			if ( is_int( $demo_api_uri ) ) {
				$demo_api_uri = Demo_Importer_Plus::get_instance()->get_api_url() . 'demo-importer-plus/' . $demo_api_uri;
			}

			// default values.
			$remote_args = array();
			$defaults = array(
				'id'                 => '',
				'widgets-data'       => '',
				'customizer-data'    => '',
				'options-data'       => '',
				'post-data-mapping'  => '',
				'wxr-path'           => '',
				'wpforms-path'       => '',
				'enabled-extensions' => '',
				'custom-404'         => '',
				'required-plugins'   => '',
				'taxonomy-mapping'   => '',
				'license-status'     => '',
				'site-type'          => '',
				'site-url'           => '',
			);

			$api_args = apply_filters(
				'demo_importer_plus_sites_api_args',
				array(
					'timeout' => 15,
				)
			);

			// Use this for premium demos.
			$request_params = apply_filters(
				'demo_importer_plus_sites_api_params',
				array(
					'purchase_key' => '',
					'site_url'     => '',
				)
			);

			$demo_api_uri = add_query_arg( $request_params, $demo_api_uri );

			// API Call.
			$response = wp_remote_get( $demo_api_uri, $api_args );

			if ( is_wp_error( $response ) || ( isset( $response->status ) && 0 === $response->status ) ) {
				if ( isset( $response->status ) ) {
					$data = json_decode( $response, true );
				} else {
					return new WP_Error( 'api_invalid_response_code', $response->get_error_message() );
				}
			}

			if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
				return new WP_Error( 'api_invalid_response_code', wp_remote_retrieve_body( $response ) );
			} else {
				$data = json_decode( wp_remote_retrieve_body( $response ), true );
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( !isset( $data[ 'code' ] ) ) {
				$remote_args[ 'id' ] = $data[ 'id' ];
				$remote_args[ 'widgets-data' ] = json_decode( $data[ 'widgets-data' ] );
				$remote_args[ 'customizer-data' ] = $data[ 'customizer-data' ];
				$remote_args[ 'options-data' ] = $data[ 'options-data' ];
				$remote_args[ 'post-data-mapping' ] = $data[ 'post-data-mapping' ];
				$remote_args[ 'wxr-path' ] = $data[ 'wxr-path' ];
				$remote_args[ 'wpforms-path' ] = $data[ 'wpforms-path' ];
				$remote_args[ 'enabled-extensions' ] = $data[ 'enabled-extensions' ];
				$remote_args[ 'custom-404' ] = $data[ 'custom-404' ];
				$remote_args[ 'required-plugins' ] = $data[ 'required-plugins' ];
				$remote_args[ 'taxonomy-mapping' ] = $data[ 'taxonomy-mapping' ];
				$remote_args[ 'license-status' ] = $data[ 'license-status' ];
				$remote_args[ 'site-type' ] = $data[ 'site-type' ];
				$remote_args[ 'site-url' ] = $data[ 'site-url' ];
			}

			return wp_parse_args( $remote_args, $defaults );
		}

		/**
		 * Clear Cache.
		 */
		public function after_batch_complete () {

			$this->update_latest_checksums();

			flush_rewrite_rules();

			Demo_Importer_Plus_Sites_Importer_Log::add( 'Complete ' );
		}

		/**
		 * Update Latest Checksums
		 */
		public function update_latest_checksums () {
			$latest_checksums = get_site_option( 'demo-importer-plus-last-export-checksums-latest', '' );
			update_site_option( 'demo-importer-plus-last-export-checksums', $latest_checksums, 'no' );
		}

		/**
		 * Reset customizer data
		 */
		public function reset_customizer_data () {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			Demo_Importer_Plus_Sites_Importer_Log::add( 'Deleted customizer Settings ' . wp_json_encode( get_option( 'demo-importer-plus-settings', array() ) ) );

			delete_option( 'demo-importer-plus-settings' );

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Deleted Customizer Settings!' );
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_success();
				}
			}
		}

		/**
		 * Reset site options
		 */
		public function reset_site_options () {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {

				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$options = get_option( '_demo_importer_plus_old_site_options', array() );

			Demo_Importer_Plus_Sites_Importer_Log::add( 'Deleted - Site Options ' . wp_json_encode( $options ) );

			if ( $options ) {
				foreach ( $options as $option_key => $option_value ) {
					delete_option( $option_key );
				}
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Deleted Site Options!' );
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_success();
				}
			}
		}

		/**
		 * Reset widgets data
		 */
		public function reset_widgets_data () {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {

				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$old_widgets_data = (array)get_option( '_demo_importer_plus_old_widgets_data', array() );
			$old_widgets_data = json_decode( json_encode( $old_widgets_data ), true );
			$old_widget_ids = array();

			foreach ( $old_widgets_data as $old_sidebar_key => $old_widgets ) {
				$old_widgets = (array)$old_widgets;
				if ( !empty( $old_widgets ) && is_array( $old_widgets ) ) {
					$old_widget_ids = array_merge( $old_widget_ids, $old_widgets );
				}
			}
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			if ( !empty( $old_widget_ids ) && !empty( $sidebars_widgets ) ) {

				Demo_Importer_Plus_Sites_Importer_Log::add( 'DELETED - WIDGETS ' . wp_json_encode( $old_widget_ids ) );

				foreach ( $sidebars_widgets as $sidebar_id => $widgets ) {
					$widgets = (array)$widgets;

					if ( !is_array( $sidebars_widgets[ $sidebar_id ] ) ) {
						continue;
					}

					if ( !empty( $widgets ) && is_array( $widgets ) ) {
						foreach ( $widgets as $widget_id ) {

							if ( in_array( $widget_id, $old_widget_ids, true ) ) {
								Demo_Importer_Plus_Sites_Importer_Log::add( 'DELETED - WIDGET ' . $widget_id );

								$sidebars_widgets[ 'wp_inactive_widgets' ][] = $widget_id;

								$sidebars_widgets[ $sidebar_id ] = array_diff( $sidebars_widgets[ $sidebar_id ], array( $widget_id ) );
							}
						}
					}
				}

				update_option( 'sidebars_widgets', $sidebars_widgets );
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Deleted Widgets!' );
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_success();
				}
			}
		}

		/**
		 * Delete imported posts
		 *
		 * @param integer $post_id Post ID.
		 */
		public function delete_imported_posts ( $post_id = 0 ) {

			if ( wp_doing_ajax() ) {

				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$post_id = isset( $_REQUEST[ 'post_id' ] ) ? absint( $_REQUEST[ 'post_id' ] ) : $post_id;

			$message = 'Deleted - Post ID ' . $post_id . ' - ' . get_post_type( $post_id ) . ' - ' . get_the_title( $post_id );

			$message = '';
			if ( $post_id ) {

				$post_type = get_post_type( $post_id );
				$message = 'Deleted - Post ID ' . $post_id . ' - ' . $post_type . ' - ' . get_the_title( $post_id );

				do_action( 'demo_importer_plus_sites_before_delete_imported_posts', $post_id, $post_type );

				Demo_Importer_Plus_Sites_Importer_Log::add( $message );
				wp_delete_post( $post_id, true );
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( $message );
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_success( $message );
				}
			}
		}

		/**
		 * Delete imported WP forms
		 *
		 * @param integer $post_id Post ID.
		 */
		public function delete_imported_contact_form7 ( $post_id = 0 ) {

			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$post_id = isset( $_REQUEST[ 'post_id' ] ) ? absint( $_REQUEST[ 'post_id' ] ) : $post_id;

			$message = '';
			if ( $post_id ) {

				do_action( 'demo_importer_plus_sites_before_delete_imported_contact_form7', $post_id );

				$message = 'Deleted - Form ID ' . $post_id . ' - ' . get_post_type( $post_id ) . ' - ' . get_the_title( $post_id );
				Demo_Importer_Plus_Sites_Importer_Log::add( $message );
				wp_delete_post( $post_id, true );
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( $message );
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_success( $message );
				}
			}
		}

		/**
		 * Delete imported terms
		 *
		 * @param integer $term_id Term ID.
		 */
		public function delete_imported_terms ( $term_id = 0 ) {
			if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {
				// Verify Nonce.
				check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

				if ( !current_user_can( 'customize' ) ) {
					wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
				}
			}

			$term_id = isset( $_REQUEST[ 'term_id' ] ) ? absint( $_REQUEST[ 'term_id' ] ) : $term_id;

			$message = '';
			if ( $term_id ) {
				$term = get_term( $term_id );
				if ( !is_wp_error( $term ) ) {

					do_action( 'demo_importer_plus_before_delete_imported_terms', $term_id, $term );

					$message = 'Deleted - Term ' . $term_id . ' - ' . $term->name . ' ' . $term->taxonomy;
					Demo_Importer_Plus_Sites_Importer_Log::add( $message );
					wp_delete_term( $term_id, $term->taxonomy );
				}
			}

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( $message );
			} else {
				if ( wp_doing_ajax() ) {
					wp_send_json_success( $message );
				}
			}
		}

	}

	/**
	 * Starting this by calling 'get_instance()' method
	 */
	Demo_Importer_Site_Importer::get_instance();
}
