<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Lite_WPKoi_Advanced_Heading extends Widget_Base {

	public function get_name() {
		return 'wpkoi-advanced-heading';
	}

	public function get_title() {
		return esc_html__( 'Advanced Heading', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

   public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}


	protected function register_controls() {


  		$this->start_controls_section(
			'section_content_heading',
			[
				'label' => __( 'Heading', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'main_heading',
			[
				'label'       => __( 'Heading Text', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Add Your text here', 'wpkoi-elements' ),
				'default'     => '',
			]
		);

		$this->add_control(
			'after_main_heading',
			[
				'label'     => __( 'After Heading Text', 'wpkoi-elements' ),
				'separator' => 'before',
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'after_text',
			[
				'label'       => __( 'After Text', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
                'placeholder' => __( 'Add Your text here', 'wpkoi-elements' ),
                'condition'   => [
                    'after_main_heading' => 'yes'
				]
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'wpkoi-elements' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL or type', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => __( 'HTML Tag', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
						'h1'  => esc_html__( 'H1', 'wpkoi-elements' ),
						'h2'  => esc_html__( 'H2', 'wpkoi-elements' ),
						'h3'  => esc_html__( 'H3', 'wpkoi-elements' ),
						'h4'  => esc_html__( 'H4', 'wpkoi-elements' ),
						'h5'  => esc_html__( 'H5', 'wpkoi-elements' ),
						'h6'  => esc_html__( 'H6', 'wpkoi-elements' ),
						'div'  => esc_html__( 'div', 'wpkoi-elements' ),
						'span'  => esc_html__( 'span', 'wpkoi-elements' ),
						'p'  => esc_html__( 'p', 'wpkoi-elements' ),
					),
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Alignment', 'wpkoi-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],

			]
		);

		$this->end_controls_section();
		

		$this->start_controls_section(
			'section_style_main_heading',
			[
				'label'     => __( 'Heading', 'wpkoi-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'main_heading!' => '',
				],
			]
		);

		$this->add_control(
			'main_heading_color',
			[
				'label'     => __( 'Color', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'main_heading_background',
			[
				'label'     => __( 'Background', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'main_heading_padding',
			[
				'label'      => esc_html__('Padding', 'wpkoi-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'main_heading_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div'
			]
		);

		$this->add_control(
			'main_heading_radius',
			[
				'label'      => esc_html__('Radius', 'wpkoi-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'main_heading_shadow',
				'selector' => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'main_heading_text_shadow',
				'selector' => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'main_heading_typography',
				'selector' => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div',
			]
		);

		$this->add_control(
			'heading_mainh_after_text',
			[
				'label'     => __( 'After Text', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);

		$this->add_control(
			'mainh_after_text_color',
			[
				'label'     => __( 'Color', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading .wpkoi-mainh-after-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);

		$this->add_control(
			'mainh_after_text_background',
			[
				'label'     => __( 'Background', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading .wpkoi-mainh-after-text' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);

        $this->add_responsive_control(
            'after_text_space',
            [
                'label'   => __( 'Space Before', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpkoi-main-heading .wpkoi-main-heading-inner' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition'   => [
                    'after_main_heading' => 'yes'
                ],
                'separator'   => 'after',
            ]
        );

		$this->add_responsive_control(
			'mainh_after_text_padding',
			[
				'label'      => esc_html__('Padding', 'wpkoi-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading .wpkoi-mainh-after-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'mainh_after_text_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading .wpkoi-mainh-after-text',
				'condition'   => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);

		$this->add_control(
			'mainh_after_text_radius',
			[
				'label'      => esc_html__('Radius', 'wpkoi-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading .wpkoi-mainh-after-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;'
				],
				'condition' => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'mainh_after_text_shadow',
				'selector'  => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading .wpkoi-mainh-after-text',
				'condition' => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'mainh_after_text_typography',
				'selector'  => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading .wpkoi-mainh-after-text',
				'condition' => [
					'after_main_heading' => 'yes',
					'after_text!'        => ''
				]
			]
		);	

		$this->add_control(
			'heading_advanced_text',
			[
				'label'     => __( 'Advanced Style', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'main_heading_advanced_color',
			[
				'label'        => __( 'Advanced Style', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpkoi-main-color-',
				'render_type'  => 'template',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'main_heading_advanced_color',
				'selector' => '{{WRAPPER}} .wpkoi-advanced-heading .wpkoi-main-heading > div'
			]
		);

		$this->add_control(
			'heading_flicker_text',
			[
				'label'     => __( 'Flicker', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'main_heading_flicker',
			[
				'label'        => __( 'Add Flicker', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpkoi-flicker-',
				'render_type'  => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'flicker_color_1',
			[
				'label'     => __( 'Color 1', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default' 	=> '#ff0000',
				'condition' => [
					'main_heading_flicker' => 'yes'
				]
			]
		);

		$this->add_control(
			'flicker_color_2',
			[
				'label'     => __( 'Color 2', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default' 	=> '#00ff2a',
				'condition' => [
					'main_heading_flicker' => 'yes'
				]
			]
		);

        $this->add_control(
            'flicker_speed',
            [
                'label'   => __( 'Flicker Speed', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 190,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 199,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_flicker' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'flicker_size',
            [
                'label'   => __( 'Flicker Size', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_flicker' => 'yes'
                ]
            ]
        );

		$this->add_control(
			'heading_circletype_text',
			[
				'label'     => __( 'Circletype', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'main_heading_circletype',
			[
				'label'        => __( 'Add Circletype', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpkoi-circletype-',
				'render_type'  => 'template',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
            'circletype_radius',
            [
                'label'   => __( 'Radius', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 150,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 360,
						'step' => 1,
					]
				],
				'render_type'  => 'template',
                'condition'   => [
                    'main_heading_circletype' => 'yes'
                ]
            ]
        );
		
		$this->add_control(
			'circletype_dir',
			[
				'label'   => __( 'Direction', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
						'1'  => esc_html__( 'clockwise', 'wpkoi-elements' ),
						'-1'  => esc_html__( 'counter-clockwise', 'wpkoi-elements' )
					),
				'default' => '1',
				'render_type'  => 'template',
                'condition'   => [
                    'main_heading_circletype' => 'yes'
                ]
			]
		);

		$this->add_control(
			'heading_stroke_text',
			[
				'label'     => __( 'Stroke', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'main_heading_stroke',
			[
				'label'        => __( 'Add Stroke', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpkoi-stroke-',
				'render_type'  => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'stroke_color',
			[
				'label'     => __( 'Stroke Color', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default' 	=> '#000000',
				'condition' => [
					'main_heading_stroke' => 'yes'
				]
			]
		);
		
		$this->add_control(
            'stroke_width',
            [
                'label'   => __( 'Width', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_stroke' => 'yes'
                ]
            ]
        );

		$this->add_control(
			'stroke_bg_transparent',
			[
				'label'        => __( 'Transparent Font', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpkoi-stroke-tbg-',
				'render_type'  => 'template',
                'condition'   => [
                    'main_heading_stroke' => 'yes'
                ]
			]
		);

		$this->end_controls_section();


	}


	protected function render( ) {

      	$settings         = $this->get_settings_for_display();
		$id               = $this->get_id();
		$heading_html     = [];
		$main_heading     = '';
		$after_heading    = '';

		if ( empty( $settings['main_heading'] ) ) {
			return;
		}

		$this->add_render_attribute( 'heading', 'class', 'wpkoi-heading-title' );


		$this->add_render_attribute( 'main_heading', 'class', 'wpkoi-main-heading-inner' );
		$this->add_inline_editing_attributes( 'main_heading' );

		$this->add_render_attribute( 'after_heading', 'class', 'wpkoi-mainh-after-text' );

		if ($settings['main_heading']) :

			$mainh_style = '';

			if ( ( 'yes' == $settings['after_main_heading'] ) and ( ! empty($settings['after_text']) ) ) {
				$after_heading = '<div '.$this->get_render_attribute_string( 'after_heading' ).'>' . $settings['after_text'] . '</div>';
			}

			$main_heading = '<div '.$this->get_render_attribute_string( 'main_heading' ).'  id="wpkoi-heading-title-'.$id.'"><span>' . $settings['main_heading'] . '</span></div>';

			$main_heading = '<div class="wpkoi-main-heading">' . $main_heading . $after_heading . $mainh_style . '</div>';

		endif;


		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', esc_url($settings['link']['url']) );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'url', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'url', 'rel', 'nofollow' );
			}

			$main_heading = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $main_heading );
		}

		$heading_html[] = '<div id ="'.$id.'" class="wpkoi-advanced-heading">';
		
		$validated_header_size = $settings['header_size'];
		if ( ( $validated_header_size != 'h1' ) && ( $validated_header_size != 'h2' ) && ( $validated_header_size != 'h3' ) && ( $validated_header_size != 'h4' ) && ( $validated_header_size != 'h5' ) && ( $validated_header_size != 'h6' ) && ( $validated_header_size != 'div' ) && ( $validated_header_size != 'span' ) && ( $validated_header_size != 'p' ) ){
			$validated_header_size = 'h2';
		}
		
		$heading_html[] = sprintf( '<%1$s %2$s">%3$s</%1$s>', $validated_header_size, $this->get_render_attribute_string( 'heading' ), $main_heading );
		
		$heading_html[] = '</div>';
		
		if ( 'yes' == $settings['main_heading_flicker'] ) {
			$flickerspeed = $settings["flicker_speed"]["size"] / 100;
			$flickerspeed = 2 - $flickerspeed;
			if ( 1 == $settings["flicker_size"]["size"] ) {
				$flickersize_1 = '1px 0 0';
				$flickersize_2 = '-1px 0 0';
				$flickersize_3 = '2px 0.5px 1px';
				$flickersize_4 = '-1px -0.5px 1px';
			} elseif ( 3 == $settings["flicker_size"]["size"] ) {
				$flickersize_1 = '3px 0 0';
				$flickersize_2 = '-5px 0 0';
				$flickersize_3 = '7px 2px 1px';
				$flickersize_4 = '-4px -1px 4px';
			} elseif ( 4 == $settings["flicker_size"]["size"] ) {
				$flickersize_1 = '8px 0 0';
				$flickersize_2 = '-10px 0 0';
				$flickersize_3 = '-7px -3px 8px';
				$flickersize_4 = '10px 4px 3px';
			} elseif ( 5 == $settings["flicker_size"]["size"] ) {
				$flickersize_1 = '15px 0 0';
				$flickersize_2 = '-18px 0 0';
				$flickersize_3 = '-10px -5px 12px';
				$flickersize_4 = '15px 7px 5px';
			} else {
				$flickersize_1 = '2px 0 0';
				$flickersize_2 = '-3px 0 0';
				$flickersize_3 = '4px 0.5px 1px';
				$flickersize_4 = '-2px -1px 3px';
			}
			$heading_html[] = '<style type="text/css">.elementor-element-' . $id .' .wpkoi-heading-title{animation-duration: ' . $flickerspeed . 's;animation-name: textflicker' . $id .';}@keyframes textflicker' . $id .' {from {text-shadow: ' . $flickersize_1 . ' ' . $settings["flicker_color_1"] . ', ' . $flickersize_2 . ' ' . $settings["flicker_color_2"] . ';}to {text-shadow: ' . $flickersize_3 . ' ' . $settings["flicker_color_1"] . ', ' . $flickersize_4 . ' ' . $settings["flicker_color_2"] . ';}}</style>';
		}
		
		if ( 'yes' == $settings['main_heading_stroke'] ) {
			$heading_html[] = '<style type="text/css">.elementor-element-' . $id .' .wpkoi-heading-title{-webkit-text-stroke: ' . $settings["stroke_width"]["size"] . 'px ' . $settings["stroke_color"] . '; text-stroke: ' . $settings["stroke_width"]["size"] . 'px ' . $settings["stroke_color"] . ';}</style>';
		}
		
		if ( 'yes' == $settings['main_heading_circletype'] ) {
			$circletyperadius = $settings["circletype_radius"]["size"];
			$circletypedir = $settings["circletype_dir"];
			$heading_html[] = '<script type="text/javascript">
			jQuery(document).ready(function($) {
			new CircleType(document.getElementById("wpkoi-heading-title-'.$id.'"))';
			
			if ( $circletyperadius != '360' ) {
				$heading_html[] = '.dir(' . $circletypedir . ').radius(' . $circletyperadius . ');';
			}
			
			$heading_html[] = '});
			</script>';
		}

		echo implode("", $heading_html);
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi-circletype-js',WPKOI_ELEMENTS_LITE_URL.'elements/advanced-heading/assets/circletype.min.js', [ 'elementor-frontend' ],'1.0', true);
	}

	public function get_script_depends() {
		return [ 'wpkoi-circletype-js' ];
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register( new Widget_Lite_WPKoi_Advanced_Heading() );