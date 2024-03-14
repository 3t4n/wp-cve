<?php

namespace Element_Ready\Widgets\progressbar;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Element_Ready_Progress_Roadmap_Widget extends Widget_Base {

	public function get_name() {
		return 'Element_Ready_Progress_Roadmap_Widget';
	}

	public function get_title() {
		return esc_html__( 'ER Progress Roadmap', 'element-ready-lite' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	public function get_categories() {
		return array('element-ready-addons');
	}

    public function get_keywords() {
        return [ 'Timeline Progeress', 'Progress', 'Roadmap', 'Timeline' ];
    }

	public function get_style_depends() {

        wp_register_style( 'eready-progress-roadmap' , ELEMENT_READY_ROOT_CSS. 'widgets/progress-roadmap.css' );
        return [ 'eready-progress-roadmap' ];
    }

	public static function content_layout_style(){
		return [
			'single__timeline__roadmap__layout__1'      => 'Timeline Roadmap Style 1',
			'single__timeline__roadmap__layout__2'      => 'Timeline Roadmap Style 2',
			'single__timeline__roadmap__layout__custom' => 'Custom Roadmap Style',
		];
	}

	public function get_script_depends(){
        return [
            'roadmap',
            'element-ready-core',
        ];
	}

	protected function register_controls() {

		/******************************
		 * 	CONTENT SECTION
		 ******************************/
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'progressbar_percent',
			[
				'label'      => esc_html__( 'Progress Percent', 'element-ready-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => '10',
				],
			]
		);

		$this->end_controls_section();

		/*********************************
		 * 		STYLE SECTION
		 *********************************/

		/*----------------------------
			PROGRESSBAR WRAP
		-----------------------------*/
		$this->start_controls_section(
			'prog_wrap_style_section',
			[
				'label' => esc_html__( 'Progress Wrap', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background:: get_type(),
			[
				'name'     => 'prog_wrap_background',
				'label'    => esc_html__( 'Background', 'element-ready-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .progressbar__wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Border:: get_type(),
			[
				'name'     => 'prog_wrap_border',
				'label'    => esc_html__( 'Border', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} .progressbar__wrap',
			]
		);

		$this->add_control(
			'prog_wrap_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .progressbar__wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow:: get_type(),
			[
				'name'     => 'prog_wrap_shadow',
				'selector' => '{{WRAPPER}} .progressbar__wrap',
			]
		);

		$this->add_responsive_control(
			'prog_wrap_height',
			[
				'label'      => esc_html__( 'Height', 'element-ready-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .progressbar__wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'prog_wrap_margin',
			[
				'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .progressbar__wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'prog_wrap_padding',
			[
				'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .progressbar__wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			PROGRESSBAR WRAP END
		-----------------------------*/

		/*----------------------------
			PROGRESSBAR
		-----------------------------*/
		$this->start_controls_section(
			'progress_style_section',
			[
				'label' => esc_html__( 'Progressbar', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background:: get_type(),
			[
				'name'     => 'progress_background',
				'label'    => esc_html__( 'Background', 'element-ready-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .element__ready__prgressbar',
			]
		);

		$this->add_group_control(
			Group_Control_Border:: get_type(),
			[
				'name'     => 'progress_border',
				'label'    => esc_html__( 'Border', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} .element__ready__prgressbar',
			]
		);

		$this->add_control(
			'progress_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .element__ready__prgressbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow:: get_type(),
			[
				'name'     => 'progress_shadow',
				'selector' => '{{WRAPPER}} .element__ready__prgressbar',
			]
		);

		$this->add_responsive_control(
			'progress_height',
			[
				'label'      => esc_html__( 'Height', 'element-ready-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .element__ready__prgressbar' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'progress_margin',
			[
				'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .element__ready__prgressbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'progress_padding',
			[
				'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .element__ready__prgressbar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			PROGRESSBAR END
		-----------------------------*/

		/*----------------------------
			PROGRESSBAR COUNT BAR
		-----------------------------*/
		$this->start_controls_section(
			'progress_count_style_section',
			[
				'label' => esc_html__( 'Progress Count Bar', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Background:: get_type(),
			[
				'name'     => 'progress_count_background',
				'label'    => esc_html__( 'Background', 'element-ready-lite' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .count__bar',
			]
		);

		$this->add_group_control(
			Group_Control_Border:: get_type(),
			[
				'name'     => 'progress_count_border',
				'label'    => esc_html__( 'Border', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} .count__bar',
			]
		);

		$this->add_control(
			'progress_count_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .count__bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow:: get_type(),
			[
				'name'     => 'progress_count_shadow',
				'selector' => '{{WRAPPER}} .count__bar',
			]
		);

		$this->add_responsive_control(
			'progress_count_height',
			[
				'label'      => esc_html__( 'Height', 'element-ready-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .count__bar' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'progress_count_margin',
			[
				'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .count__bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'progress_count_padding',
			[
				'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .count__bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		/*----------------------------
			PROGRESSBAR COUNT BAR END
		-----------------------------*/
	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();

		$random_id = rand(2545,6546);
		$options = array(
			'random_id'    => $random_id,
			'fill_percent' => '#000000',
			'fill_color'   => '#333333',
		);


		// Title
		if ( !empty( $settings['title'] ) ) {
			$title = '<div class="progress__title">'.esc_html( $settings['title'] ).'</div>';
		}else{
			$title = '';
		}

		// Description
		if ( !empty( $settings['description'] ) ) {
			$description = '<div class="progress__description">'.wpautop( $settings['description'] ).'</div>';
		}else{
			$description = '';
		}


		$this->add_render_attribute( 'roadmap_style_attr', 'id', 'element__ready__roadmap__timeline__'.esc_attr($random_id) );
		$this->add_render_attribute( 'roadmap_style_attr', 'class', 'element__ready__prgoressbar__activation' );

		$this->add_render_attribute( 'roadmap_style_attr', 'class', 'single__roadmap__timeline' );
		$this->add_render_attribute( 'roadmap_style_attr', 'data-settings', wp_json_encode( $options ) );

		echo'
		<div class="progress__content">
			'. $title. $description .'
		</div>
		<div class="progressbar__wrap">
			<div class="element__ready__prgressbar" data-percent="'.esc_attr($settings['progressbar_percent']['size']).'%">
	            <div class="count__bar"></div>
	            <div class="count"></div>
	       </div>
       </div>';
	}
}