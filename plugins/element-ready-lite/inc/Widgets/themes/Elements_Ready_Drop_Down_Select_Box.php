<?php

namespace Element_Ready\Widgets\themes;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elements_Ready_Drop_Down_Select_Box extends Widget_Base {

	public function get_name() {
		return 'Elements_Ready_Drop_Down_Select_Box';
	}

	public function get_title(){
		return esc_html__( 'ER DropDown Box' , 'element-ready-lite' );
	}

	public function get_script_depends() {

		return[
			'element-ready-core',
		];
	}

	public function get_style_depends() {

		wp_register_style( 'eready-dropdown-box' , ELEMENT_READY_ROOT_CSS.'widgets/eready-dropdown-box.min.css' );
		
		return[
			'eready-dropdown-box'
		];
	}


	public function get_icon() {
		return 'eicon-date';
	}

	public function get_categories() {
		return array('element-ready-addons');
	}

    public function get_keywords() {
        return [ 'select', 'dropdown', 'combobox' ];
    }

	protected function register_controls() {

	
		$this->start_controls_section(
			'section_Settings',
			[
				'label' => esc_html__( 'Settings', 'element-ready-lite' ),
			]
		);

		$repeater = new \Elementor\Repeater();
		$this->add_control(
			'open_link_on_select',
			[
				'label' => esc_html__( 'Open Link On select?', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'your-plugin' ),
				'label_off' => esc_html__( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'open_link_new_tab',
			[
				'label' => esc_html__( 'New Tab?', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'your-plugin' ),
				'label_off' => esc_html__( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'element-ready-lite' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'open_link_on_select',
			[
				'label' => esc_html__( 'Link / Text value?', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'your-plugin' ),
				'label_off' => esc_html__( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$repeater->add_control(
			'list_value', [
				'label' => esc_html__( 'Value', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Value' , 'element-ready-lite' ),
				'label_block' => true,
				'condition' => [
					'open_link_on_select!' => ['yes']
				]
			]
		);
		$repeater->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'element-ready-lite' ),
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
				],
				'condition' => [
					'open_link_on_select' => ['yes']
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Option List', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'English', 'element-ready-lite' ),
						'open_link_on_select' => 'yes',
						'list_value' => '',
						'website_link' => [
							'url' => '',
							'is_external' => true,
							'nofollow' => true,
						]
					],
					[
						'list_title' => esc_html__( 'Arabic', 'element-ready-lite' ),
						'open_link_on_select' => 'yes',
						'list_value' => '',
						'website_link' => [
							'url' => '',
							'is_external' => true,
							'nofollow' => true,
						]
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_select_section',
			[
				'label' => esc_html__( 'Select', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
    

			$this->add_responsive_control(
				'select_container_width',
					[
						'label' => esc_html__( 'Width', 'element-ready-lite' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' , '%' ],
						'range' => [
							'px' => [
								'min' => 50,
								'max' => 550,
								'step' => 5,
							],
							
						],
					
						'selectors' => [
							'{{WRAPPER}} .eready-dropdown-wrapper select' => 'width: {{SIZE}}{{UNIT}};',
						],
				
				]
			);

			$this->add_responsive_control(
				'select_container_height',
					[
						'label' => esc_html__( 'Height', 'element-ready-lite' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' , '%' ],
						'range' => [
							
							'px' => [
								'min' => 20,
								'max' => 550,
								'step' => 5,
							]
							
						],
					
						'selectors' => [
							'{{WRAPPER}} .eready-dropdown-wrapper select' => 'height: {{SIZE}}{{UNIT}};',
						],
				
				]
			);

			$this->add_control(
				'text_color',
				[
					'label' => esc_html__( 'Text Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eready-dropdown-wrapper select' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .eready-dropdown-wrapper select',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'element_ready_selct_border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .eready-dropdown-wrapper select',
				]
			);

			$this->add_control(
				'icon_select_border_rad',
					[
						'label' => esc_html__( 'Border Radius', 'element-ready-lite' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
							
						],
						'default' => [
							'unit' => 'px',
							'size' => 3,
						],
						'selectors' => [
							'{{WRAPPER}} .eready-dropdown-wrapper:before' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
				
					]
				);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'element_ready_select_background',
					'label' => esc_html__( 'Background', 'element-ready-lite' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .eready-dropdown-wrapper select',
				]
			);

			$this->add_responsive_control(
				'element_ready_select_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .eready-dropdown-wrapper select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			
			$this->add_responsive_control(
				'element_ready_select_margin',
				[
					'label' => esc_html__( 'Margin', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .eready-dropdown-wrapper select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_ste_wrapper_section',
			[
				'label' => esc_html__( 'Icon', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
			'icon_width_gap',
				[
					'label' => esc_html__( 'Icon Width', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 150,
							'step' => 5,
						],
						
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .eready-dropdown-wrapper:before' => 'width: {{SIZE}}{{UNIT}};',
					],
			
				]
			);

			$this->add_responsive_control(
				'icon_height_gap',
					[
						'label' => esc_html__( 'Icon Height', 'element-ready-lite' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 150,
								'step' => 5,
							],
							
						],
						'default' => [
							'unit' => 'px',
							'size' => 31,
						],
						'selectors' => [
							'{{WRAPPER}} .eready-dropdown-wrapper:before' => 'height: {{SIZE}}{{UNIT}};',
						],
				
					]
			);

			$this->add_responsive_control(
			'icon_pos_container_right',
				[
					'label' => esc_html__( 'Position Container Right', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -450,
							'max' => 450,
							'step' => 2,
						],
						
					],
				
					'selectors' => [
						'{{WRAPPER}} .eready-dropdown-wrapper:before' => 'right: {{SIZE}}{{UNIT}};',
					],
			
				]
			);

			$this->add_responsive_control(
				'icon_pos_container_top',
					[
						'label' => esc_html__( 'Position Container Top', 'element-ready-lite' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => -250,
								'max' => 250,
								'step' => 5,
							],
							
						],
					
						'selectors' => [
							'{{WRAPPER}} .eready-dropdown-wrapper:before' => 'top: {{SIZE}}{{UNIT}};',
						],
				
					]
			);

			$this->add_responsive_control(
				'icon_pos_top',
					[
						'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px','%' ],
						'range' => [
							'px' => [
								'min' => -250,
								'max' => 250,
								'step' => 5,
							],
							'%' => [
								'min' => -100,
								'max' => 100,
								'step' => 1,
							],
							
						],
					
						'selectors' => [
							'{{WRAPPER}} .eready-dropdown-wrapper:after' => 'top: {{SIZE}}{{UNIT}};',
						],
				
					]
			);

			$this->add_responsive_control(
				'icon_pos__right',
					[
						'label' => esc_html__( 'Position Right', 'element-ready-lite' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => -450,
								'max' => 450,
								'step' => 2,
							],
							
						],
					
						'selectors' => [
							'{{WRAPPER}} .eready-dropdown-wrapper:after' => 'right: {{SIZE}}{{UNIT}};',
						],
				
					]
				);

			$this->add_control(
				'icon_bg_color',
				[
					'label' => esc_html__( 'Icon Background', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eready-dropdown-wrapper:before' => 'background: {{VALUE}}',
					],
				]
			);
		
			$this->add_control(
				'icon_bg_afye_color',
				[
					'label' => esc_html__( 'Icon Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .eready-dropdown-wrapper:after' => 'border-color: {{VALUE}} transparent transparent transparent;',
					],
				]
			);
		

		$this->end_controls_section();

	}
	
	protected function render() {

		$settings            = $this->get_settings_for_display();
		$list                = $settings[ 'list' ];
		$open_link_on_select = $settings[ 'open_link_on_select' ];
		$open_link_new_tab   = $settings[ 'open_link_new_tab' ];
      ?>
	  
	    <div class="eready-dropdown-wrapper <?php echo esc_attr($open_link_on_select == 'yes'? 'er-open-link' : ''); ?>">
			<select data-open_tab="<?php echo esc_attr($open_link_new_tab); ?>">
				<?php foreach($list as $item): ?>
					<?php if($item['open_link_on_select'] == 'yes'): ?>
						<option value="<?php echo esc_url($item['website_link']['url']); ?>"> <?php echo esc_html($item['list_title']); ?> </option>
					<?php else: ?>	
						<option value="<?php echo esc_attr($item['list_value']); ?>"> <?php echo esc_html($item['list_title']); ?> </option>
					<?php endif; ?>	
				<?php endforeach; ?>
			</select>
		</div>
	
	  <?php
	}	
}
