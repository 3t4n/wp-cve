<?php

namespace Shopengine_Gutenberg_Addon;

defined('ABSPATH') || exit;

class Block_Config
{

	public function get_active_block_list()
	{

		return $this->block_list();
	}

	public function is_active($block_key)
	{

		//todo - key must be same in both gutenberg and shopengine
		$extra_widgets = [
			'heading'
		];
		//Pass some extra widget that is not listed on shopengine widget list
		if (in_array($block_key, $extra_widgets)) {
			return true;
		}
		return \ShopEngine\Core\Register\Widget_List::instance()->is_widget_active($block_key);
	}

	private function block_list()
	{

		$blocks_list =  [
			'cart-table' => [
				'title'         => esc_html__('Cart Table', 'shopengine-gutenberg-addon'),
				'icon'          => 'editor-table',
				'server_render' => true,
				'category'      => 'shopengine-cart',
				'keywords'      => [
					'woocommerce', 'shopengine', 'cart'
				],
				'territory' => ['cart', 'empty_cart']
			],

			'cart-totals' => [
				'title'         => esc_html__('Cart Totals', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-cart',
				'keywords'      => [
					'woocommerce', 'shopengine', 'cart total'
				],
				'territory' => ['cart', 'empty_cart']
			],

			'checkout-payment' => [
				'title'         => esc_html__('Checkout Payment', 'shopengine-gutenberg-addon'),
				'icon'          => 'clipboard',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'checkout payment', 'checkout', 'shopengine', 'payment method', 'payment'
				],
				'territory' => ['checkout', 'quick_checkout']
			],

			'checkout-form-billing'	=> [
				'title'         => esc_html__('Checkout Form Billing', 'shopengine-gutenberg-addon'),
				'icon'          => 'welcome-widgets-menus',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'woocommerce', 'shopengine', 'checkout', 'checkout form billing'
				],
				'territory' => ['checkout', 'quick_checkout']
			],

			'checkout-form-additional'	=> [
				'title'         => esc_html__('Checkout Form Additional', 'shopengine-gutenberg-addon'),
				'icon'          => 'menu',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'woocommerce', 'shopengine', 'checkout', 'checkout form additional'
				],
				'territory' => ['checkout', 'quick_checkout']
			],

			'return-to-shop'	=> [
				'title'         => esc_html__('Return To Shop', 'shopengine-gutenberg-addon'),
				'icon'          => 'undo',
				'server_render' => true,
				'category'      => 'shopengine-cart',
				'keywords'      => [
					'return', 'return to shop', 'shop', 'shopengine'
				],
				'territory' => ['cart', 'single', 'checkout', 'quick_checkout', 'empty_cart']
			],

			'filter-orderby'	=> [
				'title'         => esc_html__('Order By Filter', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'shop', 'store', 'title', 'heading', 'product'
				],
				'territory' => ['shop', 'archive']
			],

			'filter-products-per-page'	=> [
				'title'         => esc_html__('Products Per Page Filter', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'shop', 'store', 'products per page', 'product'
				],
				'territory' => ['shop', 'archive']
			],

