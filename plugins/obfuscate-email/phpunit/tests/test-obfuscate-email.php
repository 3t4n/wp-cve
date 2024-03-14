<?php

defined( 'ABSPATH' ) or die();

class Obfuscate_Email_Test extends WP_UnitTestCase {

	protected $obj;

	public function setUp() {
		parent::setUp();

		$this->obj = c2c_ObfuscateEmail::instance();

		$this->obj->reset_options();
	}


	//
	//
	// DATA PROVIDERS
	//
	//


	public static function get_settings_and_defaults() {
		return array(
			array( 'encode_everything', true ),
			array( 'at_replace', '' ),
			array( 'dot_replace', '' ),
			array( 'use_text_direction', false ),
			array( 'use_display_none', true ),
		);
	}

	public static function get_default_filters() {
		return array(
			array( 'link_description' ),
			array( 'link_notes' ),
			array( 'bloginfo' ),
			array( 'nav_menu_description' ),
			array( 'term_description' ),
			array( 'the_title' ),
			array( 'the_content' ),
			array( 'get_the_excerpt' ),
			array( 'comment_text' ),
			array( 'list_cats' ),
			array( 'widget_text' ),
			array( 'the_author_email' ),
			array( 'get_comment_author_email' ),
		);
	}

	public static function get_default_hooks() {
		$hooks = array(
			array( 'action', 'wp_head', 'add_css' ),
		);

		foreach ( self::get_default_filters() as $filter ) {
			$hooks[] = array( 'filter', $filter[0], 'obfuscate_email', 15 );
		}

		return $hooks;
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	private function set_option( $settings = array() ) {
		$defaults = array(
			'encode_everything'  => true,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => true,
		);
		$settings = wp_parse_args( $settings, $defaults );
		$this->obj->update_option( $settings, true );
	}


	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_ObfuscateEmail' ) );
	}

	public function test_get_version() {
		$this->assertEquals( '3.8.1', $this->obj->version() );
	}

	public function test_plugin_framework_class_name() {
		$this->assertTrue( class_exists( 'c2c_Plugin_061' ) );
	}

	public function test_plugin_framework_version() {
		$this->assertEquals( '061', $this->obj->c2c_plugin_version() );
	}

	public function test_instance_object_is_returned() {
		$this->assertTrue( is_a( $this->obj, 'c2c_ObfuscateEmail' ) );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_ObfuscateEmail', 'instance' ) ) );
	}

	/**
	 * @dataProvider get_default_hooks
	 */
	public function test_default_hooks( $hook_type, $hook, $function, $priority = 10, $class_method = true ) {
		$callback = $class_method ? array( $this->obj, $function ) : $function;

		$prio = $hook_type === 'action' ?
			has_action( $hook, $callback ) :
			has_filter( $hook, $callback );

		$this->assertNotFalse( $prio );
		if ( $priority ) {
			$this->assertEquals( $priority, $prio );
		}
	}

	public function test_setting_name() {
		$this->assertEquals( 'c2c_obfuscate_email', c2c_ObfuscateEmail::SETTING_NAME );
	}

	/**
	 * @dataProvider get_default_filters
	 */
	public function test_obfuscation_applies_to_default_filters( $filter ) {
		$email = 'test@example.com';
		$expected = '&#x74;&#x65;&#x73;&#x74;&#x40;<span class="oe_displaynone">null</span>&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;';

		$this->assertEquals( $expected, c2c_obfuscate_email( $email ) );
	}

	/**
	 * @dataProvider get_settings_and_defaults
	 */
	public function test_default_settings( $setting, $value ) {
		$options = $this->obj->get_options();

		if ( is_bool( $value ) ) {
			if ( $value ) {
				$this->assertTrue( $options[ $setting ] );
			} else {
				$this->assertFalse( $options[ $setting ] );
			}
		} else {
			$this->assertEmpty( $options[ $setting ] );
		}
	}

