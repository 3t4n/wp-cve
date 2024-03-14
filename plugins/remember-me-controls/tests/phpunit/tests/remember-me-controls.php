<?php

defined( 'ABSPATH' ) or die();

class Remember_Me_Controls_Test extends WP_UnitTestCase {

	public $default_duration = 3 * DAY_IN_SECONDS;
	protected $obj;

	public static function setUpBeforeClass() {
		c2c_RememberMeControls::get_instance()->install();
	}

	public function setUp() {
		parent::setUp();

		$this->obj = c2c_RememberMeControls::get_instance();

		add_theme_support( 'html5', array( 'script', 'style' ) );
	}

	public function tearDown() {
		parent::tearDown();

		// Reset options
		$this->obj->reset_options();
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	protected function set_current_screen( $screen = '' ) {
		if ( ! $screen ) {
			$screen = 'options-general.php?page=remember-me-controls%2Fremember-me-controls.php';
		}

		set_current_screen( $screen );
	}

	//
	//
	// DATA PROVIDERS
	//
	//


	public static function get_default_hooks() {
		return array(
			array( 'action', 'auth_cookie_expiration',               'auth_cookie_expiration' ),
			array( 'action', 'admin_head',                           'add_admin_js' ),
			array( 'action', 'login_head',                           'add_css' ),
			array( 'filter', 'login_footer',                         'add_js' ),
			array( 'action', 'remember_me_controls__post_display_option', 'maybe_add_hr' ),
			array( 'filter', 'login_form_defaults',                  'login_form_defaults' ),
			array( 'action', 'bp_before_login_widget_loggedout',     'add_css' ),
			array( 'action', 'bp_after_login_widget_loggedout',      'add_js' ),
			array( 'filter', 'pre_option_login_afo_rem',             '__return_empty_string' ),
			array( 'filter', 'sidebar_login_widget_form_args',       'compat_for_sidebar_login' ),
			array( 'action', 'wp_ajax_sidebar_login_process',        'compat_for_sidebar_login_ajax_handler', 1 ),
			array( 'action', 'wp_ajax_nopriv_sidebar_login_process', 'compat_for_sidebar_login_ajax_handler', 1 ),
		);
	}

	public static function get_seconds_to_human_string() {
		return array(
			array( 1 * YEAR_IN_SECONDS, '1 year' ),
			array( 2 * YEAR_IN_SECONDS, '2 years' ),
			array( 5 * YEAR_IN_SECONDS, '5 years' ),
			array( 1 * MONTH_IN_SECONDS, '1 month' ),
			array( 5 * MONTH_IN_SECONDS, '5 months' ),
			array( 1 * DAY_IN_SECONDS, '1 day' ),
			array( 7 * DAY_IN_SECONDS, '7 days' ),
			array( 1 * HOUR_IN_SECONDS, '1 hour' ),
			array( 7 * HOUR_IN_SECONDS, '7 hours' ),
			array( 4 * YEAR_IN_SECONDS + 5 * MONTH_IN_SECONDS + 14 * DAY_IN_SECONDS, '4 years, 5 months, 14 days' ),
			array( 1 * YEAR_IN_SECONDS + 15 * DAY_IN_SECONDS, '1 year, 15 days' ),
			array( 5 * MONTH_IN_SECONDS + 24 * DAY_IN_SECONDS, '5 months, 24 days' ),
			array( 2 * DAY_IN_SECONDS + 5 * HOUR_IN_SECONDS, '2 days, 5 hours' ),
			array( 0, '' ),
			array( '', '' ),
			array( false, '' ),
		);
	}

	//
	//
	// HELPER FUNCTIONS
	//
	//


	protected function set_option( $settings = array() ) {
		$defaults = $this->obj->get_options();
		$settings = wp_parse_args( (array) $settings, $defaults );
		$this->obj->update_option( $settings, true );
	}

	protected function get_javascript() {
		return <<<JS
		<script>
			const rememberme_checkbox = document.getElementById('rememberme');
			if ( null !== rememberme_checkbox ) {
				rememberme_checkbox.checked = true;
			}
		</script>

JS;
	}


	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_RememberMeControls' ) );
	}

