<?php
/**
 *  Object that manage all classes related to Surfer.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer;

use SurferSEO\Surferseo;
use SurferSEO\Surfer\Integrations\Integrations;
use SurferSEO\Surfer\Surfer_GSC;
use WP_REST_Response;

/**
 * Object responsible for handlig all Surfer features.
 */
class Surfer {

	/**
	 * URL to Surfer.
	 *
	 * @var string
	 */
	protected $surfer_url = '';

	/**
	 * URL to Surfer API.
	 *
	 * @var string
	 */
	protected $surfer_api_url = '';

	/**
	 * URL to Surfer Privacy Policy
	 *
	 * @var string
	 */
	protected $surfer_privacy_policy = 'https://surferseo.com/privacy-policy/';

	/**
	 * Class that hadnle importing content from Surfer/GoogleDocs into WordPress.
	 *
	 * @var Content_Importer
	 */
	protected $content_importer = null;

	/**
	 * Class that handle exporting content from WordPress to Surfer.
	 *
	 * @var Content_Exporter
	 */
	protected $content_exporter = null;

	/**
	 * Class that handle writing guidlines sidebar.
	 *
	 * @var Surfer_Sidebar
	 */
	protected $writing_guidelines_sidebar = null;

	/**
	 * Class that handle keyword surfer tool.
	 *
	 * @var Keyword_Surfer
	 */
	protected $keyword_surfer = null;

	/**
	 * Class that handle GSC tool.
	 *
	 * @var Surfer_GSC
	 */
	protected $gsc = null;

	/**
	 * Class that handle integrations with other WP Plugins.
	 *
	 * @var Integrations
	 */
	protected $integrations = null;

	/**
	 * Class that handle ajax endpoints for React, that reach general Surfer API.
	 *
	 * @var Integrations
	 */
	protected $general_endpoints = null;

	/**
	 * Object construct.
	 */
	public function __construct() {
		$this->import_features();

		add_action( 'init', array( $this, 'resolve_api_url' ), 5 );
		add_action( 'rest_api_init', array( $this, 'register_connection_api_endpoints' ) );

		add_action( 'wp_ajax_generate_connection_url', array( $this, 'get_ajax_surfer_connect_url' ) );
		add_action( 'wp_ajax_disconnect_surfer', array( $this, 'disconnect_surfer_from_wp' ) );

		add_action( 'wp_ajax_check_connection_status', array( $this, 'check_connection_status' ) );

		add_action( 'surfer_gather_available_locations', array( $this, 'surfer_gather_available_locations' ) );

		$this->run_cron_tasks();
	}

	/**
	 * Gets API URL from config or use default one.
	 *
	 * @return void
	 */
	public function resolve_api_url() {
		$this->surfer_url     = rtrim( Surferseo::get_instance()->get_surfer_settings()->get_option( 'content-importer', 'surfer_url', 'https://app.surferseo.com' ), '/' );
		$this->surfer_api_url = rtrim( Surferseo::get_instance()->get_surfer_settings()->get_option( 'content-importer', 'surfer_api_url', 'https://app.surferseo.com/api/v1/wordpress/' ), '/' );
	}

	/**
	 * Returns URL to Surfer API.
	 *
	 * @return string
	 */
	public function get_api_url() {
		return $this->surfer_api_url;
	}

	/**
	 * Returns URL to Surfer.
	 *
	 * @return string
	 */
	public function get_surfer_url() {
		return $this->surfer_url;
	}

	/**
	 * Returns URL to Surfer Privacy Policy.
	 *
	 * @return string
	 */
	public function get_privacy_policy_url() {
		return $this->surfer_privacy_policy;
	}

	/**
	 * Returns GSC object.
	 *
	 * @return Surfer_GSC
	 */
	public function get_gsc() {
		return $this->gsc;
	}