			'product-title'	=> [
				'title'         => esc_html__('Product Title', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shop', 'store', 'title', 'heading', 'product'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'additional-information' => [
				'title'         => esc_html__('Additional Information', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'shopengine', 'woocommerce', 'additional information', 'single product'
				],
				'territory' => ['single', 'quick_view', 'account_orders_view', 'quick_checkout']
			],

			'product-categories' => [
				'title'         => esc_html__('Product Categories', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'category', 'product categories'
				],
				'territory' => ['single']
			],

			'product-excerpt'	=> [
				'title'         => esc_html__('Product Excerpt', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'excerpt', 'product excerpt'
				],
				'territory' => ['single', 'quick_checkout', 'quick_view']
			],

			'product-review'	=> [
				'title'         => esc_html__('Product Review', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'shopengine', 'woocommerce', 'product reviews', 'product review'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'product-share'	=> [
				'title'         => esc_html__('Product Share', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'share', 'social share'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'product-stock'	=> [
				'title'         => esc_html__('Product Stock', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'stock', 'shopengine'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'archive-description'	=> [
				'title'         => esc_html__('Archive Description', 'shopengine-gutenberg-addon'),
				'icon'          => 'smiley',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'shopengine', 'archive', 'archive products'
				],
				'territory' => ['shop', 'archive']
			],

			'archive-result-count'	=> [
				'title'         => esc_html__('Archive Result Count', 'shopengine-gutenberg-addon'),
				'icon'          => 'smiley',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'title', 'archive', 'archive result count', 'result count'
				],
				'territory' => ['shop', 'archive']
			],

			'archive-title'	=> [
				'title'         => esc_html__('Archive Title', 'shopengine-gutenberg-addon'),
				'icon'          => 'smiley',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'title', 'archive', 'archive title', 'page title'
				],
				'territory' => ['shop', 'archive']
			],

			'notice'	=> [
				'title'         => esc_html__('Notice', 'shopengine-gutenberg-addon'),
				'icon'          => 'bell',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'checkout', 'notice', 'single'
				],
				'territory' => ['single', 'checkout', 'quick_checkout']
			],

			'product-category-lists'	=> [
				'title'         => esc_html__('Product Category List', 'shopengine-gutenberg-addon'),
				'icon'          => 'smiley',
				'server_render' => true,
				'category'      => 'shopengine-general',
				'keywords'      => [
					'woocommerce', 'shopengine', 'category', 'product category lists'
				],
				'territory' => []
			],

			'product-rating'	=> [
				'title'         => esc_html__('Product Rating', 'shopengine-gutenberg-addon'),
				'icon'          => 'smiley',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'rating', 'product', 'single product', 'review', 'comments', 'stars'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'product-tags'	=>  [
				'title'         => esc_html__('Product Tags', 'shopengine-gutenberg-addon'),
				'icon'          => 'tag',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'tags', 'product tags'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'product-meta'	=>  [
				'title'         => esc_html__('Product Meta', 'shopengine-gutenberg-addon'),
				'icon'          => 'admin-generic',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'meta', 'product meta', 'Single product meta'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'recently-viewed-products'	=>  [
				'title'         => esc_html__('Recently Viewed Products', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-general',
				'keywords'      => [
					'woocommerce', 'recently', 'viewed', 'product', 'single product'
				],
				'territory' => []
			],

			'view-single-product'	=>  [
				'title'         => esc_html__('View Single Product', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'product', 'single', 'view single product', 'shopengine'
				],
				'territory' => ['quick_view']
			],

			'checkout-form-shipping'	=>  [
				'title'         => esc_html__('Checkout Form Shipping', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'checkout', 'shopengine', 'checkout form shipping', 'form', 'shopengine'
				],
				'territory' => ['checkout', 'quick_checkout']
			],

			'checkout-review-order'	=>  [
				'title'         => esc_html__('Checkout Review Order', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'checkout', 'shopengine', 'checkout review orders', 'review orders'
				],
				'territory' => ['checkout', 'quick_checkout']
			],

			'checkout-shipping-methods'	=>  [
				'title'         => esc_html__('Checkout Shipping Methods', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'checkout', 'shopengine', 'checkout shipping method', 'shipping method'
				],
				'territory' => ['checkout', 'quick_checkout']
			],

			'add-to-cart'	=>  [
				'title'         => esc_html__('Add To Cart', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'cart', 'add to cart'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'breadcrumbs'	=>  [
				'title'         => esc_html__('Breadcrumbs', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'Breadcrumbs'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout', 'shop', 'archive', 'checkout']
			],

			'product-price'	=>  [
				'title'         => esc_html__('Product Price', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'shopengine', 'price', 'product', 'single product'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'product-image'	=>  [
				'title'         => esc_html__('Product Image', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shop', 'shopengine', 'image', 'product', 'gallery', 'lightbox'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'advanced-search'	=>  [
				'title'         => esc_html__('Advanced Search', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-general',
				'keywords'      => [
					'woocommerce', 'shop', 'archive', 'advanced search', 'search', 'advanced', 'shopengine'
				],
				'territory' => []
			],

			'product-list'	=>  [
				'title'         => esc_html__('Product List', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'shopengine', 'product', 'product list'
				],
				'territory' => []
			],

			'product-tabs'	=>  [
				'title'         => esc_html__('Product Tabs', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'shopengine', 'woocommerce', 'product tabs', 'tabs'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'checkout-coupon-form'	=>  [
				'title'         => esc_html__('Coupon Form', 'shopengine-gutenberg-addon'),
				'icon'          => 'tickets',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'shopengine', 'woocommerce', 'coupon', 'coupon form'
				],
				'territory' => ['checkout', 'cart', 'quick_checkout']
			],

			'filterable-product-list'	=>  [
				'title'         => esc_html__('Filterable Product List', 'shopengine-gutenberg-addon'),
				'icon'          => 'smiley',
				'server_render' => true,
				'category'      => 'shopengine-general',
				'keywords'      => [
					'woocommerce', 'filter', 'filterable product list', 'product list', 'list', 'shopengine'
				],
				'territory' => []
			],

			'archive-products'	=>  [
				'title'         => esc_html__('Archive Products', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'shopengine', 'archive', 'archive products'
				],
				'territory' => ['shop', 'archive']
			],

			'cross-sells'	=>  [
				'title'         => esc_html__('Cross Sells', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-cart',
				'keywords'      => [
					'woocommerce', 'shop', 'Cross Sells', 'cart', 'product', 'table', 'tabs', 'Sells'
				],
				'territory' => ['cart', 'quick_checkout']
			],

			'deal-products'	=>  [
				'title'         => esc_html__('Deal Products', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-general',
				'keywords'      => [
					'woocommerce', 'shopengine', 'deal products', 'deal', 'product'
				],
				'territory' => []
			],

			'up-sells'	=>  [
				'title'         => esc_html__('Product Upsells', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'upsells', 'product', 'single product'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'related'	=>  [
				'title'         => esc_html__('Related Products', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'related', 'product', 'single product'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'product-description'	=>  [
				'title'         => esc_html__('Product Description', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'description', 'content'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			'product-sku'	=>  [
				'title'         => esc_html__('Product Sku', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-single',
				'keywords'      => [
					'woocommerce', 'shopengine', 'sku', 'product sku'
				],
				'territory' => ['single', 'quick_view', 'quick_checkout']
			],

			//gutenova extra Widget

			'heading' => [
				'title'         => esc_html__('Shopengine Heading', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-general',
				'keywords'      => [
					'heading', 'shopengine', 'title'
				],
				'territory' => []
			],

			'checkout-form-login' => [
				'title'   		=> esc_html__('Checkout Form Login', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-checkout',
				'keywords'      => [
					'checkout', 'login form', 'checkout login'
				],
				'territory' => ['checkout']
			],

			'archive-view-mode'	=>  [
				'title'   => esc_html__('Archive View Mode', 'shopengine-gutenberg-addon'),
				'icon'          => 'universal-access-alt',
				'server_render' => true,
				'category'      => 'shopengine-archive',
				'keywords'      => [
					'woocommerce', 'view', 'view mode', 'archive view mode', 'shopengine'
				],
				'territory' => ['shop', 'archive']
			],
		];

		if (class_exists('ShopEngine_Pro')) {
			add_filter('shopengine/widgets/list', [$this, 'get_list']);
			$blocks_list_pro = [
				'thankyou-thankyou'	=>  [
					'title'         => esc_html__('Order Thank You', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-order',
					'keywords'      => [
						'woocommerce', 'shopengine', 'thank you'
					],
					'territory' => ['order']
				],

				'thankyou-order-confirm'	=>  [
					'title'         => esc_html__('Order Confirm', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-order',
					'keywords'      => [
						'woocommerce', 'shopengine', 'order confirmation', 'thank you'
					],
					'territory' => ['order']
				],

				'thankyou-order-details'	=>  [
					'title'         => esc_html__('Order Details', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-order',
					'keywords'      => [
						'woocommerce', 'shopengine', 'order details', 'thank you'
					],
					'territory' => ['order']
				],

				'thankyou-address-details'	=>  [
					'title'         => esc_html__('Address details', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-order',
					'keywords'      => [
						'woocommerce', 'shopengine', 'address details', 'thank you'
					],
					'territory' => ['order']
				],

				'account-form-login'	=>  [
					'title'         => esc_html__('My Account Form Login', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'dashboard', 'my account form', 'login', 'my account'
					],
					'territory' => ['my_account_login', 'my_account', 'checkout_without_account']
				],

				'account-form-register'	=>  [
					'title'         => esc_html__('Account Register Form', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'my account', 'account register form'
					],
					'territory' => ['my_account', 'my_account_login', 'checkout_without_account']
				],

				'account-orders'	=>  [
					'title'         => esc_html__('My Account Orders', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'account orders', 'shopengine', 'account'
					],
					'territory' => ['my_account', 'account_orders']
				],

				'account-downloads'	=>  [
					'title'         => esc_html__('Account Downloads', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'my account', 'downloads', 'account downloads'
					],
					'territory' => ['account_downloads', 'my_account']
				],

				'account-dashboard'	=>  [
					'title'         => esc_html__('Account dashboard', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'my account', 'account dashboard'
					],
					'territory' => ['my_account']
				],

				'account-details'	=>  [
					'title'         => esc_html__('Account Details', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'my account', 'dashboard', 'account details'
					],
					'territory' => ['account_edit_account', 'my_account', 'account_edit_address']
				],


				'account-address'	=>  [
					'title'         => esc_html__('Account Address', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'my account', 'account address'
					],
					'territory' => ['my_account', 'account_edit_account', 'account_edit_address']
				],


				'account-order-details'	=>  [
					'title'         => esc_html__('Account Order Details', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'dashboard', 'account order details'
					],
					'territory' => ['account_orders_view']
				],

				'categories' => [
					'title'         => esc_html__('Categories', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'category', 'categories', 'general',
					],
					'territory' => []
				],
				'currency-switcher' => [
					'title'         => esc_html__('Currency Switcher', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'woocommerce', 'shop', 'currency', 'currency switcher', 'money', 'switcher', 'shopengine'
					],
					'territory' => []
				],
				'flash-sale-products' => [
					'title'         => esc_html__('Flash Sale Products', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'woocommerce', 'shopengine', 'flash sale products', 'sale', 'product'
					],
					'territory' => []
				],

				'best-selling-product' => [
					'title'         => esc_html__('Best Selling Product', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'woocommerce', 'best selling product', 'shopengine', 'product', 'best', 'sell'
					],
					'territory' => []
				],
				'comparison-button' => [
					'title'         => esc_html__('Comparison Button', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'woocommerce', 'shopengine', 'comparison button', 'compare button'
					],
					'territory' => []
				],
				'account-navigation' => [
					'title'         => esc_html__('Account Navigation', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'my account', 'dashboard', 'account navigation'
					],
					'territory' => ['my_account', 'account_orders', 'account_orders_view', 'account_downloads', 'account_edit_address', 'account_edit_account', 'my_account_login']
				],
				'account-logout' => [
					'title'         => esc_html__('Account Logout', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-my-account',
					'keywords'      => [
						'woocommerce', 'shopengine', 'logout', 'my account'
					],
					'territory' => ['my_account', 'my_account_login', 'account_downloads', 'account_edit_account', 'account_edit_address', 'account_orders_view', 'account_orders']
				],
				'advanced-coupon' => [
					'title'         => esc_html__('Advanced Coupon', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'woocommerce', 'shopengine', 'advanced-coupon'
					],
					'territory' => []
				],
				'vacation' => [
					'title'         => esc_html__('Vacation Notice', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'woocommerce', 'shopengine', 'vacation'
					],
					'territory' => []
				],
				'product-size-charts' => [
					'title'         => esc_html__('Product Size Charts', 'shopengine-gutenberg-addon'),
					
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-single',
					'keywords'      => [
						'woocommerce', 'charts', 'size', 'single'
					],
					'territory' => ['single', 'quick_view']
				],
				'product-filters' => [
					'title'         => esc_html__('Product Filters', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-archive',
					'keywords'      => [
						'woocommerce', 'shop', 'store', 'title', 'heading', 'product', 'ajax'
					],
					'territory' => ['shop', 'archive']
				],
				'avatar' => [
					'title'         => esc_html__('Avatar', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'design',
					'keywords'      => [
						'woocommerce', 'account', 'avatar', 'profile', 'user', 'info'
					],
					'territory' => ['my_account', 'account_orders', 'account_orders_view', 'account_downloads', 'account_edit_address', 'account_edit_account']
				],
				'checkout-order-pay' => [
					'title'   		=> esc_html__('Checkout Order Pay', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-checkout',
					'keywords'      => [
						'checkout', 'order pay', 'order'
					],
					'territory' => []
				],
				'account-lost-password-form' => [
					'title'   		=> esc_html__('Account Lost Password Form', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'account lost', 'lost password', 'password form', 'lost from', 'lost', 'account lost password form'
					],
					'territory' => []
				],
				'account-reset-password' => [
					'title'   		=> esc_html__('Account Reset Password Form', 'shopengine-gutenberg-addon'),
					'icon'          => 'universal-access-alt',
					'server_render' => true,
					'category'      => 'shopengine-general',
					'keywords'      => [
						'account reset', 'reset password', 'password form', 'reset from', 'reset', 'account reset password form'
					],
					'territory' => []
				]
			];

			$combine_list = array_merge($blocks_list, $blocks_list_pro);
			return $combine_list;
		}

		return $blocks_list;
	}

	public function get_list($list)
	{
		$pro_list = [
			'account-dashboard'          => [
				'slug'    => 'account-dashboard',
				'title'   => esc_html__('Account Dashboard', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-address'          => [
				'slug'    => 'account-address',
				'title'   => esc_html__('Account Address', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-details'          => [
				'slug'    => 'account-details',
				'title'   => esc_html__('Account Details', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-downloads'        => [
				'slug'    => 'account-downloads',
				'title'   => esc_html__('Account Downloads', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-form-login'       => [
				'slug'    => 'account-form-login',
				'title'   => esc_html__('Account Form - Login', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-form-register'    => [
				'slug'    => 'account-form-register',
				'title'   => esc_html__('Account Form - Register', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-logout'           => [
				'slug'    => 'account-logout',
				'title'   => esc_html__('Account Logout', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-navigation'       => [
				'slug'    => 'account-navigation',
				'title'   => esc_html__('Account Navigation', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-order-details'    => [
				'slug'    => 'account-order-details',
				'title'   => esc_html__('Account Order - Details', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-orders'           => [
				'slug'    => 'account-orders',
				'title'   => esc_html__('Account Orders', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'categories'               => [
				'slug'    => 'categories',
				'title'   => esc_html__('Categories', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'product-filters'          => [
				'slug'    => 'product-filters',
				'title'   => esc_html__('Product Filters', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'thankyou-address-details' => [
				'slug'    => 'thankyou-address-details',
				'title'   => esc_html__('Thank You Address Details', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'thankyou-order-confirm'   => [
				'slug'    => 'thankyou-order-confirm',
				'title'   => esc_html__('Order Confirm', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'thankyou-order-details'   => [
				'slug'    => 'thankyou-order-details',
				'title'   => esc_html__('Order Details', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'thankyou-thankyou'        => [
				'slug'    => 'thankyou-thankyou',
				'title'   => esc_html__('Order Thank You', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'currency-switcher' => [
				'slug'	=> 'currency-switcher',
				'title' => esc_html__('Currency Switcher', 'shopengine-gutenberg-addon'),
				'package' => 'pro'
			],
			'flash-sale-products'       => [
				'slug'    => 'flash-sale-products',
				'title'   => esc_html__('Flash Sale Products', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'best-selling-product'       => [
				'slug'    => 'best-selling-product',
				'title'   => esc_html__('Best Selling Product', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'comparison-button'         => [
				'slug'    => 'comparison-button',
				'title'   => esc_html__('Comparison Button', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'product-size-charts'         => [
				'slug'    => 'product-size-charts',
				'title'   => esc_html__('Product Size Chart', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'vacation'         => [
				'slug'    => 'vacation',
				'title'   => esc_html__('Vacation', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'advanced-coupon'         => [
				'slug'    => 'advanced-coupon',
				'title'   => esc_html__('Advanced Coupon', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'avatar'         => [
				'slug'    => 'avatar',
				'title'   => esc_html__('Avatar', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'checkout-order-pay'         => [
				'slug'    => 'checkout-order-pay',
				'title'   => esc_html__('Checkout Order Pay', 'shopengine-gutenberg-addon'),
				'package' => 'pro'
			],
			'account-lost-password-form'         => [
				'slug'    => 'account-lost-password-form',
				'title'   => esc_html__('Account Lost Password Form', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			],
			'account-reset-password'         => [
				'slug'    => 'account-reset-password',
				'title'   => esc_html__('Account Reset Password Form', 'shopengine-gutenberg-addon'),
				'package' => 'pro',
			]
		];

		return array_merge($list, array_map(function ($v) {
			$v['path'] = \ShopEngine_Pro::widget_dir() . $v['slug'] . '/';
			return $v;
		}, $pro_list));
	}
}
