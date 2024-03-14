<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerFactory;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

class OrderTestProvider implements DataProvider {
	/** @var CustomerFactory */
	private $customer_factory;

	public function __construct( CustomerFactory $customer_factory ) {
		$this->customer_factory = $customer_factory;
	}

	public function get_provided_data_domains(): array {
		return [ \WC_Order::class, Customer::class, \WP_User::class ];
	}

	public function get_provided_data(): DataLayer {
		$order = $this->get_order();

		$data_layer = new DataLayer( [ \WC_Order::class => $order ] );

		if ( $order->get_user() instanceof \WP_User ) {
			$data_layer->set( \WP_User::class, $order->get_user() );
			$data_layer->set(
				Customer::class,
				$this->customer_factory->create_from_user_and_order( $order->get_user(), $order ) );
		}

		return $data_layer;
	}

	private function get_order(): \WC_Order {
		[ $order ] = wc_get_orders(
			[
				'limit' => 1,
				'orderby' => 'date_created',
				'order' => 'DESC',
				'type' => 'shop_order',
				'status' => array_filter(
					array_keys( wc_get_order_statuses() ),
					static function ( string $status ): bool {
						return 'wc-refunded' !== $status;
					}
				),
			]
		);

		if ( $order instanceof \WC_Order ) {
			return $order;
		}

		return $this->get_stub_order_data();
	}

	private function get_stub_order_data(): \WC_Order {
		return new class() extends \WC_Order {

			/**
			 * @var int[]|string[]|array<string, string>[]|true[]|null[]&mixed[]
			 */
			protected $data = [
				// Abstract order props.
				'parent_id'            => 0,
				'status'               => 'completed',
				'currency'             => 'USD',
				'version'              => '',
				'prices_include_tax'   => false,
				'date_created'         => null,
				'date_modified'        => null,
				'discount_total'       => '0',
				'discount_tax'         => '0',
				'shipping_total'       => '0',
				'shipping_tax'         => '0',
				'cart_tax'             => '0',
				'total'                => '123.3',
				'total_tax'            => '0',

				// Order props.
				'customer_id'          => 0,
				'order_key'            => '',
				'billing'              => [
					'first_name' => 'Andrew',
					'last_name'  => 'Jonte',
					'company'    => 'Acme Inc.',
					'address_1'  => 'Silicon Valley',
					'address_2'  => '23/54',
					'city'       => 'San Francisco',
					'state'      => '',
					'postcode'   => 'AXZ AYX',
					'country'    => 'US',
					'email'      => 'ajonte@acme.com',
					'phone'      => '123123123',
				],
				'shipping'             => [
					'first_name' => 'Andrew',
					'last_name'  => 'Jonte',
					'company'    => 'Acme Inc.',
					'address_1'  => 'Silicon Valley',
					'address_2'  => '23/54',
					'city'       => 'San Francisco',
					'state'      => '',
					'postcode'   => 'AXZ AYX',
					'country'    => 'US',
					'email'      => 'ajonte@acme.com',
					'phone'      => '123123123',
				],
				'payment_method'       => 'bacs',
				'payment_method_title' => 'Bank transfer',
				'transaction_id'       => '',
				'customer_ip_address'  => '',
				'customer_user_agent'  => '',
				'created_via'          => 'checkout',
				'customer_note'        => '',
				'date_completed'       => null,
				'date_paid'            => null,
				'cart_hash'            => '',
			];

			public function get_date_completed( $context = 'view' ): \DateTimeImmutable {
				return new \DateTimeImmutable( '+2 days' );
			}

			public function get_date_created( $context = 'view' ): \DateTimeImmutable {
				return new \DateTimeImmutable();
			}

			public function get_date_paid( $context = 'view' ): \DateTimeImmutable {
				return new \DateTimeImmutable( '+1 day' );
			}

			/**
			 * @return \WC_Order_Item_Product[]
			 */
			public function get_items( $types = 'line_item' ): array {
				return [
					new class() extends \WC_Order_Item_Product {
						public function get_product(): object {
							return new class() extends \WC_Product_Simple {

								/**
								 * @var int[]|string[]|mixed[][]|bool[]|null[]|mixed[]
								 */
								protected $data = [
									'name'               => 'ShopMagic Test Product',
									'slug'               => 'shopmagic-test-product',
									'date_created'       => null,
									'date_modified'      => null,
									'status'             => false,
									'featured'           => false,
									'catalog_visibility' => 'visible',
									'description'        => '',
									'short_description'  => '',
									'sku'                => '',
									'price'              => '',
									'regular_price'      => '',
									'sale_price'         => '',
									'date_on_sale_from'  => null,
									'date_on_sale_to'    => null,
									'total_sales'        => '0',
									'tax_status'         => 'taxable',
									'tax_class'          => '',
									'manage_stock'       => false,
									'stock_quantity'     => null,
									'stock_status'       => 'instock',
									'backorders'         => 'no',
									'low_stock_amount'   => '',
									'sold_individually'  => false,
									'weight'             => '',
									'length'             => '',
									'width'              => '',
									'height'             => '',
									'upsell_ids'         => [],
									'cross_sell_ids'     => [],
									'parent_id'          => 0,
									'reviews_allowed'    => true,
									'purchase_note'      => '',
									'attributes'         => [],
									'default_attributes' => [],
									'menu_order'         => 0,
									'post_password'      => '',
									'virtual'            => false,
									'downloadable'       => false,
									'category_ids'       => [],
									'tag_ids'            => [],
									'shipping_class_id'  => 0,
									'downloads'          => [],
									'image_id'           => '',
									'gallery_image_ids'  => [],
									'download_limit'     => - 1,
									'download_expiry'    => - 1,
									'rating_counts'      => [],
									'average_rating'     => 0,
									'review_count'       => 0,
								];

							};
						}
					},
				];
			}
		};
	}
}
