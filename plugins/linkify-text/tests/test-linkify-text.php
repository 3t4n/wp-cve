<?php

defined( 'ABSPATH' ) or die();

class Linkify_Text_Test extends WP_UnitTestCase {

	protected static $text_to_link = array(
		'coffee2code'    => 'http://coffee2code.com',
		'Matt Mullenweg' => 'https://ma.tt',
		'BuddyPress'     => 'buddypress.org',
		'my posts'       => '/authors/scott',
		'my readme'      => 'readme.html',
		'Scott Reilly'   => ':coffee2code',
		'c2c'            => ':coffee2code',
		'me'             => ':c2c',
		'ref dne'        => ':nonexistent',
		'not a link'     => 'some text',
		'blank'          => '',
		'Cocktail glacé' => 'http://www.domain.com/cocktail-glace.html',
		'AT&T'           => 'http://att.com',
		'漢字はユニコード'  => 'http://php.net/manual/en/ref.mbstring.php',
		'Apple iPhone 6' => 'http://example.com/apple1',
		'iPhone 6'       => 'http://example.com/aople2',
		'test'           => 'http://example.com/test1',
		'test place'     => 'http://example.com/test2',
		'example.com'    => 'http://example.com',
		'wonder'         => 'https://example.com || Example title',
		'bác sĩ thú y'   => 'https://www.bacsithuy.org',
	);

	public static function setUpBeforeClass() {
		c2c_LinkifyText::get_instance()->install();
	}

	public function setUp() {
		parent::setUp();
		c2c_LinkifyText::get_instance()->reset_options();
		$this->set_option();
	}

	public function tearDown() {
		parent::tearDown();

		// Reset options
		c2c_LinkifyText::get_instance()->reset_options();

		remove_filter( 'c2c_linkify_text',                array( $this, 'add_text_to_linkify' ) );
		remove_filter( 'c2c_linkify_text_replace_once',   '__return_true' );
		remove_filter( 'c2c_linkify_text_case_sensitive', '__return_true' );
		remove_filter( 'c2c_linkify_text_comments',       '__return_true' );
		remove_filter( 'c2c_linkify_text_open_new_window','__return_true' );
		remove_filter( 'c2c_linkify_text_filters',        array( $this, 'add_custom_filter' ) );
		remove_filter( 'c2c_linkify_text_linked_text',    array( $this, 'add_title_attribute_to_linkified_text' ), 10, 4 );
		remove_filter( 'c2c_linkify_text_linked_text',    array( $this, 'disable_linking' ), 10, 3 );
		remove_filter( 'c2c_linkify_text_linked_text',    array( $this, 'selectively_disable_text_linkification' ), 10, 4 );
		remove_filter( 'c2c_linkify_text_link_attrs',     array( $this, 'my_linkify_text_attrs' ), 10, 3 );
		remove_filter( 'c2c_linkify_text_link_attrs',     array( $this, 'text_link_attrs' ), 10, 3 );
		remove_filter( 'c2c_linkify_text_link_attrs',     array( $this, 'add_title_attr_to_linkified_text' ), 10, 3 );
	}


	//
	//
	// DATA PROVIDERS
	//
	//


	public static function get_default_filters() {
		return array(
			array( 'the_content' ),
			array( 'the_excerpt' ),
			array( 'widget_text' ),
		);
	}

	public static function get_comment_filters() {
		return array(
			array( 'get_comment_text' ),
			array( 'get_comment_excerpt' ),
		);
	}

