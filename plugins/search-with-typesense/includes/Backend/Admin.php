<?php

namespace Codemanas\Typesense\Backend;

use Codemanas\Typesense\Helpers\Logger;
use Codemanas\Typesense\Main\TypesenseAPI;

class Admin {
	public static ?Admin $instance = null;
	public string $menu_page = '';
	private string $admin_page_url = 'codemanas-typesense';
	public static string $general_options_key = 'cm_typesense_admin_settings';
	public static string $search_config_key = 'cm_typesense_search_config_settings';

	public static array $default_settings
		= [
			'protocol'       => 'https://',
			'node'           => '',
			'admin_api_key'  => '',
			'search_api_key' => '',
			'port'           => '443',
			'debug_log'      => false,
			'error_log'      => true,
		];

	public static array $search_config_settings
		= [
			'enabled_post_types' => [
				'post',
			],

			'available_post_types'          => [
				'post'     => [ 'label' => 'Posts', 'value' => 'post' ],
				'page'     => [ 'label' => 'Pages', 'value' => 'page' ],
				'category' => [ 'label' => 'Category', 'value' => 'category', 'type' => 'taxonomy' ],
			],
			//to be used on frontend to show label if multiple collections are searched at once
			'config'                        => [
				'post_type' => [
					'post' => [
						'label'           => 'Post',
						'max_suggestions' => 3,
					],
				],
				//taxonomy has not been implemented yet but adding now for future 
				'taxonomy'  => [
					'category' => 'Categories',
				],
			],
			'hijack_wp_search'              => true,
			//due to backward compatibility autocomplete is default option
			'hijack_wp_search__type'        => 'autocomplete', //accepted values - instant_search / autocomplete
			'autocomplete_placeholder_text' => 'Search for :',
			'autocomplete_input_delay'      => 300,
			'autocomplete_submit_action'    => 'keep_open',
		];
	/**
	 * @var false|string
	 */
	private $settings_page_slug;
	/**
	 * @var false|string
	 */
	private $log_page_slug;

