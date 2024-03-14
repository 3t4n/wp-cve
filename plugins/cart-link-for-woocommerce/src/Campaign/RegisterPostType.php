<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

/**
 * Register CPT.
 */
class RegisterPostType {
	public const POST_TYPE = 'ic-campaign';

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'woocommerce_screen_ids', [ $this, 'register_screen_ids' ] );
	}

	/**
	 * @return void
	 */
	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'label'               => __( 'Cart Link Campaigns', 'cart-link-for-woocommerce' ),
				'labels'              => [
					'search_items' => __( 'Search campaigns', 'cart-link-for-woocommerce' ),
					'edit_item'    => __( 'Edit Campaign', 'cart-link-for-woocommerce' ),
				],
				'supports'            => [ 'title' ],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'woocommerce-marketing',
				'show_in_admin_bar'   => false,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable ' => true,
				'show_in_quick_edit'  => false,
				'rewrite'             => false,
				'capability_type'     => 'page',
				'show_in_rest'        => true,
			]
		);
	}

	/**
	 * @param string[] $screen_ids .
	 *
	 * @return string[]
	 */
	public function register_screen_ids( $screen_ids ): array {
		$screen_ids[] = self::POST_TYPE;
		$screen_ids[] = 'edit-' . self::POST_TYPE;

		return $screen_ids;
	}
}
