<?php
	
	namespace UltimateStoreKit\Modules\BrandCarousel\Widgets;
	
	use UltimateStoreKit\Base\Module_Base;
	use Elementor\Group_Control_Css_Filter;
	use Elementor\Repeater;
	use Elementor\Controls_Manager;
	use Elementor\Group_Control_Box_Shadow;
	use Elementor\Group_Control_Image_Size;
	use Elementor\Group_Control_Typography;
	use Elementor\Group_Control_Text_Shadow;
	use Elementor\Group_Control_Background;
	use Elementor\Group_Control_Border;
	use Elementor\Utils;

	use UltimateStoreKit\traits\Global_Widget_Controls;
	use UltimateStoreKit\traits\Global_Widget_Template;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
	
	class Brand_Carousel extends Module_Base {
		use Global_Widget_Controls;
    	use Global_Widget_Template;
		
		public function get_name() {
			return 'usk-brand-carousel';
		}
		
		public function get_title() {
			return esc_html__( 'Brand Carousel', 'ultimate-store-kit' );
		}
		
		public function get_icon() {
			return 'usk-widget-icon usk-icon-brand-carousel';
		}
	
		public function get_categories() {
			return ['ultimate-store-kit'];
		}
		
		public function get_keywords() {
			return [ 'brand', 'carousel', 'client', 'logo', 'showcase' ];
		}
		
		public function get_style_depends() {
			if ( $this->usk_is_edit_mode() ) {
				return [ 'usk-styles' ];
			} else {
				return [ 'ultimate-store-kit-font', 'usk-brand-carousel' ];
			}
		}
		
		// public function get_custom_help_url() {
		// 	return 'https://youtu.be/a_wJL950Kz4';
		// }
		
		protected function register_controls() {
			
			$this->start_controls_section(
				'section_layout',
				[
					'label' => __( 'Layout', 'ultimate-store-kit' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$this->add_responsive_control(
				'columns',
				[
					'label' => esc_html__('Columns', 'ultimate-store-kit'),
					'type' => Controls_Manager::SELECT,
					'default' => 3,
					'tablet_default' => 2,
					'mobile_default' => 1,
					'options' => [
						1 => '1',
						2 => '2',
						3 => '3',
						4 => '4',
						5 => '5',
						6 => '6',
					],
				]
			);

			$this->add_responsive_control(
				'items_gap',
				[
					'label' => esc_html__('Item Gap', 'ultimate-store-kit'),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 30,
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'tablet_default' => [
						'size' => 20,
					],
					'mobile_default' => [
						'size' => 20,
					],
				]
			);

			$this->add_responsive_control(
				'alignment',
				[
					'label'     => esc_html__('Alignment', 'ultimate-store-kit'),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => esc_html__('Left', 'ultimate-store-kit'),
							'icon'  => 'eicon-h-align-left',
						],
						'center' => [
							'title' => esc_html__('Center', 'ultimate-store-kit'),
							'icon'  => 'eicon-h-align-center',
						],
						'right'  => [
							'title' => esc_html__('Right', 'ultimate-store-kit'),
							'icon'  => 'eicon-h-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .' . $this->get_name() . ' .usk-item .usk-item-box .usk-content' => 'text-align: {{VALUE}}',
					],
					'render_type' => 'template'
				]
			);
		
			$this->end_controls_section();

			$this->start_controls_section(
				'usk_section_brands',
				[
					'label' => __( 'Brand Items', 'ultimate-store-kit' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				]
			);
			
			$repeater = new Repeater();
			
			$repeater->add_control(
				'image',
				[
					'label'   => __( 'Brand Image', 'ultimate-store-kit' ),
					'type'    => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				]
			);

			$repeater->add_control(
				'brand_name',
				[
					'label'       => __( 'Brand Name', 'ultimate-store-kit' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Brand Name', 'ultimate-store-kit' ),
					'label_block' => true,
					'dynamic'     => [ 'active'      =>true ],
				]
			);
			
			$repeater->add_control(
				'link',
				[
					'label'         => __( 'Url', 'ultimate-store-kit' ),
					'type'          => Controls_Manager::URL,
					'placeholder'   => __( 'https://your-link.com', 'plugin-domain' ),
					'show_external' => true,
					'default'      => [
						'url'         => '#',
						'is_external' => true,
						'nofollow'    => true,
					],
					'label_block'   => true,
					'dynamic'       => [ 'active'      =>true ],
				]
			);
			
			$this->add_control(
				'brand_items',
				[
					'show_label'  => false,
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ name }}}',
					'default'     => [
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
						[ 'image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					]
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name'      => 'thumbnail',
					'default'   => 'medium',
					'separator' => 'before',
					'exclude'   => ['custom']
				]
			);
			
			$this->end_controls_section();

			$this->register_global_controls_carousel_navigation();
        	$this->register_global_controls_carousel_settings();
			
			//Style
			$this->start_controls_section(
				'section_style_items',
				[
					'label' => __( 'Items', 'ultimate-store-kit' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->start_controls_tabs( 'tabs_item_style' );

			$this->start_controls_tab(
				'tab_item_normal',
				[
					'label' => esc_html__( 'Normal', 'ultimate-store-kit' ),
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'      => 'item_background',
					'selector'  => '{{WRAPPER}} .usk-brand-carousel-item',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'           => 'item_border',
					'label'          => esc_html__( 'Border', 'ultimate-store-kit' ),
					'fields_options' => [
						'border' => [
							'default' => 'solid',
						],
						'width'  => [
							'default' => [
								'top'      => '1',
								'right'    => '1',
								'bottom'   => '1',
								'left'     => '1',
								'isLinked' => false,
							],
						],
						'color'  => [
							'default' => '#dbdbdb',
						],
					],
					'selector'       => '{{WRAPPER}} .usk-brand-carousel-item',
					'separator'   => 'before',
				]
			);

			$this->add_responsive_control(
				'item_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'ultimate-store-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .usk-brand-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'item_padding',
				[
					'label'      => esc_html__( 'Padding', 'ultimate-store-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .usk-brand-carousel-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'item_box_shadow',
					'selector' => '{{WRAPPER}} .usk-brand-carousel-item',
				]
			);

			$this->add_control(
                'carousel_shadow_mode',
                [
                    'label'        => esc_html__( 'Shadow Mode', 'ultimate-store-kit' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'prefix_class' => 'usk-shadow-mode-',
					'render_type' => 'template',
					'separator' => 'before'
                ]
            );
            
            $this->add_control(
                'carousel_shadow_color',
                [
                    'label'     => esc_html__( 'Shadow Color', 'ultimate-store-kit' ),
                    'type'      => Controls_Manager::COLOR,
                    'condition' => [
                        'carousel_shadow_mode' => 'yes',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-widget-container:before' => is_rtl() ? 'background: linear-gradient(to left, {{VALUE}} 5%,rgba(255,255,255,0) 100%);' : 'background: linear-gradient(to right, {{VALUE}} 5%,rgba(255,255,255,0) 100%);',
                        '{{WRAPPER}} .elementor-widget-container:after'  => is_rtl() ? 'background: linear-gradient(to left, rgba(255,255,255,0) 0%, {{VALUE}} 95%);' : 'background: linear-gradient(to right, rgba(255,255,255,0) 0%, {{VALUE}} 95%);',
                    ],
                ]
            );

			$this->add_responsive_control(
                'item_match_padding',
                [
                    'label'       => __( 'Match Padding', 'ultimate-store-kit' ),
                    'description' => __( 'You have to add padding for matching overlaping normal/hover box shadow when you used Box Shadow option.', 'ultimate-store-kit' ),
                    'type'        => Controls_Manager::SLIDER,
                    'range'       => [
                        'px' => [
                            'min'  => 0,
                            'step' => 1,
                            'max'  => 50,
                        ]
                    ],
                    'default'     => [
                        'size' => 10
                    ],
                    'selectors'   => [
                        '{{WRAPPER}} .swiper-carousel' => 'padding: {{SIZE}}{{UNIT}}; margin: 0 -{{SIZE}}{{UNIT}};'
                    ],
                ]
            );
			
			$this->add_control(
				'image_heading',
				[
					'label'     => __('IMAGE', 'ultimate-store-kit'),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before'
				]
			);

			$this->add_responsive_control(
				'brand_image_height',
				[
					'label' => __('Height', 'ultimate-store-kit'),
					'type'  => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 500,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .usk-brand-carousel-img' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'brand_image_width',
				[
					'label' => __('Width', 'ultimate-store-kit'),
					'type'  => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 500,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .usk-brand-carousel-img' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'brand_image_opaciry',
				[
					'label' => __('Opaciry', 'ultimate-store-kit'),
					'type'  => Controls_Manager::SLIDER,
					'default' => [
                        'size' => 0.3,
                    ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => 0.1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .usk-brand-carousel-img' => 'opacity: {{SIZE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name'     => 'css_filters',
					'selector' => '{{WRAPPER}} .usk-brand-carousel-img',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_item_hover',
				[
					'label' => esc_html__( 'Hover', 'ultimate-store-kit' ),
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'      => 'item_hover_background',
					'selector'  => '{{WRAPPER}} .usk-brand-carousel-item:hover',
				]
			);

			$this->add_control(
				'item_hover_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'ultimate-store-kit' ),
					'type'      => Controls_Manager::COLOR,
					'default' 	=> '#2B2D42',
					'condition' => [
						'item_border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .usk-brand-carousel-item:hover' => 'border-color: {{VALUE}};',
					],
					'separator' => 'before'
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'item_hover_box_shadow',
					'selector' => '{{WRAPPER}} .usk-brand-carousel-item:hover',
				]
			);

			$this->add_control(
				'image_heading_hover',
				[
					'label'     => __('I M A G E', 'ultimate-store-kit'),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before'
				]
			);

			$this->add_control(
				'brand_image_opaciry_hover',
				[
					'label' => __('Opaciry', 'ultimate-store-kit'),
					'type'  => Controls_Manager::SLIDER,
					'default' => [
                        'size' => 1,
                    ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => 0.1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .usk-brand-carousel-item:hover .usk-brand-carousel-img' => 'opacity: {{SIZE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name'     => 'css_filters_hover',
					'selector' => '{{WRAPPER}} .usk-brand-carousel-item:hover .usk-brand-carousel-img',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

			$this->register_global_controls_navigation_style();

		}
		
		public function render_loop_item() {
			$settings = $this->get_settings_for_display();
			
			if ( empty( $settings['brand_items'] ) ) {
				return;
			}
			
			// $this->add_render_attribute( 'brand-carousel', 'class', 'usk-brand-carousel' );

			?>
        	<!-- <div <?php //$this->print_render_attribute_string( 'brand-carousel' ); ?>> -->
			<?php foreach ( $settings['brand_items'] as $item ) : 
				
				$thumb_url = Group_Control_Image_Size::get_attachment_image_src($item['image']['id'], 'thumbnail', $settings);
				if ( !$thumb_url ) {
					$thumb_url = $item['image']['url'];
				}

				$this->add_render_attribute(
					[
						'link' => [
							'href'   => isset($item['link']['url']) && !empty($item['link']['url']) ? esc_url($item['link']['url']) : 'javascript:void(0);',
							'target' => $item['link']['is_external'] ? '_blank' : '_self'
						]
					], '', '', true
				);

				$this->add_render_attribute('item-wrap', 'class', 'usk-brand-carousel-item', true);
		
				?>
				<div class="swiper-slide">
				<div <?php echo $this->get_render_attribute_string('item-wrap'); ?> title="<?php echo esc_html($item['brand_name']); ?>">
					<img class="usk-brand-carousel-img" src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_html($item['brand_name']); ?>">
					<?php 
					if ( !empty($item['link']['url']) ){
					printf('<a %1$s title="%2$s"></a>',$this->get_render_attribute_string( 'link' ), wp_kses_post($item['brand_name'])); }?>
				</div>
				</div>

			<?php endforeach; ?>
            <!-- </div> -->
			<?php
		}

		public function render() {
			$this->register_global_template_carousel_header();
			$this->render_loop_item();
			$this->usk_register_global_template_carousel_footer();
		}
	}
