<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Public {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.3.4
	 * @access protected
	 * @var    Nelio_Content
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Public the single instance of this class.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * Adds hooks into WordPress.
	 *
	 * @since 2.0.0
	 */
	public function init() {
		add_filter( 'the_content', array( $this, 'remove_share_blocks' ), 99 );
	}//end init()

	/**
	 * Strips all ncshare tags from the content.
	 *
	 * @param string $content The original content.
	 *
	 * @return string The content with all `ncshare` tagsstripped.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public function remove_share_blocks( $content ) {
		$content = preg_replace( '/<.?ncshare[^>]*>/', '', $content );
		return $content;
	}//end remove_share_blocks()

}//end class
