<?php

defined( 'ABSPATH' ) or die();

class HTML_Special_Characters_Helper_Test extends WP_UnitTestCase {

	public function tearDown() {
		parent::tearDown();

		remove_filter( 'c2c_html_special_characters', array( $this, 'more_html_special_characters' ) );
		remove_filter( 'c2c_html_special_characters_helper_post_types', array( $this, 'filter_post_types' ) );
	}


	//
	//
	// DATA PROVIDERS
	//
	//


	//
	//
	// HELPER FUNCTIONS
	//
	//


	protected function get_currencies() {
		return array(
			'name'     => __( 'Currency', 'c2c_hsch' ),
			'&cent;'   => __( 'cent sign', 'c2c_hsch' ),
			'&pound;'  => __( 'British Pound', 'c2c_hsch' ),
			'&yen;'    => __( 'Japanese Yen', 'c2c_hsch' ),
			'&euro;'   => __( 'Euro symbol', 'c2c_hsch' ),
			'&fnof;'   => __( 'Dutch Florin symbol', 'c2c_hsch' ),
			'&curren;' => __( 'generic currency symbol', 'c2c_hsch' ),
		);
	}

	protected function get_accented_a() {
		return array(
			'name'     => 'Accented A',
			'&Agrave;' => 'A grave accent',
			'&Aacute;' => 'A accute accent',
			'&Acirc;'  => 'A circumflex',
			'&Atilde;' => 'A tilde',
			'&Auml;'   => 'A umlaut',
			'&Aring;'  => 'A ring',
			'&AElig;'  => 'AE ligature',
		);
	}

	// Add a new grouping of characters (accented 'A's).
	public function more_html_special_characters( $characters ) {
		$characters['accented_a'] = $this->get_accented_a();
		return $characters; // Important!
	}

	public function filter_post_types( $post_types ) {
		$post_types[] = 'filtered_post_type';

		unset( $post_types[ 'page' ] );

		return $post_types;
	}

	//
	//
	// TESTS
	//
	//


	public function test_class_name() {
		$this->assertTrue( class_exists( 'c2c_HTMLSpecialCharactersHelper' ) );
	}

	public function test_version() {
		$this->assertEquals( '2.2', c2c_HTMLSpecialCharactersHelper::version() );
	}

	public function test_get_default_html_special_characters_returns_all_categories_by_default() {
		$data = c2c_HTMLSpecialCharactersHelper::get_default_html_special_characters();

		$this->assertEquals(
			array( 'common', 'punctuation', 'currency', 'math', 'symbols', 'greek' ),
			array_keys( $data )
		);
	}

	public function test_html_special_characters_returns_all_categories_by_default() {
		$data = c2c_HTMLSpecialCharactersHelper::html_special_characters();

		$this->assertEquals(
			array( 'common', 'punctuation', 'currency', 'math', 'symbols', 'greek' ),
			array_keys( $data )
		);
	}

	public function test_html_special_characters_returns_special_characters_array() {
		$data = c2c_HTMLSpecialCharactersHelper::html_special_characters();

		$this->assertEquals( $this->get_currencies(), $data['currency'] );
	}

	public function test_get_default_html_special_characters_returns_specified_category() {
		$this->assertEquals( $this->get_currencies(), c2c_HTMLSpecialCharactersHelper::get_default_html_special_characters( 'currency' ) );
	}

	public function test_html_special_characters_returns_specified_category() {
		$this->assertEquals( $this->get_currencies(), c2c_HTMLSpecialCharactersHelper::html_special_characters( 'currency' ) );
	}

	public function test_get_default_html_special_characters_with_unknown_category_returns_empty_array() {
		$this->assertEmpty( c2c_HTMLSpecialCharactersHelper::get_default_html_special_characters( 'unknown' ) );
	}

	public function test_html_special_characters_with_unknown_category_returns_empty_array() {
		$this->assertEmpty( c2c_HTMLSpecialCharactersHelper::html_special_characters( 'unknown' ) );
	}

	public function test_adding_new_character_category_via_c2c_html_special_characters_filter() {
		add_filter( 'c2c_html_special_characters', array( $this, 'more_html_special_characters' ) );

		$data = c2c_HTMLSpecialCharactersHelper::html_special_characters();

		$this->assertTrue( array_key_exists( 'accented_a', $data ) );
		$this->assertEquals( $this->get_accented_a(), $data['accented_a'] );
	}

	public function test_hooks_action_admin_init() {
		$this->assertEquals( 10, has_action( 'admin_init', array( 'c2c_HTMLSpecialCharactersHelper', 'do_admin_init' ) ) );
	}

	/*
	 * get_post_types()
	 */

	public function test_supports_default_public_post_types() {
		$this->assertEquals( array( 'post', 'page' ), c2c_HTMLSpecialCharactersHelper::get_post_types() );
	}

	public function test_supports_public_custom_post_type() {
		register_post_type( 'sample', array( 'show_ui' => true ) );

		$this->assertEquals( array( 'post', 'page', 'sample' ), c2c_HTMLSpecialCharactersHelper::get_post_types() );

		unregister_post_type( 'sample' );
	}

	public function test_does_not_support_nonpublic_custom_post_type() {
		register_post_type( 'example', array( 'show_ui' => false ) );

		$this->assertEquals( array( 'post', 'page' ), c2c_HTMLSpecialCharactersHelper::get_post_types() );

		unregister_post_type( 'example' );
	}

	public function test_filter_c2c_html_special_characters_helper_post_types() {
		add_filter( 'c2c_html_special_characters_helper_post_types', array( $this, 'filter_post_types' ) );

		$this->assertEquals( array( 'post', 'filtered_post_type' ), c2c_HTMLSpecialCharactersHelper::get_post_types() );
	}

	/*
	 * TEST TODO:
	 * - JS is not enqueued on frontend
	 * - JS is enqueue on appropriate admin page(s)
	 * - JS is not enqueued on inappropriate admin page(s)
	 * - CSS is not enqueued on frontend
	 * - CSS is enqueue on appropriate admin page(s)
	 * - CSS is not enqueued on inappropriate admin page(s)
	 */
}
