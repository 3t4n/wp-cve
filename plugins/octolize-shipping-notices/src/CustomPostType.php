<?php
/**
 * Class CustomPostType
 */

namespace Octolize\Shipping\Notices;

use OctolizeShippingNoticesVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Register post type for notices.
 */
class CustomPostType implements Hookable {
	public const POST_TYPE = 'fs-shipping-notice';

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	/**
	 * @return void
	 */
	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'              => [
					'name' => __( 'Shipping Notices', 'octolize-shipping-notices' ),
				],
				'public'              => false,
				'show_ui'             => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'hierarchical'        => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => [ 'title', 'revisions' ],
				'has_archive'         => false,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => false,
				'show_in_menu'        => false,
			]
		);
	}
}
