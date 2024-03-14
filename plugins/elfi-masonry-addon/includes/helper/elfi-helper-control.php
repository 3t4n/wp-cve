<?php 

	use \Elementor\Controls_Manager;
	use \Elementor\Group_Control_Background;
	use \Elementor\Group_Control_Typography;

	trait elfiLightHelper{

	function elfi_Content_Settings(){

		$this->start_controls_section(

			'content_section',
			[
				'label' => esc_html__( 'Query Settings', 'elfi-masonry-addon' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);


	$this->add_control(
		'elfi_post_type',
		[
			'label' => esc_html__( 'Post Type Name', 'elfi-masonry-addon' ),
			'description' =>  esc_html__( 'Select Post Type', 'elfi-masonry-addon' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'elfi',
			'options' => elfi_light_selct_post_type(),
		]
	);
		$this->add_control(
			'elfi_display_types',
			[
				'label' => __( 'Types of sorting:', 'elfi-masonry-addon' ),
			'description' =>  esc_html__( 'Masonry display based on categories or posts?', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'category',
				'options' => [
					'category'  => __( 'Categories', 'elfi-masonry-addon' ),
					'posts' => __( 'Posts', 'elfi-masonry-addon' ),

				],
			]
		);


		$this->add_control(
			'elfi_portfolio',
			[
				'label' => esc_html__( 'Portfolio Categories', 'elfi-masonry-addon' ),	
				'description' =>  esc_html__( 'Choose Multiple Category', 'elfi-masonry-addon' ),		
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,

				'options' => elfi_light_cat_type_portfolio(),

				'condition' => [
					'elfi_post_type' => 'elfi',
					'elfi_display_types' => 'category',

				],
			]
		);		
	$this->add_control(
		'elfi_portfolio_selcttype',
		[
			'label' => esc_html__( 'Categories (Premium)', 'elfi-masonry-addon' ),	
			'description' =>  esc_html__( 'Choose Multiple Category', 'elfi-masonry-addon' ),		
			'type' => Controls_Manager::SELECT2,
			'multiple' => true,

			'options' => elfilifg_cat_type_selct_posttype(),
			'conditions' => [
				'relation' => 'and',
				'terms' =>
				 [
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'post',
					],
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'product',
					],
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'elfi',
					],
					[
						'name' => 'elfi_display_types',
						'operator' => '!==',
						'value' => 'posts',
					],
				],
				],

		]
	);

	$this->add_control(
		'elfi_taxonomy_selcttype',
		[
			'label' => esc_html__( 'Taxonomy Name', 'elfi-masonry-addon' ),	
			'description' =>  esc_html__( 'Select the taxonomy name according to the Post type', 'elfi-masonry-addon' ),		
			'type' => Controls_Manager::SELECT,
			'options' => elfilight_taxonmyname_type(),
			'conditions' => [
				'relation' => 'and',
				'terms' =>
				 [
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'post',
					],
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'product',
					],
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'elfi',
					],
					[
						'name' => 'elfi_display_types',
						'operator' => '!==',
						'value' => 'posts',
					],
				],
				],

		]
	);
	
		$this->add_control(
			'elfi_portfolio_not_in',
			[
				'label' => esc_html__( 'Exclude ', 'elfi-masonry-addon' ),	
				'description' =>  esc_html__( 'Exclude Portfolio Item', 'elfi-masonry-addon' ),		
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,

				'options' => elfi_light_cat_portfolio_not_in(),

				'condition' => [
					'elfi_post_type' => 'elfi',
					'elfi_display_types' => 'category',

				],
			]
		);

		if(class_exists('WooCommerce')){
		$this->add_control(
			'elfi_product',
			[
				'label' => esc_html__( 'Product Categories', 'elfi-masonry-addon' ),
				'description' =>  esc_html__( 'Choose Multiple Category', 'elfi-masonry-addon' ),			
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,

				'options' => elfilight_cat_type_product(),
				'condition' => [
					'elfi_post_type' => 'product',
					'elfi_display_types' => 'category',

				],
			]
		);

		$this->add_control(
			'elfipro_product_not_in',
			[
				'label' => esc_html__( 'Exclude ', 'elfi-masonry-addon' ),	
				'description' =>  esc_html__( 'Exclude Product Item', 'elfi-masonry-addon' ),		
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,

				'options' => elfilight_product_not_in(),

				'condition' => [
					'elfi_post_type' => 'product',
					'elfi_display_types' => 'category',

				],
			]
		);




	}
