<?php
/**
 * Albums Preview class.
 *
 * @since 1.8.7
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team
 */

/**
 * Albums Preview Class
 *
 * @since 1.8.7
 */
class Envira_Albums_Preview {

	/**
	 * Holds base singleton.
	 *
	 * @since 1.8.7
	 *
	 * @var object
	 */
	public $base = null;

	/**
	 * Class Constructor
	 *
	 * @since 1.8.7
	 */
	public function __construct() {
		// Load the base class object.
		$this->base = Envira_Gallery_Lite::get_instance();
	}

	/**
	 * Class Hooks
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 10 );
	}

	/**
	 * Helper Method to add Admin Menu
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function admin_menu() {

		add_submenu_page(
			'edit.php?post_type=envira',
			esc_html__( 'Albums', 'envira-gallery-lite' ),
			esc_html__( 'Albums', 'envira-gallery-lite' ),
			apply_filters( 'envira_gallery_menu_cap', 'manage_options' ),
			ENVIRA_LITE_SLUG . '-albums',
			[ $this, 'page' ]
		);
	}

	/**
	 * Helper Method to display Admin Page
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function page() {
		// If here, we're on an Envira Gallery or Album screen, so output the header.
		$this->base->load_admin_partial(
			'albums'
		);
	}
}
