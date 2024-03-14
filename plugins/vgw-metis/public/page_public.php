<?php

namespace WP_VGWORT;

/**
 * public plugin functionality
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Page_Public extends Page {
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( object $plugin) {
		parent::__construct( $plugin );
		$this->load_dependencies();
		$this->add_hooks();
	}

	/**
	 * Requires all dependence Files for public area
	 *
	 * @return void
	 */
	private function load_dependencies(): void {

	}

	/**
	 * Define all hooks for the private area
	 *
	 * @return void
	 */
	public function add_hooks(): void {
		// Add the pixel to the post content
		add_filter( 'the_content', [ $this, 'addPixelToPost' ], 10000 );
	}

	/**
	 * This function add a pixel to the post
	 *
	 * If Pixel was found for this post and no other pixel was found with a search - pixel image will be assigned
	 *
	 * Hooked to "the_content" filter
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function addPixelToPost( string $content ): string {
		return Services::add_pixel_img_to_post_content( $content );
	}

}