	public static function get_text_to_link() {
		return array_map( function($v) { return array( $v ); }, array_keys( self::$text_to_link ) );
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	protected function set_option( $settings = array() ) {
		$defaults = array(
			'text_to_link' => self::$text_to_link,
		);
		$settings = wp_parse_args( $settings, $defaults );
		c2c_LinkifyText::get_instance()->update_option( $settings, true );
	}

	protected function linkify_text( $text ) {
		return c2c_LinkifyText::get_instance()->linkify_text( $text );
	}

	protected function expected_link( $text, $link = '' ) {
		if ( ! $link && isset( self::$text_to_link[ $text ] ) ) {
			$link = self::$text_to_link[ $text ];
		}

		return '<a href="' . $link . '">' . $text . '</a>';
	}

	public function add_text_to_linkify( $text_to_link ) {
		$text_to_link = (array) $text_to_link;
		$text_to_link['bbPress'] = 'https://bbpress.org';
		return $text_to_link;
	}

	public function add_custom_filter( $filters ) {
		$filters[] = 'custom_filter';
		return $filters;
	}

	// Taken from example in readme.txt.
	public function add_title_attribute_to_linkified_text( $display_link, $old_text, $link_for_text, $text_to_link ) {
		// The string that you chose to separate the link URL and the title attribute text.
		$separator = ' || ';

		// Only change the linked text if a title has been defined
		if ( false !== strpos( $link_for_text, $separator ) ) {
			// Get the link and title that was defined for the text to be linked.
			list( $link, $title ) = explode( $separator, $link_for_text, 2 );

			// Make the link the way you want.
			$display_link = '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '">' . $old_text . '</a>';
		}

		return $display_link;
	}

	public function disable_linking( $display_link, $text_to_link, $link_for_text  ) {
		return $text_to_link;
	}

	// Taken from example in readme.txt.
	public function my_linkify_text_attrs( $attrs, $old_text, $link ) {
		$attrs['target'] = '_blank';
		return $attrs;
	}

	public function text_link_attrs( $attrs, $old_text, $link ) {
		$attrs['href'] = strtr( $attrs['href'], array( 'http://' => 'https://' ) );
		$attrs['target'] = '_blank';
		$attrs['title'] = 'Scott Reilly is "' . $old_text . '"';

		return $attrs;
	}

	// Taken from example in readme.txt.
	public function add_title_attr_to_linkified_text( $attrs, $old_text, $link_for_text ) {
		// The string that you chose to separate the link URL and the title attribute text.
		$separator = ' || ';

		// Only change the linked text if a title has been defined
		if ( false !== strpos( $link_for_text, $separator ) ) {
			// Get the link and title that was defined for the text to be linked.
			list( $url, $title ) = explode( $separator, $link_for_text, 2 );

			// Set the attributes ('href' must be overridden to be a proper URL).
			$attrs['href']  = $url;
			$attrs['title'] = $title;
		}

		return $attrs;
	}

	// Taken from example in readme.txt.
	public function selectively_disable_text_linkification( $display_link, $old_text, $link_for_text, $text_to_link ) {
		if ( get_metadata( 'post', get_the_ID(), 'disable_linkify_text', true ) ) {
			$display_link = $old_text;
		}
		return $display_link;
	}


	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_LinkifyText' ) );
	}

	public function test_plugin_framework_class_name() {
		$this->assertTrue( class_exists( 'c2c_LinkifyText_Plugin_048' ) );
	}

	public function test_plugin_framework_version() {
		$this->assertEquals( '048', c2c_LinkifyText::get_instance()->c2c_plugin_version() );
	}

	public function test_get_version() {
		$this->assertEquals( '1.9.1', c2c_LinkifyText::get_instance()->version() );
	}

	public function test_instance_object_is_returned() {
		$this->assertTrue( is_a( c2c_LinkifyText::get_instance(), 'c2c_LinkifyText' ) );
	}

	public function test_no_problems_if_no_linkable_text_defined() {
		$this->set_option( array( 'text_to_link' => ' ' ) );

		$this->assertEquals( 'coffee2code', $this->linkify_text( 'coffee2code' ) );
	}

