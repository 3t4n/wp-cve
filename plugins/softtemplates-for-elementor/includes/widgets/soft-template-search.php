<?php
/**
 * Class: Soft_Template_Search
 * Name: Search
 * Slug: soft-template-search
 */
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Search extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-search';
	}

	public function get_title() {
		return esc_html__( 'Search', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-search';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}
    
	/**
	 * Enqueue custom scripts.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'soft-template-search', 'magnific-popup' );
	}

	/**
	 * Enqueue custom styles.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'magnific-popup' );
	}

    protected function register_controls() {
        $this->elements_options();
        $this->serach_icons_style();
        $this->search_container_style();
        $this->search_button_style();
    }

    public function elements_options() {
		$this->start_controls_section(
			'elements_search',
			array(
				'label' => esc_html__( 'Search Icons', 'soft-template-core' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
			)
		);

        $this->add_control(
			'sg_search_style',
			array(
				'label' => esc_html__( 'Layout', 'soft-template-core' ),
				'type'  => Controls_Manager::SELECT,
				'default' => 'popup',
				'options' => array(
                    'form'  => esc_html__( 'Form', 'soft-template-core' ),
                    'popup' => esc_html__( 'Pop Up', 'soft-template-core' ),
				),
			)
		);

        $this->add_control(
			'sg_search_placeholder',
			array(
				'label' => esc_html__( 'Placeholder', 'soft-template-core' ),
				'type'  => Controls_Manager::TEXT,
				'default'     => 'Search...',
			)
		);

        $this->add_control(
			'sg_search_button_style',
			array(
				'label' => esc_html__( 'Button Style', 'soft-template-core' ),
				'type'  => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
                    'icon' => esc_html__( 'Icon', 'soft-template-core' ),
                    'text' => esc_html__( 'Text', 'soft-template-core' ),
				),
			)
		);

        $this->add_control(
			'sg_search_text',
			array(
				'label' => esc_html__( 'Text', 'soft-template-core' ),
				'type'  => Controls_Manager::TEXT,
				'default'     => 'Search',
                'condition'  => array(
                    'sg_search_button_style' => 'text',
				),
			)
		);

        $this->add_control(
			'sg_search_icon',
			array(
				'label'       => esc_html__( 'Icon', 'soft-template-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::ICONS,
				'default' => array(
                    'value'   => 'fas fa-search',
                    'library' => 'fa-solid',
				),
				'conditions'   => array(
                    'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'sg_search_style',
							'operator' => '==',
							'value'    => 'popup',
						),
						array(
							'name'     => 'sg_search_button_style',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			)
		);

        $this->__add_responsive_control(
			'sg_search_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 20,
                    'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
                        'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal svg' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
                    'sg_search_style' => 'popup',
				),
			)
		);

        $this->__add_responsive_control(
			'sg_search_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'soft-template-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'soft-template-core' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-soft-template-search' => 'text-align: {{VALUE}}',
				),
			)
		);

        $this->end_controls_section();
    }  
    
    public function serach_icons_style() {
        $this->start_controls_section(
			'elements_search_style',
			array(
				'label' => esc_html__( 'Search Icons', 'soft-template-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
			)
		);
			$this->__start_controls_tabs( 'elements_search_style_tab' );
				$this->__start_controls_tab(
					'tab_icons_normal',
					array(
						'label'     => __( 'Normal', 'soft-template-core' ),
					)
				);

					$this->__add_responsive_control(
						'st_icon_normal_color',
						array(
							'label'     => __( 'Normal Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal'     => 'color: {{VALUE}};',
								'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal svg' => 'fill: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'           => 'st_icon_normal_background',
							'label'          => __( 'Background Color', 'soft-template-core' ),
							'types'          => array( 'classic', 'gradient' ),
							'selector'       => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal',
						)
					);

				$this->__end_controls_tab();


				$this->__start_controls_tab(
					'tab_icons_hover',
					array(
						'label'     => __( 'Hover', 'soft-template-core' ),
					)
				);

					$this->__add_responsive_control(
						'st_icon_hover_color',
						array(
							'label'     => __( 'Normal Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal'     => 'color: {{VALUE}};',
								'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal svg' => 'fill: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'           => 'st_icon_hover_background',
							'label'          => __( 'Background Color', 'soft-template-core' ),
							'types'          => array( 'classic', 'gradient' ),
							'selector'       => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal:hover',
						)
					);

				$this->__end_controls_tab();
			$this->__end_controls_tabs();


			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'st_icon_border',
					'label' => esc_html__( 'Border', 'soft-template-core' ),
					'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal',
					'separator' => 'before',
				]
			); 

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'st_icon_boxshadow',
					'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal'
				]
			);

			$this->add_control(
				'st_icon_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'soft-template-core' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'st_icon_margin',
				[
					'label' => esc_html__( 'Margin', 'soft-template-core' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'   => array(
						'top'      => '5',
						'right'    => '5',
						'bottom'   => '5',
						'left'     => '5',
						'unit'     => 'px',
						'isLinked' => true,
					),
				]
			);	

			$this->add_responsive_control(
				'st_icon_padding',
				[
					'label' => esc_html__( 'Padding', 'soft-template-core' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'   => array(
						'top'      => '0',
						'right'    => '0',
						'bottom'   => '0',
						'left'     => '0',
						'unit'     => 'px',
						'isLinked' => true,
					),
				]
			);	

			$this->__add_responsive_control(
				'st_icon_alignment',
				array(
					'label'   => esc_html__( 'Alignment', 'soft-template-core' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'soft-template-core' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'soft-template-core' ),
							'icon'  => 'fa fa-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'soft-template-core' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'default'    => 'center',
					'selectors' => array(
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal' => 'text-align: {{VALUE}}',
					),
					'prefix_class' => 'text-align-',
				)
			);

			
			$this->add_control(
				'st_icon_height_width',
				[
					'label'                 => __( 'Use Height Width', 'soft-template-core' ),
					'type'                  => Controls_Manager::SWITCHER,
					'default'               => 'yes',
					'label_on'              => __( 'Yes', 'soft-template-core' ),
					'label_off'             => __( 'No', 'soft-template-core' ),
					'return_value'          => 'yes',
				]
			);

			$this->__add_responsive_control(
				'st_icon_height_width_width',
				[
					'label' => __( 'Width', 'soft-template-core' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						],
					],
					'size_units'        	=> ['px', 'em', '%'],
					'default'     	=> [ 'size' => '40', 'unit' => 'px' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal' => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition'  => array(
						'st_icon_height_width' => 'yes',
					),
				]
			);

			$this->__add_responsive_control(
				'st_icon_height_width_height',
				[
					'label' => __( 'Width', 'soft-template-core' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						],
					],
					'size_units'        	=> ['px', 'em', '%'],
					'default'     	=> [ 'size' => '40', 'unit' => 'px' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal' => 'height: {{SIZE}}{{UNIT}};',
					],
					'condition'  => array(
						'st_icon_height_width' => 'yes',
					),
				]
			);

			$this->__add_responsive_control(
				'st_icon_height_width_line_height',
				[
					'label' => __( 'Line Height', 'soft-template-core' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						],
					],
					'size_units'        	=> ['px', 'em', '%'],
					'default'     	=> [ 'size' => '40', 'unit' => 'px' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-modal' => 'line-height: {{SIZE}}{{UNIT}};',
					],
					'condition'  => array(
						'st_icon_height_width' => 'yes',
					),
				]
			);

        $this->end_controls_section();


    }

	public function search_container_style() {
        $this->start_controls_section(
			'elements_search_container_style',
			array(
				'label' => esc_html__( 'Search Container', 'soft-template-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'           => 'st_container_background',
					'label'          => __( 'Container Background', 'soft-template-core' ),
					'types'          => array( 'classic', 'gradient' ),
					'selector'       => '{{WRAPPER}} .elementor-soft-template-search .mfp-bg',
				)
			);

			$this->__start_controls_tabs( 'elements_search_container_tab' );
				$this->__start_controls_tab(
					'tab_container_normal',
					array(
						'label'     => __( 'Normal', 'soft-template-core' ),
					)
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'           => 'st_container_form_background',
							'label'          => __( 'Form Background', 'soft-template-core' ),
							'types'          => array( 'classic', 'gradient' ),
							'selector'       => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])',
						)
					);

					$this->__add_responsive_control(
						'st_container_color',
						array(
							'label'     => __( 'Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])'     => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'st_container_border',
							'label' => esc_html__( 'Border', 'soft-template-core' ),
							'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])',
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'st_container_boxshadow',
							'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])'
						]
					);

				$this->__end_controls_tab();


				$this->__start_controls_tab(
					'tab_container_focus',
					array(
						'label'     => __( 'Focus', 'soft-template-core' ),
					)
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'           => 'st_container_focus_form_background',
							'label'          => __( 'Form Background', 'soft-template-core' ),
							'types'          => array( 'classic', 'gradient' ),
							'selector'       => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit]):focus',
						)
					);

					$this->__add_responsive_control(
						'st_container_focus_color',
						array(
							'label'     => __( 'Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit]):focus'     => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'st_container_focus_border',
							'label' => esc_html__( 'Border', 'soft-template-core' ),
							'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit]):focus',
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'st_container_focus_boxshadow',
							'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit]):focus'
						]
					);

				$this->__end_controls_tab();
			$this->__end_controls_tabs();


			$this->__add_responsive_control(
				'st_container_placeholder_color',
				array(
					'label'     => __( 'Placeholder Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])::placeholder'     => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'st_container_placeholder_typography',
					'label' => __( 'Placeholder Typography', 'soft-template-core' ),
					'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])::placeholder',
				]
			);

			$this->__add_responsive_control(
				'st_container_close_color',
				array(
					'label'     => __( 'Close Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .elementor-soft-template-search .mfp-close' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					),
					'condition'  => array(
						'sg_search_style' => 'popup',
					),
				)
			);

			$this->add_responsive_control(
				'st_container_padding',
				[
					'label' => esc_html__( 'Padding', 'soft-template-core' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'st_container_margin',
				[
					'label' => esc_html__( 'Margin', 'soft-template-core' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->__add_responsive_control(
				'st_container_max_width',
				[
					'label' => __( 'Max Width', 'soft-template-core' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						],
					],
					'size_units'    => ['px', '%'],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-modal-search-panel .stfe-search-panel' => 'max-width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .elementor-soft-template-search > .stfe-search-panel' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->__add_responsive_control(
				'st_container_height',
				[
					'label' => __( 'Height', 'soft-template-core' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						],
					],
					'size_units'    => ['px', '%'],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel input:not([type=submit])' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}	
	
	public function search_button_style() {
		$this->start_controls_section(
			'elements_search_button_style',
			array(
				'label' => esc_html__( 'Search Button', 'soft-template-core' ),
                'tab'       => Controls_Manager::TAB_STYLE,
			)
		);

			$this->__add_responsive_control(
				'st_button_icon_size',
				[
					'label' => __( 'Height', 'soft-template-core' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'size_units'    => ['px', 'em'],
					'selectors' => [
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-button i'     => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .elementor-soft-template-search .stfe-search-button svg'   => 'width: {{SIZE}}{{UNIT}};',
					],
					'condition'  => array(
						'sg_search_button_style' => 'icon',
					),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'st_button_typography',
					'label' => __( 'Typography', 'soft-template-core' ),
					'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-button',
					'condition'  => array(
						'sg_search_button_style' => 'text',
					),
				]
			);


			$this->__start_controls_tabs( 'elements_search_button_tab' );
			$this->__start_controls_tab(
				'tab_button_normal',
				array(
					'label'     => __( 'Normal', 'soft-template-core' ),
				)
			);

				$this->__add_responsive_control(
					'st_button_color',
					array(
						'label'     => __( 'Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button'     => 'color: {{VALUE}};',
							'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button svg' => 'fill: {{VALUE}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					array(
						'name'           => 'st_button_background',
						'label'          => __( 'Form Background', 'soft-template-core' ),
						'types'          => array( 'classic', 'gradient' ),
						'selector'       => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button',
					)
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'st_button_border',
						'label' => esc_html__( 'Border', 'soft-template-core' ),
						'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'st_button_boxshadow',
						'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button'
					]
				);

			$this->__end_controls_tab();


			$this->__start_controls_tab(
				'tab_button_focus',
				array(
					'label'     => __( 'Hover', 'soft-template-core' ),
				)
			);

				$this->__add_responsive_control(
					'st_button_hover_color',
					array(
						'label'     => __( 'Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button:hover'     => 'color: {{VALUE}};',
							'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button:hover svg' => 'fill: {{VALUE}};',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					array(
						'name'           => 'st_button_hover_background',
						'label'          => __( 'Background', 'soft-template-core' ),
						'types'          => array( 'classic', 'gradient' ),
						'selector'       => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button:hover',
					)
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'st_button_hover_border',
						'label' => esc_html__( 'Border', 'soft-template-core' ),
						'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button:hover',
					]
				);

				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'st_button_hover_boxshadow',
						'selector' => '{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button:hover'
					]
				);

			$this->__end_controls_tab();
		$this->__end_controls_tabs();

		$this->add_responsive_control(
			'st_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'st_button_padding',
			[
				'label' => esc_html__( 'Padding', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'st_button_margin',
			[
				'label' => esc_html__( 'Margin', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->__add_responsive_control(
			'st_button_width',
			[
				'label' => __( 'Width', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'size_units'    => ['px', 'em'],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button'  => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->__add_responsive_control(
			'st_button_height',
			[
				'label' => __( 'Height', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'size_units'    => ['px', 'em'],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-search .stfe-search-panel .stfe-search-button'  => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

    protected function render() {
        $this->__context = 'render';

		$this->__open_wrap();
		
		$settings  = $this->get_settings();
		$icon   = $this->render_icon_element( $settings['sg_search_icon'] );
		$type   = $settings['sg_search_style'];

		if ( 'popup' === $type ) {
			echo sprintf("%s",$this->render_modal( $icon ));
		} else {
			echo sprintf("%s",$this->render_form( $icon ));
		}

		$this->__close_wrap();
    }

	/**
	 * Render Modal
	 *
	 * @param string $icon Rendered icon.
	 */
	private function render_modal( $icon ) {
		$unique_id = uniqid();

		return '<a href="#stfe-search-modal-' . $unique_id . '" class="stfe-search-modal">' . $icon . '</a>
		<div class="mfp-hide stfe-modal-search-panel" id="stfe-search-modal-' . $unique_id . '">
			' . $this->render_form( $icon ) . '
		</div>';
	}

	/**
	 * Render Form
	 *
	 * @param string $icon Rendered icon.
	 */
	private function render_form( $icon ) {
		$settings  = $this->get_settings();

		$language_prefix = function_exists( 'pll_current_language' ) ? pll_current_language() : '';
		$placeholder     = esc_attr( $settings['sg_search_placeholder'] );
		$button_icon     = 'icon' === $settings['sg_search_button_style'] ? $icon : esc_attr( $settings['sg_search_text'] );

		return '<div class="stfe-search-panel">
			<form role="search" method="get" class="stfe-search-group" action="' . esc_url( home_url( '/' . $language_prefix ) ) . '">
				<input type="search" class="stfe-search-field" placeholder="' . $placeholder . '" value="' . esc_attr( get_search_query() ) . '" name="s" />
				<button type="submit" class="stfe-search-button">' . $button_icon . '</button>
			</form>
		</div>';
	}

	protected function render_icon_element( $icon, $attr = array() ) {
		$attr = array_merge( $attr, array( 'aria-hidden' => 'true' ) );

		ob_start();
		Icons_Manager::render_icon( $icon, $attr );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
