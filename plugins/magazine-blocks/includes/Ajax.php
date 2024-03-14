<?php
/**
 * Class Ajax.
 *
 * @since TBD
 * @package Magazine Blocks
 */

namespace MagazineBlocks;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

use MagazineBlocks\Traits\Singleton;
use function Sodium\add;

/**
 * Ajax class.
 *
 * @since TBD
 */
class Ajax {

	use Singleton;

	/**
	 * Constructor.
	 */
	protected function __construct() {
		$this->init_hooks();
	}

	/**
	 * init hooks.
	 *
	 * @since TBD
	 * @return void.
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_magazine_blocks_get_widget_blocks', array( $this, 'get_widget_blocks' ) );
		add_action( 'wp_ajax_magazine_blocks_get_library_data', array( $this, 'get_library_data' ) );
		add_action( 'wp_ajax_magazine_blocks_import_content', array( $this, 'import_content' ) );
		add_action( 'wp_ajax_magazine_blocks_save_block_css', array( $this, 'save_block_css' ) );
	}

	/**
	 * Save block CSS.
	 *
	 * @retun void
	 */
	public function save_block_css() {
		check_ajax_referer( '_magazine_blocks_nonce', 'security', false );

		$css            = isset( $_POST['css'] ) ? sanitize_text_field( wp_unslash( $_POST['css'] ) ) : '';
		$post_id        = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;
		$has_blocks     = isset( $_POST['has_blocks'] ) && wp_unslash( $_POST['has_blocks'] );
		$filename       = "magazine-blocks-css-$post_id.css";
		$upload_dir_url = wp_upload_dir();
		$dir            = trailingslashit( $upload_dir_url['basedir'] ) . 'magazine-blocks/';

		if ( $has_blocks ) {
			if ( ! magazine_blocks()->utils->create_files( $filename, $css ) ) {
				wp_send_json_error();
			}
			update_post_meta( $post_id, '_magazine_blocks_active', 'yes' );
			update_post_meta( $post_id, '_magazine_blocks_css', $css );
		} else {
			delete_post_meta( $post_id, '_magazine_blocks_active' );
			delete_post_meta( $post_id, '_magazine_blocks_css' );
			file_exists( "$dir$filename" ) && unlink( "$dir$filename" );
		}

		wp_send_json_success();

	}

	/**
	 * Get widget block.
	 *
	 * @return void
	 */
	public function get_widget_blocks() {
		check_ajax_referer( '_magazine_blocks_nonce', 'security', false );
		wp_send_json_success( array( 'blocks' => magazine_blocks()->utils->get_widget_blocks() ) );
	}
}
