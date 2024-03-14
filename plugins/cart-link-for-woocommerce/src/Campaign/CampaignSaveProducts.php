<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use IC\Plugin\CartLinkWooCommerce\Campaign\Metabox\MetaboxProducts;
use IC\Plugin\CartLinkWooCommerce\Notice\NoticeNoCampaigns;

class CampaignSaveProducts {

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
		if ( ! wp_verify_nonce( $_POST[ MetaboxProducts::NONCE_NAME ] ?? '', MetaboxProducts::NONCE_ACTION ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		update_option( NoticeNoCampaigns::OPTION_NAME, true );

		update_post_meta( $post_id, Campaign::META_PRODUCTS, map_deep( wp_unslash( $_POST[ Campaign::META_PRODUCTS ] ?? [] ), 'sanitize_text_field' ) );
	}
}
