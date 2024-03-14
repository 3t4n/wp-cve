<?php

/**
 * Plugin Name: Bramework Integration
 * Description: Quickly integrate Bramework to your WordPress site and easily publish your post. Bramework's AI-powered writing assistant helps you write engaging, SEO friendly, long-form content and blog posts that convert.
 * Version: 1.0.0
 * Author: <a href="https://bramework.com">Bramework</a>
 * Author URI: https://bramework.com
 * Text Domain: bramework
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

const BRAMEWORK_CONNECT_URL    = 'https://app.bramework.com/connect-wordpress/';
const BRAMEWORK_CHECK_AUTH_URL = 'https://api.bramework.com/api/authorization-status';

if ( ! class_exists( 'WPM_Bramework_Plugin' ) ) {
	class WPM_Bramework_Plugin {

		public static function init() {
			add_option( 'wpb_bramework_tokens', '' );
			add_action( 'admin_menu', array( 'WPM_Bramework_Plugin', 'create_settings_menu' ) );
			add_filter( 'determine_current_user', array( 'WPM_Bramework_Plugin', 'determine_current_user' ) );
			add_action( 'rest_api_init', array( 'WPM_Bramework_Plugin', 'register_api_endpoints' ) );
			add_action( 'admin_enqueue_scripts', array( 'WPM_Bramework_Plugin', 'add_admin_scripts' ) );
			register_deactivation_hook( __FILE__, array( 'WPM_Bramework_Plugin', 'deactivation' ) );
		}

		public static function deactivation() {
			delete_option( 'wpb_bramework_tokens' );
		}

		public static function add_admin_scripts() {
			wp_enqueue_style( 'wpb-plugin', plugin_dir_url( __FILE__ ) . 'assets/css/style.css?v1.5' );
		}

		public static function create_settings_menu() {
			add_menu_page( 'Bramework Settings', 'Bramework', 'manage_options', 'bramework', array( 'WPM_Bramework_Plugin', 'create_settings_page' ), plugin_dir_url( __FILE__ ) . '/images/bramework_22x16.png' );
		}

		public static function create_settings_page() {
			include plugin_dir_path( __FILE__ ) . '/templates/settings.php';
		}

		public static function determine_current_user( $user ) {
			$rest_api_slug   = rest_get_url_prefix();
			$rest_route_slug = 'rest_route';
			$valid_api_uri   = strpos( sanitize_text_field( $_SERVER['REQUEST_URI'] ), $rest_api_slug ) || strpos( sanitize_text_field( $_SERVER['REQUEST_URI'] ), $rest_route_slug );
			if ( ! $valid_api_uri ) {
				return $user;
			}

			$auth = isset( $_SERVER['HTTP_TOKEN'] ) ? sanitize_text_field( $_SERVER['HTTP_TOKEN'] ) : false;

			if ( ! $auth ) {
				return $user;
			}

			return WPM_Bramework_Plugin::get_user_by_token( $auth, $user );
		}

		public static function get_user_by_token( $token, $user ) {
			$wpb_bramework_tokens = get_option( 'wpb_bramework_tokens' );

			if ( ! is_array( $wpb_bramework_tokens ) ) {
				return $user;
			}

			$user_email = array_search( $token, $wpb_bramework_tokens );

			if ( $user_email ) {
				$user = get_user_by( 'email', $user_email );

				return $user->ID;
			}

			return $user;
		}

		public static function register_api_endpoints() {
			$users_controller = new WP_REST_Users_Controller();
			register_rest_route( 'bramework/v2', '/users', array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WPM_Bramework_Plugin', 'get_users' ),
				'permission_callback' => array( 'WPM_Bramework_Plugin', 'get_users_permissions_check' ),
				'args'                => $users_controller->get_collection_params()
			) );

			$categories_controller = new WP_REST_Terms_Controller( 'category' );
			register_rest_route( 'bramework/v2', '/categories', array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( 'WPM_Bramework_Plugin', 'get_categories' ),
				'permission_callback' => array( 'WPM_Bramework_Plugin', 'get_categories_permissions_check' ),
				'args'                => $categories_controller->get_collection_params()
			) );

			$posts_controller = new WP_REST_Posts_Controller( 'post' );
			register_rest_route( 'bramework/v2', '/posts', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( 'WPM_Bramework_Plugin', 'get_posts' ),
					'permission_callback' => array( 'WPM_Bramework_Plugin', 'get_posts_permission_check' ),
					'args'                => $posts_controller->get_collection_params()

				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( 'WPM_Bramework_Plugin', 'create_post' ),
					'permission_callback' => array( 'WPM_Bramework_Plugin', 'create_post_permissions_check' ),
					'args'                => $posts_controller->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( 'WPM_Bramework_Plugin', 'get_public_item_schema' ),
			) );

			$attachment_controller = new WP_REST_Attachments_Controller( 'attachment' );
			register_rest_route( 'bramework/v2', '/media', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( 'WPM_Bramework_Plugin', 'get_media' ),
					'permission_callback' => array( 'WPM_Bramework_Plugin', 'get_media_permission_check' ),
					'args'                => $attachment_controller->get_collection_params()

				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( 'WPM_Bramework_Plugin', 'create_media' ),
					'permission_callback' => array( 'WPM_Bramework_Plugin', 'create_media_permissions_check' ),
					'args'                => $attachment_controller->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( 'WPM_Bramework_Plugin', 'get_public_item_schema' ),
			) );
		}

		public static function get_public_item_schema() {
			$posts_controller = new WP_REST_Posts_Controller( 'post' );

			return $posts_controller->get_public_item_schema();
		}

		public static function get_users_permissions_check( WP_REST_Request $request ) {
			$controller  = new WP_REST_Users_Controller();
			$permissions = $controller->get_items_permissions_check( $request );

			return $permissions;
		}

		public static function get_categories_permissions_check( WP_REST_Request $request ) {
			$controller  = new WP_REST_Terms_Controller( 'category' );
			$permissions = $controller->get_items_permissions_check( $request );

			return $permissions;
		}

		public static function get_users( WP_REST_Request $request ) {
			$controller = new WP_REST_Users_Controller();
			$response   = $controller->get_items( $request );

			return $response;
		}

		public static function get_categories( WP_REST_Request $request ) {
			$controller = new WP_REST_Terms_Controller( 'category' );
			$response   = $controller->get_items( $request );

			return $response;
		}

		public static function get_posts_permission_check( WP_REST_Request $request ) {
			$controller  = new WP_REST_Posts_Controller( 'post' );
			$permissions = $controller->get_items_permissions_check( $request );

			return $permissions;
		}

		public static function get_posts( WP_REST_Request $request ) {
			$controller = new WP_REST_Posts_Controller( 'post' );
			$response   = $controller->get_items( $request );

			return $response;
		}

		public static function create_post_permissions_check( WP_REST_Request $request ) {
			$controller  = new WP_REST_Posts_Controller( 'post' );
			$permissions = $controller->create_item_permissions_check( $request );

			return $permissions;
		}

		public static function create_post( WP_REST_Request $request ) {
			$controller = new WP_REST_Posts_Controller( 'post' );
			$response   = $controller->create_item( $request );

			return $response;
		}

		public static function get_media_permission_check( WP_REST_Request $request ) {
			$controller  = new WP_REST_Attachments_Controller( 'attachment' );
			$permissions = $controller->get_items_permissions_check( $request );

			return $permissions;
		}

		public static function get_media( WP_REST_Request $request ) {
			$controller = new WP_REST_Attachments_Controller( 'attachment' );
			$response   = $controller->get_items( $request );

			return $response;
		}

		public static function create_media_permissions_check( WP_REST_Request $request ) {
			$controller  = new WP_REST_Attachments_Controller( 'attachment' );
			$permissions = $controller->create_item_permissions_check( $request );

			return $permissions;
		}

		public static function create_media( WP_REST_Request $request ) {
			$controller = new WP_REST_Attachments_Controller( 'attachment' );
			$response   = $controller->create_item( $request );

			return $response;
		}

		public static function checkAuthorization( $token, $domain ) {
			$body = [
				'token'  => $token,
				'domain' => $domain,
			];

			$body = wp_json_encode( $body );

			$options = [
				'body'        => $body,
				'headers'     => [
					'Content-Type' => 'application/json',
				],
				'data_format' => 'body',
			];

			$request = wp_remote_post( BRAMEWORK_CHECK_AUTH_URL, $options );

			$tmp = json_decode( wp_remote_retrieve_body( $request ), true );
			if ( isset( $tmp['authorized'] ) && (bool) $tmp['authorized'] === true ) {
				return true;
			}

			return false;
		}
	}

	WPM_Bramework_Plugin::init();
}