<?php

namespace Element_Ready\Document;

use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 
* Site Global Settings
* @since 1.0 
*/
class Global_Settings extends Tab_Base {

	public function get_id() {
		return 'elements-ready-basic';
	}

	public function get_title() {
		return esc_html__( 'ElementsReady', 'element-ready-lite' );
	}

	public function get_group() {
		return 'settings';
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_help_url() {
		return 'quomodosoft.com';
	}

	protected function register_tab_controls() {
       
        do_action('elements_ready_global_settings_start', $this, $this->get_id());
        do_action('elements_ready_before_newslatter_popup', $this, $this->get_id());
        do_action('elements_ready_newslatter_popup', $this, $this->get_id());
        do_action('elements_ready_global_settings_end', $this, $this->get_id());

		$this->start_controls_section(
			'er_ready_body_line_animation_section_gl_settings',
			[

                'label' =>  esc_html__('ER Full Page Line Animation','element-ready-lite'),
				'tab' => $this->get_id(),
			]
		); 	

		$this->start_controls_tabs(
			'er_ready_body_line_animation_sectionstyle_tabs'
		);
		
		$this->start_controls_tab(
			'er_ready_body_line_animation_sectiontyle_normal_tab',
			[
				'label' => esc_html__( 'Settings', 'element-ready-lite' ),
			]
		);

			$this->add_control(
				'er_body_line_animation_enable',
				[
					'label'        => esc_html__( 'Enable?', 'element-ready-lite' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
					'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
					'return_value' => 'yes',
					'default'      => '',
				]
			);

			$this->add_control(
				'er_body_line_animation_direction',
				[
					'label' => esc_html__( 'Direction', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'down',
					'options' => [
						'down'  => esc_html__( 'Down', 'element-ready-lite' ),
						'up' => esc_html__( 'Up', 'element-ready-lite' ),
					],
				]
			);

			$this->add_control(
				'er_body_line_animation_conditional_display',
				[
					'label' => esc_html__( 'Conditional Display', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'global',
					'options' => [
						'global'  => esc_html__( 'Full Sites', 'element-ready-lite' ),
						'page_specific' => esc_html__( 'Page Specific', 'element-ready-lite' ),
					],
				]
			);

			$this->add_control(
				'er_body_line_animation_page_option',
				[
					'label' => esc_html__( 'Pages IDS', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'condition' => [
						'er_body_line_animation_conditional_display' => [
							'page_specific'		
						]
					],
					'default' => '',
					'description' => 'Type your page id with commas',
					'placeholder' => esc_html__( 'Type your page id with commas', 'element-ready-lite' ),
				]
			);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'er_ready_body_line_animation_sectiontyle_style_tab',
			[
				'label' => esc_html__( 'Style', 'element-ready-lite' ),
			]
		);

		$this->add_control(
			'r_ready_body_line_animation_z_index',
			[
				'label' => esc_html__( 'Z-index', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 99999999999999,
						'step' => 5,
					],
					
				],
				
				'selectors' => [
					'body .er-full-page-lines' => 'z-index: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'er_ready_body_line_animation_line_width',
			[
				'label' => esc_html__( 'Line Width', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					
				],
				
				'selectors' => [
					'body .er-full-page-line' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'er_ready_body_line_animationmore_options',
			[
				'label' => esc_html__( 'Line Background', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'er_ready_body_line_animation_line_background',
				'label' => esc_html__( 'line Background', 'element-ready-lite' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => 'body .er-full-page-line',
			]
		);

		$this->add_control(
			'er_ready_body_line_animation_aftermore_options',
			[
				'label' => esc_html__( 'Line Button', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'er_ready_body_line_animation_line_height',
			[
				'label' => esc_html__( 'Button Height', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'vh' ],
				'range' => [
					'vh' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
					
				],
				
				'selectors' => [
					'body .er-full-page-line::after' => 'height: {{SIZE}}vh;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'er_ready_body_line_animation_Afyer_line_background',
				'label' => esc_html__( 'line Background', 'element-ready-lite' ),
				'types' => [ 'gradient' ],
				'selector' => 'body .er-full-page-line::after',
			]
		);

		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
	
		$this->end_controls_section();	

		$this->start_controls_section(
			'er_ready_footer_section_gl_settings',
			[

                'label' =>  esc_html__('ER Footer Builder','element-ready-lite'),
				'tab' => $this->get_id(),
			]
		); 

			$this->add_control(
				'er_blog_footer_missing_div',
				[
					'label'        => esc_html__( 'Fix Blog Pages Missing div?', 'element-ready-lite' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
					'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
					'return_value' => 'yes',
					'default'      => '',
				]
			);

		$this->end_controls_section();	
	
	}

	/**
	 * Should check for the current action to avoid infinite loop
	 * 
	*/
    public function on_save( $data ) {
       
		if (
			! isset( $data['settings'])
		) {
			return;
		}

        $grid_style = 'elements_products_archive_shop_grid_style';

        if( isset( $data[ 'settings' ][ $grid_style ] ) ){
          
           update_option($grid_style,$data['settings'][$grid_style]);
        }
     
	}

    public function get_additional_tab_content(){

        // use this for notice 
        // as a helper link
        // docs 
        return sprintf( '
				<div class="element-ready-account-module elementor-nerd-box">
                <a class="elementor-button elementor-button-success elementor-nerd-box-link" target="_blank" href="#"> Settings Module </a>
				</div>
				'
			);
    }
 
}
