<?php 
namespace HTMega_Scroll_To_Top;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMegaScrollToTop_Elementor {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
    public function __construct() {
		add_action('elementor/documents/register_controls', [ $this, 'register_controls' ], 10);
        add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }
	/**
	 * Enqueue scripts.
	 *
	 * Enqueue required JS dependencies for the extension.
	 *
	 * @since 2.2.7
	 * @access public
	 */
	public static function enqueue_scripts() {
        $htmega_stt_module_settings = htmega_get_option( 'htmega_stt', 'htmega_stt_module_settings' );
        $htmega_stt_module_settings = json_decode( $htmega_stt_module_settings, true );

        $stt_global = isset( $htmega_stt_module_settings['stt_global'] ) ? $htmega_stt_module_settings['stt_global'] : 'off';

        $htmega_stt_enable  = htmega_get_elementor_option( 'htmega_stt_enable', get_the_ID() );
        $htmega_stt_disable = htmega_get_elementor_option( 'htmega_stt_disable', get_the_ID() );

        $stt_icon_type      = htmega_get_elementor_option( 'stt_icon_type', get_the_ID() );
        $stt_icon           = htmega_get_elementor_option( 'stt_icon', get_the_ID() );
        $stt_image          = htmega_get_elementor_option( 'stt_image', get_the_ID() );

        $buton_icon = '';
        if( 'none' != $stt_icon_type ) {
            $buton_icon = ( 'icon' == $stt_icon_type ) ? $stt_icon : $stt_image;
        }

        $sttmodule_localize_data = [
            'stt_icon_type'    => $stt_icon_type,
            'buton_icon'    => $buton_icon,
            'stt_button_text'     => htmega_get_elementor_option( 'stt_button_text', get_the_ID() ),
        ];

        if( ( isset( $htmega_stt_enable ) &&  'yes' == $htmega_stt_enable ) ) {
            wp_enqueue_script( 'htmega-stt-script' );
            wp_enqueue_style( 'htmega-stt-css' );
            wp_localize_script( 'htmega-stt-script', 'sttData', $sttmodule_localize_data );
        }
        if( ( isset( $htmega_stt_disable ) &&  'yes' == $htmega_stt_disable )  && 'on' == $stt_global ) {
            wp_dequeue_script( 'htmega-stt-script' );
            wp_dequeue_script( 'htmega-stt-css' );
        }
	}

