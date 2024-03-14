<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_SJEaImageSeparator extends Widget_Base {

	public function get_name() {
		return 'sjea-image-separator';
	}

	public function get_title() {
		return __( 'SJEA - Image Separator', 'sjea' );
	}

	public function get_categories() {
		return [ 'sjea-elements' ];
	}

	public function get_icon() {
		return 'eicon-divider';
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'sjea' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'sjea' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`
				'label' => __( 'Image Size', 'sjea' ),
				'default' => 'large',
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link to', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'sjea' ),
					'file' => __( 'Media File', 'sjea' ),
					'custom' => __( 'Custom URL', 'sjea' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link to', 'sjea' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'http://your-link.com', 'sjea' ),
				'condition' => [
					'link_to' => 'custom',
				],
				'show_label' => false,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'sjea' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		/* Image Position */
		$this->start_controls_section(
			'section_image_position',
			[
				'label' => __( 'Image Position', 'sjea' ),
			]
		);

		$this->add_control(
			'position',
			[
					'label' => __( 'Top / Bottom Position', 'sjea' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
							'top' => __( 'Top', 'sjea' ),
							'bottom' => __( 'Bottom', 'sjea' ),
					],
					'default' => 'top',
			]
		);

		$this->add_control(
			'align',
			[
				'label' => __( 'Alignment', 'sjea' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'sjea' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'sjea' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'sjea' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
			]
		);
		
		$this->add_control(
				'gutter',
				[
						'type' => Controls_Manager::NUMBER,
						'label' => __( 'Image Gutter (%)', 'sjea' ),
						'placeholder' => __( '50', 'sjea' ),
						'default' => __( '50', 'sjea' ),
				]
		);

		$this->add_control(
				'top_offset',
				[
						'type' => Controls_Manager::NUMBER,
						'label' => __( 'Top Offset (px)', 'sjea' ),
						'placeholder' => __( '-10', 'sjea' ),
						'default' => __( '-10', 'sjea' ),
						'condition' => [
							'position' => 'top',
						],
						'selectors' => [
							'{{WRAPPER}} .sjea-image-separator' => 'top: {{SIZE}}px;',
						],
				]
		);
		$this->add_control(
				'bottom_offset',
				[
						'type' => Controls_Manager::NUMBER,
						'label' => __( 'Bottom Offset (px)', 'sjea' ),
						'placeholder' => __( '-10', 'sjea' ),
						'default' => __( '-10', 'sjea' ),
						'condition' => [
							'position' => 'bottom',
						],
						'selectors' => [
							'{{WRAPPER}} .sjea-image-separator' => 'bottom: {{SIZE}}px;',
						],
				]
		);
		$this->add_control(
				'left_offset',
				[
						'type' => Controls_Manager::NUMBER,
						'label' => __( 'Left Offset (px)', 'sjea' ),
						'placeholder' => __( '-10', 'sjea' ),
						'default' => __( '-10', 'sjea' ),
						'condition' => [
							'align' => 'left',
						],
						'selectors' => [
							'{{WRAPPER}} .sjea-image-separator' => 'left: {{SIZE}}px;',
						],
				]
		);


		$this->add_control(
				'right_offset',
				[
						'type' => Controls_Manager::NUMBER,
						'label' => __( 'Right Offset (px)', 'sjea' ),
						'placeholder' => __( '-10', 'sjea' ),
						'default' => __( '-10', 'sjea' ),
						'condition' => [
							'align' => 'right',
						],
						'selectors' => [
							'{{WRAPPER}} .sjea-image-separator' => 'right: {{SIZE}}px;',
						],
				]
		);


		$this->end_controls_section();

		/* Style Tab */ 
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'sjea' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'img_size',
			[
				'label' => __( 'Size (px)', 'sjea' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 200,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sjea-image-separator img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity (%)', 'sjea' ),
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
					'{{WRAPPER}} .sjea-image-separator img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'sjea' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Image Border', 'sjea' ),
				'selector' => '{{WRAPPER}} .sjea-image-separator img',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'sjea' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sjea-image-separator img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .sjea-image-separator img',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$node_id = $this->get_id();
		$name = $this->get_name();
        $settings = $this->get_settings();
        
		SJEaModuleScripts::sjea_image_separator();
		
        if ( Plugin::instance()->editor->is_edit_mode() ) {
			
			SJEaModuleScripts::sjea_image_separator_dynamic( $node_id, $settings, true );
			
			echo "<div style='text-align:center;'><span>Click here to edit image-separator-".$node_id." module.</span>";
			echo "<br><span>This message will not show in frontend.</span></div>";
		}
        //var_dump( Plugin::instance()->preview->is_preview_mode() );
		
		include SJ_EA_DIR . 'modules/sjea-image-separator/includes/frontend.php';
	}

	private function get_attachment_image_html( $settings ) {
		return Group_Control_Image_Size::get_attachment_image_html( $settings );
	}


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
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_SJEaImageSeparator() );