	/**
	 * @return Admin|null
	 */
	public static function getInstance(): ?Admin {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	public function __construct() {
		/*load dependencies if required*/
		add_action( 'admin_menu', [ $this, 'admin_menu_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_textdomain' ], 100 );

		//Plugin Settings Link
		add_filter( 'plugin_action_links_search-with-typesense/codemanas-typesense.php', [ $this, 'settings_link' ] );

		add_action( 'wp_ajax_getCMTypesenseAdminSettings', [ $this, 'getSettings' ] );
		add_action( 'wp_ajax_getCMTypesenseSearchConfig', [ $this, 'getSearchConfig' ] );

		add_action( 'wp_ajax_saveCMTypesenseAdminSettings', [ $this, 'saveGeneralSettings' ] );
		add_action( 'wp_ajax_saveCMTypesenseSearchConfigSettings', [ $this, 'saveSearchConfigSettings' ] );

		//Get Schema Details
		add_action( 'wp_ajax_cmswtGetSchemaDetail', [ $this, 'getSchemaDetails' ] );

		//Drop Collection
		add_action( 'wp_ajax_CMTypesenseDropCollection', [ $this, 'handleDropCollection' ] );

		//Import function
		add_action( 'wp_ajax_CMTypesenseBulkImport', [ $this, 'CMTypesenseBulkImport' ] );

		//Delete Log File
		add_action( 'wp_ajax_CMTypesenseDeleteFile', [ $this, 'CMTypesenseDeleteFile' ] );

		//activate ajax handler
		AdminAjaxHandler::getInstance();
	}

	public function getSchemaDetails() {
		$posted_data = [ 'nonce' => filter_input( INPUT_GET, 'nonce' ) ];
		if ( ! $this->validateGetAccess( $posted_data ) ) {
			return false;
		}
		$collection_name                = filter_input( INPUT_GET, 'collection_name' );
		$maybe_prefixed_collection_name = TypesenseAPI::getInstance()->getCollectionNameFromSchema( $collection_name );
		$schemaDetails                  = TypesenseAPI::getInstance()->getCollectionInfo( $maybe_prefixed_collection_name );
		wp_send_json( $schemaDetails );
	}

	/**
	 * @param $links
	 *
	 * @return array
	 */
	public function settings_link( $links ): array {
		// Build and escape the URL.
		$url = esc_url( add_query_arg(
			[
				'page' => 'codemanas-typesense',
				'tab'  => 'general'
			],
			get_admin_url() . 'admin.php'
		) );
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

		//documentation link
		$documentation_link = '<a href="https://docs.wptypesense.com/" target="_blank" rel="noopener nofollow">Documentation</a>';

		// Adds the link to the end of the array.
		return array_merge( [ 'settings' => $settings_link, 'documentation' => $documentation_link ], $links );
	}

	/**
	 * @return array
	 */
	public static function get_default_settings(): array {
		return wp_parse_args( get_option( self::$general_options_key ), self::$default_settings );

	}

	/**
	 * @return array
	 */
	public static function get_search_config_settings(): array {
		$search_config_settings                         = wp_parse_args( get_option( self::$search_config_key ), self::$search_config_settings );
		$available_index_types                          = apply_filters_deprecated( 'cm_typesense_available_post_types', [ $search_config_settings['available_post_types'] ], '1.3.0', 'cm_typesense_available_index_types' );
		$search_config_settings['available_post_types'] = apply_filters( 'cm_typesense_available_index_types', $available_index_types );
		//this code is confusing i know but it was the simplest solution
		//what i'm doing is the available post type is first keyed via post slug and then when someone uses the filter they can add their own
		//now we loop through the keyed [post_slug] => ['label'=>post_label, 'value'=>post_value] format and make it ready for consumption by react admin settings
		$formatted_available_post_types = [];
		$available_post_type_slugs      = [];
		foreach ( $search_config_settings['available_post_types'] as $key => $value ) {
			$formatted_available_post_types[ $key ] = $value;
			//use to remove unavailable enabled post types
			$available_post_type_slugs[] = $key;
		}
		$search_config_settings['available_post_types'] = $formatted_available_post_types;
		//use to remove unavailable enabled post types
		$search_config_settings['enabled_post_types'] = array_values( array_intersect( $search_config_settings['enabled_post_types'], $available_post_type_slugs ) );


		return $search_config_settings;
	}


	/**
	 * Add Admin Menu for Plugin
	 */
	public function admin_menu_page(): void {
		$this->menu_page = add_menu_page(
			__( 'Typesense Settings', 'search-with-typesense' ),
			__( 'Typesense' ),
			'manage_options',
			$this->admin_page_url,
			array( $this, 'generate_admin_page' ),
			'dashicons-search'
		);
	}

	//to be retrieved in ajax call
	public function getSettings() {
		$posted_data = [ 'nonce' => filter_input( INPUT_GET, 'nonce' ) ];

		if ( ! $this->validateGetAccess( $posted_data ) ) {
			wp_send_json( false );
		}

		wp_send_json( self::get_default_settings() );
	}

	//to be retrieved in ajax call
	public function getSearchConfig() {
		$posted_data = [ 'nonce' => filter_input( INPUT_GET, 'nonce' ) ];

		if ( ! $this->validateGetAccess( $posted_data ) ) {
			wp_send_json( false );
		}

		wp_send_json( self::get_search_config_settings() );
	}

	/**
	 * @param $posted_data
	 *
	 * @return bool
	 */
	private function validateGetAccess( $posted_data ): bool {

		/*Bail Early*/
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( empty( $posted_data['nonce'] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $posted_data['nonce'], 'cm_typesense_ValidateNonce' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $settings
	 *
	 * @return array
	 */
	private function sanitizeGeneralSettings( $settings ): array {
		/*				[
		'node'           => '',
					'protocol'       => 'https://',
					'admin_api_key'  => '',
					'search_api_key' => '',
					'port'           => '443',
					'debug_log'      => false,
				];*/
		$sanitizeSettings = self::$default_settings;

		$sanitizeSettings['protocol']       = ( $settings['protocol'] == 'https://' || $settings['protocol'] == 'http://' ) ? $settings['protocol'] : 'https://';
		$sanitizeSettings['admin_api_key']  = sanitize_text_field( $settings['admin_api_key'] );
		$sanitizeSettings['search_api_key'] = sanitize_text_field( $settings['search_api_key'] );
		$sanitizeSettings['port']           = is_numeric( $settings['port'] ) ? $settings['port'] : 443;
		$sanitizeSettings['debug_log']      = is_bool( $settings['debug_log'] ) && $settings['debug_log'];
		$sanitizeSettings['error_log']      = is_bool( $settings['error_log'] ) && $settings['error_log'];
		$sanitizeSettings['node']           = sanitize_text_field( $settings['node'] );

		return $sanitizeSettings;
	}

	/**
	 * @param $newSettings
	 *
	 * @return array
	 */
	private function sanitizeSearchConfigSettings( $newSettings ): array {
		$sanitizeSettings = self::$search_config_settings;

		$sanitizeSettings['hijack_wp_search'] = $newSettings['hijack_wp_search'] ?? $sanitizeSettings['hijack_wp_search'];
		//either instant_search or autocomplete - default will be instant_search
		$sanitizeSettings['hijack_wp_search__type']        = $newSettings['hijack_wp_search__type'] ?? $sanitizeSettings['hijack_wp_search__type'];
		$sanitizeSettings['autocomplete_placeholder_text'] = sanitize_text_field( $newSettings['autocomplete_placeholder_text'] ?? $sanitizeSettings['autocomplete_placeholder_text'] );
		$sanitizeSettings['autocomplete_input_delay']      = sanitize_text_field( $newSettings['autocomplete_input_delay'] ?? $sanitizeSettings['autocomplete_input_delay'] );
		$sanitizeSettings['autocomplete_submit_action']    = sanitize_text_field( $newSettings['autocomplete_submit_action'] ?? $sanitizeSettings['autocomplete_submit_action'] );

		$sanitizeSettings['enabled_post_types'] = is_array( $newSettings['enabled_post_types'] ) ? $this->sanitizeArrayAsTextField( $newSettings['enabled_post_types'] ) : $sanitizeSettings['enabled_post_types'];

		unset( $sanitizeSettings['available_post_types'] );

		if ( isset( $newSettings['config']['post_type'] ) && is_array( $newSettings['config']['post_type'] ) ) {
			$newPostTypeConfig = [];
			foreach ( $newSettings['config']['post_type'] as $post_slug => $post_configuration ) {
				$newPostTypeConfig[ sanitize_text_field( $post_slug ) ]['label']           = sanitize_text_field( $post_configuration['label'] );
				$newPostTypeConfig[ sanitize_text_field( $post_slug ) ]['max_suggestions'] = sanitize_text_field( $post_configuration['max_suggestions'] );
			}
			$sanitizeSettings['config']['post_type'] = $newPostTypeConfig;
		}

		//print_r($sanitizeSettings); die;

		return $sanitizeSettings;
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function sanitizeArrayAsTextField( $data ): array {
		return array_map( static function ( $item ) {
			return sanitize_text_field( $item );
		}, $data );
	}

	/**
	 * This is for general settings
	 */
	public function saveGeneralSettings() {
		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );
		if ( ! $this->validateGetAccess( $posted_data ) ) {
			return false;
		}

		$updatedSettings = $this->sanitizeGeneralSettings( $posted_data['settings'] );

		update_option( self::$general_options_key, $updatedSettings );
		$server_health = TypesenseAPI::getInstance()->getDebugInfo();
		$response      = [
			'settings' => $updatedSettings,
			'notice'   => [
				'status'  => 'success',
				'message' => 'Settings Saved',
			],
		];
		if ( is_wp_error( $server_health ) ) {
			$response['notice']['status']  = 'error';
			$response['notice']['message'] = 'The credentials could not be verified - please check your settings';
		}
		wp_send_json( $response );
	}

	/**
	 * @return false|void
	 */
	public function saveSearchConfigSettings() {
		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );
		if ( ! $this->validateGetAccess( $posted_data ) ) {
			return false;
		}

		$updatedSettings = $this->sanitizeSearchConfigSettings( $posted_data['settings'] );

		update_option( self::$search_config_key, $updatedSettings );
		wp_send_json( [
			'settings' => $updatedSettings,
			'notice'   => [
				'status'  => 'success',
				'message' => 'Settings Saved',
			],
		] );

	}

	public function CMTypesenseDeleteFile() {
		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );
		if ( ! $this->validateGetAccess( $posted_data ) ) {
			return false;
		}

		if ( ! isset( $posted_data['filename'] ) || ! isset( $posted_data['log_type'] ) ) {
			wp_send_json( [
				'notice' => [
					'status'  => 'error',
					'message' => 'Bad Request',
				],
			] );
		}

		$logger  = new Logger();
		$deleted = $logger->deleteFile( $posted_data['log_type'], $posted_data['filename'] );

		if ( $deleted ) {
			wp_send_json( [
				'notice' => [
					'status'  => 'success',
					'message' => 'File Deleted',
				],
			] );
		} else {
			wp_send_json( [
				'notice' => [
					'status'  => 'error',
					'message' => 'Failure',
				],
			] );
		}

	}

	public function handleDropCollection() {
		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );
		if ( ! $this->validateGetAccess( $posted_data ) ) {
			return false;
		}

		$result   = TypesenseAPI::getInstance()->dropCollection( $posted_data['collectionName'] );
		$response = '';
		if ( is_wp_error( $result ) ) {
			$response = [
				'notice' => [
					'status'  => 'error',
					'message' => $result->get_error_message(),
				],
			];
		} else {
			$response = [
				'notice' => [
					'status' => 'success',
				],
			];
		}

		wp_send_json( $response );
	}

	public function bulkImportPosts( $posted_data, $send_json = true ) {
		$posts_per_page = apply_filters( 'cm_typesense_bulk_posts_per_page', 10 );
		$args           = [
			'post_type'      => $posted_data['post_type'],
			'posts_per_page' => $posts_per_page,
			'post_status'    => apply_filters( 'cm_typesense_post_status', 'publish' ),
		];
		if ( isset( $posted_data['offset'] ) && $posted_data['offset'] != 0 ) {
			$args['offset'] = $posted_data['offset'];
		}
		$posts = new \WP_Query( apply_filters( 'cm_typesense_bulk_index_query_args', $args ) );
		if ( $posts->have_posts() ) :
			$documents   = [];
			$total_posts = $posts->found_posts;
			foreach ( $posts->posts as $post ) {
				if ( apply_filters( 'cm_typesense_bulk_import_skip_post', false, $post ) ) {
					//if you want to delete on bulk import you can
					do_action( 'cm_typesense_bulk_import_on_post_skipped', $post );
					continue;
				}
				$documents[] = TypesenseAPI::getInstance()->formatDocumentForEntry( $post, $post->ID, $post->post_type );
			}


			$result = TypesenseAPI::getInstance()->bulkUpsertDocuments( $posted_data['post_type'], $documents );

			//var_dump(is_wp_error($result)); die;
			if ( is_wp_error( $result ) ) {
				if ( is_wp_error( $result ) && $result->get_error_code() == 404 ) {
					$schema             = TypesenseAPI::getInstance()->getSchema( $posted_data['post_type'] );
					$schemaMaybeCreated = TypesenseAPI::getInstance()->createCollection( $schema );
					if ( is_object( $schemaMaybeCreated ) && $schemaMaybeCreated->name == TypesenseAPI::getInstance()->getCollectionNameFromSchema( $post->post_type ) ) {
						TypesenseAPI::getInstance()->bulkUpsertDocuments( $posted_data['post_type'], $documents );
						$response = [
							'status'  => 'success',
							'notice'  => [
								'status'  => 'success',
								'message' => 'All entries for "' . $posted_data['post_type'] . '" have been successfully Imported',
							],
							'addInfo' => [
								'total_posts'    => $total_posts,
								'offset'         => $posted_data['offset'] + $posts_per_page,
								'posts_per_page' => $posts_per_page,
							],
						];
						if ( $send_json ) {
							wp_send_json( $response );
						} else {
							return $response;
						}
					} else {
						wp_send_json( [
							'notice' => [
								'status'  => 'error',
								'message' => $posted_data['post_type'] . ' were not imported please check error log',
							],
						] );
					}
				} else {
					$log = new Logger();
					$log->logError( $result );
				}
			} else {

				$response = [
					'status'  => 'success',
					'notice'  => [
						'status'  => 'success',
						'message' => 'All entries for "' . $posted_data['post_type'] . '" have been successfully Imported',
					],
					'addInfo' => [
						'total_posts'    => $total_posts,
						'offset'         => $posted_data['offset'] + $posts_per_page,
						'posts_per_page' => $posts_per_page,
					],
				];
				if ( $send_json == true ) {
					wp_send_json( $response );
				} else {
					return $response;
				}

			}
		endif;
	}

	public function bulkImportTaxonomy( $posted_data ) {
		$taxonomy = trim( $posted_data['post_type'] );

		$terms_per_page = apply_filters( 'cm_typesense_bulk_posts_per_page', 40 );

		$args = [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'number'     => $terms_per_page,
		];

		if ( isset( $posted_data['offset'] ) && $posted_data['offset'] != 0 ) {
			$args['offset'] = $posted_data['offset'];
		}
		$terms = new \WP_Term_Query( apply_filters( 'cm_typesense_bulk_index_query_args', $args ) );

		$documents = [];

		$total_terms = wp_count_terms( $taxonomy, [ 'hide_empty' => false ] );

		foreach ( $terms->get_terms() as $term ) {
			if ( apply_filters( 'cm_typesense_bulk_import_skip_post', false, $term ) ) {
				//if you want to delete on bulk import you can
				do_action( 'cm_typesense_bulk_import_on_term_skipped', $term );
				continue;
			}
			$documents[] = TypesenseAPI::getInstance()->formatDocumentForEntry( $term, $term->term_id, $taxonomy );
		}


		$result = TypesenseAPI::getInstance()->bulkUpsertDocuments( $taxonomy, $documents );

		if ( is_wp_error( $result ) ) {
			if ( is_wp_error( $result ) && $result->get_error_code() == 404 ) {
				$schema             = TypesenseAPI::getInstance()->getSchema( $taxonomy );
				$schemaMaybeCreated = TypesenseAPI::getInstance()->createCollection( $schema );
				if ( is_object( $schemaMaybeCreated ) && $schemaMaybeCreated->name == TypesenseAPI::getInstance()->getCollectionNameFromSchema( $taxonomy ) ) {
					TypesenseAPI::getInstance()->bulkUpsertDocuments( $taxonomy, $documents );
					wp_send_json( [
						'status'  => 'success',
						'notice'  => [
							'status'  => 'success',
							'message' => 'All entries for "' . $taxonomy . '" have been successfully Imported',
						],
						'addInfo' => [
							'total_posts'    => $total_terms,
							'offset'         => $posted_data['offset'] + $terms_per_page,
							'posts_per_page' => $terms_per_page,
						],
					] );
				} else {
					wp_send_json( [
						'notice' => [
							'status'  => 'error',
							'message' => $taxonomy . ' were not imported please check error log',
						],
					] );
				}
			} else {
				$log = new Logger();
				$log->logError( $result );
			}
		} else {
			wp_send_json( [
					'status'  => 'success',
					'notice'  => [
						'status'  => 'success',
						'message' => 'All entries for "' . $taxonomy . '" have been successfully Imported',
					],
					'addInfo' => [
						'total_posts'    => $total_terms,
						'offset'         => $posted_data['offset'] + $terms_per_page,
						'posts_per_page' => $terms_per_page,
					],
				]
			);
		}
	}

	/**
	 * @throws \JsonException
	 */
	public function CMTypesenseBulkImport() {
		// Check if node is not expired and CURL on TS server is working and return early if it doesn't work.
		$server_info = TypesenseAPI::getInstance()->getServerHealth();
		if ( property_exists( $server_info, 'errors' ) ) {
			$errors = $server_info->errors;

			if ( isset( $errors['http_request_failed'] ) ) {
				wp_send_json( [
					'notice' => [
						'status'  => 'error',
						'message' => wp_sprintf( '%s Check %shere%s for more details.', $errors['http_request_failed'][0], '<a href="https://docs.wptypesense.com/debug/" target="_blank">', '</a>' ),
					],
				] );

				return false;
			}
		}

		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );
		if ( ! $this->validateGetAccess( $posted_data ) ) {
			return false;
		}


		//Old
		$updatedSettings = $this->sanitizeSearchConfigSettings( $posted_data['settings'] );
		update_option( self::$search_config_key, $updatedSettings );
		do_action( 'cm_typesense_before_bulk_index' );

		if ( 'post_type' == $posted_data['index_type'] ) :
			$this->bulkImportPosts( $posted_data );

		elseif ( 'taxonomy' == $posted_data['index_type'] ) :
			$this->bulkImportTaxonomy( $posted_data );
		endif;


		do_action( 'cm_typesense_after_bulk_index' );

		wp_send_json( [
			'notice' => [
				'status'  => 'error',
				'message' => 'Something Has gone wrong while bulk importing',
			],
		] );

	}