$this->add_control(
		'elfipro_custompost_not_in',
		[
			'label' => esc_html__( 'Exclude ', 'elfi-masonry-addon' ),	
			'description' =>  esc_html__( 'Exclude Post Item', 'elfi-masonry-addon' ),		
			'type' => Controls_Manager::SELECT2,
			'multiple' => true,

			'options' => elfilight_custompost_not_in(),

			'conditions' => [
				'relation' => 'and',
				'terms' =>
				 [
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'post',
					],
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'product',
					],
					[
						'name' => 'elfi_post_type',
						'operator' => '!==',
						'value' => 'elfi',
					],
					[
						'name' => 'elfi_display_types',
						'operator' => '!==',
						'value' => 'posts',
					],
				],
				],
		]
	);

		$this->add_control(
			'elfi_post',
			[
				'label' => esc_html__( 'Post Categories', 'elfi-masonry-addon' ),
				'description' =>  esc_html__( 'Choose Multiple Category', 'elfi-masonry-addon' ),			
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,

				'options' => elfi_light_cat_type_post(),
				'condition' => [
					'elfi_post_type' => 'post',
					'elfi_display_types' => 'category',

				],
			]
		);
		$this->add_control(
			'elfi_light_post_not_in',
			[
				'label' => esc_html__( 'Exclude ', 'elfi-masonry-addon' ),	
				'description' =>  esc_html__( 'Exclude Post Item', 'elfi-masonry-addon' ),		
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,

				'options' => elfi_light_post_not_in(),

				'condition' => [
					'elfi_post_type' => 'post',
					'elfi_post_type' => 'category',

				],
			]
		);

		$this->add_control(
					'grid_clmn',
					[
						'label' => esc_html__( 'Column Settings', 'elfi-masonry-addon' ),
						'description' =>  esc_html__( 'Item Per Row', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'three',
						'options' => [
							'two'  => esc_html__( 'Two Column', 'elfi-masonry-addon' ),
							'three' => esc_html__( 'Three Column', 'elfi-masonry-addon' ),
							'four' => esc_html__( 'Four Column', 'elfi-masonry-addon' ),

						],
											
					]
				);

		$this->add_control(
					'grid_style',
					[
						'label' => esc_html__( 'Item Style', 'elfi-masonry-addon' ),
						'description' =>  esc_html__( 'Choose Item Style', 'elfi-masonry-addon' ),							
						'type' => Controls_Manager::SELECT,
						'default' => 'portfolio_wrap_free',
						'options' => [
							'portfolio_wrap_free'  => esc_html__( 'Free Style', 'elfi-masonry-addon' ),
							'portfolio_wrap_one'  => esc_html__( 'Style 1 (Pro)', 'elfi-masonry-addon' ),
							'portfolio_wrap_two' => esc_html__( 'Style 2 (Pro)', 'elfi-masonry-addon' ),
							'portfolio_wrap_three' => esc_html__( 'Style 3 (Pro)', 'elfi-masonry-addon' ),

							'portfolio_wrap_four' => esc_html__( 'Style 4 (Pro)', 'elfi-masonry-addon' ),

							'portfolio_wrap_five' => esc_html__( 'Style 5 (Pro)', 'elfi-masonry-addon' ),

							'portfolio_wrap_six' => esc_html__( 'Style 6 (Pro)', 'elfi-masonry-addon' ),

							'portfolio_wrap_seven' => esc_html__( 'Style 7 (Pro)', 'elfi-masonry-addon' ),

							'portfolio_wrap_eight' => esc_html__( 'Style 8 (Pro)', 'elfi-masonry-addon' ),

							'portfolio_wrap_nine' => esc_html__( 'Card one (Pro)', 'elfi-masonry-addon' ),							
							'portfolio_wrap_ten' => esc_html__( 'Card Two (Pro)', 'elfi-masonry-addon' ),


						],


					]
				);


		$this->add_control(
					'grid_style_effetcs',
					[
						'label' => esc_html__( 'Item Effect', 'elfi-masonry-addon' ),
						'description' =>  esc_html__( 'Choose Hover Style', 'elfi-masonry-addon' ),							
						'type' => Controls_Manager::SELECT,
						'default' => 'elfi-free-item--eff1',
						'options' => [
							'elfi-free-item--eff1'  => esc_html__( 'Effect 1', 'elfi-masonry-addon' ),
							'elfi-free-item--eff2'  => esc_html__( 'Effect 2', 'elfi-masonry-addon' ),
							'elfi-free-item--eff3'  => esc_html__( 'Effect 3', 'elfi-masonry-addon' ),
							'elfi-free-item--eff4'  => esc_html__( 'Effect 4', 'elfi-masonry-addon' ),
							'elfi-free-item--eff5'  => esc_html__( 'Effect 5', 'elfi-masonry-addon' ),



						],

						'condition' => [
							'grid_style' => 'portfolio_wrap_free',
							
						],
					]
				);

		$this->add_control(
			'post_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'elfi-masonry-addon' ),	

				'description' =>  esc_html__( 'Choose items per page,for display all write  "-1"', 'elfi-masonry-addon'),
				'type' => Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 50,
				'default' => -1,

			]
		);
		$this->add_control(
			'important_note',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'If the item per page is less than the total item number, the infinite Scroll works', 'elfi-masonry-addon' ),
				'content_classes' => 'itemsclass',
			]
		);
		$this->add_control(
					'elfi_order_by',
					[
						'label' => esc_html__( 'Order By', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'title',
						'options' => [
							'ID'  => esc_html__( 'Post ID', 'elfi-masonry-addon' ),
							'title' => esc_html__( 'Title', 'elfi-masonry-addon' ),
							'date' => esc_html__( 'Date', 'elfi-masonry-addon' ),

							'menu_order' => esc_html__( 'Menu Order', 'elfi-masonry-addon' ),

							'rand' => esc_html__( 'Random', 'elfi-masonry-addon' ),

							'modified' => esc_html__( 'Last Modified', 'elfi-masonry-addon' ),

							'none' => esc_html__( 'None', 'elfi-masonry-addon' ),

						],
					]
				);
		$this->add_control(
					'elfi_order',
					[
						'label' => esc_html__( 'Order', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'DESC',
						'options' => [
							'ASC'  => esc_html__( 'Ascending ', 'elfi-masonry-addon' ),
							'DESC' => esc_html__( 'Descending ', 'elfi-masonry-addon' ),
						
						],
					]
				);
		$this->add_control(
			'show_excerpt',
			[
				'label' => esc_html__( 'Hide Excerpt (Pro!)', 'elfi-masonry-addon' ),
				'description' =>  esc_html__( 'Click to hide Excerpt Option', 'elfi-masonry-addon' ),			
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elfi-masonry-addon' ),
				'label_off' => esc_html__( 'Hide', 'elfi-masonry-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [

					'grid_style' => ['portfolio_wrap_nine', 'portfolio_wrap_ten'],
					'elfi_post_type' => ['post', 'elfi'],
					
				],
			]
		);		
		$this->add_control(
			'except_length',
			[
				'label' => esc_html__( 'Excerpt length', 'elfi-masonry-addon' ),	

				'description' =>  esc_html__( 'Excerpt length,Step 10,default:200' ,'elfi-masonry-addon'),
				'type' => Controls_Manager::NUMBER,
				'min' => 20,
				'max' => 500,
				'step' => 10,
				'default' => 200,
				'condition' => [

					'show_excerpt' => 'yes',
					'grid_style' => ['portfolio_wrap_nine', 'portfolio_wrap_ten'],
					'elfi_post_type' => ['post', 'elfi'],
				],

			]
		);
		$this->add_control(
			'hide_cat',
			[
				'label' => esc_html__( 'Hide Category Text from Item?', 'elfi-masonry-addon' ),
				'description' =>  esc_html__( 'Click to hide Category Text from Item', 'elfi-masonry-addon' ),			
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Hide', 'elfi-masonry-addon' ),
				'label_off' => esc_html__( 'Show', 'elfi-masonry-addon' ),
				'return_value' => 'yes',
				'default' => 'no',

			]
		);
		$this->add_control(
			'elfipro_title_full',
			[
				'label' => esc_html__( 'Show Full Titile Of Item?', 'elfi-masonry-addon' ),
				'description' =>  esc_html__( 'Click to display Full Text of Item', 'elfi-masonry-addon' ),			
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Hide', 'elfi-masonry-addon' ),
				'label_off' => esc_html__( 'Show', 'elfi-masonry-addon' ),
				'return_value' => 'yes',
				'default' => 'no',

			]
		);

		$this->add_control(
			'comments_icon',
			[
				'label' => esc_html__( 'Comments Icon(Pro!)', 'elfi-masonry-addon' ),
				'description' =>  esc_html__( 'Set Comments Icon' ,'elfi-masonry-addon'),
				'fa4compatibility' => 'icon',	
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-comments-o',
					'library' => 'solid',
				],

				'condition' => [
					'grid_style' =>  'portfolio_wrap_ten',

					'elfi_post_type' => ['post'],
				],
			]
		);

		$this->add_control(
			'tag_icon',
			[
				'label' => esc_html__( 'Tag Icon(Pro!)', 'elfi-masonry-addon' ),
				'description' =>  esc_html__( 'Set Tag Icon' ,'elfi-masonry-addon' ),	
				'fa4compatibility' => 'icon',					
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa fa-tags',
					'library' => 'solid',
				],

				'condition' => [
					'grid_style' =>  'portfolio_wrap_ten',
					'elfi_post_type' => ['post'],
				],
			]
		);

		$this->add_control(
				'prev_title',
					[
						'label' => esc_html__( 'Preview Text(Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'Preview',
						'condition' => [

							'grid_style' => [
								'portfolio_wrap_nine',
								'portfolio_wrap_ten',
							],
							'elfi_post_type' => 'elfi'

							],
						]
					);
		$this->add_control(
					'prev_link',
					[
						'label' => esc_html__( 'Preview Link(Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'https://',
						'condition' => [

							'grid_style' => [
								'portfolio_wrap_nine',
								'portfolio_wrap_ten',
							],
							'elfi_post_type' => 'elfi'

						],
					]
					);

		$this->add_control(
				'buy_title',
				[
					'label' => esc_html__( 'Buy Title(Pro!)', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'Buy',
					'condition' => [

						'grid_style' => [
							'portfolio_wrap_nine',
							'portfolio_wrap_ten',
						],

						'elfi_post_type' => 'elfi',

					],
					]
				);
		$this->add_control(
					'buy_link',
					[
						'label' => esc_html__( 'Buy Link(Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'https://',
						'condition' => [

							'grid_style' => [
								'portfolio_wrap_nine',
								'portfolio_wrap_ten',
							],
							'elfi_post_type' => 'elfi'

						],
						]
						);
		$this->add_control(
					'buy_prev_radius',
					[
						'label' => esc_html__( 'Border Radius(Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							],
						
						],
						'default' => [
							'unit' => 'px',
							'size' => 50,
						],							
						'condition' => [

							'grid_style' => [
								'portfolio_wrap_nine',
								'portfolio_wrap_ten',
							],
							'elfi_post_type' => 'elfi',

							],
						]
					);


	if(class_exists('WooCommerce')){

		$this->add_control(
					'add_cart_swtich',
					[
						'label' => esc_html__( 'Use Add To Cart Icon', 'elfi-masonry-addon' ),
						'description' => esc_html__( 'Click to use icon of Add to Cart', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::SWITCHER,
									'label_on' => esc_html__( 'Show', 'elfi-masonry-addon' ),
									'label_off' => esc_html__( 'Hide', 'elfi-masonry-addon' ),
									'return_value' => 'yes',
									'default' => ' ',
						'condition' => [

							'elfi_post_type' =>'product',
				
						],
					]
				);
		$this->add_control(
					'add_cart_text',
					[
						'label' => esc_html__( 'Change Add to Cart Text(Pro)', 'elfi-masonry-addon' ),
						'description' => esc_html__( 'Change Add to Cart Text', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::TEXT,

						'default' => esc_html__('Add To Cart' ,'elfi-masonry-addon'),

				'conditions' => [
					'relation' => 'and',
					'terms' =>
					 [
						[
							'name' => 'elfi_post_type',
							'operator' => '==',
							'value' => 'product',
						],
						[
							'name' => 'add_cart_swtich',
							'operator' => '!==',
							'value' => 'yes',
						],
					],
					],


					]
				);
				$this->add_control(
							'add_cart_text_icon',
							[
								'label' => esc_html__( 'Choose Icon(Pro)', 'elfi-masonry-addon' ),
								'type' => Controls_Manager::ICONS,
								'fa4compatibility' => 'icon',	
								'default' => [
									'value' => 'fas fa-shopping-cart',
									'library' => 'solid',
								],
								'condition' => [

									'elfi_post_type' =>'product',
									'add_cart_swtich' =>'yes',
								],
							]
						);

				$this->add_control(
							'select_cart_text',
							[
								'label' => esc_html__( 'Change Select Option Text(Pro)', 'elfi-masonry-addon' ),
								'description' => esc_html__( 'Change Select Option Text', 'elfi-masonry-addon' ),								
								'type' => Controls_Manager::TEXT,

								'default' => esc_html__('Select Option' ,'elfi-masonry-addon'),
								'condition' => [

									'elfi_post_type' =>'product'
								],
							]
						);

					}
			$this->end_controls_section();

	}


		function elfi_ColorSettings(){

		$this->start_controls_section(
			'color_section',
			[
				'label' => esc_html__( 'Item Color', 'elfi-masonry-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Title Color', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .portfolio_content h2' => 'color: {{VALUE}}',
							'{{WRAPPER}} h3.elfi-free-item__header' => 'color: {{VALUE}}',
						],
					]
				);


		$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Title Hover', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .portfolio_content h2:hover' => 'color: {{VALUE}}',
						],
					]
				);

		$this->add_control(
					'category_color',
					[
						'label' => esc_html__( 'Category Text Color', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .portfolio_content small' => 'color: {{VALUE}}',
							'{{WRAPPER}} .portfolio_content p.temllink a' => 'color: {{VALUE}}',

							'{{WRAPPER}} .portfolio_content p.temllink_pdt a' => 'color: {{VALUE}}',

							'{{WRAPPER}} small.elfi-cat' => 'color: {{VALUE}}',

							'{{WRAPPER}} .T_post_comment' => 'color: {{VALUE}}',

						],

						'conditions' => [
							'terms' => [
								
							[
							'name' => 'hide_cat',
							'value' => 'yes',
							'operator' => '!==',
							],	
							[
						'name' => 'elfi_display_types',
						'operator' => '!==',
						'value' => 'posts',
							]
							]			
						],
					]
				);		


				
		$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'bg_color',
						'label' => esc_html__( 'Background', 'elfi-masonry-addon' ),
						'types' => [ 'gradient'],
						'selector' => '{{WRAPPER}} .portfolio_content',

						'condition' => [

							'grid_style' => [

								'portfolio_wrap_two',
								'portfolio_wrap_one',
							],
						
						],

					]
				);	


				

		$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => 'bg_three_color',
						'label' => esc_html__( 'Background Color', 'elfi-masonry-addon' ),
						'types' => [ 'gradient'],
						'selector' => 

						'{{WRAPPER}} .portfolio_wrap_three:after',

						'condition' => [

							'grid_style' => [

								'portfolio_wrap_three',

							],
						
						],


					]
				);		

		$this->add_control(
					'free_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'default' => 'rgba(255, 255, 255, 0.7)',
						'selectors' => [
							'{{WRAPPER}} .elfi-free-item__info' => 'background-color: {{VALUE}}',

						],

						'condition' => [
							'grid_style' => 'portfolio_wrap_free',

						],

					]
				);
