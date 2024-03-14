<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use Exception;

class Campaign {
	public const META_PRODUCTS    = 'products';
	public const META_CLEAR_CART  = 'clear_cart';
	public const META_REDIRECT_TO = 'redirect_to';

	/**
	 * @var int
	 */
	private $campaign_id;

	/**
	 * @param int $campaign_id .
	 */
	public function __construct( int $campaign_id ) {
		$this->campaign_id = $campaign_id;
	}

	/**
	 * @return int
	 */
	public function get_id(): int {
		return $this->campaign_id;
	}

	/**
	 * @return bool
	 */
	public function is_active(): bool {
		return $this->get_status() === 'publish';
	}

	/**
	 * @return string
	 */
	public function get_status(): string {
		return (string) get_post_field( 'post_status', $this->campaign_id );
	}

	/**
	 * @return string
	 */
	public function get_link(): string {
		return (string) get_permalink( $this->campaign_id );
	}

	/**
	 * @return bool
	 */
	public function clear_cart(): bool {
		return $this->get_meta( self::META_CLEAR_CART ) === 'yes';
	}

	/**
	 * @return int
	 */
	public function get_redirect_page_id(): int {
		$redirect_to = (int) $this->get_meta( self::META_REDIRECT_TO );

		if ( $redirect_to && get_post_type( $redirect_to ) === 'page' && get_post_status( $redirect_to ) === 'publish' ) {
			return $redirect_to;
		}

		return wc_get_page_id( 'cart' );
	}

	/**
	 * @return string
	 */
	public function get_redirect_url(): string {
		return (string) get_permalink( $this->get_redirect_page_id() );
	}

	/**
	 * @return CampaignProduct[]
	 */
	public function get_products(): array {
		$products = [];

		foreach ( $this->get_products_data() as $product ) {
			if ( ! is_array( $product ) ) {
				continue;
			}

			$product['id'] = wp_generate_uuid4();

			$campaign_product = new CampaignProduct( $product );

			try {
				$campaign_product->get_product();

				$products[] = $campaign_product;
			} catch ( Exception $e ) {
				// Doing noting.
			}
		}

		return $products;
	}

	/**
	 * @return array
	 */
	private function get_products_data(): array {
		$products = $this->get_meta( self::META_PRODUCTS );

		if ( ! is_array( $products ) ) {
			$products = [];
		}

		return $products;
	}

	/**
	 * @param string $key .
	 *
	 * @return mixed
	 */
	private function get_meta( string $key ) {
		return get_post_meta( $this->campaign_id, $key, true );
	}
}
