<?php

namespace WP_VGWORT;

// include the wp table dependency if not yet loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Pixel Page View Class
 *
 * holds all things necessary to set up the pixel list page template
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Page_Pixels extends Page {
	/**
	 * @var array allowed order by values
	 */
	const ALLOWED_ORDER_BY = ['STATE'];

	/**
	 * @var object instance of the pixel table class
	 */
	public object $pixels_table;

	/**
	 * constructor
	 */
	public function __construct( object $plugin ) {
		parent::__construct( $plugin );
		add_action( 'admin_menu', [$this, 'add_pixels_submenu'] );
	}

	/**
	 * add the submenu for the pixels overview
	 *
	 * @return void
	 */

	public function add_pixels_submenu() {
		$page_metis_pixels_hook = add_submenu_page( 'metis-dashboard', esc_html__( 'VG WORT METIS Zählmarkenübersicht', 'vgw-metis' ), esc_html__( 'Zählmarken', 'vgw-metis' ), 'manage_options', 'metis-pixel', array(
			$this,
			'render'
		), 3 );

		add_action("load-$page_metis_pixels_hook", [$this, 'add_screen_options']);
	}

	public function add_screen_options(): void {
		$args = array(
			'label' => __('Zählmarken pro Seite', 'vgw-metis'),
			'default' => 20,
			'option' => 'metis_pixels_per_page'
		);
		add_screen_option( 'per_page', $args );

		$this->pixels_table = new List_Table_Pixels();
	}

	/**
	 * Loads the template of the view > render page
	 *
	 * @return void
	 */
	public function render(): void {
		$this->plugin->notifications->display_notices();
		$this->pixels_table->prepare_items();
		require_once 'partials/pixels.php';
	}
}





