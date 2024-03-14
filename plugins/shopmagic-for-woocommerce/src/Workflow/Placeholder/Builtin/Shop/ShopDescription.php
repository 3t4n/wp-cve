<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Shop;

use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;


final class ShopDescription extends Placeholder {

	public function get_slug(): string {
		return 'tagline';
	}

	public function get_description(): string {
		return esc_html__( 'Display tagline of your website. Can be configured in Settings -> General -> Tagline.', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return mixed[]
	 */
	public function get_required_data_domains(): array {
		return [];
	}

	public function value( array $parameters ): string {
		return get_bloginfo( 'description' );
	}
}
