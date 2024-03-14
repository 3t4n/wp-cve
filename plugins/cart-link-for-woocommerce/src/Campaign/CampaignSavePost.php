<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use IC\Plugin\CartLinkWooCommerce\Notice\NoticeNoCampaigns;

class CampaignSavePost {

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
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		update_option( NoticeNoCampaigns::OPTION_NAME, true );

		update_post_meta( $post_id, Campaign::META_CLEAR_CART, isset( $_POST[ Campaign::META_CLEAR_CART ] ) ? 'yes' : 'no' );
		update_post_meta( $post_id, Campaign::META_REDIRECT_TO, absint( wp_unslash( $_POST[ Campaign::META_REDIRECT_TO ] ?? 0 ) ) );
		update_post_meta( $post_id, Campaign::META_PRODUCTS, map_deep( wp_unslash( $_POST[ Campaign::META_PRODUCTS ] ?? [] ), 'sanitize_text_field' ) );
	}
}
