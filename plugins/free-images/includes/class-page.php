<?php
/**
 * Page class.
 *
 * @since      2.0.0
 * @package    FAL
 * @subpackage FAL\Page
 * @author     FAL <support@surror.com>
 */

namespace FAL;

defined( 'ABSPATH' ) || exit;

/**
 * Page class.
 */
class Page {

	/**
	 * The single instance of the class.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'plugin_action_links_' . FAL_BASE, [ $this, 'plugin_action_links' ] );
	}

	/**
	 * Add plugin action link.
	 * 
	 * @param  array $links
	 */
	public function plugin_action_links( $links = [] ) {
		$links[] = '<a href="' . admin_url( 'upload.php?page=fal' ) . '">' . __( 'See Library', 'fal' ) . '</a>';
		$links[] = '<a target="_blank" href="https://docs.surror.com/doc/free-assets-library/getting-started/#how-to-use-plugin">' . __( 'Getting Started', 'fal' ) . '</a>';
		return $links;
	}

	/**
	 * Register menu in upload.php menu.
	 */
	public function register_menu() {
		add_submenu_page(
			'upload.php',
			__( 'Free Assets Library', 'fal' ),
			__( 'Free Assets Library', 'fal' ),
			'manage_options',
			'fal',
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Render page.
	 */
	public function render_page() {
		?>
		<div id="fal-root"></div>
		<?php
	}

	/**
	 * Enqueue scripts.
	 * 
	 * @param  string $hook
	 */
	public function enqueue_scripts( $hook = '' ) {

		wp_enqueue_style(
			'fal',
			FAL_URI . 'css/style.css',
			null,
			FAL_VERSION,
			'all'
		);

		wp_enqueue_script(
			'fal',
			FAL_URI . 'build/stats.js',
			[ 
				'lodash',
				'jquery',
				'wp-element',
				'wp-api-fetch',
				'wp-i18n',
				'masonry',
				'imagesloaded',
			],
			FAL_VERSION,
			true
		);

		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT post_id, meta_value
			FROM {$wpdb->prefix}postmeta
			WHERE meta_key = %s",
			'fal_source_id'
		);

		$results = $wpdb->get_results( $query, ARRAY_A );

		$image_ids = array();
		foreach ($results as $result ) {
			$image_ids[ $result['post_id'] ] = $result['meta_value'];
		}

		wp_localize_script( 'fal', 'FAL', [
			'downloaded' => $image_ids,
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		] );
	}

}
