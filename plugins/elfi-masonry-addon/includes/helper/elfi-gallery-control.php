<?php 

	use \Elementor\Controls_Manager;
	use \Elementor\Group_Control_Background;
	use Elementor\Core\Schemes;
	use \Elementor\Group_Control_Typography;

	trait elfiLightallery{




	function elfi_Gallery_Settings(){

		$this->start_controls_section(

			'gallery_section',
			[
				'label' => esc_html__( 'Elfi Gallery Settings', 'elfi-masonry-addon' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

	$repeater = new \Elementor\Repeater();

	$repeater->add_control(
			'galley_title', [
				'label' => __( 'Title', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Title' , 'elfi-masonry-addon' ),
				'label_block' => true,
			]
		);
	$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'popup_style',
			[
				'label' => __( 'Popup Element', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image'  => __( 'image', 'elfi-masonry-addon' ),
					'video' => __( 'video (PRO)', 'elfi-masonry-addon' ),
					'shortcode' => __( 'shortcode (PRO)', 'elfi-masonry-addon' ),
					'rawhtml' => __( 'Raw Html (PRO)', 'elfi-masonry-addon' ),
				],
			]
		);
		$repeater->add_control(
			'popup_content',
			[
				'label' => __( 'Popup Content (PRO)', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
			'conditions' => [
				'relation' => 'and',
				'terms' =>
				 [
					[
						'name' => 'popup_style',
						'operator' => '!==',
						'value' => 'image',
					],

				],
				],
			]
		);
		$repeater->add_control(
			'popupicon',
			[
				'label' => __( 'Popup Icon', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-search',
					'library' => 'solid',
				],

			]
		);

		$repeater->add_control(
			'enable_link',
			[
				'label' => __( 'Enable Link', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elfi-masonry-addon' ),
				'label_off' => __( 'Hide', 'elfi-masonry-addon' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_control(
			'gallery_link',
			[
				'label' => __( 'Link', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elfi-masonry-addon' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [

				'enable_link' => 'yes'

				]
			]
		);
		$repeater->add_control(
			'linkicon',
			[
				'label' => __( 'Link Icon', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-link',
					'library' => 'solid',
				],
			'condition' => [

				'enable_link' => 'yes',
				]
			]
		);
		$repeater->add_control(
			'enable_prevbuy',
			[
				'label' => __( 'Preview Link for Card design (PRO)', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'This option is applicable for Item Style Card One', 'elfi-masonry-addon' ),
				'label_on' => __( 'Show', 'elfi-masonry-addon' ),
				'label_off' => __( 'Hide', 'elfi-masonry-addon' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$repeater->add_control(
				'prev_title',
					[
						'label' => esc_html__( 'Preview Text (PRO)', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'Preview',

						'condition' => [

							'enable_prevbuy' => 'yes',
						],
		]
					);
		$repeater->add_control(
			'preview_link',
			[
				'label' => __( 'Preview Link (PRO)', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elfi-masonry-addon' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			'condition' => [

				'enable_prevbuy' => 'yes',
			]				
			]
		);
		$repeater->add_control(
				'buy_title',
				[
					'label' => esc_html__( 'Buy Title (PRO)', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'Buy',
			'condition' => [

				'enable_prevbuy' => 'yes',
			],					
					]

				);

		$repeater->add_control(
			'Buy_link',
			[
				'label' => __( 'Buy Link (PRO)', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elfi-masonry-addon' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			'condition' => [

				'enable_prevbuy' => 'yes',
			]				
			]
		);


		$this->add_control(
			'elfigallerylist',
			[
				'label' => __( 'Gallery List', 'elfi-masonry-addon' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'galley_title' => __( 'Title', 'elfi-masonry-addon' ),
						'image' => __( 'Item content. Click the edit button to change this text.', 'elfi-masonry-addon' ),
					],
					[
						'galley_title' => __( 'Title', 'elfi-masonry-addon' ),
						'image' => __( 'Item content. Click the edit button to change this text.', 'elfi-masonry-addon' ),
					],
					[
						'galley_title' => __( 'Title', 'elfi-masonry-addon' ),
						'image' => __( 'Item content. Click the edit button to change this text.', 'elfi-masonry-addon' ),
					],
				],
				'title_field' => '{{{ galley_title }}}',
			]
		);
		$this->end_controls_section();


		$this->start_controls_section(
			'gridsrtle',
			[
				'label' => esc_html__( 'Item Style', 'elfi-masonry-addon' ),
				'tab' => Controls_Manager::TAB_CONTENT,
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
							'portfolio_wrap_one'  => esc_html__( 'Style 1 (PRO)', 'elfi-masonry-addon' ),
							'portfolio_wrap_two' => esc_html__( 'Style 2 (PRO)', 'elfi-masonry-addon' ),
							'portfolio_wrap_three' => esc_html__( 'Style 3 (PRO)', 'elfi-masonry-addon' ),

							'portfolio_wrap_four' => esc_html__( 'Style 4 (PRO)', 'elfi-masonry-addon' ),

							'portfolio_wrap_five' => esc_html__( 'Style 5 (PRO)', 'elfi-masonry-addon' ),

							'portfolio_wrap_six' => esc_html__( 'Style 6 (PRO)', 'elfi-masonry-addon' ),

							'portfolio_wrap_seven' => esc_html__( 'Style 7 (PRO)', 'elfi-masonry-addon' ),

							'portfolio_wrap_eight' => esc_html__( 'Style 8 (PRO)', 'elfi-masonry-addon' ),

							'portfolio_wrap_nine' => esc_html__( 'Card one (PRO)', 'elfi-masonry-addon' ),							


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

			$this->end_controls_section();
		}

		function elfi_ColorSettings(){

		$this->start_controls_section(
			'color_section',
			[
				'label' => esc_html__( 'Item color & typography', 'elfi-masonry-addon' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Title Typography', 'elfi-masonry-addon' ),
				'selector' => '{{WRAPPER}} .portfolio_content h2',


			]
		);
		$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Title Color', 'elfi-masonry-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .portfolio_content h2' => 'color: {{VALUE}}',
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
					'label' => esc_html__( 'Popup Icon', 'elfi-masonry-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup' => 'color: {{VALUE}}',
					],

				]
			);

	$this->add_control(
				'zoom_bg_color',
				[
					'label' => esc_html__( 'Popup Icon Background', 'elfi-masonry-addon' ),
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
					'label' => esc_html__( 'Popup Icon Border', 'elfi-masonry-addon' ),						
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link' => '
					border-color: {{VALUE}}',
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
					'label' => esc_html__( 'Popup Icon hover', 'elfi-masonry-addon' ),							
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} a.image-popup:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} a.video-popup:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} a.elfi_port_link:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elfi-free-item__link:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} a.image-popup:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} a.video-popup:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .elfi_port_link:hover svg#fancyLinkOIconTwoss' => 'fill: {{VALUE}}',
					],

				]
			);



	$this->add_control(
				'zoom_bg_hover_color',
				[
					'label' => esc_html__( 'Popup Icon hover Background', 'elfi-masonry-addon' ),							
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
					'label' => esc_html__( 'Popup Icon hover Border', 'elfi-masonry-addon' ),				
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
$this->end_controls_section();
		

}


		function gallery_render_script(){
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

		        });
		    });

		}elfi_active_grid();
		                
	</script>'; 

		}

	}
