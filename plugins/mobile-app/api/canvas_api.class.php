<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}
class Canvas_Api {


	static $api_version = 6;

	/**
	 * Plugin API initialization
	 */
	public static function init() {
		add_action( 'init', array( 'Canvas_Api', 'add_endpoint' ), 0 );
		add_filter( 'query_vars', array( 'Canvas_Api', 'add_query_vars' ), 0 );
		add_action( 'parse_request', array( 'Canvas_Api', 'check_requests' ), 0 );
		if ( Canvas::get_option( 'api_version', 1 ) != self::$api_version ) {
			// flush rules after new endpoint added.
			add_action( 'init', array( 'Canvas_Api', 'activate' ), 0 );
		}
	}

	/**
	 * Plugin API activation
	 */
	public static function activate() {
		self::add_endpoint();
		flush_rewrite_rules();
		Canvas::set_option( 'api_version', self::$api_version );
	}

	/**
	 * Add public query vars
	 *
	 * @return array $vars
	 */
	public static function add_query_vars( $vars ) {
		$vars[] = '__canvas_api';
		$vars[] = '__canvas_path';
		return $vars;
	}

	/**
	 * Add Endpoint
	 */
	public static function add_endpoint() {
		add_rewrite_rule( '^canvas-api/loginstate/?', 'index.php?__canvas_api=loginstate', 'top' );
		add_rewrite_rule( '^canvas-api/bp/?(.*)?', 'index.php?__canvas_api=bp&__canvas_path=$matches[1]', 'top' );
		add_rewrite_rule( '^canvas-api/peepso/?(.*)?', 'index.php?__canvas_api=peepso&__canvas_path=$matches[1]', 'top' );
		add_rewrite_rule( '^canvas-api/notifications/data/?', 'index.php?__canvas_api=notifications_data', 'top' );
		add_rewrite_rule( '^canvas-api/notifications/?', 'index.php?__canvas_api=notifications', 'top' );
		add_rewrite_rule( '^canvas-api/login/?', 'index.php?__canvas_api=login', 'top' );
		add_rewrite_rule( '^canvas-api/registration/?', 'index.php?__canvas_api=registration', 'top' );
		add_rewrite_rule( '^canvas-api/forgot-password/?', 'index.php?__canvas_api=forgot-password', 'top' );
	}

	/**
	 * Check Requests
	 *
	 * @param WP $wp
	 */
	public static function check_requests( $wp ) {
		if ( isset( $wp->query_vars['__canvas_api'] ) ) {
			self::request( $wp->query_vars['__canvas_api'] );
			exit;
		}
	}

	/**
	 * Handle Requests
	 */
	protected static function request( $api_endpoint ) {
		switch ( $api_endpoint ) {
			case 'loginstate':
				self::header_json();
				include_once dirname( __FILE__ ) . '/loginstate.php';
				break;
			case 'bp':
				include_once dirname( __FILE__ ) . '/bp.php';
				break;
			case 'notifications': // html content.
				include_once CANVAS_DIR . '/templates/notifications/list.php';
				break;
			case 'notifications_data': // json content.
				self::header_json();
				include_once dirname( __FILE__ ) . '/notifications_data.php';
				break;
			case 'login':
				include_once dirname( __FILE__ ) . '/login.php';
				if ( Canvas::is_request_from_application() ) {
				}
				break;
			case 'registration':
				if ( Canvas::is_request_from_application() ) {
					include_once dirname( __FILE__ ) . '/registration.php';
				}
				break;
			case 'forgot-password':
				if ( Canvas::is_request_from_application() ) {
					include_once dirname( __FILE__ ) . '/forgot-password.php';
				}
				break;
			case 'peepso':
				include_once dirname( __FILE__ ) . '/peepso.php';
				break;
			default:
				echo 'Mobiloud API v1.';
		}
	}

	public static function header_json() {
		header( 'Content-Type: application/json' );
	}
}

Canvas_Api::init();