	public function test_at_and_dot_default_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals( 'test&#064;example&#046;com', c2c_obfuscate_email( 'test@example.com' ) );
	}

	public function test_custom_at_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => 'AT',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals( 'testATexample&#046;com', c2c_obfuscate_email( 'test@example.com' ) );
	}

	public function test_custom_dot_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => 'DOT',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals( 'test&#064;exampleDOTcom', c2c_obfuscate_email( 'test@example.com' ) );
	}

	public function test_text_direction() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => true,
			'use_display_none'   => false,
		) );

		$this->assertEquals(
			'<span class="oe_textdirection">moc&#046;elpmaxe&#064;tset</span>',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_display_none() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => true,
		) );

		$this->assertEquals(
			'test&#064;<span class="oe_displaynone">null</span>example&#046;com',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_display_none_and_text_direction_and_at_and_dot_replace() {
		$this->set_option( array(
			'encode_everything'  => false,
			'at_replace'         => 'AT',
			'dot_replace'        => 'DOT',
			'use_text_direction' => true,
			'use_display_none'   => true,
		) );

		$this->assertEquals(
			'<span class="oe_textdirection">mocTODelpmaxe<span class="oe_displaynone">null</span>TAtset</span>',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_encode_everything() {
		$this->set_option( array(
			'encode_everything'  => true,
			'at_replace'         => '',
			'dot_replace'        => '',
			'use_text_direction' => false,
			'use_display_none'   => false,
		) );

		$this->assertEquals(
			'&#x74;&#x65;&#x73;&#x74;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	public function test_everything_enabled() {
		$this->set_option( array(
			'encode_everything'  => true,
			'at_replace'         => 'AT',
			'dot_replace'        => 'DOT',
			'use_text_direction' => true,
			'use_display_none'   => true,
		) );

		$this->assertEquals(
			'<span class="oe_textdirection">&#x6d;&#x6f;&#x63;&#x2e;&#x65;&#x6c;&#x70;&#x6d;&#x61;&#x78;&#x65;<span class="oe_displaynone">null</span>&#x40;&#x74;&#x73;&#x65;&#x74;</span>',
			c2c_obfuscate_email( 'test@example.com' )
		);
	}

	/*
	 * add_css()
	 */

	public function test_add_css( $attr = '', $support_html5 = true ) {
		if ( $support_html5 ) {
			add_theme_support( 'html5' );
		}

		$expected = "<style{$attr}>
	span.oe_textdirection { unicode-bidi: bidi-override; direction: rtl; }
	span.oe_displaynone { display: none; }
</style>\n";

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', $this->obj->add_css() );
	}

	public function test_add_css_with_no_html5_support() {
		remove_theme_support( 'html5' );

		$this->test_add_css( ' type="text/css"', false );
	}

	/*
	 * Setting handling
	 */

	public function test_does_not_immediately_store_default_settings_in_db() {
		$option_name = c2c_ObfuscateEmail::SETTING_NAME;
		// Get the options just to see if they may get saved.
		$options     = $this->obj->get_options();

		$this->assertFalse( get_option( $option_name ) );
	}

	public function test_uninstall_deletes_option() {
		$option_name = c2c_ObfuscateEmail::SETTING_NAME;
		$options     = $this->obj->get_options();

		// Explicitly set an option to ensure options get saved to the database.
		$this->set_option( array( 'at_replace' => '(AT)' ) );

		$this->assertNotEmpty( $options );
		$this->assertNotFalse( get_option( $option_name ) );

		c2c_ObfuscateEmail::uninstall();

		$this->assertFalse( get_option( $option_name ) );
	}



	/***
	 * ALL ADMIN AREA RELATED TESTS NEED TO FOLLOW THIS FUNCTION
	 ***/



	public function test_turn_on_admin() {
		if ( ! defined( 'WP_ADMIN' ) ) {
			define( 'WP_ADMIN', true );
		}

		// Unhook existing hooks. Then re-register them.
		// Necessary due to the singleton nature of the object and where the
		// is_admin() occurs.
		foreach ( $this->get_default_filters() as $filter ) {
			remove_filter( $filter[0], array( $this->obj, 'obfuscate_email' ), 15 );
		}
		remove_action( 'wp_head', array( $this->obj, 'add_css' ) );

		$this->obj->register_filters();

		$this->assertTrue( is_admin() );
	}

	/**
	 * @dataProvider get_default_filters
	 */
	public function test_does_not_hook_default_filters_in_admin( $filter ) {
		$this->test_turn_on_admin();

		$this->assertFalse( has_filter( $filter, array( $this->obj, 'obfuscate_email' ) ) );
	}

	public function test_nothing_obfuscated_in_admin_even_with_everything_enabled() {
		$this->set_option( array(
			'encode_everything'  => true,
			'at_replace'         => 'AT',
			'dot_replace'        => 'DOT',
			'use_text_direction' => true,
			'use_display_none'   => true,
		) );

		$this->test_turn_on_admin();

		$text = 'test@example.com';

		$this->assertEquals(
			wpautop( $text ),
			apply_filters( 'the_content', $text )
		);
	}

}
