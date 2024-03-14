<?php
/**
 * This file contains the class for registering the plugin's roadmap page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      3.0.7
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that registers the plugin's roadmap page.
 */
class Nelio_Content_Roadmap_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content-roadmap',
			_x( 'Roadmap', 'text', 'nelio-content' ),
			nc_can_current_user_manage_plugin()
		);

	}//end __construct()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {

		$screen = get_current_screen();
		if ( 'nelio-content_page_nelio-content-roadmap' !== $screen->id ) {
			return;
		}//end if

		wp_enqueue_style(
			'nelio-content-roadmap-page',
			nelio_content()->plugin_url . '/assets/dist/css/roadmap-page.css',
			array(),
			nc_get_script_version( 'roadmap-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-roadmap-page', 'roadmap-page', true );

	}//end enqueue_assets()

	// @Overwrites
	// phpcs:ignore
	public function display() {
		?>
		<div class="wrap">

			<h2 class="screen-reader-text">
				<?php echo esc_html_x( 'Nelio Content - Roadmap', 'text', 'nelio-content' ); ?>
			</h2>

			<iframe id="nc-trello-iframe" src="https://trello.com/b/xzRPgkP2.html"></iframe>

		</div><!-- .wrap -->
		<?php
	}//end display()

}//end class
