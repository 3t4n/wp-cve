<?php
namespace Enteraddons\Injecting_Controls;

/**
 * Enteraddons
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */
use Elementor\Element_Base;
use Elementor\Controls_Manager;

class Injecting_Controls {

	function __construct() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'inject_widget_advance_controls' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'inject_section_control' ],10, 2 );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ __CLASS__, 'inject_section_control' ],10, 2 );
		add_action( 'elementor/frontend/after_register_scripts', [ __CLASS__, 'script_enqueue' ] );
	}

	public static function script_enqueue() {
		$elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
		if( empty( $elementor_page ) ) {
			return;
		}
		wp_enqueue_script( 'anime' );
	}

	// inject widget advance controls
	public static function inject_widget_advance_controls( $element ) {

		/**********************************
		 *  Floating Effect 
		 * ********************************/
		$element->start_controls_section(
			'ea_floating_effects',
			[
				'label' => esc_html__( 'Floating Effects', 'enteraddons' ).\Enteraddons\Classes\Helper::ea_brand_icon_html(),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);
		$element->add_control(
			'ea_enable_floating_effects',
			[
				'label' => __( 'Enable', 'enteraddons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
			]
		);

		// Translate popover
        $element->add_control(
			'floating-translate-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Translate', 'enteraddons' ),
				'frontend_available' => true,
				'condition' => [ 'ea_enable_floating_effects' => 'yes' ]
			]
		);

		$element->start_popover();

		$element->add_control(
			'ea_fe_translate_x',
			[
				'label' => esc_html__( 'Translate X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 4,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-translate-popover' => 'yes' ]
				
			]
		);
	
		$element->add_responsive_control(
			'ea_fe_translate_y',
			[
				'label' => esc_html__( 'Translate Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 4,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-translate-popover' => 'yes' ]
				
			]
		);
		$element->add_control(
			'ea_fe_translate_duration',
			[
				'label' => esc_html__( 'Duration', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'default' => [
					'size' => 1200,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					]
				],
				'condition' => [
					'floating-translate-popover' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		$element->add_control(
			'ea_fe_translate_delay',
			[
				'label' => esc_html__( 'Delay', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 1,
					]
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'condition' => [
					'floating-translate-popover' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);
		$element->end_popover();
		// End translate popover
		
		// Scale popover
        $element->add_control(
			'floating-scale-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Scale', 'enteraddons' ),
				'frontend_available' => true,
				'condition' => [ 'ea_enable_floating_effects' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_fe_scale_x',
			[
				'label' => esc_html__( 'Scale X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'default' => [
					'sizes' => [
						'from' => 1,
						'to' => 1.3,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-scale-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_fe_scale_y',
			[
				'label' => esc_html__( 'Scale Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 1,
						'to' => 1.3,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-scale-popover' => 'yes' ]
			]
		);
		$element->add_control(
			'ea_fe_scale_duration',
			[
				'label' => esc_html__( 'Duration', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'default' => [
					'size' => 1200,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					]
				],
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'floating-scale-popover' => 'yes',
				]
			]
		);
		$element->add_control(
			'ea_fe_scale_delay',
			[
				'label' => esc_html__( 'Delay', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 1,
					]
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'floating-scale-popover' => 'yes',
				]
			]
		);
		$element->end_popover();
		// End scale popover

		// Rotate popover
        $element->add_control(
			'floating-rotate-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Rotate', 'enteraddons' ),
				'frontend_available' => true,
				'condition' => [ 'ea_enable_floating_effects' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_fe_rotate_x',
			[
				'label' => esc_html__( 'Rotate X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 10,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-rotate-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_fe_rotate_y',
			[
				'label' => esc_html__( 'Rotate Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 10,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-rotate-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_fe_rotate_z',
			[
				'label' => esc_html__( 'Rotate z', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 10,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-rotate-popover' => 'yes' ]
			]
		);
		$element->add_control(
			'ea_fe_rotate_duration',
			[
				'label' => esc_html__( 'Duration', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'default' => [
					'size' => 1200,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					]
				],
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'floating-rotate-popover' => 'yes',
				]
			]
		);
		$element->add_control(
			'ea_fe_rotate_delay',
			[
				'label' => esc_html__( 'Delay', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 1,
					]
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'floating-rotate-popover' => 'yes',
				]
			]
		);
		$element->end_popover();
		// End Rotate popover 
				
		// Skew popover
        $element->add_control(
			'floating-skew-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Skew', 'enteraddons' ),
				'frontend_available' => true,
				'condition' => [ 'ea_enable_floating_effects' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_fe_skew_x',
			[
				'label' => esc_html__( 'Skew X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 10,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -90,
						'max' => 90,
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-skew-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_fe_skew_y',
			[
				'label' => esc_html__( 'Skew Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'sizes' => [
						'from' => 0,
						'to' => 10,
					],
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => -90,
						'max' => 90,
					]
				],
				'labels' => [
					esc_html__( 'From', 'enteraddons' ),
					esc_html__( 'To', 'enteraddons' ),
				],
				'scales' => 1,
				'handles' => 'range',
				'frontend_available' => true,
				'render_type' => 'none',
				'condition' => [ 'floating-skew-popover' => 'yes' ]
			]
		);
		$element->add_control(
			'ea_fe_skew_duration',
			[
				'label' => esc_html__( 'Duration', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'default' => [
					'size' => 1200,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					]
				],
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'floating-skew-popover' => 'yes',
				]
			]
		);
		$element->add_control(
			'ea_fe_skew_delay',
			[
				'label' => esc_html__( 'Delay', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5000,
						'step' => 1,
					]
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'render_type' => 'none',
				'frontend_available' => true,
				'condition' => [
					'floating-skew-popover' => 'yes',
				]
			]
		);
		$element->end_popover();
		// End skew popover 

		$element->end_controls_section();
		/**********************************
		 *  Enable CSS Transform 
		 * ********************************/
		$element->start_controls_section(
			'ea_css_transform',
			[
				'label' => esc_html__( 'CSS Transform ', 'enteraddons' ).\Enteraddons\Classes\Helper::ea_brand_icon_html(),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'enable_css_transform',
			[
				'label'     => esc_html__( 'Enable', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
                'prefix_class' => 'ea-css-transform-',
			]
		);

		$element->start_controls_tabs( 'tabs_css_transform' );

        //  Controls tab For Normal
        $element->start_controls_tab(
            'transform_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
                'condition' => [ 'enable_css_transform' => 'yes' ]
            ]
        );

        // Translate popover
        $element->add_control(
			'translate-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Translate', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'ea_transform_translate_x',
			[
				'label' => esc_html__( 'Translate X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-translate-x: {{SIZE}}px;'
				],
				'condition' => [ 'translate-popover' => 'yes' ]
			]
		);

		$element->add_responsive_control(
			'ea_transform_translate_y',
			[
				'label' => esc_html__( 'Translate Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-translate-y: {{SIZE}}px;'
				],
				'condition' => [ 'translate-popover' => 'yes' ]
			]
		);
		$element->end_popover();
		// End translate popover
		
		// Scale popover
        $element->add_control(
			'scale-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Scale', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_scale_x',
			[
				'label' => esc_html__( 'Scale X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => .1
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-scale-x: {{SIZE}};'
				],
				'condition' => [ 'scale-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_scale_y',
			[
				'label' => esc_html__( 'Scale Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => .1
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-scale-y: {{SIZE}};'
				],
				'condition' => [ 'scale-popover' => 'yes' ]
			]
		);
		
		$element->end_popover();
		// End scale popover

		// Rotate popover
        $element->add_control(
			'rotate-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Rotate', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_rotate_x',
			[
				'label' => esc_html__( 'Rotate X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-rotate-x: {{SIZE}}deg;'
				],
				'condition' => [ 'rotate-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_rotate_y',
			[
				'label' => esc_html__( 'Rotate Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-rotate-y: {{SIZE}}deg;'
				],
				'condition' => [ 'rotate-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_rotate_z',
			[
				'label' => esc_html__( 'Rotate z', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-rotate-z: {{SIZE}}deg;'
				],
				'condition' => [ 'rotate-popover' => 'yes' ]
			]
		);

		$element->end_popover();
		// End Rotate popover 
				
		// Skew popover
        $element->add_control(
			'skew-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Skew', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_skew_x',
			[
				'label' => esc_html__( 'Skew X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-skew-x: {{SIZE}}deg;'
				],
				'condition' => [ 'skew-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_skew_y',
			[
				'label' => esc_html__( 'Skew Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-skew-y: {{SIZE}}deg;'
				],
				'condition' => [ 'skew-popover' => 'yes' ]
			]
		);
		
		$element->end_popover();
		// End skew popover 


		$element->end_controls_tab(); // End Controls tab

		//  Controls tab For Hover
        $element->start_controls_tab(
            'transform_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
                'condition' => [ 'enable_css_transform' => 'yes' ]
            ]
        );

        // Translate popover
        $element->add_control(
			'translate-hover-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Translate', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);

		$element->start_popover();

		$element->add_responsive_control(
			'ea_transform_hover_translate_x',
			[
				'label' => esc_html__( 'Translate X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-translate-x-hover: {{SIZE}}px;'
				],
				'condition' => [ 'translate-hover-popover' => 'yes' ]
			]
		);

		$element->add_responsive_control(
			'ea_transform_hover_translate_y',
			[
				'label' => esc_html__( 'Translate Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-translate-y-hover: {{SIZE}}px;'
				],
				'condition' => [ 'translate-hover-popover' => 'yes' ]
			]
		);
		$element->end_popover();
		// End translate popover
		
		// Scale popover
        $element->add_control(
			'scale-hover-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Scale', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_hover_scale_x',
			[
				'label' => esc_html__( 'Scale X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => .1
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-scale-x-hover: {{SIZE}};'
				],
				'condition' => [ 'scale-hover-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_hover_scale_y',
			[
				'label' => esc_html__( 'Scale Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => .1
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-scale-y-hover: {{SIZE}};'
				],
				'condition' => [ 'scale-hover-popover' => 'yes' ]
			]
		);
		
		$element->end_popover();
		// End scale popover

		// Rotate popover
        $element->add_control(
			'rotate-hover-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Rotate', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_hover_rotate_x',
			[
				'label' => esc_html__( 'Rotate X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-rotate-x-hover: {{SIZE}}deg;'
				],
				'condition' => [ 'rotate-hover-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_hover_rotate_y',
			[
				'label' => esc_html__( 'Rotate Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-rotate-y-hover: {{SIZE}}deg;'
				],
				'condition' => [ 'rotate-hover-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_hover_rotate_z',
			[
				'label' => esc_html__( 'Rotate z', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-rotate-z-hover: {{SIZE}}deg;'
				],
				'condition' => [ 'rotate-hover-popover' => 'yes' ]
			]
		);

		$element->end_popover();
		// End Rotate popover 
				
		// Skew popover
        $element->add_control(
			'skew-hover-popover',
			[
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label' => esc_html__( 'Skew', 'enteraddons' ),
				'condition' => [ 'enable_css_transform' => 'yes' ]
			]
		);
		
		$element->start_popover();

		$element->add_responsive_control(
			'ea_hover_skew_x',
			[
				'label' => esc_html__( 'Skew X', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-skew-x-hover: {{SIZE}}deg;'
				],
				'condition' => [ 'skew-hover-popover' => 'yes' ]
			]
		);
		$element->add_responsive_control(
			'ea_hover_skew_y',
			[
				'label' => esc_html__( 'Skew Y', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-skew-y-hover: {{SIZE}}deg;'
				],
				'condition' => [ 'skew-hover-popover' => 'yes' ]
			]
		);
		
		$element->end_popover();
		// End skew popover 

		$element->end_controls_tab(); // End Controls tab

		$element->end_controls_tabs(); //  end controls tabs section

		$element->add_control(
			'ea_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'enteraddons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1,
					]
				],
				'condition' => [
					'enable_css_transform' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ea-css-transition-duration: {{SIZE}}s;'
				],
			]
		);

		$element->end_controls_section();

	}

	// inject section control
	public static function inject_section_control( $element, $args ) {

		/**********************************
		 *  Equal Height
		 * ********************************/
		$element->start_controls_section(
			'ea_equal_height',
			[
				'label' => esc_html__( 'Equal Height', 'enteraddons' ).\Enteraddons\Classes\Helper::ea_brand_icon_html(),
				'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ea_enable_equal_height',
			[
				'label'     => esc_html__( 'Enable', 'enteraddons' ),
                'type'      => \Elementor\Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'NO', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => '',
                'prefix_class' => 'ea-equal-height-',
                'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'apply_to',
			[
				'label' => esc_html__( 'Apply to', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'frontend_available' => true,
                'options' => [
                    'column' => esc_html__( 'Column', 'enteraddons' ),
                    'widget' => esc_html__( 'Widget', 'enteraddons' ),
                    'wc_1'  => esc_html__( 'Widget > Child 1', 'enteraddons' ),
                    'wc_2'  => esc_html__( 'Widget > Child 1 > Child 2', 'enteraddons' ),
                    'wc_3'  => esc_html__( 'Widget > Child 1 > Child 2 > Child 3', 'enteraddons' ),
                    'custom'  => esc_html__( 'Custom', 'enteraddons' ),
                ],
                'description' => esc_html__( 'Custom', 'enteraddons' )
			]
		);
		$element->add_control(
			'apply_on_custom_selector',
			[
				'label' => esc_html__( 'Custom Class', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => [ 'apply_to' => 'custom' ],
                'frontend_available' => true,
			]
		);

		$element->end_controls_section();

		/**********************************
		 *  Sticky
		 * ********************************/
		$element->start_controls_section(
			'sticky_menu_section',
			[
				'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
				'label' => esc_html__( 'Sticky', 'enteraddons' ).\Enteraddons\Classes\Helper::ea_brand_icon_html(),
			]
		);

		$element->add_control(
			'active_sticky_menu',
			[
				'label' => esc_html__( 'Active Sticky', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'enteraddons' ),
				'label_off' => esc_html__( 'No', 'enteraddons' ),
				'default' => '',
				'frontend_available' => true,
				'return_value'       => 'section',
				'prefix_class'       => 'ea-sticky-menu-'
			]
		);

		$element->add_responsive_control(
            'sticky_menu_offset',
            [
                'label' => esc_html__( 'Sticky Offset', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'frontend_available' => true,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],
                'condition' => [ 'active_sticky_menu' => 'section' ]
            ]
        );
        $element->add_responsive_control(
            'sticky_menu_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}.active-sticky' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [ 'active_sticky_menu' => 'section' ]
            ]
        );
        $element->add_responsive_control(
            'sticky_menu_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}.active-sticky' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [ 'active_sticky_menu' => 'section' ]
            ]
        );
        $element->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'sticky_section_background',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}}.active-sticky',
                'condition' => [ 'active_sticky_menu' => 'section' ]
            ]
        );
		$element->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'sticky_menu_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}}.active-sticky',
                'condition' => [ 'active_sticky_menu' => 'section' ]
            ]
        );
		$element->end_controls_section();



	}
}

new Injecting_Controls();