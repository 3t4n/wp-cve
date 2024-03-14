<?php

/**
 * Class AJAX functions.
 *
 * @package Demo_Importer_Plus
 */

use KraftPlugins\DemoImporterPlus\PluginManagement;

/**
 * Demo importer plus ajax
 */
class Demo_Importer_Plus_AJAX {
	protected $api_url = DEMO_IMPORTER_PLUS_MAIN_DEMO_URI . 'wp-json/demoimporterplusapi/v1/dipa-demos';

	/**
	 * Class Consructor
	 */
	public function __construct () {
		add_action( 'wp_ajax_demo_importer_plus_change_page_builder', array( $this, 'save_page_builder_on_ajax' ) );
		add_action( 'wp_ajax_demo-importer-plus-activate-theme', array( $this, 'activate_theme' ) );
		add_action( 'wp_ajax_demo-import-site-api-request', array( $this, 'api_request' ) );
		add_action( 'wp_ajax_demo-importer-plus-required-plugins', array( $this, 'required_plugin' ) );
		add_action( 'wp_ajax_demo-importer-plus-required-plugin-activate', array( $this, 'required_plugin_activate' ) );
		add_action( 'wp_ajax_demo-importer-plus-create-page', array( $this, 'create_page' ) );
		add_action( 'wp_ajax_demo-importer-plus-set-reset-data', array( $this, 'get_reset_data' ) );
		add_action( 'wp_ajax_demo-importer-plus-page-elementor-batch-process', array( $this, 'elementor_batch_process' ) );

		add_action( 'wp_ajax_demo-importer-plus-backup-settings', array( $this, 'backup_settings' ) );
	}

