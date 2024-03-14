<?php

defined( 'ABSPATH' ) or die();

class Quick_Drafts_Access_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		register_post_type( 'book',   array( 'public' => true, 'name' => 'Book' ) );
		register_post_type( 'event',  array( 'public' => true, 'name' => 'Event' ) );
		register_post_type( 'secret', array( 'public' => false, 'name' => 'Secret' ) );
	}

	//
	//
	// FUNCTIONS FOR HOOKING ACTIONS/FILTERS
	//
	//


	public function c2c_quick_drafts_access_post_types( $post_types ) {
		return array_intersect_key( $post_types, array( 'post' => true, 'book' => true ) );
	}


	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_QuickDraftsAccess' ) );
	}

	public function test_get_version() {
		$this->assertEquals( '2.3.1', c2c_QuickDraftsAccess::version() );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded' , array( 'c2c_QuickDraftsAccess', 'init' ) ) );
	}

	/*
	 * get_post_types()
	 */

	public function test_get_post_types() {
		$this->assertEquals(
			array( 'post', 'page', 'book', 'event' ),
			array_keys( c2c_QuickDraftsAccess::get_post_types() )
		);
	}

	public function test_get_post_types_returns_array() {
		add_filter( 'c2c_quick_drafts_access_post_types', '__return_false' );

		$this->assertIsArray( c2c_QuickDraftsAccess::get_post_types() );

		add_filter( 'c2c_quick_drafts_access_post_types', '__return_zero' );

		$this->assertIsArray( c2c_QuickDraftsAccess::get_post_types() );
		$this->assertEquals( array( '0' ), c2c_QuickDraftsAccess::get_post_types() );
	}

	/*
	 * Filter: c2c_quick_drafts_access_post_types
	 */

	public function test_hook_c2c_quick_drafts_access_post_types() {
		add_filter( 'c2c_quick_drafts_access_post_types', array( $this, 'c2c_quick_drafts_access_post_types' ) );

		$this->assertEquals( array( 'post', 'book' ), array_keys( c2c_QuickDraftsAccess::get_post_types() ) );
	}

	public function test_filter_c2c_quick_drafts_access_post_types_dev_docs_example() {
		add_filter( 'c2c_quick_drafts_access_post_types', function ( $post_types ) {
			// More post types can be added to this array.
			$post_types_to_exclude = array( 'event' );
			foreach ( $post_types_to_exclude as $post_type ) {
				unset( $post_types[ $post_type ] );
			}
			return $post_types;
		} );

		$this->assertEquals(
			array( 'post', 'page', 'book' ),
			array_keys( c2c_QuickDraftsAccess::get_post_types() )
		);
	}

	/*
	 * filter_drafts_by_author()
	 */

	public function test_filter_drafts_by_author_outputs_nothing_if_second_arg_is_not_top() {
		$this->factory->post->create( array( 'post_status' => 'draft' ) );
		$_GET['post_status'] = 'draft';

		$this->expectOutputRegex( '/^$/', c2c_QuickDraftsAccess::filter_drafts_by_author( 'post', 'bottom' ) );
	}

	public function test_filter_drafts_by_author_outputs_nothing_if_GET_post_status_is_not_draft() {
		$this->factory->post->create( array( 'post_status' => 'draft' ) );
		$_GET['post_status'] = 'publish';

		$this->expectOutputRegex( '/^$/', c2c_QuickDraftsAccess::filter_drafts_by_author( 'post', 'top' ) );
	}

	public function test_filter_drafts_by_author_outputs_nothing_if_post_type_not_supported() {
		$this->factory->post->create( array( 'post_type' => 'example', 'post_status' => 'draft' ) );
		$_GET['post_status'] = 'draft';

		$this->expectOutputRegex( '/^$/', c2c_QuickDraftsAccess::filter_drafts_by_author( 'example', 'top' ) );
	}

	public function test_filter_drafts_by_author_outputs_markup() {
		$user_id = $this->factory->user->create( array( 'display_name' => 'Test User' ) );
		$this->factory->post->create( array( 'post_status' => 'draft', 'post_author' => $user_id ) );
		$_GET['post_status'] = 'draft';

		$expected = <<<HTML
		<label for="filter-by-draft-author" class="screen-reader-text">Filter by author</label>
			<select name="author" id="filter-by-draft-author">
				<option selected='selected' value="0">All Draft Authors</option>
				<option value="{$user_id}">Test User</option>
			</select>

HTML;

		$this->expectOutputRegex( '~^' . preg_quote( $expected ) . '$~', c2c_QuickDraftsAccess::filter_drafts_by_author( 'post', 'top' ) );
	}

	/*
	 * filter: c2c_quick_drafts_access_disable_filter_dropdown
	 */

	public function test_filter_c2c_quick_drafts_access_disable_filter_dropdown() {
		add_filter( 'c2c_quick_drafts_access_disable_filter_dropdown', function( $disable, $post_type ) {
			return 'post' === $post_type;
		}, 10, 2 );

		$this->factory->post->create( array( 'post_status' => 'draft' ) );
		$_GET['post_status'] = 'draft';

		$this->expectOutputRegex( '/^$/', c2c_QuickDraftsAccess::filter_drafts_by_author( 'post', 'top' ) );
	}

	/*
	 * Hooks
	 */

	public function test_hooks_action_admin_init() {
		$this->assertEquals( 10, has_action( 'admin_init', array( 'c2c_QuickDraftsAccess', 'admin_init' ) ) );
	}

	public function test_hooks_action_admin_menu() {
		$this->assertEquals( 10, has_action( 'admin_menu', array( 'c2c_QuickDraftsAccess', 'quick_drafts_access' ) ) );
	}

	public function test_hooks_action_restrict_manage_posts() {
		$this->assertEquals( 10, has_action( 'restrict_manage_posts', array( 'c2c_QuickDraftsAccess', 'filter_drafts_by_author' ) ) );
	}

}