if(class_exists('WooCommerce')){

	$this->add_control(
				'add_to_cart_color',
				[
					'label' => esc_html__( 'Cart Text Color (Pro!)', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'elfi_post_type' => 'product',

					],

				]
			);
		$this->add_control(
					'add_to_cart_hover_color',
					[
						'label' => esc_html__( 'Cart Hover Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],
					]
				);
		$this->add_control(
					'add_to_cart_bg_color',
					[
						'label' => esc_html__( 'Cart Background Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],

					]
				);
		$this->add_control(
					'add_to_cart_bg_hover_color',
					[
						'label' => esc_html__( 'Cart Background Hover Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],
					]
				);



		$this->add_control(
					'add_to_cart_border_color',
					[
						'label' => esc_html__( 'Cart Border Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],
					]
				);	

		$this->add_control(
					'add_to_cart_border_hover_color',
					[
						'label' => esc_html__( 'Cart Border Hover Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],



					]
				);		


		$this->add_control(
					'price_color',
					[
						'label' => esc_html__( 'Price Text Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],

					]
				);		
		$this->add_control(
					'ten_price_bg',
					[
						'label' => esc_html__( 'Price Background (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],

					]
				);
		$this->add_control(
					'sale_color',
					[
						'label' => esc_html__( 'Sale Text Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],

					]
				);
		$this->add_control(
					'sale_bg',
					[
						'label' => esc_html__( 'Sale Background Color (Pro!)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'condition' => [
							'elfi_post_type' => 'product',

						],
					]
				);
		}
		$this->end_controls_section();


		}



	function elfi_ZoomSettings(){



		$this->start_controls_section(
			'src_link_section',
			[
				'label' => esc_html__( 'Zoom & Link Icon', 'elfi-masonry-addon' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

	$this->add_control(
		'elfi_icon',
		[
			'label' => esc_html__( 'Zoom Icon', 'elfi-masonry-addon' ),
			'description' =>  esc_html__( 'Set Zoom Icon' ,'elfi-masonry-addon' ),'
			fa4compatibility' => 'icon',					
			'type' => Controls_Manager::ICONS,
			'default' => [
				'value' => 'fa fa-search',
				'library' => 'fa-solid',
			],
		]
	);


	$this->add_control(
		'elfi_link_icon',
			[
			'label' => esc_html__( 'Link Icon', 'elfi-masonry-addon' ),
			'description' =>  esc_html__( 'Choose Link Icon' ,'elfi-masonry-addon' ),	
			'fa4compatibility' => 'icon',					
			'type' => Controls_Manager::ICONS,
			'default' => [
				'value' => 'fas fa-link',
				'library' => 'solid',
			],

			'condition' => [

				'grid_style' => [

					'portfolio_wrap_free',
					'portfolio_wrap_two',
					'portfolio_wrap_three',
					'portfolio_wrap_four',
					'portfolio_wrap_six',
				],
							
				],
		]
	);
	$this->add_control(
		'elfi_video_icon',
		[
			'label' => esc_html__( 'Video Icon(Pro features)', 'elfi-masonry-addon' ),
			'fa4compatibility' => 'icon',			
			'type' => Controls_Manager::ICONS,
			'default' => [
				'value' => 'fa fa-video',
				'library' => 'fa-solid',
			],
			'content_class' => 'elfi-pro-features',

		]
	);
	$this->add_control(
			'videolink_note',
			[
				'label' => __( 'Where to input the video link?','elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'To input video link see meta box on specific post, <a href="https://elfi.sharabindu.com/wp/docs/popup-content-setting/"> see details</a>','elfi-masonry-addon' ),
				'content_classes' => 'itemsclass',
			]
		);



	$this->add_control(
			'zommicon_options',
			[
				'label' => __( 'Icon styling ', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
	$this->start_controls_tabs( 'elfi-zoom-c' );

	   $this->start_controls_tab(
	     'zoom-color_normal',
	     [
	       'label' => esc_html__( 'Normal', 'elfi-masonry-addon' ),
	     ]
	   );



	$this->add_control(
				'zoom_color',
				[
					'label' => esc_html__( 'Zoom or Link', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup' => 'color: {{VALUE}}',
						'{{WRAPPER}} a.video-popup' => 'color: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elfi-free-item__link' => 'color: {{VALUE}}',
					],
				]
			);

	$this->add_control(
				'zoom_bg_color',
				[
					'label' => esc_html__( 'Zoom or Link Background', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup' => 'background: {{VALUE}}',
						'{{WRAPPER}} a.video-popup' => 'background: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link' => 'background: {{VALUE}}',
					'{{WRAPPER}} .elfi-free-item__link' => 'background: {{VALUE}}',
					],


				]
			);
	$this->add_control(
				'zoom_border_color',
				[
					'label' => esc_html__( 'Zoom or Link Border', 'elfi-masonry-addon' ),						
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} a.video-popup' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .elfi-free-item__link' => 'border-color: {{VALUE}}',
					],


				]
			);
	$this->end_controls_tab();

	$this->start_controls_tab(
	  'zoom_hover',
	  [
	    'label' => esc_html__( 'Hover', 'elfi-masonry-addon' ),
	  ]
	);
	$this->add_control(
				'zoom_hover_color',
				[
					'label' => esc_html__( 'Zoom or Link', 'elfi-masonry-addon' ),							
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} a.video-popup:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elfi-free-item__link:hover' => 'color: {{VALUE}}',
					],

				]
			);



	$this->add_control(
				'zoom_bg_hover_color',
				[
					'label' => esc_html__( 'Zoom or Link Background', 'elfi-masonry-addon' ),							
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup:hover' => 'background: {{VALUE}}',
						'{{WRAPPER}} a.video-popup:hover' => 'background: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link:hover' => 'background: {{VALUE}}',
					'{{WRAPPER}} .elfi-free-item__link:hover' => 'background: {{VALUE}}',
					],

				]
			);


	$this->add_control(
				'zoom_border_hover_color',
				[
					'label' => esc_html__( 'Zoom or Link Border', 'elfi-masonry-addon' ),				
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup:hover' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} a.video-popup:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .elfi-free-item__link:hover' => 'border-color: {{VALUE}}',
					],
				]
			);
	$this->end_controls_tab();
	$this->end_controls_tabs();

	$this->add_control(
			'btn_color_options',
			[
				'label' => __( 'Button  styling ', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'elfi_display_types' => 'category',

				],
			]
		);

	$this->start_controls_tabs( 'elfi-elfippop-c' ,	     
		[
	       'label' => esc_html__( 'Button Color', 'elfi-masonry-addon' )
	     ]
	 );

	   $this->start_controls_tab(
	     'elfippop_btncolor_normal',
	     [
	       'label' => esc_html__( 'Normal', 'elfi-masonry-addon' ),
	     ]
	   );
	   $this->add_control(
	   			'elfippop_btncolor_color',
	   			[
	   				'label' => esc_html__( 'Button Color', 'elfi-masonry-addon' ),
	   				'type' => Controls_Manager::COLOR,
	   				'selector' =>
	   					'{{WRAPPER}} .mfp-content a.elfi_poptilie_link_',
	   			]
	   		);
	   $this->add_control(
	   			'elfippop_btncolor_bg',
	   			[
	   				'label' => esc_html__( 'Button Background', 'elfi-masonry-addon' ),
	   				'type' => Controls_Manager::COLOR,

	   				'selector' =>
	   					'{{WRAPPER}} .mfp-content a.elfi_poptilie_link_',
	   			]
	   		);

	   $this->end_controls_tab();
	   $this->start_controls_tab(
	     'elfippop_btncolor_hover',
	     [
	       'label' => esc_html__( 'Hover', 'elfi-masonry-addon' ),
	     ]
	   );
	   $this->add_control(
	   			'elfippop_btncolor_hover_c',
	   			[
	   				'label' => esc_html__( 'Button hover Color', 'elfi-masonry-addon' ),
	   				'type' => Controls_Manager::COLOR,

	   				'selector' =>
	   					'{{WRAPPER}} .mfp-content a.elfi_poptilie_link_:hover',


	   			]
	   		);
	   $this->add_control(
	   			'elfippop_btncolor_bg_h',
	   			[
	   				'label' => esc_html__( 'Button hover Background', 'elfi-masonry-addon' ),
	   				'type' => Controls_Manager::COLOR,
	   				'selector' =>
	   					'{{WRAPPER}} .mfp-content a.elfi_poptilie_link_:hover',
	   			]
	   		);


	   $this->end_controls_tab();
	   $this->end_controls_tabs();




	$this->add_control(
			'poptilie_options',
			[
				'label' => __( 'PoPUp Title (Pro features !)', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


	$this->add_control(
				'elfi_poptilie_color',
				[
					'label' => esc_html__( 'Title Color', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
				]
			);
	$this->add_control(
		'elfi_poptilie_fsize',
		[
			'label' => esc_html__( 'Font size', 'elfi-masonry-addon' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 15,
			'max' => 100,
			'step' => 5,

		]
	);

	$this->add_control(
		'poptilie_txt_trasnfrom',
		[
			'label' => __( 'Text Transfrom', 'elfi-masonry-addon' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'capitalize'  => __( 'capitalize', 'elfi-masonry-addon' ),
				'uppercase' => __( 'uppercase', 'elfi-masonry-addon' ),
				'lowercase' => __( 'lowercase', 'elfi-masonry-addon' ),
				'none' => __( 'none', 'elfi-masonry-addon' ),

			],
		]
	);
	$this->add_control(
			'popbtn_options',
			[
				'label' => __( 'PopUp Button (Pro features !)', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

	$this->add_control(
				'elfi_poptilie_text',
				[
					'label' => esc_html__( 'Button Label', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'Learn More',

				]
			);
	$this->add_control(
		'popbtn_txt_trasnfrom',
		[
			'label' => __( 'Text Transfrom', 'elfi-masonry-addon' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'capitalize'  => __( 'capitalize', 'elfi-masonry-addon' ),
				'uppercase' => __( 'uppercase', 'elfi-masonry-addon' ),
				'lowercase' => __( 'lowercase', 'elfi-masonry-addon' ),
				'none' => __( 'none', 'elfi-masonry-addon' ),

			],
		]
	);

	$this->add_control(
		'elfi_popbtn_fsize',
		[
			'label' => esc_html__( 'Font size', 'elfi-masonry-addon' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 15,
			'max' => 100,
			'step' => 5,

		]
	);

	$this->add_control(
		'elfi_popbtn_bradius',
		[
			'label' => esc_html__( 'Border Radius', 'elfi-masonry-addon' ),
			'type' => Controls_Manager::NUMBER,
			'min' => 0,
			'max' => 100,
			'step' => 5,

		]
	);

	$this->end_controls_section();


	}



	function elfi_ButtonSettings(){
	$this->start_controls_section(
		'btn_section',
		[
			'label' => esc_html__( 'Button Settings', 'elfi-masonry-addon' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);

	$this->add_control(
				'all_text_',
				[
					'label' => esc_html__( 'Change All Text', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'All',

				]
			);

	$this->add_control(
				'btn_style',
				[
					'label' => esc_html__( 'Button Style', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'style_one',
					'options' => [
						'empty_buton'  => esc_html__( 'None', 'elfi-masonry-addon' ),
						'style_one'  => esc_html__( 'Style 1', 'elfi-masonry-addon' ),
						'style_six' => esc_html__( 'Style 2', 'elfi-masonry-addon' ),
						'style_three' => esc_html__( 'Style 3 (Pro)', 'elfi-masonry-addon' ),

						'style_four' => esc_html__( 'Style 4 (Pro)', 'elfi-masonry-addon' ),

						'empty_buton1' => esc_html__( 'Style 5 (Pro)', 'elfi-masonry-addon' ),

						'empty_buton2' => esc_html__( 'Style 6 (Pro)', 'elfi-masonry-addon' ),

						'empty_buton3' => esc_html__( 'Style 7 (Pro)', 'elfi-masonry-addon' ),

						'empty_buton4' => esc_html__( 'Style 8 (Pro)', 'elfi-masonry-addon' ),

						'empty_buton5' => esc_html__( 'Style 9 (Pro)', 'elfi-masonry-addon' ),


						'empty_buton6' => esc_html__( 'Style 10 (Pro)', 'elfi-masonry-addon' ),
						
						'empty_buton7' => esc_html__( 'Style 11 (Pro)', 'elfi-masonry-addon' ),
						
						'empty_buton8' => esc_html__( 'Style 12 (Pro)', 'elfi-masonry-addon' ),
						
						'empty_buton9' => esc_html__( 'Style 13 (Pro)', 'elfi-masonry-addon' ),


					],
				]
			);

	$this->add_control(
				'button_align',
				[
					'label' => esc_html__( 'Button Alignment', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'elfi-masonry-addon' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'elfi-masonry-addon' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'elfi-masonry-addon' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'center',
					'toggle' => true,

				]
			);


	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'btn_elfi_typography',
			'label' => esc_html__( 'Button Typography', 'elfi-masonry-addon' ),
		]
	);

	$this->add_control(
				'btn_spacing',
				[
					'label' => esc_html__( 'Buttom Bottom Spacing', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 10,
						],
					
					],
					'default' => [
						'unit' => 'px',
						'size' => 40,
					],
					'selectors' => [
						'{{WRAPPER}} .elfi-filter-nav' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					],							
				]
			);			
	$this->add_control(
				'btn_right_spacing',
				[
					'label' => esc_html__( 'Button Right Spacing', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 50,
							'step' => 5,
						],
					
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .elfi-filter-nav ul li' => 'margin-left: {{SIZE}}{{UNIT}}',
					],

				]
			);	
	$this->add_control(
				'btn_color',
				[
					'label' => esc_html__( 'Button Text Color', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elfi-filter-nav ul li' => 'color: {{VALUE}}',
					],
				]
			);	
	$this->add_control(
				'btn_active_color',
				[
					'label' => esc_html__( 'Button Active Color', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elfi-filter-nav li.active' => 'color: {{VALUE}}',
					],
				]
			);
	$this->add_control(
				'btn_border_color',
				[
					'label' => esc_html__( 'Button Border Color', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .hover_two rect' => 'stroke: {{VALUE}}',
						'{{WRAPPER}} .hover_three:before' => 'background: {{VALUE}}',							
						'{{WRAPPER}} .hover_four:before,' => 'border-top-color: {{VALUE}}',		
						'{{WRAPPER}} .hover_four:before' => 'border-bottom-color: {{VALUE}}',
				
						'{{WRAPPER}} .hover_four:after' => 'background-color: {{VALUE}}',

						'{{WRAPPER}} .hover_seven svg.button-stroke' => 'stroke: {{VALUE}}',

						'{{WRAPPER}} .hover_eight.hover_button:before' => 'background: {{VALUE}}',
						'{{WRAPPER}} .hover_eight.hover_button:after' => 'background: {{VALUE}}',
						'{{WRAPPER}} .hover_eight.hover_button' => 'background: {{VALUE}}',
						'{{WRAPPER}} .hover_nine.type1::before' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .hover_nine.type1::after' => 'border-color: {{VALUE}}',

						'{{WRAPPER}} .hover_eleven.from-middle:before' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .hover_eleven.from-middle:after' => 'background: {{VALUE}}',
						'{{WRAPPER}} .hover_twelve.from-right:before' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .hover_twelve.from-right:after' => 'background: {{VALUE}}',
						'{{WRAPPER}} .hover_thirtheen.from-bottom:before' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .hover_thirtheen.from-bottom:after' => 'background: {{VALUE}}',


						'{{WRAPPER}} .hover_six:hover' => 'border-top-color: {{VALUE}}',
						'{{WRAPPER}} .hover_six.active' => 'border-top-color: {{VALUE}}',
					],

					'condition' => [
						'btn_style' => [
							'style_two',
							'style_three',
							'style_four',
							'style_six',
							'style_seven',
							'style_eight',
							'style_eleven',
							'style_twelve',
							'style_thirteen',
						],

					],


				]
			);
	$this->add_control(
			'btn_moblie',
			[
				'label' => esc_html__( 'Mobile Layout', 'elfi-masonry-addon' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

	$this->add_control(
				'btn_mobile',
				[
					'label' => esc_html__( 'Set Vertical Button For Mobile/Tablet Layout', 'elfi-masonry-addon' ),
				'type' => Controls_Manager::SWITCHER,
								'label_on' => esc_html__( 'Show', 'elfi-masonry-addon' ),
								'label_off' => esc_html__( 'Hide', 'elfi-masonry-addon' ),
								'return_value' => 'yes',
								'default' => ' ',
				]
			);


	$this->end_controls_section();
	}

	function elfi_ReadMoreSettings(){
	$this->start_controls_section(
		'inifiniteScroll',
		[
			'label' => esc_html__( 'Infinite Scroll (Pro)', 'elfi-masonry-addon' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);

	$this->add_control(
				'display_pagination',
				[
				'label' => esc_html__( 'Disable Infinite Scroll?', 'elfi-masonry-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elfi-masonry-addon' ),
				'label_off' => esc_html__( 'Hide', 'elfi-masonry-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
				]
				);
		$this->add_control(
			'paginate_note',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'Infinite scroll will not work in preview, please see frontend. <a href="">View Pro Demo</a>', 'elfi-masonry-addon' ),
				'content_classes' => 'itemsclass',
				'condition' => [

				'display_pagination' => 'yes'
				],
			]
		);

		$this->add_control(
			'endof_content',
			[
				'label' => __( 'Text at the end of the item', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'End of content', 'elfi-masonry-addon' ),
				'description' => __( 'If the field is empty the "text" will disappear', 'elfi-masonry-addon' ),
				'condition' => [

				'display_pagination' => 'yes'
				],	
			]
		);
	$this->add_control(
				'paginate_color',
				[
					'label' => esc_html__( 'Dot Color', 'elfi-masonry-addon' ),						
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .loader-ellips__dot' => 'background: {{VALUE}}',
					],
					'condition' => [

						'display_pagination' => 'yes'
					],	

				]
			);
	$this->end_controls_section();



	$this->start_controls_section(
		'readmore_section',
		[
			'label' => esc_html__( 'Read More Button', 'elfi-masonry-addon' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);


	$this->add_control(
				'display_readmore',
				[
					'label' => esc_html__( 'Display Read More?', 'elfi-masonry-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elfi-masonry-addon' ),
				'label_off' => esc_html__( 'Hide', 'elfi-masonry-addon' ),
				'return_value' => 'yes',
				'default' => 'no',
				]
			);
	$this->add_control(
				'read_more_title',
				[
					'label' => esc_html__( 'Read More Title', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__('Read More' , 'elfi-masonry-addon' ),

					'condition' => [

						'display_readmore' => 'yes'
					],

				]
			);

	$this->add_control(
				'read_more_link',
				[
					'label' => esc_html__( 'Read More Link', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' =>  esc_html__('https://' , 'elfi-masonry-addon' ),

					'condition' => [

						'display_readmore' => 'yes'
					],


				]
			);
	$this->add_control(
				'readmore_spacing',
				[
					'label' => esc_html__( 'Readmore Top Spacing', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 10,
						],
					
					],
					'default' => [
						'unit' => 'px',
						'size' => 40,
					],
					'selectors' => [
						'{{WRAPPER}} .elfi_readmore' => 'margin-top: {{SIZE}}{{UNIT}}',
					],	

					'condition' => [

						'display_readmore' => 'yes'
					],						
				]
			);
	$this->add_control(
				'readmore_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 50,
							'step' => 5,
						],
					
					],
					'default' => [
						'unit' => 'px',
						'size' => 50,
					],
					'selectors' => [
						'{{WRAPPER}} .elfi_readmore a' => 'border-radius: {{SIZE}}{{UNIT}}',
					],	

					'condition' => [

						'display_readmore' => 'yes'
					],						
				]
			);

	$this->add_control(
				'readmore_color',
				[
					'label' => esc_html__( 'Text Color', 'elfi-masonry-addon' ),						
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elfi_readmore a' => 'color: {{VALUE}}',
					],
					'condition' => [

						'display_readmore' => 'yes'
					],	

				]
			);
	$this->add_control(
				'readmore_hover_color',
				[
					'label' => esc_html__( 'Hover Color', 'elfi-masonry-addon' ),						
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elfi_readmore a:hover' => 'color: {{VALUE}}',
					],
					'condition' => [

						'display_readmore' => 'yes'
					],	

				]
			);

	$this->add_control(
				'readmore_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'elfi-masonry-addon' ),						
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elfi_readmore a' => 'background: {{VALUE}}'
					],
					'condition' => [

						'display_readmore' => 'yes'
					],	

				]
			);




	$this->add_control(
				'readmore_align',
				[
					'label' => esc_html__( 'ReadMore Alignment', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'elfi-masonry-addon' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'elfi-masonry-addon' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'elfi-masonry-addon' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'center',
					'toggle' => true,
					'condition' => [

						'display_readmore' => 'yes'
					],
				]
			);
	$this->end_controls_section();




	$this->start_controls_section(
		'elfi_style_section',
		[
			'label' => __( 'Item Typography', 'elfi-masonry-addon' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'content_typography',
			'label' => __( 'Title Typography', 'elfi-masonry-addon' ),
			'selector' => '{{WRAPPER}} .elfi-free-item__header',
		]
	);
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' => 'category_typography',
			'label' => __( 'Category Typography', 'elfi-masonry-addon' ),
			'selector' => '{{WRAPPER}} .elfi-cat',
		'condition' => [
		'elfi_display_types' => 'category',

				],
		]
	);

	$this->end_controls_section();

	$this->start_controls_section(
		'cefli_ss_section',
		[
			'label' => esc_html__( 'Elfi Custom Css', 'elfi-masonry-addon' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_control(
		'elfi_custom_css_pro',
		[
			'label' => __( 'Pro Features !', 'elfi-masonry-addon' ),
			'type' => \Elementor\Controls_Manager::RAW_HTML,	
			'raw' => __( '			<div class="elementor-control-raw-html ">		<div class="elementor-nerd-box">
			<img class="elementor-nerd-box-icon" src="'.ELFI_URL_LIGHT.'assets/img/icon-256x256-min.png">
			<div class="elementor-nerd-box-title">Get Our Custom CSS</div>
			<div class="elementor-nerd-box-message">This feature is only available for the Pro version</div>
			<a class="elfi-pro-demo" href="http://sharabindu.com/plugins/elfi" target="_blank">Go Pro</a></div>
		</div>', 'elfi-masonry-addon' ),
		]
	);


	$this->end_controls_section();

	}



		function elfi_render_script(){

			echo '<script type="text/javascript">

		function elfi_active_grid(){
		    var grid_layout = jQuery(".grid-init");

		    jQuery.each(grid_layout,function (index,value) {
		        var el = jQuery(this);
		        var parentClass = jQuery(this).parent().attr("class");
		        var $selector = jQuery("#"+el.attr("id"));

		        jQuery($selector).imagesLoaded(function () {

		            var elfiMasonry = jQuery($selector).isotope({
		                itemSelector:".grid-item",
		                percentPosition: true,
		                masonry: {
		                    columnWidth: 0,
		                    gutter:0
		                }
		            });
		            jQuery(document).on("click", "." +parentClass+" .elfi-filter-nav ul li", function () {
		                var filterValue = jQuery(this).attr("data-filter");
		                elfiMasonry.isotope({
		                    filter: filterValue
		                });
		            });
		        });
		    });

		}elfi_active_grid();
		jQuery(document).on("click", ".elfi-filter-nav ul li", function () {
		    jQuery(this).siblings().removeClass("active");
		    jQuery(this).addClass("active");
		});
		                
	</script>'; 

		}

	}
