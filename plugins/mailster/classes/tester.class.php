<?php

class MailsterTester {

	private $plugin_path;
	private $plugin_url;

	public function __construct() {

		$this->plugin_path = plugin_dir_path( MAILSTER_TESTER_FILE );
		$this->plugin_url  = plugin_dir_url( MAILSTER_TESTER_FILE );

		load_plugin_textdomain( 'mailster' );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_mailster_test', array( $this, 'ajax' ) );
	}


	public function ajax() {

		// verify nonce
		if ( ! wp_verify_nonce( $_POST['_nonce'], 'mailster_tester' ) ) {
			wp_send_json_error( array( 'message' => 'Nonce failed!' ) );
		}

		require_once $this->plugin_path . '/classes/tests.class.php';

		$test = new MailsterTests();

		$test_id = isset( $_POST['test_id'] ) ? $_POST['test_id'] : null;

		$success            = $test->run( $test_id );
		$return['message']  = $test->get_message();
		$return['nexttest'] = $test->get_next();
		$return['next']     = $test->nicename( $return['nexttest'] );
		$return['total']    = $test->get_total();
		$return['errors']   = $test->get_error_counts();
		$return['current']  = $test->get_current();
		$return['type']     = $test->get_current_type();

		if ( ! $success ) {
			wp_send_json_error( $return );
		}
		wp_send_json_success( $return );
	}


	public function test( $test = null ) {

		require_once $this->plugin_path . '/classes/tests.class.php';

		$testobj = new MailsterTests();
		if ( is_null( $test ) ) {
			return $testobj;
		}
		$testobj->run( $test );
		return $testobj->get();
	}


	public function admin_enqueue_scripts() {

		wp_enqueue_style( 'mailster-tester', $this->plugin_url . '/assets/style.css', array() );
		wp_enqueue_script( 'mailster-tester', $this->plugin_url . '/assets/script.js', array( 'jquery' ), false, true );

		$user = wp_get_current_user();

		wp_localize_script(
			'mailster-tester',
			'mailster_tester',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'mailster_tester' ),
				'fs'      => array(
					'plugin_id'  => mailster_freemius()->get_id(),
					'plan_id'    => mailster_freemius()->get_plan(),
					'public_key' => mailster_freemius()->get_public_key(),
					'coupon'     => 'WPTESTER',
					'user'       => array(
						'email' => $user->user_email,
						'first' => $user->user_firstname ? $user->user_firstname : $user->display_name,
						'last'  => $user->user_lastname,
					),
				),
			)
		);
	}


	public function admin_menu() {

		$page = add_menu_page( __( 'Mailster Tester', 'mailster' ), __( 'Mailster Tester', 'mailster' ), 'read', 'mailster-tester', array( $this, 'admin_page' ), 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2OTIuOCA2MTEuOSIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTQ3MS4xIDI0LjMgMzQ2LjQgMTc2LjcgMjIxLjcgMjQuM0gwdjU2OC4xaDE5NFYyNzMuN2wxNTIuNCAyMDcuOCAxNTIuNC0yMDcuOHYzMTguNmgxOTR2LTU2OEg0NzEuMXoiIGZpbGw9IiMyYmIyZTgiLz48L3N2Zz4=', 25 );

		add_action( 'load-' . $page, array( &$this, 'admin_enqueue_scripts' ) );
	}


	public function admin_page() {

		include $this->plugin_path . '/views/testpage.php';
	}
}