	/**
	 * Register Reading progress bar controls.
	 *
	 * @since 2.2.7
	 * @access public
	 * @param object $element for current element.
	 */
	public function register_controls( $element ) {

        $htmega_stt_module_settings = htmega_get_option( 'htmega_stt', 'htmega_stt_module_settings' );
        $htmega_stt_module_settings = json_decode( $htmega_stt_module_settings,true );

        $stt_global = isset( $htmega_stt_module_settings['stt_global'] ) ? $htmega_stt_module_settings['stt_global'] : 'off';
        $stt_enable_label =  ( 'on' == $stt_global && is_plugin_active( 'htmega-pro/htmega_pro.php' ) ) ? __('Enable to Custom Style', 'htmega-addons') : __('Enable Scroll To Top', 'htmega-addons');
        
		$tabs = Controls_Manager::TAB_SETTINGS;

		$element->start_controls_section(
			'section_htmega_stt_section',
			array(
				'label' => __( 'HTMega Scroll To Top', 'htmega-addons' ),
				'tab'   => $tabs,
			)
		);

        if( 'on' == $stt_global && is_plugin_active( 'htmega-pro/htmega_pro.php' ) ) {
            $element->add_control(
                'htmega_stt_disable',
                [
                    'label' => __('Disable Scroll To Top', 'htmega-addons'),
                    'description' => __('Disable Scroll To Top for this  pages', 'htmega-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'no',
                    'label_on' => __('Yes', 'htmega-addons'),
                    'label_off' => __('No', 'htmega-addons'),
                    'return_value' => 'yes',
                ]
            );

        } 
        $element->add_control(
            'htmega_stt_enable',
            [
                'label' =>  $stt_enable_label,
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'htmega-addons'),
                'label_off' => __('No', 'htmega-addons'),
                'return_value' => 'yes',
            ]
        );
        $element->add_control(
            'htmega_stt_notice',
            [
                'raw'             => __( 'The <b>Scroll To Top settings</b> are not functional in Editor mode. Please preview the page  & Scroll to see the desired result.', 'htmega-addons' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'   => [
                    'htmega_stt_enable' => 'yes'
                ],
            ]
        );
        $element->add_control(
            'htmega_stt_position',
            [
                'label' => esc_html__('Position', 'htmega-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom_left',
                'label_block' => false,
                'options' => [
                    'bottom_left' => esc_html__('Bottom Left', 'htmega-addons'),
                    'bottom_right' => esc_html__('Bottom Right', 'htmega-addons'),
                ],
                'separator' => 'before',
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
                'selectors_dictionary' => [
                    'bottom_left' => 'left:15px !important; right:auto !important',
                    'bottom_right' =>'right:15px !important;left:auto !important'
                ],
				'selectors' => [
                    '{{WRAPPER}} .htmega-stt-wrap' => '{{VALUE}}',
                ],
            ]
        );
        $element->add_control(
            'stt_offset',
            [
                'type' =>Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Offsets', 'htmega-addons' ),
                'label_off' => esc_html__( 'Default', 'htmega-addons' ),
                'label_on' => esc_html__( 'Custom', 'htmega-addons' ),
                'return_value' => 'yes',
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->start_popover();

            $element->add_control(
                'offset_x',
                [
                    'label' => __('Offset X', 'htmega-addons'),
                    'description' => __('Add the position X  Offest of the button', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-stt-wrap' => 'left: {{SIZE}}{{UNIT}}!important;right:auto !important',
                    ],
                    'condition' => [
                        'htmega_stt_position' => 'bottom_left',
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );
            $element->add_control(
                'offset_x2',
                [
                    'label' => __('Offset X', 'htmega-addons'),
                    'description' => __('Add the position X  Offest of the button', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 20,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-stt-wrap' => 'right: {{SIZE}}{{UNIT}}!important; left:auto !important',
                    ],
                    'condition' => [
                        'htmega_stt_position' => 'bottom_right',
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'offset_Y',
                [
                    'label' => __('Offset Y', 'htmega-addons'),
                    'description' => __('Add the position Y  Offest of the button', 'htmega-addons'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 200,
                            'step' => 1,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 30,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .htmega-stt-wrap' => 'bottom: {{SIZE}}{{UNIT}} !important',
                    ],
                    'condition' => [
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );
        $element->end_popover();
        $element->add_control(
            'htmega_stt_width',
            [
                'label' => __('Width', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-stt-wrap' => 'min-width: {{SIZE}}{{UNIT}} !important',
                ],
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->add_control(
            'htmega_stt_height',
            [
                'label' => __('Height', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' =>30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-stt-wrap' => 'min-height: {{SIZE}}{{UNIT}} !important',
                ],
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->add_control(
            'stt_z_index',
            [
                'label' => __('Z Index', 'htmega-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 999,
                'selectors' => [
                    '{{WRAPPER}} .htmega-stt-wrap' => 'z-index:{{VALUE}}',
                ],
                'condition'=>[
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->add_control(
            'stt_icon_type',
            [
                'label'   => __( 'Icon Type', 'htmega-addons' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'none' => [
                        'title' => __( 'None', 'htmega-addons' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'icon' => [
                        'title' => __( 'Icon', 'htmega-addons' ),
                        'icon'  => 'eicon-info-circle',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'htmega-addons' ),
                        'icon'  => 'eicon-image-bold',
                    ],
                ],
                'default' => 'icon',
                'condition'=>[
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->add_control(
            'stt_icon',
            [
                'label'         => __( 'Icon', 'htmega-addons' ),
                'type'          => Controls_Manager::ICONS,
                'condition'=>[
                    'stt_icon_type'=>'icon',
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'stt_image',
            [
                'label' => __('Image','htmega-addons'),
                'type'=>Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'stt_icon_type' => 'image',
                    'htmega_stt_enable' => 'yes',
                ]
            ]
        );
        $element->add_control(
            'stt_icon_size',
            [
                'label' => __('Icon size', 'htmega-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .htmega-stt-wrap img' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .htmega-stt-wrap i' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->add_control(
            'stt_button_text',
            [
                'label'         => __( 'Button Text', 'htmega-addons' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => '',
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->add_control(
            'stt_colors_borders',
            [
                'label' => __( 'Colors and Border', 'htmega-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
            ]
        );
        $element->start_controls_tabs(
            'stt_colors_borders_tabs', [
                'condition' => [
                    'htmega_stt_enable' => 'yes',
                ],
            ]
            
        );
            // Normal Style Tab
            $element->start_controls_tab(
                'input_normal',
                [
                    'label' => __( 'Normal', 'htmega-addons' ),
                ]
            );
                $element->add_control(
                    'stt_text_color',
                    [
                        'label'     => __( 'Color', 'htmega-addons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-stt-wrap,{{WRAPPER}} .htmega-stt-wrap i' => 'color: {{VALUE}} !important;',
                            '{{WRAPPER}} .htmega-stt-wrap svg path' => 'fill: {{VALUE}};',
                        ],
                        'condition' => [
                            'htmega_stt_enable' => 'yes',
                        ],
                    ]
                );
                $element->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'stt_text_typography',
                        'selector' => '{{WRAPPER}} .htmega-stt-wrap',
                        'condition' => [
                            'htmega_stt_enable' => 'yes',
                            'stt_button_text!' => '',
                        ],
                    ]
                );
                $element->add_control(
                    'stt_bacground_color',
                    [
                        'label'     => __( 'Background Color', 'htmega-addons' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-stt-wrap'  => 'background-color: {{VALUE}} !important;',
                        ],
                        'condition' => [
                            'htmega_stt_enable' => 'yes',
                        ],
                    ]
                );
                $element->add_group_control(
                    Group_Control_Border::get_type(),
                    [
                        'name' => 'stt_border',
                        'label' => __( 'Border', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-stt-wrap',
                        'condition' => [
                            'htmega_stt_enable' => 'yes',
                        ],
                    ]
                );
    
                $element->add_responsive_control(
                    'stt_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'selectors' => [
                            '{{WRAPPER}} .htmega-stt-wrap' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                        ],
                        'condition' => [
                            'htmega_stt_enable' => 'yes',
                        ],
                    ]
                );
                $element->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'stt_boxshadow',
                        'label' => __( 'Box Shadow', 'htmega-addons' ),
                        'selector' => '{{WRAPPER}} .htmega-stt-wrap',
                        'condition' => [
                            'htmega_stt_enable' => 'yes',
                        ],
                    ]
                );
    
            $element->end_controls_tab();

            // Hover Style Tab
            $element->start_controls_tab(
                'input_focus',
                [
                    'label' => __( 'Hover', 'htmega-addons' ),
                ]
            );
            $element->add_control(
                'stt_text_color_hover',
                [
                    'label'     => __( 'Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-stt-wrap:hover,{{WRAPPER}} .htmega-stt-wrap:hover i' => 'color: {{VALUE}} !important;',
                        '{{WRAPPER}} .htmega-stt-wrap:hover svg path' => 'fill: {{VALUE}} !important;',
                    ],
                    'condition' => [
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );
            $element->add_control(
                'stt_bacground_color_hover',
                [
                    'label'     => __( 'Background Color', 'htmega-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-stt-wrap:hover'  => 'background-color: {{VALUE}} !important;',
                    ],
                    'condition' => [
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );
            $element->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'stt_border_hover',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-stt-wrap:hover',
                    'condition' => [
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );

            $element->add_responsive_control(
                'stt_border_radius_hover',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .htmega-stt-wrap:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                    'condition' => [
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );
            $element->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'stt_boxshadow_hover',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .htmega-stt-wrap:hover',
                    'condition' => [
                        'htmega_stt_enable' => 'yes',
                    ],
                ]
            );
            $element->end_controls_tab();
        $element->end_controls_tabs();
		$element->end_controls_section();

	}

}

HTMegaScrollToTop_Elementor::instance();