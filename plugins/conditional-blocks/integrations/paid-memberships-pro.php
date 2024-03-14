<?php
class CB_Paid_Memberships_Pro_Integration {
	private $is_pmpro_active = false;
	private $is_pro = false;

	public function __construct() {
		$this->is_pmpro_active = defined( 'PMPRO_VERSION' );
		
		add_filter( 'conditional_blocks_register_condition_categories', [ $this, 'register_categories' ], 10, 1 );
		add_filter( 'conditional_blocks_register_condition_types', [ $this, 'register_conditions' ], 10, 1 );
			}

	public function register_categories( $categories ) {
		$categories[] = [ 
			'value' => 'paid_memberships_pro',
			'label' => __( 'Paid Memberships Pro (PMPro)', 'conditional-blocks' ),
			'icon' => plugins_url( 'assets/images/mini-colored/paid-memberships-pro.svg', __DIR__ ),
			'tag' => 'plugin',
		];
		return $categories;
	}

	public function register_conditions( $conditions ) {

		$conditions[] = [ 
			'type' => 'pmpro_membership_level',
			'label' => __( 'Membership Level', 'conditional-blocks' ),
			'is_pro' => true,
			'tag' => 'plugin',
			'is_disabled' => ! $this->is_pmpro_active || ! $this->is_pro,
			'description' => __( 'Check if the current user hold a specific membership level.', 'conditional-blocks' ),
			'category' => 'paid_memberships_pro',
			'fields' => [ 
				[ 
					'key' => 'membership_level',
					'type' => 'select',
					'attributes' => [ 
						'label' => __( 'Membership Level', 'conditional-blocks' ),
						'help' => __( 'Select a level', 'conditional-blocks' ),
						'placeholder' => __( 'Select a level', 'conditional-blocks' ),
					],
					'options' => $this->get_membership_options(),
				],
				[ 
					'key' => 'blockAction',
					'type' => 'blockAction',
				],
			],
		];

		$conditions[] = [ 
			'type' => 'pmpro_user_field',
			'label' => __( 'User Field', 'conditional-blocks' ),
			'is_pro' => true,
			'tag' => 'plugin',
			'is_disabled' => ! $this->is_pmpro_active || ! $this->is_pro,
			'description' => __( 'Check a user field value from Paid Memberships Pro.', 'conditional-blocks' ),
			'category' => 'paid_memberships_pro',
			'fields' => [ 
				[ 
					'key' => 'user_field',
					'type' => 'select',
					'attributes' => [ 
						'label' => __( 'User Field', 'conditional-blocks' ),
						'help' => __( 'Select a user field from PMPro', 'conditional-blocks' ),
						'placeholder' => __( 'Select a field', 'conditional-blocks' ),
						'searchable' => true
					],
					'options' => $this->get_pmpro_fields_for_options(),
				],
				[ 
					'key' => 'operator',
					'type' => 'select',
					'attributes' => [ 
						'label' => __( 'Operator', 'conditional-blocks' ),
						'help' => __( 'Select a operator used to check the value', 'conditional-blocks' ),
					],
					'options' => [ 
						[ 'label' => __( 'Has any value', 'conditional-blocks' ), 'value' => 'not_empty' ],
						[ 'label' => __( 'No value', 'conditional-blocks' ), 'value' => 'empty' ],
						[ 'label' => __( 'Equal to', 'conditional-blocks' ), 'value' => 'equal' ],
						[ 'label' => __( 'Not equal to', 'conditional-blocks' ), 'value' => 'not_equal' ],
						[ 'label' => __( 'Contains', 'conditional-blocks' ), 'value' => 'contains' ],
						[ 'label' => __( 'Does not contain', 'conditional-blocks' ), 'value' => 'not_contains' ],
						[ 'label' => __( 'Greater than', 'conditional-blocks' ), 'value' => 'greater_than' ],
						[ 'label' => __( 'Less than', 'conditional-blocks' ), 'value' => 'less_than' ],
						[ 'label' => __( 'Greater than or equal to', 'conditional-blocks' ), 'value' => 'greater_than_or_equal_to' ],
						[ 'label' => __( 'Less than or equal to', 'conditional-blocks' ), 'value' => 'less_than_or_equal_to' ],
					],
				],
				[ 
					'key' => 'expected_value',
					'type' => 'text',
					'requires' => [ 
						'operator' => [ 'equal', 'not_equal', 'contains', 'not_contains', 'greater_than', 'less_than', 'greater_than_or_equal_to', 'less_than_or_equal_to' ],
					],
					'attributes' => [ 
						'label' => __( 'Field Value', 'conditional-blocks' ),
						'help' => __( 'Set the value to compare against.', 'conditional-blocks' ),
					],
				],
			],
		];

		return $conditions;
	}
	
	/**
	 * Helper function to get all user fields for the select field.
	 * @return array
	 */
	function get_pmpro_fields_for_options() {
		global $pmpro_user_fields, $pmpro_field_groups;

		$option_groups = [];

		// Check if any field group exists
		if ( $pmpro_field_groups && $pmpro_user_fields ) {
			foreach ( $pmpro_field_groups as $group ) {

				$options = [];

				// Check if any field exists in the group.
				if ( ! empty( $pmpro_user_fields[ $group->name ] ) ) {
					// Loop through each field in the group.
					foreach ( $pmpro_user_fields[ $group->name ] as $field ) {
						$options[] = [ 
							'label' => $field->label,
							'value' => $field->meta_key,
						];
					}
				}

				$option_groups[] = [ 
					'label' => $group->name,
					'options' => $options,
				];
			}
		}


		return $option_groups;
	}

	/**
	 * Helper function to get all membership levels for the select field.
	 * @return array
	 */
	public function get_membership_options() {

		if ( ! function_exists( 'pmpro_getAllLevels' ) ) {
			return [];
		}

		// Get all level, each level is stored as the key.
		$pmpro_levels = pmpro_getAllLevels();

		$option_groups[] = [ 
			'label' => __( 'General', 'conditional-blocks' ),
			'options' => [ 
				[ 
					'label' => __( 'No Membership', 'conditional-blocks' ),
					'value' => 'no-membership'
				],
				[ 
					'label' => __( 'Any Membership', 'conditional-blocks' ),
					'value' => 'any-membership'
				],
				[ 
					'label' => __( 'Expired Membership', 'conditional-blocks' ),
					'value' => 'expired-membership'
				]
			]
		];


		$level_options = [];

		// Check if any field group exists
		if ( $pmpro_levels ) {
			foreach ( $pmpro_levels as $level ) {
				$level_options[] = [ 
					'label' => $level->name,
					'value' => $level->id
				];
			}
		}

		$option_groups[] = [ 
			'label' => __( 'Membership Levels', 'conditional-blocks' ),
			'options' => $level_options,
		];

		return $option_groups;
	}
}

// Initialize the class to set up the hooks.
new CB_Paid_Memberships_Pro_Integration();
