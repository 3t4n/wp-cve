<?php
/**
 * Abstract class that implements a page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * A class that represents a page.
 */
abstract class Nelio_Content_Abstract_Page {

	protected $parent_slug;
	protected $slug;
	protected $title;
	protected $capability;
	protected $mode;

	public function __construct( $parent_slug, $slug, $title, $capability, $mode = 'regular' ) {

		$this->parent_slug = $parent_slug;
		$this->slug        = $slug;
		$this->title       = $title;
		$this->capability  = $capability;
		$this->mode        = $mode;

	}//end __construct()

	public function init() {

		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_assets' ) );

		add_action(
			'current_screen',
			function() {
				if ( $this->is_current_screen_this_page() ) {
					$this->add_page_specific_hooks();
				}//end if
			}
		);

	}//end init()

	public function add_page() {

		$capability = $this->capability;
		if ( is_bool( $capability ) ) {
			$capability = $capability ? 'read' : 'invalid-capability';
		}//end if

		add_submenu_page(
			$this->parent_slug,
			$this->title,
			$this->title,
			$capability,
			$this->slug,
			$this->get_render_function()
		);

	}//end add_page()

	public function display() {

		printf(
			'<div class="%s wrap">',
			esc_attr( $this->slug )
		);

		printf(
			'<div class="notice notice-error notice-alt hide-if-js"><p>%s</p></div>',
			esc_html_x( 'This page requires JavaScript. Please enable JavaScript in your browser settings.', 'user', 'nelio-content' )
		);

		printf(
			'<div id="%s" class="hide-if-no-js"></div>',
			esc_attr( "{$this->slug}-page" )
		);

		echo '</div>';

	}//end display()

	public function maybe_enqueue_assets() {

		if ( ! $this->is_current_screen_this_page() ) {
			return;
		}//end if

		$this->enqueue_assets();

	}//end maybe_enqueue_assets()

	abstract protected function enqueue_assets();

	private function get_render_function() {

		switch ( $this->mode ) {

			case 'extends-existing-page':
				return null;

			case 'regular':
			default:
				return array( $this, 'display' );

		}//end switch

	}//end get_render_function()

	protected function remove_page_from_menu( $parent, $slug ) {

		global $submenu;
		if ( ! isset( $submenu[ $parent ] ) ) {
			return;
		}//end if

		$submenu[ $parent ] = array_filter( // phpcs:ignore
			$submenu[ $parent ],
			function( $item ) use ( $slug ) {
				return $item[2] !== $slug;
			}//end if
		);

	}//end remove_page_from_menu()

	protected function is_current_screen_this_page() {

		$screen   = get_current_screen();
		$haystack = $screen->id;
		$needle   = str_replace( 'edit.php?post_type=', 'edit-', $this->slug );

		return strlen( $needle ) <= strlen( $haystack ) && 0 === substr_compare( $haystack, $needle, -strlen( $needle ) );

	}//end is_current_screen_this_page()

	protected function add_page_specific_hooks() {
		// Nothing to be done.
	}//end add_page_specific_hooks()

}//end class