	/**
	 * Register endpoints in API to make connectio with surfer.
	 *
	 * @return void
	 */
	public function register_connection_api_endpoints() {
		register_rest_route(
			'surferseo/v1',
			'/connect/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'surfer_connect_verify' ),
				'permission_callback' => function ( $request ) {
					return true;
				},
				'args'                => array(),
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/disconnect/',
			array(
				'methods'             => 'DELETE',
				'callback'            => array( $this, 'disconnect_surfer' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
				'args'                => array(),
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/import_post/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'surfer_import_post' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/get_posts/',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'surfer_return_posts_list' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
				'args'                => array(),
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/get_users/',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'surfer_return_users_list' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
				'args'                => array(),
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/get_categories/',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'surfer_return_categories' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
				'args'                => array(),
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/get_tags/',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'surfer_return_tags' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
				'args'                => array(),
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/get_post_types/',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'surfer_return_post_types' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
				'args'                => array(),
			)
		);

		register_rest_route(
			'surferseo/v1',
			'/disconnect_draft/',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'disconnect_post_from_draft' ),
				'permission_callback' => function ( $request ) {
					return $this->verify_request_permission( $request );
				},
				'args'                => array(),
			)
		);
	}

	/**
	 * Execute cron tasks.
	 */
	private function run_cron_tasks() {
		if ( ! wp_next_scheduled( 'surfer_gather_available_locations' ) ) {
			wp_schedule_event( time(), 'monthly', 'surfer_gather_available_locations' );
		}
	}

	/**
	 * Get header Authorization
	 *
	 * @return string
	 */
	private function get_authorization_header() {
		$headers = null;

		if ( isset( $_SERVER['Authorization'] ) ) {
			$headers = sanitize_text_field( wp_unslash( $_SERVER['Authorization'] ) );
		} elseif ( isset( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
			// Nginx or fast CGI.
			$headers = sanitize_text_field( wp_unslash( $_SERVER['HTTP_AUTHORIZATION'] ) );
		} elseif ( function_exists( 'apache_request_headers' ) ) {
			$request_headers = apache_request_headers();
			// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization).
			$request_headers = array_combine( array_map( 'ucwords', array_keys( $request_headers ) ), array_values( $request_headers ) );
			if ( isset( $request_headers['Authorization'] ) ) {
				$headers = sanitize_text_field( wp_unslash( $request_headers['Authorization'] ) );
			}
		}

		return $headers;
	}

	/**
	 * Get access token from header
	 *
	 * @return null | string
	 */
	private function get_bearer_token() {
		$headers = $this->get_authorization_header();

		// HEADER: Get the access token from the header.
		if ( ! empty( $headers ) ) {
			if ( preg_match( '/Bearer\s(\S+)/', $headers, $matches ) ) {
				return $matches[1];
			}
		}

		return null;
	}

	/**
	 * Import all classes that handle different features.
	 *
	 * @return void
	 */
	private function import_features() {
		$this->content_importer  = new Content_Importer();
		$this->content_exporter  = new Content_Exporter();
		$this->keyword_surfer    = new Keyword_Surfer();
		$this->gsc               = new Surfer_GSC();
		$this->integrations      = new Integrations();
		$this->general_endpoints = new Surfer_General_Endpoints();
	}

	/**
	 * Checks permission of user that is trying to use API.
	 *
	 * @param WP_API_Request $request - Request object.
	 * @return bool
	 */
	private function verify_request_permission( $request ) {
		$received_token = $this->get_bearer_token();
		$saved_token    = get_option( 'wpsurfer_api_access_key', false );

		if ( null !== $received_token && false !== $saved_token && $received_token === $saved_token ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns initial connection URL for AJAX request.
	 *
	 * @return void
	 */
	public function get_ajax_surfer_connect_url() {

		if ( ! surfer_validate_ajax_request() ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$response = array(
			'url' => $this->get_surfer_connect_url(),
		);

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Function returns initial connect URL.
	 *
	 * @return string
	 */
	public function get_surfer_connect_url() {
		$url   = site_url();
		$token = $this->genereate_connection_token();

		return $this->surfer_url . '/wordpress/connect?token=' . $token . '&url=' . $url;
	}

	/**
	 * Creates token to make connection with Surfer.
	 *
	 * @return string
	 */
	private function genereate_connection_token() {
		$token = wp_generate_uuid4();

		set_transient( 'surfer_connection_token', $token, 60 * 5 );

		return $token;
	}

	/**
	 * Function to verify response from Surfer.
	 *
	 * @param WP_REST_Request $request - Request object.
	 * @return string
	 */
	public function surfer_connect_verify( $request ) {
		$token = false;
		if ( isset( $request['token'] ) ) {
			$token = sanitize_text_field( wp_unslash( $request['token'] ) );
		}

		if ( false !== $token && $this->verify_connection_token( $token ) ) {
			$api_key     = sanitize_text_field( wp_unslash( $request['api_key'] ) );
			$token_saved = update_option( 'wpsurfer_api_access_key', $api_key, false );
			delete_transient( 'surfer_connection_token' );

			$connection_details = array(
				'organization_name' => $request['organization_name'],
				'via_email'         => $request['via_email'],
			);

			update_option( 'surfer_connection_details', $connection_details, false );

			$response = new WP_REST_Response( array( 'token_saved' => $token_saved ) );
		} else {
			$response = new WP_REST_Response( array( 'error' => __( 'Token verification failed', 'surferseo' ) ), 403 );
		}

		return $response;
	}

	/**
	 * Verify if provided token is the same generated token.
	 *
	 * @param string $token - Token.
	 * @return bool
	 */
	private function verify_connection_token( $token ) {
		$wp_token = get_transient( 'surfer_connection_token' );

		if ( false !== $wp_token && $wp_token === $token ) {
			return true;
		}

		return false;
	}

	/**
	 * Disconnects Surfer from WPSurfer on Surfer request.
	 *
	 * @return WP_REST_Response
	 */
	public function disconnect_surfer() {
		$this->make_disconnection_cleanup();
		$response = new WP_REST_Response();

		return $response;
	}

	/**
	 * Allows to check if connection to Surfer exists.
	 *
	 * @return void
	 */
	public function check_connection_status() {

		if ( ! surfer_validate_ajax_request() ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$response = array(
			'connection' => false,
		);

		$connection_details = get_option( 'surfer_connection_details', false );

		if ( false !== $connection_details ) {
			$response['connection'] = true;
			$response['details']    = $connection_details;
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Function to disconnect Surfer and inform Surfer about that.
	 *
	 * @return void
	 */
	public function disconnect_surfer_from_wp() {

		if ( ! surfer_validate_ajax_request() ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$token   = get_option( 'wpsurfer_api_access_key', false );
		$api_url = $this->surfer_api_url . '/disconnect';

		$this->make_disconnection_cleanup();

		if ( false === $token ) {
			return;
		}

		$args = array(
			'method'  => 'DELETE',
			'headers' => array(
				'Content-Type' => 'application/json',
				'api-key'      => $token,
			),
		);

		$result = wp_remote_request( $api_url, $args );

		echo esc_html( $result['body'] );
		wp_die();
	}

	/**
	 * Function that clears all options during disconnection.
	 *
	 * @return void
	 */
	private function make_disconnection_cleanup() {
		delete_option( 'wpsurfer_api_access_key' );
		delete_option( 'surfer_connection_details' );
	}

	/**
	 * Creates parsed post based on content from Surfer.
	 *
	 * @param WP_REST_Request $request - request object.
	 * @return WP_REST_Response
	 */
	public function surfer_import_post( $request ) {
		$args    = array();
		$content = $request->get_param( 'content' );

		if ( empty( $content ) || '' === $content || strlen( $content ) < 1 ) {
			return new WP_REST_Response(
				array(
					'error' => __( 'Cannot add post with empty content.', 'surferseo' ),
				),
				422
			);
		}

		// Optional params.
		$args['post_id']          = $request->get_param( 'post_id' );
		$args['post_author']      = $request->get_param( 'post_author' );
		$args['post_date']        = $request->get_param( 'post_date' );
		$args['post_category']    = $request->get_param( 'post_category' );
		$args['tags_input']       = $request->get_param( 'tags_input' );
		$args['post_status']      = $request->get_param( 'post_status' );
		$args['post_name']        = $request->get_param( 'url' );
		$args['meta_title']       = $request->get_param( 'meta_title' );
		$args['meta_description'] = $request->get_param( 'meta_description' );
		$args['draft_id']         = $request->get_param( 'draft_id' );
		$args['permalink_hash']   = $request->get_param( 'permalink_hash' );
		$args['keywords']         = $request->get_param( 'keywords' );
		$args['location']         = $request->get_param( 'location' );

		$modification_date = gmdate( 'Y-m-d' );
		if ( isset( $args['post_id'] ) ) {
			$modification_date = get_the_modified_time( 'Y-m-d', $args['post_id'] );
		}

		$post_id = $this->content_importer->save_data_into_database( $content, $args );

		if ( ! is_wp_error( $post_id ) ) {
			return new WP_REST_Response(
				array(
					'post_id'       => $post_id,
					'edit_post_url' => $this->get_edit_post_link( $post_id ),
					'post_url'      => get_permalink( $post_id ),
					'post_status'   => get_post_status( $post_id ),
					'modified_at'   => $modification_date,
					'url'           => site_url(),
				)
			);
		} else {
			return new WP_REST_Response(
				array(
					'error'            => __( 'There was an error on post adding', 'surferseo' ),
					'wp_error_message' => $post_id->get_error_message(),
				),
				403
			);
		}
	}

	/**
	 * Returns post edit link in wp-admin, without checking permission.
	 *
	 * @param int    $post_id - ID of the post.
	 * @param string $context - how to display ampersand char.
	 * @return string
	 */
	private function get_edit_post_link( $post_id, $context = 'display' ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return;
		}

		if ( 'revision' === $post->post_type ) {
			$action = '';
		} elseif ( 'display' === $context ) {
			$action = '&amp;action=edit';
		} else {
			$action = '&action=edit';
		}

		$post_type_object = get_post_type_object( $post->post_type );
		if ( ! $post_type_object ) {
			return;
		}

		if ( $post_type_object->_edit_link ) {
			$link = admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) );
		} else {
			$link = '';
		}

		return $link;
	}

	/**
	 * Returns list of all posts. Version for WPSurfer >=1.2.0
	 *
	 * @param WP_REST_Request $request - request object.
	 * @return array
	 */
	public function surfer_return_posts_list( $request ) {
		$posts_page    = $request->get_param( 'postsPage' );
		$posts_status  = $request->get_param( 'postsStatus' );
		$posts_keyword = $request->get_param( 'postsKeyword' );

		if ( 'all' === $posts_status || ! isset( $posts_status ) ) {
			$posts_status = array( 'publish', 'pending', 'draft', 'future' );
		} else {
			$posts_status = array( $posts_status );
		}

		$posts      = array();
		$post_types = apply_filters( 'surfer_allowed_post_types', array( 'post' ) );

		$query_args = array(
			'post_status' => $posts_status,
			'page'        => 5,
			'paged'       => max( $posts_page, 1 ),
			'post_type'   => $post_types,
		);

		if ( $posts_keyword ) {
			$query_args['s'] = $posts_keyword;
		}

		$all_posts = get_posts( $query_args );

		if ( $all_posts ) {
			foreach ( $all_posts as $post ) {
				$user = get_user_by( 'ID', $post->post_author );

				$author = array(
					'id'           => 0,
					'user_login'   => __( 'Anonymous', 'surferseo' ),
					'display_name' => __( 'Anonymous', 'surferseo' ),
				);

				if ( $user ) {
					$author = array(
						'id'           => $user->ID,
						'user_login'   => $user->user_login,
						'display_name' => $user->display_name,
					);
				}

				$posts[] = array(
					'id'          => $post->ID,
					'title'       => $post->post_title,
					'status'      => $post->post_status,
					'created_at'  => $post->post_date,
					'modified_at' => $post->post_modified,
					'post_type'   => $post->post_type,
					'url'         => get_permalink( $post ),
					'author'      => $author,
				);
			}
		}

		$count_posts          = new \stdClass();
		$count_posts->publish = $this->count_posts_for_query( $query_args, 'publish' );
		$count_posts->pending = $this->count_posts_for_query( $query_args, 'pending' );
		$count_posts->draft   = $this->count_posts_for_query( $query_args, 'draft' );
		$count_posts->future  = $this->count_posts_for_query( $query_args, 'future' );
		$count_posts->all     = $count_posts->publish + $count_posts->pending + $count_posts->draft + $count_posts->future;

		$result = array(
			'posts'  => $posts,
			'counts' => $count_posts,
		);

		return $result;
	}

	/**
	 * Count posts for query in post lis.
	 *
	 * @param array  $params - query params.
	 * @param string $post_status - post status.
	 */
	private function count_posts_for_query( $params, $post_status ) {
		$args = array(
			'posts_per_page' => 1,
			'post_type'      => $params['post_type'],
			'post_status'    => $post_status,
			'fields'         => 'ids',
		);

		if ( isset( $params['s'] ) ) {
			$args['s'] = $params['s'];
		}

		$query = new \WP_Query( $args );
		$count = $query->found_posts;

		return $count;
	}

	/**
	 * Returns list of all users
	 *
	 * @param WP_REST_Request $request - request object.
	 * @return array
	 */
	public function surfer_return_users_list( $request ) {
		$users = array();

		$all_users = get_users( array( 'number' => -1 ) );

		if ( $all_users ) {
			foreach ( $all_users as $user ) {
				$posts[] = array(
					'user_id'      => $user->ID,
					'user_login'   => $user->user_login,
					'display_name' => $user->display_name,
				);
			}
		}

		return $users;
	}

	/**
	 * Returns list of all categories
	 *
	 * @param WP_REST_Request $request - request object.
	 * @return array
	 */
	public function surfer_return_categories( $request ) {
		$args = array(
			'hide_empty' => false,
		);

		$categories = get_categories( $args );

		return $categories;
	}

	/**
	 * Returns list of all tags
	 *
	 * @param WP_REST_Request $request - request object.
	 * @return array
	 */
	public function surfer_return_tags( $request ) {
		$args = array(
			'hide_empty' => false,
		);

		$tags = get_tags( $args );

		return $tags;
	}

	/**
	 * Returns list of supported post types.
	 *
	 * @param WP_REST_Request $request - request object.
	 * @return array
	 */
	public function surfer_return_post_types( $request ) {
		return surfer_return_supported_post_types();
	}

	/**
	 * Returns details of the connection, or false if connection is not made.
	 *
	 * @return array|false
	 */
	public function wp_connection_details() {
		return get_option( 'surfer_connection_details', false );
	}

	/**
	 * Checks if page is meeting requirements to connect with Surfer.
	 *
	 * @return bool
	 */
	public function wp_ready_to_connect() {
		$wp_version = get_bloginfo( 'version' );

		if ( version_compare( $wp_version, '5.7', '<' ) ) {
			return false;
		}

		if ( ! is_ssl() ) {
			return false;
		}

		if ( '' === get_option( 'permalink_structure' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Return requirements array
	 *
	 * @return array
	 */
	public function wp_ready_to_connect_errors() {
		$wp_version = get_bloginfo( 'version' );

		$permalinks = true;
		if ( '' === get_option( 'permalink_structure' ) ) {
			$permalinks = false;
		}

		return array(
			'version'    => array(
				/* translators: %s - version of the WordPress */
				'msg'   => sprintf( __( 'WordPress version 5.7 or newer. Your version: %s', 'surferseo' ), $wp_version ),
				'valid' => version_compare( $wp_version, '5.7', '>=' ),
			),
			'ssl'        => array(
				'msg'   => __( 'SSL should be enabled.', 'surferseo' ),
				'valid' => is_ssl(),
			),
			'permalinks' => array(
				'msg'   => __( 'Permalinks should be active', 'surferseo' ),
				'valid' => $permalinks,
			),
		);
	}

	/**
	 * Gets current locations from Surfer API.
	 *
	 * @return string
	 */
	public function surfer_gather_available_locations() {
		$response  = wp_remote_get( 'https://app.surferseo.com/api/v1/locations' );
		$locations = wp_remote_retrieve_body( $response );
		if ( '' !== $locations ) { // wp_remote_retrieve_body returns empty string if response is not valid json.
			update_option( 'surfer_available_locations', $locations );
		}

		return $locations;
	}


	/**
	 * Returns available locations based on Surfer API.
	 *
	 * @param bool $force - if true, it will force to fetch locations from API.
	 * @return string
	 */
	public function surfer_locations( $force = false ) {
		$locations = get_option( 'surfer_available_locations', array() );

		if ( empty( $locations ) || $force ) {
			$locations = $this->surfer_gather_available_locations();
		}

		return $locations;
	}

	/**
	 * Returns hardcoded available locations.
	 *
	 * API returns over 1500 records, and most of them are not used in Surfer.
	 *
	 * @return string
	 */
	public function surfer_hardcoded_location() {
		return 'Argentina;Australia;Adelaide;Brisbane;Canberra;Melbourne;Perth;Sydney;Austria;Graz;Vienna;Azerbaijan;Barbados;Belarus;Belgium;Belgium - NL;Brazil;Brasilia;São Bernardo do Campo;Bulgaria;Canada - EN;Calgary - EN;Halifax - EN;Montreal - EN;Ottawa - EN;Red Deer - EN;Toronto - EN;Victoria - EN;Canada - FR;Calgary - FR;Halifax - FR;Montreal - FR;Ottawa - FR;Red Deer - FR;Toronto - FR;Victoria - FR;Chile;China;Colombia;Croatia;Cyprus - GR;Cyprus - TR;Czech Republic;Denmark;Dominican Republic;Ecuador;Egypt;Estonia;Finland;France;Abondance;Annecy;Biot;Cluses;Douvaine;Evian-les-Bains;Morzine;Paris;Taninges;Thonon-les-Bains;Germany;Aachen;Augsburg;Berlin;Bielefeld;Bochum;Bonn;Braunschweig;Bremen;Dortmund;Dresden;Duisburg;Düsseldorf;Essen;Frankfurt am Main;Gelsenkirchen;Hamburg;Hannover;Karlsruhe;Kiel;Köln;Leipzig;Mannheim;München;Münster;Nürnberg;Stuttgart;Wiesbaden;Wuppertal;Greece;Guatemala;Honduras;Hong Kong;Hungary;India;Delhi;Mumbai;India - EN;Delhi - EN;Mumbai - EN;Indonesia;Ireland;Israel;Italy;Bolzano - DE;Rome;Japan;Kazakhstan;Kenya;Latvia;Lithuania;Malaysia;Malaysia - EN;Mexico;Morocco;Netherlands;New Zealand;Auckland;Christchurch;Wellington;Nigeria;Norway;Pakistan;Pakistan - EN;Peru;Philippines;Philippines - EN;Poland;Białystok;Bielsko-Biała;Bieruń;Bydgoszcz;Częstochowa;Gdańsk;Gorlice;Gorzów Wielkopolski;Jasło;Katowice;Kielce;Kraków;Lublin;Nowy Sącz;Poznań;Płock;Rzeszów;Szczecin;Warszawa;Wrocław;Łódź;Portugal;Lisbon;Porto;Puerto Rico;Qatar;Romania;Russia;Chelyabinsk;Jekaterynburg;Kazan;Krasnoyarsk;Moscow;Nizhny Novgorod;Novosibirsk;Omsk;Perm;Rostov-on-Don;Saint Petersburg;Samara;Ufa;Volgograd;Voronezh;Saudi Arabia;Serbia;Singapore;Slovakia;Slovenia;South Africa;Johannesburg;Polokwane;Pretoria;South Africa - EN;Johannesburg - EN;Polokwane - EN;Pretoria - EN;South Korea;Spain;Sudan;Sweden;Switzerland - DE;Bern - DE;Geneva - DE;Zurich - DE;Switzerland - FR;Bern - FR;Geneva - FR;Zurich - FR;Taiwan;Thailand;Thailand - EN;Turkey;Ukraine;United Arab Emirates;Abu Dhabi;Dubai;United Kingdom;Aberdeen;Belfast;Birmingham;Bolton;Bournemouth;Bradford;Brighton;Bristol;Cardiff;Corby;Coventry;Derby;Edinburgh;Exeter;Glasgow;Ipswich;Kingston Upon Hull;Leeds;Leicester;Liverpool;London;Luton;Manchester;Milton Keynes;Newcastle Upon Tyne;Northampton;Nottingham;Plymouth;Portsmouth;Reading;Sheffield;Southampton;Stoke-on-Trent;Stratford-upon-Avon;Wimbledon;Wolverhampton;United States;Abilene, TX;Aiken, SC;Almaden, CA;Aptos, CA;Arlington Heights, IL;Arlington, TX;Atlanta;Auburn, WA;Augusta, GA;Austin, TX;Baltimore;Baton Rouge, LA;Beaumont, TX;Beaverton, ORE;Bellevue, WA;Belmar, NJ;Ben Lomond, CA;Bergen County, NJ;Bonita Springs, FL;Bonny Doon, CA;Boston;Boulder Creek, CA;Burlingame, CA;Byron Center, MI;Camas, WA;Canton, MA;Capitola, CA;Cedar Springs, MI;Charlotte, NC;Chattanooga, TN;Chicago;Cincinnati;Clermont, FL;Cleveland;College Station, TX;Colorado Springs;Columbus, MS;Corpus Christi, TX;Corvallis, ORE;Croydon, PA;Dallas;Danbury, CT;Davenport, CA;Davenport, FL;Del Monte Forest, CA;Denver, CO;Des Moines, IA;Detroit;East Brunswick, NJ;Elmhurst, IL;Eugene, ORE;Everett, WA;Fairfield, CT;Farmington Hills, MI;Felton, CA;Fenton, MI;Flint, MI;Flowery Branch, GA;Fort Mill, SC;Fort Myers, FL;Fort Worth;Freedom, CA;Fresno, CA;Gilmer, TX;Grand Rapids, MI;Greenville, SC;Greenwich, CT;Gresham, ORE;Half Moon Bay, CA;Hartford, CT;Hawaii;Hinsdale, IL;Hollister, CA;Houston, TX;Huntsville, AL;Indianapolis;Jacksonville, FL;Jersey City, NJ;Jupiter, FL;Kansas City;Keego Harbor, MI;Kent, WA;Kirkland, WA;La Selva Beach, CA;Lake Elsinore, CA;Las Vegas, NV;Leawood, KS;Leesburg, FL;Live Oak, CA;Livingston, TX;Long Island;Longview, TX;Longview, WA;Los Angeles;Los Gatos, CA;Louisville, KY;Lubbock, TX;Manassas, VA;Mansfield, NJ;McKinney, TX;Medford, ORE;Melbourne, FL;Memphis;Menifee, CA;Miami;Michigan City;Milwaukee;Minneapolis;Modesto, CA;Monterey, CA;Moss Landing, CA;Mt Hermon, CA;Murrieta, CA;Naples, FL;Nashville, TN;New Canaan, CT;New Jersey;New York;Newark, NJ;Oklahoma City;Olympia, WA;Orlando, FL;Overland Park, KS;Palm Beach Gardens, FL;Paramus, NJ;Philadelphia;Phoenix;Pittsburgh;Ponte Vedra Beach, FL;Portland, ORE;Prescott, AZ;Renton, WA;Rio Del Mar, CA;Roanoke, VA;Roseville, CA;Royal Oak, MI;Sacramento;Salem, ORE;Salinas, CA;Salt Lake City;San Antonio, TX;San Diego;San Francisco;Santa Barbara;Santa Clara, CA;Santa Cruz, CA;Saratoga, CA;Scotts Valley, CA;Seattle, WA;Shreveport;Soquel, CA;Spartanburg, SC;Spring Hill, FL;St. Johns County, FL;St. Louis, MO;St. Petersburg, FL;Stamford, CT;Tacoma, WA;Tampa, FL;Temecula, CA;Topeka, KS;Trenton, NJ;Vacaville, CA;Vancouver, WA;Wall Township, NJ;Washington;Watsonville, CA;Wayne, PA;Westport, CT;Windermere, FL;Winter Garden, FL;Vietnam;Vietnam - EN';
	}

	/**
	 * Returns array with a response from Surfer API.
	 *
	 * @param string $endpoint - endpoint to Surfer API.
	 * @param array  $params - params to send.
	 * @param string $method - method to send. (Optional, default: POST).
	 */
	public function make_surfer_request( $endpoint, $params, $method = 'POST' ) {
		$token   = get_option( 'wpsurfer_api_access_key', false );
		$api_url = Surfer()->get_surfer()->get_api_url() . $endpoint;

		if ( false === $token ) {
			return array( 'message' => __( 'You need to connect your page to Surfer first.', 'surferseo' ) );
		}

		$args = array(
			'method'  => $method,
			'headers' => array(
				'Content-Type' => 'application/json',
				'api-key'      => $token,
			),
			'body'    => wp_json_encode( $params ),
		);

		if ( 'GET' === $method ) {
			unset( $args['body'] );
		}

		$result = wp_remote_request( $api_url, $args );
		$code   = wp_remote_retrieve_response_code( $result );

		if ( 200 !== $code && 201 !== $code ) {
			$response = $this->handle_surfer_errors( $code, $result );
		} else {
			$response = json_decode( wp_remote_retrieve_body( $result ), true );
		}

		return array(
			'code'     => $code,
			'response' => $response,
		);
	}

	/**
	 * Handle Surfer API errors.
	 *
	 * @param int   $code - error code.
	 * @param array $result - result from Surfer API.
	 * @return array
	 */
	private function handle_surfer_errors( $code, $result ) {
		$error_message = wp_remote_retrieve_body( $result );
		$response      = $code;

		if ( 401 === $code ) {
			$response = array( 'message' => __( '401: Authorization process failed.', 'surferseo' ) );
		}

		if ( 404 === $code ) {
			$response = array( 'message' => __( '404: Endpoint do not exists. Please reach our support.', 'surferseo' ) );
		}

		if ( 422 === $code ) {
			/* translators: %s - error message */
			$response = array( 'message' => sprintf( __( '422: Request failed with message: %s', 'surferseo' ), $error_message ) );
		}

		if ( 500 === $code ) {
			$response = array( 'message' => __( '500: Unknown error. Please reach our support', 'surferseo' ) );
		}

		return $response;
	}

	/**
	 * Checks if Surfer is connected to WordPress site.
	 *
	 * @return bool
	 */
	public function is_surfer_connected() {
		$connected          = false;
		$connection_details = Surferseo::get_instance()->get_surfer()->wp_connection_details();
		if ( is_array( $connection_details ) && false !== $connection_details ) {
			$connected = true;
		}

		return $connected;
	}

	/**
	 * Enqueue sidebar script.
	 */
	public function enqueue_surfer_react_apps() {

		$base_url = Surfer()->get_baseurl();

		wp_register_script( 'surfer-guidelines-debug', $base_url . '/assets/js/surfer-guidelines-debug.js', array(), SURFER_VERSION, false );
		wp_register_script( 'surfer-guidelines', 'https://app.surferseo.com/static/surfer_guidelines_1_x_x.js', array( 'surfer-guidelines-debug' ), SURFER_VERSION, false );

		$react_deps = array(
			'wp-plugins',
			'wp-edit-post',
			'wp-element',
			'wp-components',
			'wp-data',
			'wp-dom-ready',
			'wp-tinymce',
			'surfer-guidelines',
		);

		wp_enqueue_script(
			'surfer-general',
			$base_url . '/assets/js/surfer-general.js',
			$react_deps,
			SURFER_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		$connection_details = get_option( 'surfer_connection_details', false );

		wp_localize_script(
			'surfer-general',
			'wpsurfer',
			array(
				// General usage.
				'ajaxurl'                => admin_url( 'admin-ajax.php' ),
				'wp_admin_url'           => admin_url( 'index.php' ),
				'baseurl'                => Surfer()->get_baseurl(),
				// Surfer genral usage.
				'wp_language'            => strtolower( $this->get_langauge_code() ),
				'learnmore_url'          => 'https://docs.surferseo.com/en/collections/3548643-wpsurfer',
				'config_url'             => admin_url( 'admin.php?page=surfer#header_core' ),
				'apiurl'                 => Surfer()->get_surfer()->get_api_url(),
				'surferurl'              => Surfer()->get_surfer()->get_surfer_url(),
				'locations'              => Surfer()->get_surfer()->surfer_hardcoded_location(),
				'organization_name'      => $connection_details['organization_name'],
				// Surfer connection details.
				'api_key'                => get_option( 'wpsurfer_api_access_key', null ),
				'connected'              => Surfer()->get_surfer()->is_surfer_connected(),
				'emails_enabled'         => Surfer()->get_surfer()->get_gsc()->performance_report_email_notification_endabled(),
				// Surfer post details.
				'default_draft_id'       => get_post_meta( get_the_ID(), 'surfer_draft_id', true ),
				'default_permalink_hash' => get_post_meta( get_the_ID(), 'surfer_permalink_hash', true ),
				'post_is_scraped'        => get_post_meta( get_the_ID(), 'surfer_scrape_ready', true ),
				'last_post_update'       => get_post_meta( get_the_ID(), 'surfer_last_post_update', true ),
				'default_post_keywords'  => get_post_meta( get_the_ID(), 'surfer_keywords', true ),
				'default_post_location'  => get_post_meta( get_the_ID(), 'surfer_location', true ),
				// Security.
				'surfer_ajax_nonce'      => wp_create_nonce( 'surfer-ajax-nonce' ),
			)
		);
	}

	/**
	 * Returns language code for current WordPress
	 *
	 * @return string
	 */
	private function get_langauge_code() {
		$locale = explode( '_', get_locale() );
		if ( isset( $locale[1] ) ) {
			return strtoupper( $locale[1] );
		}

		return 'EN';
	}

	/**
	 * Disconnects post from Surfer draft
	 *
	 * @param WP_REST_Request $request - request object.
	 * @return WP_REST_Response
	 */
	public function disconnect_post_from_draft( $request ) {

		$post_id = $request->get_param( 'wp_post_id' );

		if ( ! $post_id || null === get_post( $post_id ) ) {
			return new WP_REST_Response(
				array(
					'error' => __( 'Cannot add post with empty content.', 'surferseo' ),
				),
				422
			);
		}

		$this->disconnect_post_and_draft( $post_id );

		return new WP_REST_Response(
			array(
				'post_id' => $post_id,
			)
		);
	}

	/**
	 * Removes all meta data related to Surfer from post.
	 *
	 * @param int $post_id - ID of the post.
	 */
	private function disconnect_post_and_draft( $post_id ) {

		delete_post_meta( $post_id, 'surfer_draft_id' );
		delete_post_meta( $post_id, 'surfer_scrape_ready' );
		delete_post_meta( $post_id, 'surfer_permalink_hash' );
		delete_post_meta( $post_id, 'surfer_last_post_update' );
		delete_post_meta( $post_id, 'surfer_last_post_update_direction' );
		delete_post_meta( $post_id, 'surfer_keywords' );
		delete_post_meta( $post_id, 'surfer_location' );
	}
}
