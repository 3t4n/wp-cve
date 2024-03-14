<?php
/**
 *  Object that manage sidebar in Gutenberg
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer;

use SurferSEO\Surferseo;

/**
 * Object responsible for handlig Surfer sidebar.
 */
class Surfer_Sidebar {


	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'include_surfer_sidebar_scripts' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_post_export_meta_box' ) );
	}

	/**
	 * Enqueue sidebar script.
	 */
	public function include_surfer_sidebar_scripts() {
		$screen = get_current_screen();
		if ( 'post' !== $screen->post_type && 'page' !== $screen->post_type ) {
			return;
		}

		$base_url = Surferseo::get_instance()->get_baseurl();

		wp_enqueue_style(
			'surfer-sidebar',
			$base_url . 'assets/css/surfer-sidebar.css',
			array(),
			SURFER_VERSION
		);
	}

	/**
	 * Creates metabox where we will store writing guidelines in iFrame.
	 *
	 * @return void
	 */
	public function add_post_export_meta_box() {
		$current_screen = get_current_screen();

		// Add meta box only in classic editor (in Gutenber we have sidebar).
		if ( ! $current_screen->is_block_editor() ) {
			add_meta_box(
				'surfer_export_content',
				__( 'Optimize', 'surferseo' ),
				array( $this, 'render_contet_export_box' ),
				array( 'post', 'page' ),
				'side',
				'default'
			);
		}
	}

	/**
	 * Displays content of the content export box
	 *
	 * @return void
	 */
	public function render_contet_export_box() {

		?>
			<div key="surfer-guidelines" id="surfer-content-export-box"></div>
		<?php
	}
}
