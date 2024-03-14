<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_FD_Elementor_Imagebox extends Widget_Base {

	public function get_name() { 		//Function for get the slug of the element name.
		return 'fd_adv_imagebox';
	}

	public function get_title() { 		//Function for get the name of the element.
		return __( 'Elementor Imagebox', 'FD_EAW' );
	}

	public function get_icon() {
		return 'eicon-image-box'; //Function for get the icon of the element.
	}
	
	public function get_categories() { 		//Function for include element into the category.
		return [ 'fd-imagebox' ];
	}
	/**
	 * Retrieve image box widget link URL.
	 *
	 * @access private
	 *
	 * @param object $instance
	 *
	 * @return array|string|false An array/string containing the link URL, or false if no link.
	 */
	private function get_link_url( $instance ) {
		if ( 'none' === $instance['link_to'] ) {
			return false;
		}

		if ( 'custom' === $instance['link_to'] ) {
			if ( empty( $instance['link']['url'] ) ) {
				return false;
			}
			return $instance['link'];
		}

		return [
			'url' => $instance['image']['url'],
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'FD_EAW' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'FD_EAW' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link', 'FD_EAW' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'FD_EAW' ),
					'file' => __( 'Media File', 'FD_EAW' ),
					'custom' => __( 'Custom URL', 'FD_EAW' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'FD_EAW' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'http://www.example.com', 'FD_EAW' ),
				'condition' => [
					'link_to' => 'custom',
				],
				'show_label' => false,
			]
		);

		$this->add_control(
			'title1',
			[
				'label' => __( 'Heading 1', 'FD_EAW' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'This is', 'FD_EAW' ),
				'placeholder' => __( 'Your Title', 'FD_EAW' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'title2',
			[
				'label' => __( 'Heading 2', 'FD_EAW' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Heading', 'FD_EAW' ),
				'placeholder' => __( 'Your Title', 'FD_EAW' ),
				'label_block' => true,
				'separator' => 'after',
			]
		);
		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'FD_EAW' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'FD_EAW' ),
				'placeholder' => __( 'Button Text', 'FD_EAW' ),
				'label_block' => true,
			]
		);
		
		
		
		$this->add_control(//Add control to select an icon for button2.
            'btn_icon', 			
			[
				'label' => __('Icon', FD_EAW),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],				
            ]
			
        );

		$this->add_control(
			'btn_icon_align',
			[
				'label' => __( 'Icon Position', 'FD_EAW' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left' => __( 'Before', 'FD_EAW' ),
					'right' => __( 'After', 'FD_EAW' ),
				],
				'condition' => [
					'btn_icon!' => '',
				],
			]
		); 

		$this->add_control(
			'btn_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'FD_EAW' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'btn_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .fd-image-box .fd-image-box-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .fd-image-box .fd-image-box-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		); 
		$this->end_controls_section(); // End of image setting section
		
		$this->start_controls_section(   // Start style section 
			'section_style_image',
			[
				'label' => __( 'Image', 'FD_EAW' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Size (%)', 'FD_EAW' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .fd-image-box img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity (%)', 'FD_EAW' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .fd-image-box img' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_title1',
			[
				'label' => __( 'Title 1', 'FD_EAW' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_title1_style' );

		$this->start_controls_tab(
			'normal_tab_title1',
			[
				'label' => __( 'Normal', 'FD_EAW' ),
			]
		);
   
		$this->add_control(
			'title1_text_color',
			[
				'label' => __( 'Text Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h4'=> 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title1_typography',
				'selector' => '{{WRAPPER}} .fd-image-box h4',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover_tab_title1',
			[
				'label' => __( 'Hover', 'FD_EAW' ),
			]
		);

		$this->add_control(
			'hover_color_title1',
			[
				'label' => __( 'Text Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h4:hover'=> 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();   // End Of title1 style
		
		$this->start_controls_section(   // Start new section for title2 style
			'section_style_title2',
			[
				'label' => __( 'Title 2', 'FD_EAW' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_title2_style' );

		$this->start_controls_tab(
			'normal_tab_title2',
			[
				'label' => __( 'Normal', 'FD_EAW' ),
			]
		);
   
		$this->add_control(
			'title2_text_color',
			[
				'label' => __( 'Text Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h4 strong'=> 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title2_typography',
				'selector' => '{{WRAPPER}} .fd-image-box h4 strong',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover_tab_title2',
			[
				'label' => __( 'Hover', 'FD_EAW' ),
			]
		);

		$this->add_control(
			'hover_color_title2',
			[
				'label' => __( 'Text Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h4 strong:hover'=> 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();   // End Of title2 Style
		
	    $this->start_controls_section(   // Start new section for button style
			'section_style_button',
			[
				'label' => __( 'Button', 'FD_EAW' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'normal_tab_button',
			[
				'label' => __( 'Normal', 'FD_EAW' ),
			]
		);
   
		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h5'=> 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h5'=> 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(			//Add style control to set border width for Single button.
			Group_Control_Border::get_type(),
			[
				'name' => 'btn_border',
				'label' => __( 'Border', 'FD_EAW' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .fd-image-box h5',
			]
		);
		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'FD_EAW' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h5' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .fd-image-box h5',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover_tab_button',
			[
				'label' => __( 'Hover', 'FD_EAW' ),
			]
		);

		$this->add_control(
			'hover_color_button',
			[
				'label' => __( 'Text Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h5:hover'=> 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_bg_hover_color',
			[
				'label' => __( 'Background Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h5:hover'=> 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();   // End Of button Style
		
        $this->start_controls_section(   // Start new section for button style
			'section_style_button_icon',
			[
				'label' => __( 'Button Icon', 'FD_EAW' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_icon_style' );

		$this->start_controls_tab(
			'normal_tab_button_icon',
			[
				'label' => __( 'Normal', 'FD_EAW' ),
			]
		);
   
		$this->add_control(
			'button_icon_text_color',
			[
				'label' => __( 'Icon Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .fd-image-box h5 i'=> 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover_tab_button_icon',
			[
				'label' => __( 'Hover', 'FD_EAW' ),
			]
		);

		$this->add_control(
			'hover_color_button_icon',
			[
				'label' => __( 'Icon Color', 'FD_EAW' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .fd-image-box i:hover'=> 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();   // End Of title2 Style
		
	}
	
	/**
	 * Render image box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		
		$this->add_render_attribute( 'wrapper', 'class', 'fd-image-box' );
		if ( ! empty( $settings['image']['url'] ) ) {
			$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
		}
       
		$link = $this->get_link_url( $settings );

		if ( $link ) {
			$this->add_render_attribute( 'link', [
				'href' => $link['url'],
				
			] );

			if ( ! empty( $link['is_external'] ) ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}
		$title1 = $settings['title1'];
		$title2 = $settings['title2'];
		$button = $settings['button_text'];
		$this->add_render_attribute( 'btn-icon-align', 'class', 'fd-image-box-icon-' . $settings['btn_icon_align'] );
		$this->add_render_attribute( 'btn-icon-align', 'class', 'fd-image-box' );
		
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php //if ( $link['url'] ) { ?>
			<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
			<?php //} ?>
			 <img <?php echo $this->get_render_attribute_string( 'image' ); ?>>
			    <?php if ( $settings['title1'] != "" || $settings['title2'] != "" )  { ?>
				<h4>
					<?php echo $title1; ?>
					<?php if ( $settings['title2'] != "" )  { ?>
						<strong><?php echo ' '.$title2; ?></strong>
					<?php } ?>
				</h4>
				<?php } ?>
				<h5>
				    <?php   if ( !empty( $settings['btn_icon'] ) ) : ?>
					            <span <?php echo $this->get_render_attribute_string( 'btn-icon-align' ); ?>>
				                    
									 <?php Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</span>	
			                <?php endif; ?>
				    <?php echo $button; ?>
				</h5>
			<?php //if ( $link['url'] ) { ?></a><?php //} ?>
		</div>
	<?php	
	}

    /**
	 * Render image box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() { 
		//TODO: implement instant inline editing
		/*?>
			<h4 class="{{{ settings.class }}}">{{{ settings.title1 }}}</h4>
	<?php */
	}	
}
Plugin::instance()->widgets_manager->register( new Widget_FD_Elementor_Imagebox() );