	/**
	 * Save Page Builder
	 *
	 * @return void
	 */
	public function save_page_builder_on_ajax () {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$stored_data = $this->get_settings();

		$new_data = array(
			'page_builder' => ( isset( $_REQUEST[ 'page_builder' ] ) ) ? sanitize_key( $_REQUEST[ 'page_builder' ] ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		);

		$data = wp_parse_args( $new_data, $stored_data );

		update_option( 'demo_importer_plus_settings', $data, 'no' );

		$sites = $this->get_sites_by_page_builder( $new_data[ 'page_builder' ] );

		wp_send_json_success( $sites );
	}

	/**
	 * Elementor Batch Process via AJAX
	 */
	public function elementor_batch_process () {
		check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

		if ( !current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
		}

		if ( !isset( $_POST[ 'url' ] ) ) {
			wp_send_json_error( __( 'Invalid API URL', 'demo-importer-plus' ) );
		}

		$response = wp_remote_get( esc_url_raw( $_POST[ 'url' ] ) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( wp_remote_retrieve_body( $response ) );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( !isset( $data[ 'post-meta' ][ '_elementor_data' ] ) ) {
			wp_send_json_error( __( 'Invalid Post Meta', 'demo-importer-plus' ) );
		}

		$meta = json_decode( $data[ 'post-meta' ][ '_elementor_data' ], true );
		$post_id = $data[ 'id' ];

		if ( empty( $post_id ) || empty( $meta ) ) {
			wp_send_json_error( __( 'Invalid Post ID or Elementor Meta', 'demo-importer-plus' ) );
		}

		if ( isset( $data[ 'site-option' ] ) && isset( $data[ 'site-option' ][ 'elementor_load_fa4_shim' ] ) ) {
			update_option( 'elementor_load_fa4_shim', $data[ 'site-option' ][ 'elementor_load_fa4_shim' ] );
		}

		$import = new \Elementor\TemplateLibrary\Demo_Importer_Plus_Elementor_Pages();
		$import_data = $import->import( $post_id, $meta );

		wp_send_json_success( $import_data );
	}

	/**
	 * Get Settings
	 *
	 * @return array Stored settings.
	 */
	public function get_settings () {
		$defaults = array(
			'page_builder' => '',
		);

		$stored_data = get_option( 'demo_importer_plus_settings', $defaults );

		return wp_parse_args( $stored_data, $defaults );
	}

	/**
	 * Get Page Builder Sites
	 *
	 * @param string $default_page_builder Default page builder slug.
	 */
	public function get_sites_by_page_builder ( $default_page_builder = '' ) {
		$sites_and_pages = Demo_Importer_Plus::get_instance()->get_all_sites();
		$current_page_builder_sites = array_filter(
			$sites_and_pages,
			function ( $site ) {
				return $site[ 'site_page_builder' ] === $default_page_builder;
			}
		);

		return $current_page_builder_sites;
	}

	/**
	 * Activate theme
	 */
	public function activate_theme () {
		// Verify Nonce.
		check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

		if ( !current_user_can( 'customize' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
		}

		$theme_name = ( isset( $_POST[ 'theme_name' ] ) ) ? sanitize_text_field( $_POST[ 'theme_name' ] ) : '';

		switch_theme( $theme_name );

		wp_send_json_success(
			array(
				'success' => true,
				'message' => __( 'Theme Activated', 'demo-importer-plus' ),
			)
		);
	}

	/**
	 * API Request
	 */
	public function api_request () {
		$url = isset( $_POST[ 'url' ] ) ? esc_url_raw( $_POST[ 'url' ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( empty( $url ) ) {
			wp_send_json_error( __( 'Provided API URL is empty! Please try again!', 'demo-importer-plus' ) );
		}

		$api_args = apply_filters(
			'demo_importer_plus_api_args',
			array(
				'timeout' => 30,
			)
		);
		$request_url = $this->api_url . $url;
		$request = wp_remote_get( $request_url, $api_args );

		if ( is_wp_error( $request ) ) {
			wp_send_json_error( 'API Request is failed due to ' . $request->get_error_message() );
		}

		if ( 200 !== (int)wp_remote_retrieve_response_code( $request ) ) {
			$demo_data = json_decode( wp_remote_retrieve_body( $request ), true );
			if ( is_array( $demo_data ) && isset( $demo_data[ 'code' ] ) ) {
				wp_send_json_error( $demo_data[ 'message' ] );
			} else {
				wp_send_json_error( wp_remote_retrieve_body( $request ) );
			}
		}

		$demo_data = json_decode( wp_remote_retrieve_body( $request ), true );
		update_option( 'demo_importer_plus_import_data', $demo_data );

		$demo_id = isset( $_POST[ 'demo_id' ] ) ? (int)$_POST[ 'demo_id' ] : '';
		set_transient( "demo_importer_plus_import_data_{$demo_id}", $demo_data, HOUR_IN_SECONDS );

		wp_send_json_success( $demo_data );

	}

	/**
	 * Required Plugins
	 *
	 * @param array $required_plugins Required Plugins.
	 * @param array $options Site Options.
	 * @param array $enabled_extensions Enabled Extensions.
	 *
	 * @return mixed
	 */
	public function required_plugin ( ...$args ) {

		// Verify Nonce.
		if ( !defined( 'WP_CLI' ) ) {
			check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );
		}

		$handler = new PluginManagement();
		$handler->handle( ...$args );

	}

	/**
	 * Check Pro plugin version exist of requested plugin lite version.
	 *
	 * @param string $lite_version Lite version init file.
	 *
	 * @return mixed Return false if not installed or not supported by us else return 'Pro' version details.
	 */
	public function pro_plugin_exist ( $lite_version = '' ) {

		$plugins = apply_filters(
			'demo_importer_plus_pro_plugin_exist',
			array(
				'beaver-builder-lite-version/fl-builder.php'                    => array(
					'slug' => 'bb-plugin',
					'init' => 'bb-plugin/fl-builder.php',
					'name' => 'Beaver Builder Plugin',
				),
				'ultimate-addons-for-beaver-builder-lite/bb-ultimate-addon.php' => array(
					'slug' => 'bb-ultimate-addon',
					'init' => 'bb-ultimate-addon/bb-ultimate-addon.php',
					'name' => 'Ultimate Addon for Beaver Builder',
				),
				'wpforms-lite/wpforms.php'                                      => array(
					'slug' => 'wpforms',
					'init' => 'wpforms/wpforms.php',
					'name' => 'WPForms',
				),
			),
			$lite_version
		);

		if ( isset( $plugins[ $lite_version ] ) ) {

			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugins[ $lite_version ][ 'init' ] ) ) {
				return $plugins[ $lite_version ];
			}
		}

		return false;
	}

	/**
	 * Required Plugin Activate
	 *
	 * @param string $init Plugin init file.
	 * @param array $options Site options.
	 * @param array $enabled_extensions Enabled extensions.
	 */
	public function required_plugin_activate ( $init = '', $options = array(), $enabled_extensions = array() ) {
		if ( !defined( 'WP_CLI' ) ) {
			check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

			if ( !current_user_can( 'install_plugins' ) || !isset( $_POST[ 'init' ] ) || !$_POST[ 'init' ] ) {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => __( 'Error: You don\'t have the required permissions to install plugins.', 'demo-importer-plus' ),
					)
				);
			}
		}

		$plugin_init = ( isset( $_POST[ 'init' ] ) ) ? sanitize_text_field( $_POST[ 'init' ] ) : $init;

		wp_clean_plugins_cache();

		$activate = activate_plugin( $plugin_init, '', false, true );

		if ( is_wp_error( $activate ) ) {
			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::error( 'Plugin Activation Error: ' . $activate->get_error_message() );
			} else {
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $activate->get_error_message(),
					)
				);
			}
		}

		if ( defined( 'WP_CLI' ) ) {
			WP_CLI::line( 'Plugin Activated!' );
		} else {
			wp_send_json_success(
				array(
					'success' => true,
					'message' => __( 'Plugin Activated', 'demo-importer-plus' ),
				)
			);
		}
	}

	/**
	 * Import Page.
	 */
	public function create_page () {
		check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

		if ( !current_user_can( 'customize' ) ) {
			wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
		}

		$default_page_builder = Demo_Importer_Plus::get_instance()->get_setting( 'page_builder' );

		$content = isset( $_POST[ 'data' ][ 'original_content' ] ) ? demo_importer_plus_clean_vars( $_POST[ 'data' ][ 'original_content' ] ) : ( isset( $_POST[ 'data' ][ 'content' ][ 'rendered' ] ) ? demo_importer_plus_clean_vars( $_POST[ 'data' ][ 'content' ][ 'rendered' ] ) : '' );

		if ( 'elementor' === $default_page_builder ) {
			if ( isset( $_POST[ 'data' ][ 'options-data' ] ) && isset( $_POST[ 'data' ][ 'options-data' ][ 'elementor_load_fa4_shim' ] ) ) {
				update_option( 'elementor_load_fa4_shim', wp_kses_post( $_POST[ 'data' ][ 'options-data' ][ 'elementor_load_fa4_shim' ] ) );
			}
		}

		$data = isset( $_POST[ 'data' ] ) ? demo_importer_plus_clean_vars( $_POST[ 'data' ] ) : array();

		if ( empty( $data ) ) {
			wp_send_json_error( 'Empty page data.' );
		}

		$page_id = isset( $_POST[ 'data' ][ 'id' ] ) ? absint( $_POST[ 'data' ][ 'id' ] ) : '';
		$title = isset( $_POST[ 'data' ][ 'title' ][ 'rendered' ] ) ? sanitize_text_field( $_POST[ 'data' ][ 'title' ][ 'rendered' ] ) : '';
		$excerpt = isset( $_POST[ 'data' ][ 'excerpt' ][ 'rendered' ] ) ? wp_kses_post( $_POST[ 'data' ][ 'excerpt' ][ 'rendered' ] ) : '';

		$post_args = array(
			'post_type'    => 'page',
			'post_status'  => 'draft',
			'post_title'   => sanitize_text_field( $title ),
			'post_content' => wp_kses_post( $content ),
			'post_excerpt' => $excerpt,
		);

		$new_page_id = wp_insert_post( $post_args );
		update_post_meta( $new_page_id, '_demo_importer_enable_for_batch', true );

		$post_meta = isset( $_POST[ 'data' ][ 'post-meta' ] ) ? $_POST[ 'data' ][ 'post-meta' ] : array();

		if ( !empty( $post_meta ) ) {
			$this->import_post_meta( $new_page_id, $post_meta );
		}

		if ( isset( $_POST[ 'data' ][ 'options-data' ] ) && !empty( $_POST[ 'data' ][ 'options-data' ] ) ) {

			// $options_data = demo_importer_plus_clean_vars( $_POST['data']['options-data'] );
			$options_data = $_POST[ 'data' ][ 'options-data' ];

			foreach ( $options_data as $option => $value ) {
				update_option( $option, sanitize_text_field( $value ) );
			}
		}

		do_action( 'demo_importer_plus_process_single', $new_page_id );

		wp_send_json_success(
			array(
				'remove-page-id' => $page_id,
				'id'             => $new_page_id,
				'link'           => get_permalink( $new_page_id ),
			)
		);
	}

	/**
	 * Import Post Meta
	 *
	 * @param integer $post_id Post ID.
	 * @param array $metadata Post meta.
	 */
	public function import_post_meta ( $post_id, $metadata ) {

		$metadata = (array)$metadata;

		foreach ( $metadata as $meta_key => $meta_value ) {

			if ( $meta_value ) {

				if ( '_elementor_data' === $meta_key ) {

					$raw_data = json_decode( stripslashes( $meta_value ), true );

					if ( is_array( $raw_data ) ) {
						$raw_data = wp_slash( wp_json_encode( $raw_data ) );
					} else {
						$raw_data = wp_slash( $raw_data );
					}
				} else {

					if ( is_serialized( $meta_value, true ) ) {
						$raw_data = maybe_unserialize( stripslashes( $meta_value ) );
					} else {
						if ( is_array( $meta_value ) ) {
							$raw_data = json_decode( stripslashes( $meta_value ), true );
						} else {
							$raw_data = $meta_value;
						}
					}
				}

				update_post_meta( $post_id, $meta_key, $raw_data );
			}
		}
	}

	/**
	 * Set reset data
	 */
	public function get_reset_data () {
		if ( !defined( 'WP_CLI' ) ) {
			check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

			if ( !current_user_can( 'manage_options' ) ) {
				return;
			}
		}

		global $wpdb;

		$post_ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_demo_importer_plus_sites_imported_post'" );
		$form_ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_demo_importer_plus_imported_contact_form7'" );
		$term_ids = $wpdb->get_col( "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='_demo_importer_plus_imported_term'" );

		$data = array(
			'reset_posts'         => $post_ids,
			'reset_contact_form7' => $form_ids,
			'reset_terms'         => $term_ids,
		);

		if ( defined( 'WP_CLI' ) ) {
			return $data;
		} else {
			wp_send_json_success( $data );
		}
	}

	/**
	 * Backup our existing settings.
	 */
	public function backup_settings () {
		if ( !defined( 'WP_CLI' ) ) {
			check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

			if ( !current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'User does not have permission!', 'demo-importer-plus' ) );
			}
		}

		$file_name = 'demo-importer-plus-backup-' . gmdate( 'd-M-Y-h-i-s' ) . '.json';
		$old_settings = get_option( 'demo-importer-plus-settings', array() );

		$upload_dir = $this->log_dir();
		$upload_path = trailingslashit( $upload_dir[ 'path' ] );
		$log_file = $upload_path . $file_name;
		$file_system = $this->get_filesystem();

		if ( false === $file_system->put_contents( $log_file, wp_json_encode( $old_settings ), FS_CHMOD_FILE ) ) {
			update_option( 'demo_importer_plus_sites_' . $file_name, $old_settings );
		}

		if ( defined( 'WP_CLI' ) ) {
			WP_CLI::line( 'File generated at ' . $log_file );
		} else {
			wp_send_json_success();
		}
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

		$customizer_data = ( isset( $_POST[ 'customizer_data' ] ) ) ? (array)demo_importer_plus_clean_vars( json_decode( stripcslashes( $_POST[ 'customizer_data' ] ), 1 ) ) : $customizer_data;

		wp_send_json( $customizer_data );
		if ( !empty( $customizer_data ) ) {

			Demo_Importer_Plus_Sites_Importer_Log::add( 'Imported Customizer Settings ' . wp_json_encode( $customizer_data ) );

			update_option( '_demo_importer_plus_old_customizer_data', $customizer_data );

			$this->import( $customizer_data );

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

			$xml_path = Demo_Importer_Plus_Sites_Helper::download_file( $wxr_url, $overrides );

			if ( $xml_path[ 'success' ] ) {

				$post = array(
					'post_title'     => basename( $wxr_url ),
					'guid'           => $xml_path[ 'data' ][ 'url' ],
					'post_mime_type' => $xml_path[ 'data' ][ 'type' ],
				);

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
	 * Import End.
	 */
	public function import_end () {
		if ( !defined( 'WP_CLI' ) && wp_doing_ajax() ) {

			check_ajax_referer( 'demo-importer-plus', '_ajax_nonce' );

			if ( !current_user_can( 'customize' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'demo-importer-plus' ) );
			}
		}

		$demo_data = get_option( 'demo_importer_plus_import_data', array() );

		do_action( 'demo_importer_plus_import_complete', $demo_data );

		update_option( 'demo_importer_plus_import_complete', 'yes' );

		if ( wp_doing_ajax() ) {
			wp_send_json_success();
		}
	}

	/**
	 * Log file directory
	 *
	 * @param string $dir_name Directory Name.
	 */
	public function log_dir ( $dir_name = 'demo-importer-plus' ) {

		$upload_dir = wp_upload_dir();

		// Build the paths.
		$dir_info = array(
			'path' => $upload_dir[ 'basedir' ] . '/' . $dir_name . '/',
			'url'  => $upload_dir[ 'baseurl' ] . '/' . $dir_name . '/',
		);

		return $dir_info;
	}

	/**
	 * Get an instance of WP_Filesystem_Direct.
	 */
	public function get_filesystem () {
		global $wp_filesystem;

		require_once ABSPATH . '/wp-admin/includes/file.php';

		WP_Filesystem();

		return $wp_filesystem;
	}

	/**
	 * Import customizer options.
	 *
	 * @param (Array) $options customizer options from the demo.
	 */
	public function import ( $options ) {

		if ( isset( $options[ 'demo-importer-plus-settings' ] ) ) {
			$this->import_settings( $options[ 'demo-importer-plus-settings' ] );
		}

		if ( isset( $options[ 'custom-css' ] ) ) {
			wp_update_custom_css_post( $options[ 'custom-css' ] );
		}
	}

	/**
	 * Import Theme Setting's
	 *
	 * @param array $options Customizer setting array.
	 */
	public function import_settings ( $options = array() ) {

		array_walk_recursive(
			$options,
			function ( &$value ) {
				if ( !is_array( $value ) ) {

					if ( Demo_Importer_Plus_Sites_Helper::is_image_url( $value ) ) {
						$data = Demo_Importer_Plus_Sites_Helper::sideload_image( $value );

						if ( !is_wp_error( $data ) ) {
							$value = $data->url;
						}
					}
				}
			}
		);

		update_option( 'demo-importer-plus-settings', $options );
	}
}

new Demo_Importer_Plus_AJAX();
