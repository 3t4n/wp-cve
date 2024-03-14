<?php
/**
 *  Object that manage keyword surfer option in sidebar.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer;

use SurferSEO\Surferseo;

/**
 * Object responsible for handlig keyword surfer in post edition.
 */
class Keyword_Surfer {

	/**
	 * Base URL to keyword surfer
	 *
	 * @var string
	 */
	protected $keyword_surfer_url = 'https://db.keywordsur.fr/urlsOnPage';

	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'include_keyword_surfer_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'include_keyword_surfer_scripts' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_keyword_surfer_meta_box' ) );
	}

	/**
	 * Enqueue sidebar script.
	 */
	public function include_keyword_surfer_scripts() {
		$screen = get_current_screen();
		if ( ! in_array( $screen->post_type, surfer_return_supported_post_types(), true ) ) {
			return;
		}

		Surfer()->get_surfer()->enqueue_surfer_react_apps();
	}

	/**
	 * Creates metabox where we will store writing guidelines in iFrame.
	 *
	 * @return void
	 */
	public function add_keyword_surfer_meta_box() {
		$current_screen = get_current_screen();

		// Add meta box only in classic editor (in Gutenber we have sidebar).
		if ( ! $current_screen->is_block_editor() ) {
			add_meta_box(
				'surfer_keyword_surfer',
				__(
					'Keyword Research
				',
					'surferseo'
				),
				array( $this, 'render_keyword_surfer_meta_box_content' ),
				'post',
				'side',
				'default'
			);
		}
	}

	/**
	 * Displays content of the keyword research box.
	 *
	 * @return void
	 */
	public function render_keyword_surfer_meta_box_content() {

		?>
			<div id="surfer-keyword-surfer"></div>
		<?php
	}
}
