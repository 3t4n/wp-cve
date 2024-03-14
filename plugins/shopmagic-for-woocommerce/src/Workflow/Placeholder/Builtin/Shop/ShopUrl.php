<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Shop;

use WPDesk\ShopMagic\Workflow\Placeholder\Helper\PlaceholderUTMBuilder;
use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;


final class ShopUrl extends Placeholder {

	/** @var PlaceholderUTMBuilder */
	private $utm_builder;

	public function __construct( PlaceholderUTMBuilder $utm_builder ) {
		$this->utm_builder = $utm_builder;
	}

	public function get_slug(): string {
		return 'url';
	}

	public function get_description(): string {
		return esc_html__( 'Display url of your website.', 'shopmagic-for-woocommerce' ) . '<br>' .
				$this->utm_builder->get_description();
	}

	/**
	 * @return mixed[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return $this->utm_builder->get_utm_fields();
	}

	/**
	 * @return mixed[]
	 */
	public function get_required_data_domains(): array {
		return [];
	}

	public function value( array $parameters ): string {
		return $this->utm_builder->append_utm_parameters_to_uri( $parameters, get_bloginfo( 'url' ) );
	}
}
