<?php
class CB_EDD_Integration {
	private $is_edd_active = false;
	private $is_pro = false;

	public function __construct() {
		$this->is_edd_active = function_exists( 'EDD' );
		
		add_filter( 'conditional_blocks_register_condition_categories', [ $this, 'register_categories' ], 10, 1 );
		add_filter( 'conditional_blocks_register_condition_types', [ $this, 'register_conditions' ], 10, 1 );

			}

	public function register_categories( $categories ) {
		$categories[] = [ 
			'value' => 'easy_digital_downloads',
			'label' => 'Easy Digital Downloads',
			'icon' => plugins_url( 'assets/images/mini-colored/easy-digital-downloads.svg', __DIR__ ), // URL or path to your icon, or dashicon name.
			'tag' => 'plugin',
		];
		return $categories;
	}
	public function register_conditions( $conditions ) {

		$conditions[] = [ 
			'type' => 'edd_cart_value',
			'label' => __( 'Cart Value', 'conditional-blocks' ),
			'is_pro' => true,
			'tag' => 'plugin',
			'is_disabled' => ! $this->is_edd_active || ! $this->is_pro,
			'description' => __( 'Trigger the Block Action depending on the current customer cart value in Easy Digital Downloads.', 'conditional-blocks' ),
			'category' => 'easy_digital_downloads',
			'fields' => [ 
				[ 
					'key' => 'more_than',
					'type' => 'number',
					'attributes' => [ 
						'label' => __( 'More than', 'conditional-blocks' ),
						'value' => false,
						'placeholder' => __( 'Any Value', 'conditional-blocks' ),
						'help' => __( 'Leave blank for any value.', 'conditional-blocks' ),
					],
				],
				[ 
					'key' => 'less_than',
					'type' => 'number',
					'attributes' => [ 
						'label' => __( 'Less than', 'conditional-blocks' ),
						'value' => false,
						'placeholder' => __( 'Any Value', 'conditional-blocks' ),
						'help' => __( 'Leave blank for any value.', 'conditional-blocks' ),
					],
				],
				[ 
					'key' => 'blockAction',
					'type' => 'blockAction',
				],
			],
		];

		$conditions[] = [ 
			'type' => 'edd_items_in_cart',
			'label' => __( 'Product in Cart', 'conditional-blocks' ),
			'is_pro' => true,
			'tag' => 'plugin',
			'is_disabled' => ! $this->is_edd_active || ! $this->is_pro,
			'description' => __( 'Check if selected product is found in the customers cart.', 'conditional-blocks' ),
			'category' => 'easy_digital_downloads',
			'fields' => [ 
				[ 
					'key' => 'products',
					'type' => 'EDDProductSelect',
					'attributes' => [ 
						'label' => __( 'Products', 'conditional-blocks' ),
						'help' => __( 'Select a product to check for in the cart.', 'conditional-blocks' ),
						'placeholder' => __( 'Select Product', 'conditional-blocks' ),
						'multiple' => true,
					],
				],
				[ 
					'key' => 'blockAction',
					'type' => 'blockAction',
				],
			],
		];

		// EDD Product Categories in Cart.
		$conditions[] = [ 
			'type' => 'edd_product_category_in_cart',
			'label' => __( 'Product Category in Cart', 'conditional-blocks' ),
			'is_pro' => true,
			'tag' => 'plugin',
			'is_disabled' => ! $this->is_edd_active || ! $this->is_pro,
			'description' => __( 'Check if selected product category are found in the customers cart.', 'conditional-blocks' ),
			'category' => 'easy_digital_downloads',
			'fields' => [ 
				[ 
					'key' => 'categories',
					'type' => 'EDDProductCategorySelect',
					'attributes' => [ 
						'label' => __( 'Product Category', 'conditional-blocks' ),
						'help' => __( 'Select a product category to check for in the cart.', 'conditional-blocks' ),
						'placeholder' => __( 'Select Product Category', 'conditional-blocks' ),
					],
				],
				[ 
					'key' => 'blockAction',
					'type' => 'blockAction',
				],
			],
		];

		$conditions[] = [ 
			'type' => 'edd_user_recurring_subscription',
			'label' => __( 'Recurring Subscription', 'conditional-blocks' ),
			'is_pro' => true,
			'tag' => 'plugin',
			'is_disabled' => ! $this->is_edd_active || ! $this->is_pro || ! class_exists( 'EDD_Recurring_Subscriber' ),
			'description' => __( 'Check if the current user has a recurring subscription, optionally check for a selected product or status. This condition requires the EDD Recurring Payments extension.', 'conditional-blocks' ),
			'category' => 'easy_digital_downloads',
			'fields' => [ 
				[ 
					'key' => 'product',
					'type' => 'EDDProductSelect',
					'attributes' => [ 
						'label' => __( 'Product', 'conditional-blocks' ),
						'help' => __( 'Select product to check for a specific subscription, or leave blank for any.', 'conditional-blocks' ),
						'placeholder' => __( 'Select Product Subscription (Leave blank for any)', 'conditional-blocks' ),
					],
				],
				[ 
					'key' => 'statues',
					'type' => 'select',
					'attributes' => [ 
						'label' => __( 'Subscription Status', 'conditional-blocks' ),
						'help' => __( 'Select one or multiple statuses. Leave blank for any.', 'conditional-blocks' ), //  active, pending, cancelled, expired, trialling, failing, completed.
						'placeholder' => __( 'Select Subscription Status', 'conditional-blocks' ),
						'multiple' => true,
					],
					'options' => [ 
						[ 
							'value' => 'active',
							'label' => __( 'Active', 'conditional-blocks' ),
						],
						[ 
							'value' => 'pending',
							'label' => __( 'Pending', 'conditional-blocks' ),
						],
						[ 
							'value' => 'cancelled',
							'label' => __( 'Cancelled', 'conditional-blocks' ),
						],
						[ 
							'value' => 'expired',
							'label' => __( 'Expired', 'conditional-blocks' ),
						],
						[ 
							'value' => 'trialling',
							'label' => __( 'Trialling', 'conditional-blocks' ),
						],
						[ 
							'value' => 'failing',
							'label' => __( 'Failing', 'conditional-blocks' ),
						],
						[ 
							'value' => 'completed',
							'label' => __( 'Completed', 'conditional-blocks' ),
						],
					],
				],
				[ 
					'key' => 'blockAction',
					'type' => 'blockAction',
				],
			],
		];

		// EDD Customer has purchased specific product.
		$conditions[] = [ 
			'type' => 'edd_customer_purchase',
			'label' => __( 'Product Purchased', 'conditional-blocks' ),
			'is_pro' => true,
			'tag' => 'plugin',
			'is_disabled' => ! $this->is_edd_active || ! $this->is_pro,
			'description' => __( 'Check if the current user has purchased a specific product, or any product', 'conditional-blocks' ),
			'category' => 'easy_digital_downloads',
			'fields' => [ 
				[ 
					'key' => 'product',
					'type' => 'EDDProductSelect',
					'attributes' => [ 
						'label' => __( 'Product', 'conditional-blocks' ),
						'help' => __( 'Select a product from Easy Digital Downloads', 'conditional-blocks' ),
						'placeholder' => __( 'Select Product (Leave blank for any)', 'conditional-blocks' ),
					],
				],
				[ 
					'key' => 'blockAction',
					'type' => 'blockAction',
				],
			],
		];

		return $conditions;
	}

	}

// Initialize the class to set up the hooks.
new CB_EDD_Integration();
