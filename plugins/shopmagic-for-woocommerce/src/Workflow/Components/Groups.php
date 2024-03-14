<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Components;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Integration\ContactForms\FormEntry;

class Groups {
	/**
	 * @var string
	 */
	public const CUSTOMER = 'customer';

	/**
	 * @var string
	 */
	public const USER = self::CUSTOMER;

	/**
	 * @var string
	 */
	public const POST = 'post';

	/**
	 * @var string
	 */
	public const ORDER = 'order';

	/**
	 * @var string
	 */
	public const PRODUCT = 'product';

	/**
	 * @var string
	 */
	public const CART = 'cart';

	/**
	 * @var string
	 */
	public const MEMBERSHIP = 'membership';

	/**
	 * @var string
	 */
	public const SUBSCRIPTION = 'subscription';

	/**
	 * @var string
	 */
	public const FORM = 'form';

	/**
	 * @var string
	 */
	public const AUTOMATION = 'automation';

	/**
	 * @var string
	 */
	public const SHOP = 'shop';

	/**
	 * @var string
	 */
	public const PRO = 'pro';

	public const COMMENT = 'comment';

	public static function get_group_name( string $group_id ): string {
		$groups = apply_filters(
			'shopmagic/core/groups',
			[
				self::ORDER        => __( 'Orders', 'shopmagic-for-woocommerce' ),
				self::CUSTOMER     => __( 'Customers', 'shopmagic-for-woocommerce' ),
				self::POST         => __( 'Post', 'shopmagic-for-woocommerce' ),
				self::SUBSCRIPTION => __( 'Subscriptions', 'shopmagic-for-woocommerce' ),
				self::CART         => __( 'Carts', 'shopmagic-for-woocommerce' ),
				self::MEMBERSHIP   => __( 'Memberships', 'shopmagic-for-woocommerce' ),
				self::PRO          => __( 'PRO', 'shopmagic-for-woocommerce' ),
				self::FORM         => __( 'Forms', 'shopmagic-for-woocommerce' ),
				self::AUTOMATION   => __( 'Automation', 'shopmagic-for-woocommerce' ),
				self::COMMENT      => __( 'Comment', 'shopmagic-for-woocommerce' ),
			]
		);

		return $groups[ $group_id ] ?: '';
	}


	/**
	 * @param class-string[] $classes
	 *
	 * @deprecated 3.0 Classes should control group on their own. Trying to abstract this would be
	 *             maintenance burden.
	 * @codeCoverageIgnore
	 */
	public static function class_to_group( array $classes ): string {
		foreach ( $classes as $class ) {
			// WC_Subscription has to be first as it extends Order.
			if ( is_a( $class, \WPDesk\ShopMagicCart\Cart\Cart::class, true ) ) {
				return self::CART;
			}

			if ( is_a( $class, \WC_Subscription::class, true ) ) {
				return self::SUBSCRIPTION;
			}

			if ( is_a( $class, \WC_Abstract_Order::class, true ) ) {
				return self::ORDER;
			}

			if ( is_a( $class, \WC_Order::class, true ) ) {
				return self::ORDER;
			}

			if ( is_a( $class, \WC_Product::class, true ) ) {
				return self::PRODUCT;
			}

			if ( is_a( $class, Customer::class, true ) ) {
				return self::USER;
			}

			if ( is_a( $class, \WP_User::class, true ) ) {
				return self::USER;
			}

			if ( is_a( $class, \WC_Memberships_User_Membership::class, true ) ) {
				return self::MEMBERSHIP;
			}

			if ( is_a( $class, FormEntry::class, true ) ) {
				return self::FORM;
			}
		}

		return self::SHOP;
	}

}
