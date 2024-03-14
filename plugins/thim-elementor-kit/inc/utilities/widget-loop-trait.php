<?php
namespace Thim_EL_Kit\Utilities;

use Thim_EL_Kit\Custom_Post_Type;

trait Widget_Loop_Trait {

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_RECOMMENDED );
	}

	public function show_in_panel() {
		$type = get_post_meta( get_the_ID(), Custom_Post_Type::TYPE, true );

		// Check if the current post type is supported.
		$post_type = get_post_meta( get_the_ID(), 'thim_loop_item_post_type', true );

		// If $type start with single- then it's a single post type.
		if ( ! empty( $post_type ) || ( ! empty( $type ) && strpos( $type, 'single-' ) === 0 ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Deprecated method. Will be removed in the future.
	 */
	public function before_preview_query() {
		do_action( 'thim-ekit/modules/loop-item/before-preview-query' );
	}

	/**
	 * Deprecated method. Will be removed in the future.
	 */
	public function after_preview_query() {
		do_action( 'thim-ekit/modules/loop-item/after-preview-query' );
	}

	protected function content_template() {}
}
