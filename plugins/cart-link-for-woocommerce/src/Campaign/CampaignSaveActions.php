<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use IC\Plugin\CartLinkWooCommerce\Campaign\Metabox\MetaboxActions;

class CampaignSaveActions {

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'save_post_' . RegisterPostType::POST_TYPE, [ $this, 'save_post' ], 100 );
	}

	/**
	 * @param int $post_id .
	 *
	 * @return void
	 */
	public function save_post( int $post_id ): void {
		if ( ! wp_verify_nonce( $_POST[ MetaboxActions::NONCE_NAME ] ?? '', MetaboxActions::NONCE_ACTION ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		update_post_meta( $post_id, Campaign::META_CLEAR_CART, isset( $_POST[ Campaign::META_CLEAR_CART ] ) ? 'yes' : 'no' );
		update_post_meta( $post_id, Campaign::META_REDIRECT_TO, absint( wp_unslash( $_POST[ Campaign::META_REDIRECT_TO ] ?? 0 ) ) );
	}
}
