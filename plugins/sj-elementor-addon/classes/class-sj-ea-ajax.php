<?php

/**
 * Front-end AJAX handler for the builder interface. We use this 
 * instead of wp_ajax because that only works in the admin and 
 * certain things like some shortcodes won't render there. AJAX
 * requests handled through this method only run for logged in users
 * for extra security. Developers creating custom modules that need
 * AJAX should use wp_ajax instead.
 *
 * @since 0.1.3
 */
final class SJEaAJAX {

	/**
	 * An array of registered action data.
	 *
	 * @since 0.1.3
	 * @access private
	 * @var array $actions
	 */
	static private $actions = array();

	/**
	 * Initializes hooks.
	 *
	 * @since 0.1.3
	 * @return void
	 */
	static public function init()
	{
		add_action( 'wp_ajax_sjea_add_subscriber', 'SJEaServices::add_subscriber' );
		add_action( 'wp_ajax_nopriv_sjea_add_subscriber', 'SJEaServices::add_subscriber' );
		add_action( 'wp_ajax_sjea_submit_support_form', 'SJEaServices::submit_support' );
		
		add_action( 'admin_init', __CLASS__ . '::run' );
	}

	/**
	 * Runs builder's frontend AJAX.
	 *
	 * @since 0.1.3
	 * @return void
	 */
	static public function run()
	{
		self::add_actions();
		self::call_action();
	}

	/**
	 * Adds a callable AJAX action.
	 *
	 * @since 0.1.3
	 * @param string $action The action name.
	 * @param string $method The method to call.
	 * @param array $args An array of method arg names that are present in the post data.
	 * @return void
	 */
	static public function add_action( $action, $method, $args = array() )
	{
		self::$actions[ $action ] = array(
			'action' => $action,
			'method' => $method,
			'args'	 => $args
		);
	}

	/**
	 * Removes an AJAX action.
	 *
	 * @since 0.1.3
	 * @param string $action The action to remove.
	 * @return void
	 */
	static public function remove_action( $action )
	{
		if ( isset( self::$actions[ $action ] ) ) {
			unset( self::$actions[ $action ] );
		}
	}

	/**
	 * Adds all callable AJAX actions.
	 *
	 * @since 0.1.3
	 * @access private
	 * @return void
	 */
	static private function add_actions()
	{
		// SJEaServices
		self::add_action( 'render_service_settings', 'SJEaServices::render_settings' );
		self::add_action( 'render_service_fields', 'SJEaServices::render_fields' );
		self::add_action( 'connect_service', 'SJEaServices::connect_service' );
		self::add_action( 'delete_service_account', 'SJEaServices::delete_account' );
		self::add_action( 'save_mailer_campaign', 'SJEaServices::save_campaign' );
		self::add_action( 'delete_mailer_campaign', 'SJEaServices::delete_campaign' );
	}

	/**
	 * Runs the current AJAX action.
	 *
	 * @since 0.1.3
	 * @access private
	 * @return void
	 */
	static private function call_action()
	{
		// Only run for logged in users.
		if ( ! is_user_logged_in() ) {
			return;
		}
		
		// Get the action.
		if ( ! empty( $_REQUEST['action'] ) ) {
			$action = $_REQUEST['action'];
		}
		else if( ! empty( $post_data['action'] ) ) {
			$action = $post_data['action'];
		}
		else {
			return;
		}
		
		// Allow developers to modify actions before they are called.
		do_action( 'sjea_ajax_before_call_action', $action );
		
		// Make sure the action exists.
		if ( ! isset( self::$actions[ $action ] ) ) {
			return;
		}
		
		// Get the action data.
		$action 	= self::$actions[ $action ];
		$args   	= array();
		$keys_args  = array();
		
		// Build the args array.
		foreach ( $action['args'] as $arg ) {
			$args[] = $keys_args[ $arg ] = isset( $post_data[ $arg ] ) ? $post_data[ $arg ] : null;
		}

		// Tell WordPress this is an AJAX request.
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}
		
		// Allow developers to hook before the action runs.
		do_action( 'sjea_ajax_before_' . $action['action'], $keys_args );

		// Call the action and allow developers to filter the result.
		$result = apply_filters( 'sjea_ajax_' . $action['action'], call_user_func_array( $action['method'], $args ), $keys_args );
		
		// Allow developers to hook after the action runs.
		do_action( 'sjea_ajax_after_' . $action['action'], $keys_args );
		
		// JSON encode the result.
		echo json_encode( $result );
		
		// Complete the request.
		die();
	}
}

SJEaAJAX::init();