	public function test_plugin_framework_class_name() {
		$this->assertTrue( class_exists( 'c2c_Plugin_065' ) );
	}

	public function test_plugin_framework_version() {
		$this->assertEquals( '065', $this->obj->c2c_plugin_version() );
	}

	public function test_get_version() {
		$this->assertEquals( '2.0.1', $this->obj->version() );
	}

	public function test_instance_object_is_returned() {
		$this->assertTrue( is_a( $this->obj, 'c2c_RememberMeControls' ) );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_RememberMeControls', 'get_instance' ) ) );
	}

	public function test_setting_name() {
		$this->assertEquals( 'c2c_remember_me_controls', c2c_RememberMeControls::SETTING_NAME );
	}

	/**
	 * @dataProvider get_default_hooks
	 */
	public function test_default_hooks( $hook_type, $hook, $function, $priority = 10 ) {
		$callback = 0 === strpos( $function, '__' ) ? $function : array( $this->obj, $function );

		$prio = $hook_type === 'action' ?
			has_action( $hook, $callback ) :
			has_filter( $hook, $callback );
		$this->assertNotFalse( $prio );
		if ( $priority ) {
			$this->assertEquals( $priority, $prio );
		}
	}

	public function test_option_default_for_auto_remember_me() {
		$this->assertFalse( $this->obj->get_options()['auto_remember_me'] );
	}

	public function test_option_default_for_remember_me_forever() {
		$this->assertFalse( $this->obj->get_options()['remember_me_forever'] );
	}

	public function test_option_default_for_remember_me_duration() {
		$this->assertEmpty( $this->obj->get_options()['remember_me_duration'] );
	}

	public function test_option_default_for_disable_remember_me() {
		$this->assertFalse( $this->obj->get_options()['disable_remember_me'] );
	}

	/*
	 * options_page_description()
	 */