	public function test_linkifies_text() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );

		$this->assertEquals( $expected, $this->linkify_text( 'coffee2code' ) );
		$this->assertEquals( "ends with $expected", $this->linkify_text( 'ends with coffee2code' ) );
		$this->assertEquals( "ends with period $expected.", $this->linkify_text( 'ends with period coffee2code.' ) );
		$this->assertEquals( "$expected starts", $this->linkify_text( 'coffee2code starts' ) );

		$this->assertEquals( $this->expected_link( 'Matt Mullenweg', 'https://ma.tt' ), $this->linkify_text( 'Matt Mullenweg' ) );
	}

	public function test_linkifies_text_with_ampersand() {
		$this->assertEquals( $this->expected_link( 'AT&T', self::$text_to_link[ 'AT&T' ] ), $this->linkify_text( 'AT&T' ) );
	}

	public function test_linkifies_text_with_html_encoded_amp_ampersand() {
		$this->assertEquals( $this->expected_link( 'AT&amp;T', self::$text_to_link[ 'AT&T' ] ), $this->linkify_text( 'AT&amp;T' ) );
	}

	public function test_linkifies_text_with_html_encoded_038_ampersand() {
		$this->assertEquals( $this->expected_link( 'AT&#038;T', self::$text_to_link[ 'AT&T' ] ), $this->linkify_text( 'AT&#038;T' ) );
	}

	public function test_linkifies_multibyte_text() {
		$mb_string = '漢字はユニコード';

		$this->assertEquals( $this->expected_link( $mb_string, self::$text_to_link[ $mb_string ] ), $this->linkify_text( $mb_string ) );
	}

	public function test_linkifies_multibyte_text_long_text() {
		$text = <<<HTML
<p>Liệu bạn đã biết cách nuôi chó sạch sẽ hay chưa. Phải làm thế nào sau một ngày làm việc vất vả, bạn trở về ngôi nhà thân yêu được bình yên nhất. Nếu bạn có đang nuôi một chú chó, điều đó có phải là quá khó hay không?</p>

<p>Với bản tính tinh nghịch, chúng sẽ phá tan ngôi nhà của bạn ra mất. Đừng lo, sau đây bác sĩ thú y sẽ cho bạn một vài lời khuyên hữu ích về các cách nuôi chó sạch sẽ&nbsp; nhé.</p>

<h2>Dạy chó đi vệ sinh đúng chỗ</h2>
HTML;

		$expected = <<<HTML
<p>Liệu bạn đã biết cách nuôi chó sạch sẽ hay chưa. Phải làm thế nào sau một ngày làm việc vất vả, bạn trở về ngôi nhà thân yêu được bình yên nhất. Nếu bạn có đang nuôi một chú chó, điều đó có phải là quá khó hay không?</p>

<p>Với bản tính tinh nghịch, chúng sẽ phá tan ngôi nhà của bạn ra mất. Đừng lo, sau đây <a href="https://www.bacsithuy.org">bác sĩ thú y</a> sẽ cho bạn một vài lời khuyên hữu ích về các cách nuôi chó sạch sẽ&nbsp; nhé.</p>

<h2>Dạy chó đi vệ sinh đúng chỗ</h2>
HTML;

		$this->assertEquals( $expected, $this->linkify_text( $text ) );
	}

	public function test_linkifies_multiple_multibyte_text_with_038_ampersand() {
		$mb_string = '漢字はユニコード AT&#038;T 漢字はユニコード';

		$expected = $this->expected_link( '漢字はユニコード', self::$text_to_link[ '漢字はユニコード' ] )
			. ' '
			. $this->expected_link( 'AT&#038;T', self::$text_to_link[ 'AT&T' ] )
			. ' '
			. $this->expected_link( '漢字はユニコード', self::$text_to_link[ '漢字はユニコード' ] );

		$this->assertEquals( $expected, $this->linkify_text( $mb_string ) );
	}

	public function test_linkifies_single_term_multiple_times() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );

		$this->assertEquals( "$expected $expected $expected", $this->linkify_text( 'coffee2code coffee2code coffee2code' ) );
	}

	public function test_linkifies_multiple_terms() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' )
			. ' ' . $this->expected_link( 'my posts', '/authors/scott' )
			. ' ' . $this->expected_link( 'test', 'http://example.com/test1' );

		$this->assertEquals( $expected, $this->linkify_text( 'coffee2code my posts test' ) );
	}

	public function test_linkifies_content_of_non_link_tags() {
		$expected = '<strong><a href="http://coffee2code.com">coffee2code</a></strong>';

		$this->assertEquals( $expected, $this->linkify_text( '<strong>coffee2code</strong>' ) );
	}

	public function test_does_not_linkify_substrings() {
		$expected = array(
			'xcoffee2code',
			'ycoffee2codey',
			'coffee2codez',
			'a coffee2codez',
		);

		foreach ( $expected as $e ) {
			$this->assertEquals( $e, $this->linkify_text( $e ) );
		}
	}

	public function test_does_not_link_within_links() {
		$expected = array(
			'<a href="http://coffee2code.com">coffee2code</a>',
			'<a href="http://example.com/buddypress-lastest-plugins/" class="more-link">Continue reading<span class="screen-reader-text"> &#8220;BuddyPress Latest Plugins&#8221;</span></a>',
			'<a href="https://wordpress.org">This is a test place, this is a test place.</a>', // Known failure as of v1.9
		);

		foreach ( $expected as $e ) {
			$this->assertEquals( $e, $this->linkify_text( $e ) );
		}
	}

	public function test_does_not_link_within_html_tags() {
		$expected = array(
			'<coffee2code>sample string</coffee2code>',
			'<div class="coffee2code">sample string</div>',
			'<span coffee2code="something">sample string</span>',
			'<b title="a coffee2code project">sample string</b>',
		);

		foreach ( $expected as $e ) {
			$this->assertEquals( $e, $this->linkify_text( $e ) );
		}
	}

	public function test_does_not_link_within_shortcode_tags() {
		$expected = array(
			'[code user="coffee2code"]some text[/code]',
			'[code user="a coffee2code test"]some text[/code]',
			'[coffee2code]as if shortcode itself[/coffee2code]',
		);

		foreach ( $expected as $e ) {
			$this->assertEquals( $e, $this->linkify_text( $e ) );
		}
	}

	public function test_linkifies_within_shortcode_content() {
		$this->assertEquals(
			'[code user="ok"]aaa ' . $this->linkify_text( 'coffee2code' ) . ' bbb[/code]',
			$this->linkify_text( '[code user="ok"]aaa coffee2code bbb[/code]' )
		);
	}

	public function test_linkifies_within_shortcode_content_adjacent_to_shortcode_tags() {
		$this->assertEquals(
			'[code user="ok"]' . $this->linkify_text( 'coffee2code' ) . '[/code]',
			$this->linkify_text( '[code user="ok"]coffee2code[/code]' )
		);
	}

	public function test_linkifies_outside_preceeding_shortcode() {
		$this->assertEquals(
			'[code user="ok"]eee[/code] a ' . $this->linkify_text( 'coffee2code' ),
			$this->linkify_text( '[code user="ok"]eee[/code] a coffee2code' )
		);
	}

	public function test_linkifies_outside_subsequent_shortcode() {
		$this->assertEquals(
			'a ' . $this->expected_link( 'coffee2code' ) . ' [code user="ok"]eee[/code]',
			$this->linkify_text( 'a coffee2code [code user="ok"]eee[/code]' )
		);
	}

	public function test_empty_link_does_not_linkify_text() {
		$this->assertEquals( 'blank', $this->linkify_text( 'blank' ) );
	}

	/*
	 * With 'Apple iPhone 6' followed by 'iPhone 6' as link defines, the string
	 * 'Apple iPhone 6' should not have the 'iPhone 6' linkification applied to it.
	 */
	public function test_does_not_linkify_a_general_term_that_is_included_in_earlier_listed_term() {
		$string = 'Apple iPhone 6';

		$this->assertEquals( $this->expected_link( $string, self::$text_to_link[ $string ] ), $this->linkify_text( $string ) );
	}

	/**
	 * Ensure a more specific string matches with priority over a less specific
	 * string, regardless of what order they were defined.
	 *
	 *  MAYBE! Not sure if this is desired. But the theory is if both
	 * "test" and "test place" are defined, then the text "test place" should get
	 * linked, even though "test" was defined first.
	 */
	public function test_does_not_linkify_a_more_general_term_when_general_is_first() {
		$expected = $this->expected_link( 'test place', 'http://example.com/test2' );

		$this->assertEquals( "This $expected is true", $this->linkify_text( 'This test place is true' ) );
	}

	public function test_linkifies_text_with_special_character() {
		$expected = $this->expected_link( 'Cocktail glacé', 'http://www.domain.com/cocktail-glace.html' );

		$this->assertEquals( $expected, $this->linkify_text( 'Cocktail glacé' ) );
	}

	public function test_linkifies_multibyte_text_once_via_setting() {
		$linked = $this->expected_link( 'Cocktail glacé', 'http://www.domain.com/cocktail-glace.html' );

		$this->set_option( array( 'replace_once' => true ) );

		$expected = array(
			"$linked Cocktail glacé Cocktail glacé" => $this->linkify_text( 'Cocktail glacé Cocktail glacé Cocktail glacé' ),
			$this->expected_link( '漢字はユニコード' ) => $this->linkify_text( '漢字はユニコード 漢字はユニコード' ),
		);

		foreach ( $expected as $expect => $actual ) {
			$this->assertEquals( $expect, $actual );
		}
	}

	public function test_linkifies_text_via_term_referencing() {
		$this->assertEquals( $this->expected_link( 'Scott Reilly', 'http://coffee2code.com' ), $this->linkify_text( 'Scott Reilly' ) );
		$this->assertEquals( $this->expected_link( 'c2c', 'http://coffee2code.com' ), $this->linkify_text( 'c2c' ) );
	}

	public function test_does_not_linkify_text_via_referencing_nonexistent_term() {
		$this->assertEquals( 'ref dne', $this->linkify_text( 'ref dne' ) );
	}

	// NOTE: This could eventually be supported, though circular referencing should be accounted for.
	public function test_does_not_linkify_text_that_references_another_term_that_also_references_another_term() {
		$this->assertEquals( 'me', $this->linkify_text( 'me' ) );
	}

	// NOTE: Not trying to be too sophisticted here.
	public function test_does_not_linkify_text_if_link_does_not_look_like_link() {
		$this->assertEquals( 'not a link', $this->linkify_text( 'not a link' ) );
	}

	public function tests_linkifies_term_split_across_multiple_lines() {
		$expected = array(
			"These are " . $this->expected_link( "my\nposts", '/authors/scott' ) . " to read"
				=> $this->linkify_text( "These are my\nposts to read" ),
			"These are " . $this->expected_link( "Cocktail\n\tglacé", 'http://www.domain.com/cocktail-glace.html' ) . " to read"
				=> $this->linkify_text( "These are Cocktail\n\tglacé to read" ),
		);

		foreach ( $expected as $expect => $actual ) {
			$this->assertEquals( $expect, $actual );
		}
	}

	public function test_linkifies_once_via_setting() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );
		$this->test_linkifies_single_term_multiple_times();
		$this->set_option( array( 'replace_once' => true ) );

		$this->assertEquals( "$expected coffee2code coffee2code", $this->linkify_text( 'coffee2code coffee2code coffee2code' ) );
	}

	public function test_linkifies_once_via_trueish_setting_value() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );
		$this->test_linkifies_single_term_multiple_times();
		$this->set_option( array( 'replace_once' => '1' ) );

		$this->assertEquals( "$expected coffee2code coffee2code", $this->linkify_text( 'coffee2code coffee2code coffee2code' ) );
	}

	public function test_linkifies_once_via_filter() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );
		$this->test_linkifies_single_term_multiple_times();
		add_filter( 'c2c_linkify_text_replace_once', '__return_true' );

		$this->assertEquals( "$expected coffee2code coffee2code", $this->linkify_text( 'coffee2code coffee2code coffee2code' ) );
	}

	public function test_linkifies_with_case_sensitivity_via_setting() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );
		$this->test_linkifies_with_case_insensitivity();
		$this->set_option( array( 'case_sensitive' => true ) );

		$this->assertEquals( $expected, $this->linkify_text( 'coffee2code' ) );
		$this->assertEquals( 'Coffee2code', $this->linkify_text( 'Coffee2code' ) );
		$this->assertEquals( 'COFFEE2CODE', $this->linkify_text( 'COFFEE2CODE' ) );
	}

	public function test_linkifies_with_case_sensitivity_via_filter() {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );
		$this->test_linkifies_with_case_insensitivity();
		add_filter( 'c2c_linkify_text_case_sensitive', '__return_true' );

		$this->assertEquals( $expected, $this->linkify_text( 'coffee2code' ) );
		$this->assertEquals( 'Coffee2code', $this->linkify_text( 'Coffee2code' ) );
		$this->assertEquals( 'COFFEE2CODE', $this->linkify_text( 'COFFEE2CODE' ) );
	}

	public function test_linkifies_with_case_insensitivity() {
		// Note: This is also implicitly testing that case of the original text is preserved.
		$this->assertEquals( $this->expected_link( 'coffee2code', 'http://coffee2code.com' ), $this->linkify_text( 'coffee2code' ) );
		$this->assertEquals( $this->expected_link( 'Coffee2code', 'http://coffee2code.com' ), $this->linkify_text( 'Coffee2code' ) );
		$this->assertEquals( $this->expected_link( 'COFFEE2CODE', 'http://coffee2code.com' ), $this->linkify_text( 'COFFEE2CODE' ) );
	}

	public function test_link_opens_in_new_window_via_setting() {
		$this->set_option( array( 'open_new_window' => true ) );

		$this->assertEquals(
			'<a href="http://coffee2code.com" target="_blank">coffee2code</a>',
			$this->linkify_text( 'coffee2code' )
		);
	}

	public function test_link_opens_in_new_window_via_filter() {
		add_filter( 'c2c_linkify_text_open_new_window', '__return_true' );

		$this->assertEquals(
			'<a href="http://coffee2code.com" target="_blank">coffee2code</a>',
			$this->linkify_text( 'coffee2code' )
		);
	}


	public function test_linkifies_term_added_via_filter() {
		$this->assertEquals( 'bbPress', $this->linkify_text( 'bbPress' ) );
		$expected = $this->expected_link( 'bbPress', 'https://bbpress.org' );
		add_filter( 'c2c_linkify_text', array( $this, 'add_text_to_linkify' ) );

		$this->assertEquals( $expected, $this->linkify_text( 'bbPress' ) );
	}

	public function test_linkification_prepends_protocol_if_missing_and_not_root_relative() {
		$expected = $this->expected_link( 'BuddyPress', 'http://buddypress.org' );

		$this->assertEquals( $expected, $this->linkify_text( 'BuddyPress' ) );
	}

	public function test_linkification_accepts_root_relative_link() {
		$expected = $this->expected_link( 'my posts', '/authors/scott' );

		$this->assertEquals( $expected, $this->linkify_text( 'my posts' ) );
	}

	public function test_linkification_accepts_root_relative_file() {
		$text = 'my readme';
		$expected = $this->expected_link( $text, 'http://' . self::$text_to_link[ $text ] );

		$this->assertEquals( $expected, $this->linkify_text( $text ) );
	}

	/**
	 * @dataProvider get_default_filters
	 */
	public function test_linkification_applies_to_default_filters( $filter ) {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );

		$this->assertNotFalse( has_filter( $filter, array( c2c_LinkifyText::get_instance(), 'linkify_text' ), 2 ) );
		$this->assertGreaterThan( 0, strpos( apply_filters( $filter, 'a coffee2code' ), $expected ) );
	}

	/**
	 * @dataProvider get_comment_filters
	 */
	public function test_linkification_does_not_apply_to_comments_by_default( $filter ) {
		$this->assertEquals( 'coffee2code', apply_filters( $filter, 'coffee2code' ) );
	}

	/**
	 * @dataProvider get_comment_filters
	 */
	public function test_linkification_applies_to_comment_filters_when_enabled( $filter ) {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );

		add_filter( 'c2c_linkify_text_comments', '__return_true' );

		$this->assertNotFalse( has_filter( $filter, array( c2c_LinkifyText::get_instance(), 'linkify_comment_text' ), 11 ) );
		$this->assertGreaterThan( 0, strpos( apply_filters( $filter, 'a coffee2code' ), $expected ) );
	}

	/**
	 * @dataProvider get_comment_filters
	 */
	public function test_linkification_applies_to_comments_via_setting( $filter ) {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );
		$this->test_linkification_does_not_apply_to_comments_by_default( $filter );
		$this->set_option( array( 'linkify_text_comments' => true ) );

		$this->assertEquals( $expected, apply_filters( $filter, 'coffee2code' ) );
	}

	/**
	 * @dataProvider get_comment_filters
	 */
	public function test_linkification_applies_to_comments_via_filter( $filter ) {
		$expected = $this->expected_link( 'coffee2code', 'http://coffee2code.com' );
		$this->test_linkification_does_not_apply_to_comments_by_default( $filter );
		add_filter( 'c2c_linkify_text_comments', '__return_true' );

		$this->assertEquals( $expected, apply_filters( $filter, 'coffee2code' ) );
	}

	public function test_linkification_applies_to_custom_filter_via_filter() {
		$this->assertEquals( 'coffee2code', apply_filters( 'custom_filter', 'coffee2code' ) );

		add_filter( 'c2c_linkify_text_filters', array( $this, 'add_custom_filter' ) );

		c2c_LinkifyText::get_instance()->register_filters(); // Plugins would typically register their filter before this originally fires

		$this->assertEquals( $this->expected_link( 'coffee2code', 'http://coffee2code.com' ), apply_filters( 'custom_filter', 'coffee2code' ) );
	}

	// NOTE: This is a test of an example given in the readme.
	public function test_defining_custom_link_markup_via_filter() {
		// Redine text_to_link to add title attribute text after the link in the link text.
		$this->set_option( array( 'text_to_link' => array( 'coffee2code' => 'http://coffee2code.com || Scott Reilly' ) ) );
		// Add custom handler via filter.
		add_filter( 'c2c_linkify_text_linked_text', array( $this, 'add_title_attribute_to_linkified_text' ), 10, 4 );

		$this->assertEquals(
			'<a href="http://coffee2code.com" title="Scott Reilly">coffee2code</a>',
			$this->linkify_text( 'coffee2code' )
		);
	}

	public function test_defining_custom_link_markup_via_filter_disables_linking() {
		add_filter( 'c2c_linkify_text_linked_text', array( $this, 'disable_linking' ), 10, 3 );

		$this->assertEquals( 'coffee2code', $this->linkify_text( 'coffee2code' ) );
	}

	// NOTE: This is a test of an example given in the readme.
	public function test_selectively_disabling_linking_via_filter() {
		add_filter( 'c2c_linkify_text_linked_text', array( $this, 'selectively_disable_text_linkification' ), 10, 4 );

		$post_id = $this->factory->post->create();
		$GLOBALS['post'] = get_post( $post_id );

		$this->assertEquals( $this->expected_link( 'coffee2code', 'http://coffee2code.com' ), $this->linkify_text( 'coffee2code' ) );

		add_post_meta( $post_id, 'disable_linkify_text', '1' );

		$this->assertEquals( 'coffee2code', $this->linkify_text( 'coffee2code' ) );
	}

	// NOTE: This is a test of an example given in the readme.
	public function test_adding_link_attr_via_filter() {
		add_filter( 'c2c_linkify_text_link_attrs', array( $this, 'my_linkify_text_attrs' ), 10, 3 );

		$this->assertEquals(
			'<a href="http://coffee2code.com" target="_blank">coffee2code</a>',
			$this->linkify_text( 'coffee2code' )
		);
	}

	// NOTE: This is a test of an example given in the readme.
	public function test_adding_link_title_attr_via_filter() {
		add_filter( 'c2c_linkify_text_link_attrs', array( $this, 'add_title_attr_to_linkified_text' ), 10, 3 );

		$this->assertEquals(
			'<a href="https://example.com" title="Example title">wonder</a>',
			$this->linkify_text( 'wonder' )
		);
	}

	public function test_defining_custom_link_attrs_via_filter() {
		add_filter( 'c2c_linkify_text_link_attrs', array( $this, 'text_link_attrs' ), 10, 3 );

		$this->assertEquals(
			'<a href="https://coffee2code.com" target="_blank" title="Scott Reilly is &quot;coffee2code&quot;">coffee2code</a>',
			$this->linkify_text( 'coffee2code' )
		);
	}


	/*
	 * Setting handling
	 */

	/*
	// This is normally the case, but the unit tests save the setting to db via
	// setUp(), so until the unit tests are restructured somewhat, this test
	// would fail.
	public function test_does_not_immediately_store_default_settings_in_db() {
		$option_name = c2c_LinkifyText::SETTING_NAME;
		// Get the options just to see if they may get saved.
		$options     = c2c_LinkifyText::get_instance()->get_options();

		$this->assertFalse( get_option( $option_name ) );
	}
	*/

	public function test_uninstall_deletes_option() {
		$option_name = c2c_LinkifyText::SETTING_NAME;
		$options     = c2c_LinkifyText::get_instance()->get_options();

		// Explicitly set an option to ensure options get saved to the database.
		$this->set_option( array( 'replace_once' => true ) );

		$this->assertNotEmpty( $options );
		$this->assertNotFalse( get_option( $option_name ) );

		c2c_LinkifyText::uninstall();

		$this->assertFalse( get_option( $option_name ) );
	}

}