	/**
	 * @param $hook_suffix
	 */
	public function load_scripts( $hook_suffix ): void {
		$script = [];
		if ( file_exists( CODEMANAS_TYPESENSE_ROOT_DIR_PATH . '/assets/admin/index.asset.php' ) ) {
			$script = include_once CODEMANAS_TYPESENSE_ROOT_DIR_PATH . '/assets/admin/index.asset.php';
		}
		$dependencies = $script['dependencies'];
//		$dependencies[] = 'cmtsfw-admin-script';
//		var_dump($dependencies); die;
		wp_register_script( 'cm-typesense-admin-script', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/admin/index.js', $dependencies, $script['version'], true );
		wp_register_style( 'cm-typesense-admin-style', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/admin/style-index.css', [ 'wp-components' ], CODEMANAS_TYPESENSE_VERSION );
		wp_localize_script( 'cm-typesense-admin-script', 'cmTypesenseAdmin', [
			'nonce'                         => wp_create_nonce( 'cm_typesense_ValidateNonce' ),
			'assets_url'                    => CODEMANAS_TYPESENSE_ROOT_URI_PATH . '/assets',
			'instant_search_customizer_url' => admin_url( '/customize.php?autofocus[section]=typesense_popup' ),
		] );
		if ( $hook_suffix == $this->menu_page || $hook_suffix == $this->settings_page_slug || $hook_suffix == $this->log_page_slug ) {
			wp_enqueue_script( 'cm-typesense-admin-script' );
			wp_enqueue_style( 'cm-typesense-admin-style' );
		}
	}

	public function load_textdomain( $hook_suffix ) {
		if ( $hook_suffix == $this->menu_page || $hook_suffix == $this->settings_page_slug ) {
			wp_set_script_translations( 'cm-typesense-admin-script', 'search-with-typesense' );
		}

	}

	public function generate_admin_page() {
		require_once( CODEMANAS_TYPESENSE_ROOT_DIR_PATH . '/includes/views/AdminArea/index.php' );
	}

	public function generate_license_page() {
		require_once CODEMANAS_TYPESENSE_ROOT_DIR_PATH . '/includes/views/AdminArea/licensing.php';
	}


}