	// Note that this does not test all of the actual text content, just the start.
	public function test_options_page_description() {
		// Must match the start of the content at the very least.
		$expected = '<h1>Remember Me Controls Settings</h1>' . "\n";
		$expected .= '<p class="see-help">See the "Help" link to the top-right of the page for more help.</p>' . "\n";
		$expected .= '<p>Take control of the "Remember Me" login feature for WordPress';

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '~', $this->obj->options_page_description() );
	}

	public function test_options_page_description_includes_duration_banner() {
		$this->set_option( array( 'remember_me_duration' => 2 * YEAR_IN_SECONDS / 3600 ) );
		$expected = '<div class="c2c-remember-me-duration-banner">Currently, a remembered user login session will last up to <strong>2 years</strong>.</div>' . "\n";

		$this->expectOutputRegex( '~' . preg_quote( $expected ) . '$~', $this->obj->options_page_description() );
	}

	/*
	 * get_max_login_duration()
	 */

	public function test_get_max_login_duration() {
		$this->assertEquals( 100 * YEAR_IN_SECONDS, $this->obj->get_max_login_duration() );
	}

	/*
	 * get_min_login_duration()
	 */

	public function test_get_min_login_duration() {
		$this->assertEquals( HOUR_IN_SECONDS, $this->obj->get_min_login_duration() );
	}

	/*
	 * get_default_login_duration()
	 */

	public function test_get_default_login_duration() {
		$this->assertEquals( 2 * DAY_IN_SECONDS, $this->obj->get_default_login_duration() );
	}

	/*
	 * get_default_remembered_login_duration()
	 */

	 public function get_default_remembered_login_duration() {
		$this->assertEquals( 14 * DAY_IN_SECONDS, $this->obj->get_default_remembered_login_duration() );
	}

	/*
	 * help_tabs_content()
	 */

	// Note that this does not test the full text content of the tab, just the start.
	public function test_help_tabs_content() {
		$this->set_current_screen( 'edit' );
		$screen = get_current_screen();
		$tab = 'remember-me-controls-about';
		// Must match the start of the content at the very least.
		$expected_content = '<p>Take control of the "Remember Me" login feature for WordPress by customizing its behavior or disabling it altogether.';

		$this->obj->help_tabs_content( $screen );
		$help_tab = $screen->get_help_tab( $tab );

		$this->assertEquals( $tab, $help_tab['id'] );
		$this->assertEquals( 'About', $help_tab['title'] );
		$this->assertFalse( $help_tab['callback'] );
		$this->assertEquals( 10, $help_tab['priority'] );
		$this->assertEquals( 1, preg_match( '~^' . preg_quote( $expected_content ) . '~', $help_tab['content'] ) );

		$tabs = $screen->get_help_tabs();
		$this->assertArrayHasKey( $tab, $tabs );
	}

	/*
	 * add_css()
	 */

	public function test_add_css_if_remember_me_disabled() {
		$this->set_option( array( 'disable_remember_me' => true ) );
		$expected = '<style>.forgetmenot { display:none; }</style>' . "\n";

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', $this->obj->add_css() );
	}

	public function test_add_css_if_remember_me_disabled_no_html5_support() {
		remove_theme_support( 'html5', 'style' );
		$this->set_option( array( 'disable_remember_me' => true ) );
		$expected = '<style>.forgetmenot { display:none; }</style>' . "\n";

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', $this->obj->add_css() );
	}

	public function test_add_css_if_remember_me_not_disabled() {
		$this->set_option( array( 'disable_remember_me' => false ) );

		$this->expectOutputRegex( '~^$~', $this->obj->add_css() );
	}

	/*
	 * add_js()
	 */

	public function test_add_js_if_auto_remember_me_but_not_disable_remember_me() {
		$this->set_option( array( 'auto_remember_me' => true, 'disable_remember_me' => false ) );

		$this->expectOutputRegex( '~^' . preg_quote( $this->get_javascript() ) . '$~', $this->obj->add_js() );
	}

	public function test_add_js_if_not_auto_remember_me_but_disable_remember_me() {
		$this->set_option( array( 'auto_remember_me' => false, 'disable_remember_me' => true ) );

		$this->expectOutputRegex( '~^$~', $this->obj->add_js() );
	}

	public function test_add_js_if_not_auto_remember_me_and_not_disable_remember_me() {
		$this->set_option( array( 'auto_remember_me' => false, 'disable_remember_me' => false ) );

		$this->expectOutputRegex( '~^$~', $this->obj->add_js() );
	}

	public function test_add_js_if_auto_remember_me_and_disable_remember_me() {
		$this->set_option( array( 'auto_remember_me' => true, 'disable_remember_me' => true ) );

		$this->expectOutputRegex( '~^$~', $this->obj->add_js() );
	}

	/*
	 * auth_cookie_expiration()
	 */

	public function test_auth_cookie_expiration_is_unaffected_if_plugin_not_configured() {
		$this->assertEquals( $this->default_duration, $this->obj->auth_cookie_expiration( $this->default_duration, 1, false ) );
	}

	public function test_auth_cookie_expiration_by_default_result_in_default_expiration() {
		$this->assertEquals( 2 * DAY_IN_SECONDS, $this->obj->auth_cookie_expiration( 0, 1, true ) );
	}

	public function test_auth_cookie_expiration_by_default_with_invalid_user_still_results_in_default_expiration() {
		$this->assertEquals( 2 * DAY_IN_SECONDS, $this->obj->auth_cookie_expiration( 0, 0, true ) );
	}

	public function test_auth_cookie_expiration_by_default_result_in_minimum_expiration() {
		$this->assertEquals( 1 * HOUR_IN_SECONDS, $this->obj->auth_cookie_expiration( 0.5, 1, true ) );
	}

	public function test_auth_cookie_expiration_one_hour_is_valid() {
		$this->assertEquals( 1 * HOUR_IN_SECONDS, $this->obj->auth_cookie_expiration( 1, 1, true ) );
	}

	public function test_auth_cookie_expiration_by_default() {
		$remembered_duration = 14 * DAY_IN_SECONDS;
		$this->assertEquals( $remembered_duration, $this->obj->auth_cookie_expiration( $remembered_duration, 1, true ) );
	}

	public function test_auth_cookie_expiration_is_unaffected_if_remember_me_not_checked() {
		$this->set_option( array( 'remember_me_forever' => true, 'remember_me_duration' => 27 ) );
		$this->assertEquals( $this->default_duration, $this->obj->auth_cookie_expiration( $this->default_duration, 1, false ) );
	}

	public function test_auth_cookie_expiration_if_remember_me_forever() {
		$this->set_option( array( 'remember_me_forever' => true ) );
		$this->assertEquals( 100 * YEAR_IN_SECONDS, $this->obj->auth_cookie_expiration( $this->default_duration, 1, true ) );
	}

	public function test_auth_cookie_expiration_remember_me_forever_has_priority_over_remember_me_duration() {
		$this->set_option( array( 'remember_me_forever' => true, 'remember_me_duration' => 200 ) );
		$this->assertEquals( 100 * YEAR_IN_SECONDS, $this->obj->auth_cookie_expiration( $this->default_duration, 1, true ) );
	}

	public function test_auth_cookie_expiration_if_remember_me_duration() {
		$this->set_option( array( 'remember_me_duration' => 24 * 21 ) );
		$this->assertEquals( 24 * 21 * HOUR_IN_SECONDS, $this->obj->auth_cookie_expiration( $this->default_duration, 1, true ) );
	}

	public function test_auth_cookie_expiration_remember_me_duration_does_not_exceed_max() {
		$this->set_option( array( 'remember_me_duration' => 24 * 365 * 101 ) ); // 101 years
		$this->assertEquals( 100 * YEAR_IN_SECONDS, $this->obj->auth_cookie_expiration( $this->default_duration, 1, true ) );
	}

	public function test_auth_cookie_expiration_remember_me_duration_of_0_result_in_default_expiration() {
		$this->set_option( array( 'remember_me_duration' => 0 ) );
		$this->assertEquals( $this->default_duration, $this->obj->auth_cookie_expiration( $this->default_duration, 1, true ) );
	}

	public function test_auth_cookie_expiration_if_remember_unchecked_and_disable_remember_me() {
		$this->set_option( array( 'disable_remember_me' => true ) );
		$this->assertEquals( 2 * DAY_IN_SECONDS, $this->obj->auth_cookie_expiration( $this->default_duration, 1, false ) );
	}

	public function test_auth_cookie_expiration_if_remember_checked_and_disable_remember_me() {
		$this->set_option( array( 'disable_remember_me' => true ) );
		$this->assertEquals( 2 * DAY_IN_SECONDS, $this->obj->auth_cookie_expiration( $this->default_duration, 1, true ) );
	}

	public function test_auth_cookie_expiration_if_remember_checked_that_disable_remember_me_has_top_priority() {
		$this->set_option( array( 'disable_remember_me' => true, 'remember_me_forever' => true, 'remember_me_duration' => 33 ) );
		$this->assertEquals( 2 * DAY_IN_SECONDS, $this->obj->auth_cookie_expiration( $this->default_duration, 1, true ) );
	}

	/*
	 * maybe_add_hr()
	 */

	public function test_maybe_add_hr_for_remember_me_duration() {
		$expected = "</tr><tr><td colspan='2'><div class='hr'>&nbsp;</div></td>\n";

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', $this->obj->maybe_add_hr( 'remember_me_duration' ) );
	}

	public function test_maybe_add_hr_for_something_other_than_remember_me_duration() {
		$this->expectOutputRegex( '~^$~', $this->obj->maybe_add_hr( 'disable_remember_me' ) );
	}

	/*
	 * login_form_defaults()
	 */

	public function test_login_form_defaults_unaffected_by_default( $filter = 'login_form_defaults' ) {
		$defaults = array(
			'remember'       => true,
			'value_remember' => false,
		);

		$this->assertEquals( $defaults, apply_filters( $filter, $defaults ) );
	}

	public function test_login_form_defaults_with_disable_remember_me( $filter = 'login_form_defaults' ) {
		$defaults = array(
			'remember'       => true,
			'value_remember' => false,
		);

		$this->set_option( array( 'disable_remember_me' => true ) );

		$new_defaults = apply_filters( $filter, $defaults );

		$this->assertFalse( $new_defaults['remember'] );
		$this->assertFalse( $new_defaults['value_remember'] );
	}

	public function test_login_form_defaults_with_auto_remember_me( $filter = 'login_form_defaults' ) {
		$defaults = array(
			'remember'       => true,
			'value_remember' => false,
		);

		$this->set_option( array( 'auto_remember_me' => true ) );

		$new_defaults = apply_filters( $filter, $defaults );

		$this->assertTrue( $new_defaults['remember'] );
		$this->assertTrue( $new_defaults['value_remember'] );
	}

	public function test_login_form_defaults_with_both( $filter = 'login_form_defaults' ) {
		$defaults = array(
			'remember'       => true,
			'value_remember' => false,
		);

		$this->set_option( array( 'auto_remember_me' => true, 'disable_remember_me' => true ) );

		$new_defaults = apply_filters( $filter, $defaults );

		$this->assertFalse( $new_defaults['remember'] );
		$this->assertFalse( $new_defaults['value_remember'] );
	}

	/*
	 * humanize_seconds()
	 */

	/**
	 * @dataProvider get_seconds_to_human_string
	 */
	public function test_humanize_seconds( $seconds, $human_string ) {
		$this->assertEquals(
			$human_string,
			$this->obj->humanize_seconds( $seconds )
		);

	}

	/*
	 * get_login_session_duration()
	 */

	public function test_get_login_session_duration_default() {
		$this->assertEquals( '2 days', $this->obj->get_login_session_duration( false ) );
	}

	public function test_get_login_session_duration_remembered_default() {
		$this->assertEquals( '14 days', $this->obj->get_login_session_duration( true ) );
	}

	public function test_get_login_session_duration() {
		$this->set_option( array( 'remember_me_forever' => true ) );
		$this->assertEquals( '100 years', $this->obj->get_login_session_duration() );
	}

	/**
	 * @dataProvider get_seconds_to_human_string
	 */
	public function test_get_login_session_duration_with_duration( $seconds, $time_string ) {
		if ( ! $seconds ) {
			$time_string = '2 days';
			$seconds = 0;
		}
		$hours = $seconds / 60 / 60;
		$this->set_option( array( 'remember_me_duration' => $hours ) );

		$this->assertEquals( $time_string, $this->obj->get_login_session_duration() );
	}

	/**
	 * @dataProvider get_seconds_to_human_string
	 */
	public function test_get_login_session_duration_with_duration_and_remembered( $seconds, $time_string ) {
		if ( ! $seconds ) {
			$time_string = '14 days';
			$seconds = 0;
		}
		$hours = $seconds / 60 / 60;
		$this->set_option( array( 'remember_me_duration' => $hours ) );

		$this->assertEquals( $time_string, $this->obj->get_login_session_duration( true ) );
	}

	/*
	 * display_current_login_duration()
	 */

	/**
	 * @dataProvider get_seconds_to_human_string
	 */
	public function test_display_current_login_duration( $seconds, $time_string ) {
		// Invalid seconds will default to the default time.
		if ( ! $seconds ) {
			$seconds = 2 * DAY_IN_SECONDS;
			$time_string = '2 days';
		}

		$this->set_option( array( 'remember_me_duration' => $seconds / 3600 ) );
		$expected = sprintf(
			'<div class="c2c-remember-me-duration-banner">Currently, a remembered user login session will last up to <strong>%s</strong>.</div>' . "\n",
			$time_string
		);

		$this->expectOutputRegex( '~' . preg_quote( $expected ) . '$~', $this->obj->display_current_login_duration() );
	}

	/*
	 * Compatibility with Sidebar Login plugin.
	 */

	public function test_sidebar_login_widget_form_args() {
		$this->test_login_form_defaults_unaffected_by_default( 'sidebar_login_widget_form_args' );
		$this->obj->reset_options();
		$this->test_login_form_defaults_with_disable_remember_me( 'sidebar_login_widget_form_args' );
		$this->obj->reset_options();
		$this->test_login_form_defaults_with_auto_remember_me( 'sidebar_login_widget_form_args' );
		$this->obj->reset_options();
		$this->test_login_form_defaults_with_both( 'sidebar_login_widget_form_args' );
	}

	public function test_compat_for_sidebar_login_ajax_handler_by_default() {
		$_POST['remember'] = true;
		do_action( 'wp_ajax_sidebar_login_process' );

		$this->assertTrue( $_POST['remember'] );
	}

	public function test_compat_for_sidebar_login_ajax_handler() {
		$this->set_option( array( 'disable_remember_me' => true ) );
		$_POST['remember'] = true;
		do_action( 'wp_ajax_sidebar_login_process' );

		$this->assertFalse( isset( $_POST['remember'] ) );
	}

	/*
	 * Compatibility with Login Widget With Shortcode plugin
	 */

	public function test_compat_for_login_widget_with_shortcode() {
		$option = 'login_afo_rem';
		update_option( $option, 'Yes' );

		$this->assertNotEquals( 'Yes', get_option( $option ) );
	}

	/*
	 * Setting handling
	 */

	public function test_does_not_immediately_store_default_settings_in_db() {
		$option_name = c2c_RememberMeControls::SETTING_NAME;
		// Get the options just to see if they may get saved.
		$options     = $this->obj->get_options();

		$this->assertFalse( get_option( $option_name ) );
	}

	public function test_uninstall_deletes_option() {
		$option_name = c2c_RememberMeControls::SETTING_NAME;
		$options     = $this->obj->get_options();

		// Explicitly set an option to ensure options get saved to the database.
		$this->set_option( array( 'auto_remember_me' => '1' ) );

		$this->assertNotEmpty( $options );
		$this->assertNotFalse( get_option( $option_name ) );

		c2c_RememberMeControls::uninstall();

		$this->assertFalse( get_option( $option_name ) );
	}

	/*
	 * add_admin_js()
	 */

	/**
	 * @expectedIncorrectUsage c2c_Plugin_065::is_plugin_admin_page
	 */
	public function test_add_admin_js_does_not_add_js_generally() {
		$this->expectOutputRegex( '~^$~', $this->obj->add_admin_js() );
	}

	public function test_add_admin_js_does_not_add_js_on_non_plugin_settings_admin_page() {
		global $wp_actions;
		// Mock the fact that the plugin's setting page is currently loaded.
		if ( ! defined( 'WP_ADMIN' ) ) {
			define( 'WP_ADMIN', true );
		}
		$wp_actions['admin_init'] = 1;
		$this->set_current_screen( 'edit' );
		$this->obj->options_page = 'edit';

		$this->expectOutputRegex( '~^$~', $this->obj->add_admin_js() );
	}

	public function test_add_admin_js_adds_js_to_plugin_settings_page() {
		global $wp_actions;
		// Mock the fact that the plugin's setting page is currently loaded.
		if ( ! defined( 'WP_ADMIN' ) ) {
			define( 'WP_ADMIN', true );
		}
		$wp_actions['admin_init'] = 1;
		$this->set_current_screen();
		$this->obj->options_page = 'options-generalphppageremember-me-controls2fremember-me-controls';

		$this->expectOutputRegex( '~<script>.+</script>~s', $this->obj->add_admin_js() );
	}

}
