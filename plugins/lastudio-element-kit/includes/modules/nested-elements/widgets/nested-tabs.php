<?php
namespace LaStudioKitThemeBuilder\Modules\NestedElements\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use LaStudioKitThemeBuilder\Modules\NestedElements\Base\Widget_Nested_Base;
use LaStudioKitThemeBuilder\Modules\NestedElements\Controls\Control_Nested_Repeater;

class NestedTabs extends Widget_Nested_Base {

    protected function enqueue_addon_resources() {
        if ( ! lastudio_kit_settings()->is_combine_js_css() ) {
            wp_register_script( $this->get_name(), lastudio_kit()->plugin_url( 'assets/js/addons/n-tabs.min.js' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version(), true );
            $this->add_script_depends( $this->get_name() );
            if ( !lastudio_kit()->is_optimized_css_mode() ) {
                wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/n-tabs.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
                $this->add_style_depends( $this->get_name() );
            }
        }
    }

    public function get_widget_css_config( $widget_name ) {
        $file_url  = lastudio_kit()->plugin_url( 'assets/css/addons/n-tabs.min.css' );
        $file_path = lastudio_kit()->plugin_path( 'assets/css/addons/n-tabs.min.css' );

        return [
            'key'       => $widget_name,
            'version'   => lastudio_kit()->get_version( true ),
            'file_path' => $file_path,
            'data'      => [
                'file_url' => $file_url
            ]
        ];
    }

	public function get_name() {
		return 'lakit-nested-tabs';
	}

	public function get_widget_title() {
		return esc_html__( 'Nested Tabs', 'lastudio-kit' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_keywords() {
		return [ 'nested', 'tabs', 'accordion', 'toggle' ];
	}

	protected function get_default_children_elements() {
		return [
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Tab #1', 'lastudio-kit' ),
                    'content_width' => 'full'
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Tab #2', 'lastudio-kit' ),
                    'content_width' => 'full'
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Tab #3', 'lastudio-kit' ),
                    'content_width' => 'full'
				],
			],
		];
	}

	protected function get_default_repeater_title_setting_key() {
		return 'tab_title';
	}

	protected function get_default_children_title() {
		return esc_html__( 'Tab #%d', 'lastudio-kit' );
	}

	protected function get_default_children_placeholder_selector() {
		return '.lakit-ntabs-content';
	}

	protected function register_controls() {
		$start = is_rtl() ? 'right' : 'left';
		$end = is_rtl() ? 'left' : 'right';
		$tooltip_start = is_rtl() ? esc_html__( 'Right', 'lastudio-kit' ) : esc_html__( 'Left', 'lastudio-kit' );
		$tooltip_end = is_rtl() ? esc_html__( 'Left', 'lastudio-kit' ) : esc_html__( 'Right', 'lastudio-kit' );

        $nested_tabs_heading_selector_class = '{{WRAPPER}} .lakit-ntabs-{{ID}} > .lakit-ntabs-heading';
        $nested_tabs_content_selector_class = '{{WRAPPER}}.lakit-ntabs-type--accordion .lakit-ntab-content-{{ID}} > .elementor-element, {{WRAPPER}}.lakit-ntabs-type--tab .lakit-ntab-content-{{ID}}';
        $nested_tab_control_id = '{{WRAPPER}} .lakit-ntab-controlid-{{ID}}';
        $accordion_item_selector_class = '{{WRAPPER}}.lakit-ntabs-type--accordion .lakit-ntab-content-{{ID}}';

		$this->start_controls_section( 'section_tabs', [
			'label' => esc_html__( 'Tabs', 'lastudio-kit' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'tab_title', [
			'label' => esc_html__( 'Title', 'lastudio-kit' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Tab Title', 'lastudio-kit' ),
			'placeholder' => esc_html__( 'Tab Title', 'lastudio-kit' ),
			'label_block' => true,
			'dynamic' => [
				'active' => true,
			],
		] );

        $repeater->add_control( 'tab_subtitle', [
			'label' => esc_html__( 'Sub Title', 'lastudio-kit' ),
			'type' => Controls_Manager::TEXT,
			'label_block' => true,
			'dynamic' => [
				'active' => true,
			],
		] );

        $repeater->add_control(
            'use_image',
            array(
                'label'        => esc_html__( 'Use Image?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $repeater->add_control(
            'tab_image',
            array(
                'label'      => esc_html__( 'Image', 'lastudio-kit' ),
                'type'       => Controls_Manager::MEDIA,
                'condition' => [
                    'use_image' => 'yes'
                ]
            )
        );

		$repeater->add_control(
			'tab_icon',
			[
				'label' => esc_html__( 'Icon', 'lastudio-kit' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
                'condition' => [
                    'use_image!' => 'yes'
                ]
			]
		);

		$repeater->add_control(
			'tab_icon_active',
			[
				'label' => esc_html__( 'Active Icon', 'lastudio-kit' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
                    'use_image!' => 'yes',
					'tab_icon[value]!' => '',
				],
			]
		);

		$repeater->add_control(
			'element_id',
			[
				'label' => esc_html__( 'CSS ID', 'lastudio-kit' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'lastudio-kit' ),
				'style_transfer' => false,
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$this->add_control( 'tabs', [
			'label' => esc_html__( 'Tabs Items', 'lastudio-kit' ),
			'type' => Control_Nested_Repeater::CONTROL_TYPE,
			'fields' => $repeater->get_controls(),
			'default' => [
				[
					'tab_title' => esc_html__( 'Tab #1', 'lastudio-kit' ),
				],
				[
					'tab_title' => esc_html__( 'Tab #2', 'lastudio-kit' ),
				],
				[
					'tab_title' => esc_html__( 'Tab #3', 'lastudio-kit' ),
				],
			],
			'title_field' => '{{{ tab_title }}}',
			'button_text' => 'Add Tab',
		] );

        $this->add_control(
            'tab_type',
            array(
                'label'   => esc_html__( 'Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'tab',
                'options' => array(
                    'tab'             => esc_html__( 'Tab', 'lastudio-kit' ),
                    'accordion'       => esc_html__( 'Accordion', 'lastudio-kit' ),
                ),
                'prefix_class' => 'lakit-ntabs-type--',
                'separator' => 'before',
            )
        );

		$this->add_control(
			'toggle_icon',
			[
				'label' => esc_html__( 'Icon', 'lastudio-kit' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'tab_type' => 'accordion'
				],
			]
		);

		$this->add_control(
			'toggle_icon_active',
			[
				'label' => esc_html__( 'Active Icon', 'lastudio-kit' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'tab_type' => 'accordion'
				],
			]
		);

		$this->add_responsive_control( 'tabs_direction', [
			'label' => esc_html__( 'Direction', 'lastudio-kit' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'top' => [
					'title' => esc_html__( 'Top', 'lastudio-kit' ),
					'icon' => 'eicon-v-align-top',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
					'icon' => 'eicon-v-align-bottom',
				],
				'end' => [
					'title' => $tooltip_end,
					'icon' => 'eicon-h-align-' . $end,
				],
				'start' => [
					'title' => $tooltip_start,
					'icon' => 'eicon-h-align-' . $start,
				],
			],
			'selectors_dictionary' => [
				'top' => '--n-tabs-direction: column; --n-tabs-heading-direction: row; --n-tabs-heading-width: initial;',
				'bottom' => '--n-tabs-direction: column-reverse; --n-tabs-heading-direction: row; --n-tabs-heading-width: initial;',
				'end' => '--n-tabs-direction: row-reverse; --n-tabs-heading-direction: column; --n-tabs-heading-width: 240px;',
				'start' => '--n-tabs-direction: row; --n-tabs-heading-direction: column; --n-tabs-heading-width: 240px;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
            'condition' => [
                'tab_type' => 'tab'
            ]
		] );

		$this->add_responsive_control( 'tabs_justify_horizontal', [
			'label' => esc_html__( 'Justify', 'lastudio-kit' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'start' => [
					'title' => esc_html__( 'Start', 'lastudio-kit' ),
					'icon' => 'eicon-flex eicon-align-start-h',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'lastudio-kit' ),
					'icon' => 'eicon-h-align-center',
				],
				'end' => [
					'title' => esc_html__( 'End', 'lastudio-kit' ),
					'icon' => 'eicon-flex eicon-align-end-h',
				],
				'stretch' => [
					'title' => esc_html__( 'Justified', 'lastudio-kit' ),
					'icon' => 'eicon-h-align-stretch',
				],
			],
			'selectors_dictionary' => [
				'start' => '--n-tabs-heading-justify-content: flex-start; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: center;',
				'center' => '--n-tabs-heading-justify-content: center; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: center;',
				'end' => '--n-tabs-heading-justify-content: flex-end; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: center',
				'stretch' => '--n-tabs-heading-justify-content: initial; --n-tabs-title-width: 100%; --n-tabs-title-height: initial; --n-tabs-title-align-items: center;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
			'condition' => [
                'tab_type' => 'tab',
				'tabs_direction' => [
					'',
					'top',
					'bottom',
				],
			],
		] );

		$this->add_responsive_control( 'tabs_justify_vertical', [
			'label' => esc_html__( 'Justify', 'lastudio-kit' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'start' => [
					'title' => esc_html__( 'Start', 'lastudio-kit' ),
					'icon' => 'eicon-flex eicon-align-start-v',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'lastudio-kit' ),
					'icon' => 'eicon-v-align-middle',
				],
				'end' => [
					'title' => esc_html__( 'End', 'lastudio-kit' ),
					'icon' => 'eicon-flex eicon-align-end-v',
				],
				'stretch' => [
					'title' => esc_html__( 'Justified', 'lastudio-kit' ),
					'icon' => 'eicon-v-align-stretch',
				],
			],
			'selectors_dictionary' => [
				'start' => '--n-tabs-heading-justify-content: flex-start; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: initial;',
				'center' => '--n-tabs-heading-justify-content: center; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: initial;',
				'end' => '--n-tabs-heading-justify-content: flex-end; --n-tabs-title-width: initial; --n-tabs-title-height: initial; --n-tabs-title-align-items: initial;',
				'stretch' => '--n-tabs-heading-justify-content: flex-start; --n-tabs-title-width: initial; --n-tabs-title-height: 100%; --n-tabs-title-align-items: center;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
			'condition' => [
                'tab_type' => 'tab',
				'tabs_direction' => [
					'start',
					'end',
				],
			],
		] );

		$this->add_responsive_control( 'tabs_width', [
			'label' => esc_html__( 'Width', 'lastudio-kit' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'%' => [
					'min' => 10,
					'max' => 50,
				],
				'px' => [
					'min' => 20,
					'max' => 600,
				],
			],
			'default' => [
				'unit' => '%',
			],
			'size_units' => [ '%', 'px' ],
			'selectors' => [
				'{{WRAPPER}}' => '--n-tabs-heading-width: {{SIZE}}{{UNIT}}',
			],
			'condition' => [
                'tab_type' => 'tab',
				'tabs_direction' => [
					'start',
					'end',
				],
			],
		] );

		$this->add_responsive_control( 'title_alignment', [
			'label' => esc_html__( 'Align Title', 'lastudio-kit' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'start' => [
					'title' => esc_html__( 'Left', 'lastudio-kit' ),
					'icon' => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'lastudio-kit' ),
					'icon' => 'eicon-text-align-center',
				],
				'end' => [
					'title' => esc_html__( 'Right', 'lastudio-kit' ),
					'icon' => 'eicon-text-align-right',
				],
			],
			'selectors_dictionary' => [
				'start' => '--n-tabs-title-justify-content: flex-start; --n-tabs-title-align-items: flex-start;',
				'center' => '--n-tabs-title-justify-content: center; --n-tabs-title-align-items: center;',
				'end' => '--n-tabs-title-justify-content: flex-end; --n-tabs-title-align-items: flex-end;',
			],
			'selectors' => [
				'{{WRAPPER}}' => '{{VALUE}}',
			]
		] );

        $this->add_control(
            'tab_effect',
            array(
                'label'   => esc_html__( 'Tab Effect', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'move-up',
                'options' => array(
                    'none'             => esc_html__( 'None', 'lastudio-kit' ),
                    'fade'             => esc_html__( 'Fade', 'lastudio-kit' ),
                    'zoom-in'          => esc_html__( 'Zoom In', 'lastudio-kit' ),
                    'zoom-out'         => esc_html__( 'Zoom Out', 'lastudio-kit' ),
                    'move-up'          => esc_html__( 'Move Up', 'lastudio-kit' ),
                    'fall-perspective' => esc_html__( 'Fall Perspective', 'lastudio-kit' ),
                ),
                'prefix_class' => 'lakit-ntabs-effect--',
                'condition' => [
                    'tab_type' => 'tab'
                ]
            )
        );
        $this->add_control(
            'sticky_tab_control',
            array(
                'label'        => esc_html__( 'Sticky tab controls ?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'no', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition' => [
                    'tab_type' => 'tab'
                ]
            )
        );
        $this->add_control(
            'sticky_breakpoint',
            [
                'label' => esc_html__( 'Breakpoint', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__( 'Note: this option will not work if Direction is Left or Right.', 'lastudio-kit' ),
                'options' => [
                        'none'  => esc_html__( 'None', 'lastudio-kit' ),
                        'all'   => esc_html__( 'All', 'lastudio-kit' ),
                    ] + lastudio_kit_helper()->get_active_breakpoints(false, true),
                'default' => 'none',
                'frontend_available' => true,
                'condition' => [
                    'tab_type' => 'tab',
                    'sticky_tab_control' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'tab_as_selectbox',
            array(
                'label'        => esc_html__( 'Tabs as SelectBox', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'no', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
                'frontend_available' => true,
                'condition' => [
                    'tab_type' => 'tab'
                ]
            )
        );
        $this->add_control(
            'tab_text_intro',
            array(
                'label'     => esc_html__( 'Intro Text', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => [
                    'active' => true,
                ],
                'condition' => [
                    'tab_type' => 'tab',
                    'tab_as_selectbox' => 'yes'
                ]
            )
        );

        $this->add_control(
            'breakpoint_selector',
            [
                'label' => esc_html__( 'Responsive Settings', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__( 'Note: Choose at which breakpoint tabs will automatically switch to a SelectBox layout.', 'lastudio-kit' ),
                'options' => [
                        'none' => esc_html__( 'None', 'lastudio-kit' )
                    ] + lastudio_kit_helper()->get_active_breakpoints(false, true),
                'default' => 'mobile',
                'frontend_available' => true,
                'condition' => [
                    'tab_type' => 'tab',
                    'tab_as_selectbox!' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'selectbox_icon',
            array(
                'label'       => esc_html__( 'SelectBox Icon', 'lastudio-kit' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICONS,
                'skin'        => 'inline',
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value'   => 'lastudioicon-arrow-down',
                    'library' => 'lastudioicon',
                ),
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'tab_type',
                            'operator' => '===',
                            'value' => 'tab',
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                ['name' => 'tab_as_selectbox', 'operator' => '===', 'value' => 'yes'],
                                ['name' => 'breakpoint_selector', 'operator' => '!=', 'value' => 'none'],
                            ]
                        ]
                    ],
                ],
            )
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_introbox', [
            'label' => esc_html__( 'Intro Box', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'tab_as_selectbox' => 'yes'
            ]
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'introtext_typography',
            'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox--intro"
        ] );

        $this->add_control(
            'introtext_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--intro" => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'introtext_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox" => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control( 'selectbox_space_between', [
            'label' => esc_html__( 'Gap between', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                "$nested_tabs_heading_selector_class .ntabs-selectbox" => 'gap: {{SIZE}}{{UNIT}}',
            ],
        ] );
        $this->add_responsive_control(
            'introbox_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'introbox_border',
                'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox"
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'introbox_shadow',
                'label' => esc_html__( 'Shadow', 'lastudio-kit' ),
                'separator' => 'after',
                'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox",
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_selectbox_style', [
            'label' => esc_html__( 'SelectBox', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'tab_as_selectbox' => 'yes'
            ]
        ] );

        $this->add_responsive_control( 'selectbox_width', [
            'label' => esc_html__( 'Selectbox Width', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%', 'custom' ],
            'selectors' => [
                "$nested_tabs_heading_selector_class .ntabs-selectbox--wrap" => 'width: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_control(
            'selectbox_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--wrap" => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'selectbox_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--wrap" => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'selectbox_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--wrap" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'selectbox_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--wrap" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'selectbox_border',
                'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox--wrap"
            ]
        );

        $this->add_control(
            '__custom_heading_1',
            [
                'label' => esc_html__( 'Dropdown Box', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'selectbox_dd_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--select" => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'selectbox_dd_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--select" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'selectbox_dd_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs-selectbox--select" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'selectbox_dd_border',
                'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox--select"
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'selectbox_dd_shadow',
                'label' => esc_html__( 'Shadow', 'lastudio-kit' ),
                'separator' => 'after',
                'selector' => "{$nested_tabs_heading_selector_class} .ntabs-selectbox--select",
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_accordion_style', [
            'label' => esc_html__( 'Accordion Item', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'tab_type' => 'accordion'
            ]
        ] );

        $this->start_controls_tabs( 'accordion_style_states' );

        $this->start_controls_tab(
            'accordion_style_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'accordion_bg',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$accordion_item_selector_class}",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$accordion_item_selector_class" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'accordion_border',
                'selector' => "{$accordion_item_selector_class}",
            ]
        );
        $this->add_responsive_control(
            'accordion_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em'],
                'selectors' => [
                    "$accordion_item_selector_class" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'accordion_shadow',
                'label' => esc_html__( 'Shadow', 'lastudio-kit' ),
                'selector' => "{$accordion_item_selector_class}",
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'accordion_style_active',
            [
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'accordion_bg_active',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$accordion_item_selector_class}.e-active",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_padding_active',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "{$accordion_item_selector_class}.e-active" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'accordion_border_active',
                'selector' => "{$accordion_item_selector_class}.e-active",
            ]
        );
        $this->add_responsive_control(
            'accordion_radius_active',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em'],
                'selectors' => [
                    "{$accordion_item_selector_class}.e-active" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'accordion_shadow_active',
                'label' => esc_html__( 'Shadow', 'lastudio-kit' ),
                'selector' => "{$accordion_item_selector_class}.e-active",
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

		$this->start_controls_section( 'section_accordion_icon_style', [
			'label' => esc_html__( 'Toggle Icon', 'lastudio-kit' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [
				'tab_type' => 'accordion'
			]
		] );

		$this->add_control(
			'toggle_icon_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => is_rtl() ? 'right' : 'left',
				'toggle' => false,
				'selectors_dictionary' => [
					'left' => 'order: -10; margin-right: auto',
					'right' => 'order: 10; margin-left: auto',
				],
				'selectors' => [
					"{$nested_tab_control_id} .lakit-ntab-t_icon" => '{{VALUE}}',
				],
			]
		);

		$this->add_responsive_control( 'toggle_icon_size', [
			'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em' ],
			'selectors' => [
				"{$nested_tab_control_id} .lakit-ntab-t_icon" => 'font-size: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control(
			'toggle_icon_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					"{$nested_tab_control_id} .lakit-ntab-t_icon" => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_icon_active_color',
			[
				'label' => esc_html__( 'Active Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					"{$nested_tab_control_id}.e-active .lakit-ntab-t_icon" => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'lastudio-kit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'custom' ],
				'selectors' => [
					"{$nested_tab_control_id} .lakit-ntab-t_icon" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section( 'section_tabs_style', [
            'label' => esc_html__( 'Tabs', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'tab_type' => 'tab'
            ]
        ] );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_background_color',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tabs_heading_selector_class}",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_border',
                'selector' => "{$nested_tabs_heading_selector_class}"
            ]
        );

        $this->add_responsive_control(
            'tabs_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_control_item_style', [
            'label' => esc_html__( 'Control Items', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'tabs_title_space_between', [
            'label' => esc_html__( 'Gap between tabs', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-title-gap: {{SIZE}}{{UNIT}}',
            ],
        ] );
        $this->add_responsive_control( 'tabs_title_spacing', [
            'label' => esc_html__( 'Distance from content', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->start_controls_tabs( 'tabs_title_style' );

        $this->start_controls_tab(
            'tabs_title_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_title_background_color',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tab_control_id}:not( .e-active ):not( :hover )",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}}',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_title_border',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):not( :hover )",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_title_box_shadow',
                'label' => esc_html__( 'Shadow', 'lastudio-kit' ),
                'separator' => 'after',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):not( :hover )",
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_title_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_title_background_color_hover',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tab_control_id}:not( .e-active ):hover",
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}};',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_title_border_hover',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):hover",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_title_box_shadow_hover',
                'label' => esc_html__( 'Shadow', 'lastudio-kit' ),
                'separator' => 'after',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):hover",
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'lastudio-kit' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->add_control(
            'tabs_title_transition_duration',
            [
                'label' => esc_html__( 'Transition Duration (s)', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-transition: {{SIZE}}s',
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_title_active',
            [
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tabs_title_background_color_active',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tab_control_id}.e-active",
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                        'selectors' => [
                            '{{SELECTOR}}' => 'background: {{VALUE}};',
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_title_border_active',
                'selector' => "{$nested_tab_control_id}.e-active",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                    ],
                    'width' => [
                        'label' => esc_html__( 'Border Width', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'tabs_title_box_shadow_active',
                'label' => esc_html__( 'Shadow', 'lastudio-kit' ),
                'selector' => "{$nested_tab_control_id}.e-active",
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'tabs_title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_title_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-padding-top: {{TOP}}{{UNIT}}; --n-tabs-title-padding-right: {{RIGHT}}{{UNIT}}; --n-tabs-title-padding-bottom: {{BOTTOM}}{{UNIT}}; --n-tabs-title-padding-left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tabs_title_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-margin-top: {{TOP}}{{UNIT}}; --n-tabs-title-margin-right: {{RIGHT}}{{UNIT}}; --n-tabs-title-margin-bottom: {{BOTTOM}}{{UNIT}}; --n-tabs-title-margin-left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_title_style', [
            'label' => esc_html__( 'Titles', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => "{$nested_tab_control_id} .ntabs--title",
            'label' => esc_html__( 'Title Typography', 'lastudio-kit' ),
            'fields_options' => [
                'font_size' => [
                    'selectors' => [
                        '{{WRAPPER}}' => '--n-tabs-title-font-size: {{SIZE}}{{UNIT}}',
                    ],
                ],
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'subtitle_typography',
            'selector' => "{$nested_tab_control_id} .ntabs--subtitle",
            'label' => esc_html__( 'Subtitle Typography', 'lastudio-kit' ),
            'fields_options' => [
                'font_size' => [
                    'selectors' => [
                        '{{WRAPPER}}' => '--n-tabs-subtitle-font-size: {{SIZE}}{{UNIT}}',
                    ],
                ],
            ],
        ] );

        $this->add_responsive_control( 'title_spacing', [
            'label' => esc_html__( 'Title gap', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tab-title-gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->start_controls_tabs( 'title_style' );

        $this->start_controls_tab(
            'title_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitle_text_color',
            [
                'label' => esc_html__( 'Subtitle Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-subtitle-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):not( :hover ) .ntabs--title",
                'fields_options' => [
                    'text_shadow_type' => [
                        'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):not( :hover ) .ntabs--title",
                'fields_options' => [
                    'text_stroke_type' => [
                        'label' => esc_html__( 'Stroke', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'title_text_color_hover',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "{$nested_tab_control_id}:not( .e-active ):hover" => '--n-tabs-title-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitle_text_color_hover',
            [
                'label' => esc_html__( 'Subtitle Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "{$nested_tab_control_id}:not( .e-active ):hover" => '--n-tabs-subtitle-color-hover: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow_hover',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):hover .ntabs--title",
                'fields_options' => [
                    'text_shadow_type' => [
                        'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'lastudio-kit' ),
                    ],
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke_hover',
                'selector' => "{$nested_tab_control_id}:not( .e-active ):hover .ntabs--title",
                'fields_options' => [
                    'text_stroke_type' => [
                        'label' => esc_html__( 'Stroke', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_active',
            [
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'title_text_color_active',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-title-color-active: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'subtitle_text_color_active',
            [
                'label' => esc_html__( 'Subtitle Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-subtitle-color-active: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow_active',
                'selector' => "{$nested_tab_control_id}.e-active .ntabs--title",
                'fields_options' => [
                    'text_shadow_type' => [
                        'label' => esc_html_x( 'Shadow', 'Text Shadow Control', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'title_text_stroke_active',
                'selector' => "{$nested_tab_control_id}.e-active .ntabs--title",
                'fields_options' => [
                    'text_stroke_type' => [
                        'label' => esc_html__( 'Stroke', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section( 'icon_section_style', [
            'label' => esc_html__( 'Icon', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'icon_position', [
            'label' => esc_html__( 'Position', 'lastudio-kit' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__( 'Top', 'lastudio-kit' ),
                    'icon' => 'eicon-v-align-top',
                ],
                'end' => [
                    'title' => $tooltip_end,
                    'icon' => 'eicon-h-align-' . $end,
                ],
                'bottom' => [
                    'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'start' => [
                    'title' => $tooltip_start,
                    'icon' => 'eicon-h-align-' . $start,
                ],
            ],
            'selectors_dictionary' => [
                // The toggle variables for 'align items' and 'justify content' have been added to separate the styling of the two 'flex direction' modes.
                'top' => '--n-tabs-title-direction: column; --n-tabs-icon-order: initial; --n-tabs-title-justify-content-toggle: center; --n-tabs-title-align-items-toggle: initial;',
                'end' => '--n-tabs-title-direction: row; --n-tabs-icon-order: 1; --n-tabs-title-justify-content-toggle: initial; --n-tabs-title-align-items-toggle: center;',
                'bottom' => '--n-tabs-title-direction: column; --n-tabs-icon-order: 1; --n-tabs-title-justify-content-toggle: center; --n-tabs-title-align-items-toggle: initial;',
                'start' => '--n-tabs-title-direction: row; --n-tabs-icon-order: initial; --n-tabs-title-justify-content-toggle: initial; --n-tabs-title-align-items-toggle: center;',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '{{VALUE}}',
            ],
        ] );

        $this->add_control(
            'icon_t_align',
            [
                'label' => esc_html__( 'Align Items', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'none' => [
                        'title' => esc_html__( 'None', 'lastudio-kit' ),
                        'icon' => 'eicon-ban',
                    ],
                    'space-between' => [
                        'title' => esc_html__( 'Space Between', 'lastudio-kit' ),
                        'icon' => 'eicon-justify-space-between-h'
                    ],
                ],
                'selectors_dictionary' => [
                    'space-between' => '--n-tabs-title-justify-content-toggle: space-between;',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}',
                ],
                'condition' => [
                    'tab_type' => 'accordion',
                    'icon_position' => [
                        '',
                        'start',
                        'end',
                    ],
                ]
            ]
        );

        $this->add_responsive_control( 'icon_spacing', [
            'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => 'px',
            ],
            'size_units' => [ 'px', 'vw' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-gap: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'icon_size', [
            'label' => esc_html__( 'Size', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ]
            ],
            'default' => [
                'unit' => 'px',
            ],
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-size: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'icon_padding', [
            'label' => esc_html__( 'Padding', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => 'px',
            ],
            'size_units' => [ 'px', 'em', 'custom' ],
            'selectors' => [
                "$nested_tab_control_id .lakit-ntab-icon" => 'padding: {{SIZE}}{{UNIT}}'
            ],
        ] );

        $this->add_responsive_control( 'icon_radius', [
            'label' => esc_html__( 'Radius', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => 'px',
            ],
            'size_units' => [ 'px', 'em',  '%' ],
            'selectors' => [
                "$nested_tab_control_id .lakit-ntab-icon" => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => "$nested_tab_control_id .lakit-ntab-icon",
                'exclude' => [ 'color' ],
            ]
        );

        $this->start_controls_tabs( 'icon_style_states' );

        $this->start_controls_tab(
            'icon_section_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_control( 'icon_color', [
            'label' => esc_html__( 'Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-color: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'icon_bg_color', [
            'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tab_control_id .lakit-ntab-icon" => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_border_color', [
            'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tab_control_id .lakit-ntab-icon" => 'border-color: {{VALUE}};',
            ],
        ] );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_section_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_control( 'icon_color_hover', [
            'label' => esc_html__( 'Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tab_control_id:not( .e-active ):hover" => '--n-tabs-icon-color-hover: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_bg_color_hover', [
            'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tab_control_id:not( .e-active ):hover .lakit-ntab-icon" => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_border_color_hover', [
            'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tab_control_id:not( .e-active ):hover .lakit-ntab-icon" => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_section_active',
            [
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            ]
        );

        $this->add_control( 'icon_color_active', [
            'label' => esc_html__( 'Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-icon-color-active: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_bg_color_active', [
            'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tab_control_id.e-active .lakit-ntab-icon" => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'icon_border_color_active', [
            'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                "$nested_tab_control_id.e-active .lakit-ntab-icon" => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section( 'section_box_style', [
            'label' => esc_html__( 'Content', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_background_color',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => "{$nested_tabs_content_selector_class}",
                'fields_options' => [
                    'color' => [
                        'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => "{$nested_tabs_content_selector_class}"
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-content-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_box_shadow',
                'selector' => "{$nested_tabs_content_selector_class}",
                'condition' => [
                    'box_height!' => 'height',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'section_divider_style', [
            'label' => esc_html__( 'Divider', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE
        ] );

        $this->add_control(
            'enable_divider',
            array(
                'label'        => esc_html__( 'Enable Divider', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->add_responsive_control( 'divider_position', [
            'label' => esc_html__( 'Position', 'lastudio-kit' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'top' => [
                    'title' => esc_html__( 'Top', 'lastudio-kit' ),
                    'icon' => 'eicon-v-align-top',
                ],
                'end' => [
                    'title' => $tooltip_end,
                    'icon' => 'eicon-h-align-' . $end,
                ],
                'bottom' => [
                    'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                    'icon' => 'eicon-v-align-bottom',
                ],
                'start' => [
                    'title' => $tooltip_start,
                    'icon' => 'eicon-h-align-' . $start,
                ],
            ],
            'selectors_dictionary' => [
                'top' => '--n-tabs-divider-pos-left:50%;--n-tabs-divider-pos-right:initial;--n-tabs-divider-pos-top:0;--n-tabs-divider-pos-bottom:initial;--n-tabs-divider-transform:translateX(-50%)',
                'end' => '--n-tabs-divider-pos-left:initial;--n-tabs-divider-pos-right:0;--n-tabs-divider-pos-top:50%;--n-tabs-divider-pos-bottom:initial;--n-tabs-divider-transform:translateY(-50%);--n-tabs-divider-last:0',
                'bottom' => '--n-tabs-divider-pos-left:50%;--n-tabs-divider-pos-right:initial;--n-tabs-divider-pos-top:initial;--n-tabs-divider-pos-bottom:0;--n-tabs-divider-transform:translateX(-50%);--n-tabs-divider-last:initial',
                'start' => '--n-tabs-divider-pos-left:0;--n-tabs-divider-pos-right:initial;--n-tabs-divider-pos-top:50%;--n-tabs-divider-pos-bottom:initial;--n-tabs-divider-transform:translateY(-50%)',
            ],
            'selectors' => [
                '{{WRAPPER}}' => '{{VALUE}}',
            ],
            'condition' => [
                'enable_divider' => 'yes'
            ]
        ] );

        $this->start_controls_tabs( 'tabs_divider_style', [
            'condition' => [
                'enable_divider' => 'yes'
            ]
        ] );
        $this->start_controls_tab(
            'tabs_divider_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );
        $this->add_responsive_control( 'divider_height', [
            'label' => esc_html__( 'Divider Height', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-height: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'divider_width', [
            'label' => esc_html__( 'Divider Width', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-width: {{SIZE}}{{UNIT}}',
            ],
        ] );
        $this->add_control(
            'divider_color',
            [
                'label' => esc_html__( 'Divider Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-divider-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_divider_active',
            [
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            ]
        );
        $this->add_responsive_control( 'divider_height_active', [
            'label' => esc_html__( 'Divider Height', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-active-height: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control( 'divider_width_active', [
            'label' => esc_html__( 'Divider Width', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors' => [
                '{{WRAPPER}}' => '--n-tabs-divider-active-width: {{SIZE}}{{UNIT}}',
            ],
        ] );
        $this->add_control(
            'divider_color_active',
            [
                'label' => esc_html__( 'Divider Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--n-tabs-divider-active-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section( 'section_selectbox_icon_style', [
            'label' => esc_html__( 'SelectBox Icon', 'lastudio-kit' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'conditions' => [
                'relation' => 'and',
                'terms' => [
                    [
                        'name' => 'tab_type',
                        'operator' => '===',
                        'value' => 'tab',
                    ],
                    [
                        'relation' => 'or',
                        'terms' => [
                            ['name' => 'tab_as_selectbox', 'operator' => '===', 'value' => 'yes'],
                            ['name' => 'breakpoint_selector', 'operator' => '!=', 'value' => 'none'],
                        ]
                    ]
                ],
            ],
        ] );

        $this->add_control(
            'selectbox_icon_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs--selectboxicon" => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control( 'selectbox_icon_size', [
            'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                ],
            ],
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                "$nested_tabs_heading_selector_class .ntabs--selectboxicon" => 'font-size: {{SIZE}}{{UNIT}}',
            ],
        ] );

        $this->add_responsive_control(
            'selectbox_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'custom' ],
                'selectors' => [
                    "$nested_tabs_heading_selector_class .ntabs--selectboxicon" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
        $tab_type = $this->get_settings_for_display('tab_type');
		$tabs = $settings['tabs'];

		$id_int = substr( $this->get_id_int(), 0, 3 );
        $title_control_id = 'lakit-ntab-controlid-' . $this->get_id();

		$this->add_render_attribute( 'elementor-tabs', 'class', ['lakit-ntabs', 'lakit-ntabs-' . $this->get_id()] );
		$this->add_render_attribute( 'tab-title-text', 'class', 'lakit-ntab-title-text' );
		$this->add_render_attribute( 'tab-icon', 'class', 'lakit-ntab-icon' );
		$this->add_render_attribute( 'tab-icon-active', 'class', [ 'lakit-ntab-icon', 'e-active' ] );

		$this->add_render_attribute( 'toggle-icon', 'class', 'lakit-ntab-t_icon' );

		$tabs_title_html = '';
		$mobile_tabs_title_html = '';
        $first_item = '';
        $animationClass = $settings['hover_animation']  ? 'elementor-animation-'. $settings['hover_animation'] : '';

		$toggle_icon = self::try_get_icon_html( $this->get_settings_for_display('toggle_icon') , [ 'aria-hidden' => 'true' ]);
		$toggle_icon_active = self::try_get_icon_html( $this->get_settings_for_display('toggle_icon_active') , [ 'aria-hidden' => 'true' ]);

        $toggle_icon_html = '';
        if(!empty($toggle_icon) || !empty($toggle_icon_active)){
	        $toggle_icon_html = "<span {$this->get_render_attribute_string( 'toggle-icon' )}>{$toggle_icon}{$toggle_icon_active}</span>";
        }

		foreach ( $tabs as $index => $item ) {
			// Tabs title.
			$tab_count = $index + 1;
			$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
            $tab_title_mobile_setting_key = $this->get_repeater_setting_key( 'tab_title_mobile', 'tabs', $tab_count );
			$tab_title = sprintf('<div class="ntabs--title">%1$s</div>', $item['tab_title']);
            if(!empty($item['tab_subtitle'])){
                $tab_title = $tab_title . sprintf('<div class="ntabs--subtitle">%1$s</div>', $item['tab_subtitle']);
            }

			$tab_id = empty( $item['element_id'] ) ? 'lakit-ntabs-title-' . $id_int . $tab_count : $item['element_id'];

			$this->add_render_attribute( $tab_title_setting_key, [
				'id' => $tab_id,
				'class' => [ 'lakit-ntab-title', 'e-normal', $animationClass, $title_control_id],
                'data-tabindex' => $tab_count,
			] );

            $this->add_render_attribute( $tab_title_mobile_setting_key, [
                'class' => ['lakit-ntab-title', 'e-collapse', $title_control_id],
                'aria-selected' => 1 === $tab_count ? 'true' : 'false',
                'data-tab' => $tab_count,
                'role' => 'tab',
                'tabindex' => 1 === $tab_count ? '0' : '-1',
                'data-tabindex' => $tab_count,
                'aria-controls' => 'e-n-tab-content-' . $id_int . $tab_count,
                'aria-expanded' => 'false',
                'id' => $tab_id . '-accordion',
            ] );

			$title_render_attributes = $this->get_render_attribute_string( $tab_title_setting_key );
            $mobile_title_attributes = $this->get_render_attribute_string( $tab_title_mobile_setting_key );
			$tab_title_class = $this->get_render_attribute_string( 'tab-title-text' );
			$tab_icon_class = $this->get_render_attribute_string( 'tab-icon' );

            $icon_html = self::try_get_icon_html( $item['tab_icon'], [ 'aria-hidden' => 'true' ] );
            $icon_active_html = $icon_html;
            if ( $this->is_active_icon_exist( $item ) ) {
                $icon_active_html = self::try_get_icon_html( $item['tab_icon_active'], [ 'aria-hidden' => 'true' ] );
            }

            if ( $item['use_image'] === 'yes' &&  ! empty( $item['tab_image']['url'] ) ) {
                $icon_html = sprintf( '<img src="%1$s" alt="" width="16" height="16"/>', apply_filters( 'lastudio_wp_get_attachment_image_url', $item['tab_image']['url'] ) );
                $icon_active_html = '';
            }

			$tabs_title_html .= "<div {$title_render_attributes}>";
            if(!empty($icon_html) || !empty($icon_active_html)){
                $tabs_title_html .= "\t<div {$tab_icon_class}>{$icon_html}{$icon_active_html}</div>";
            }
			$tabs_title_html .= "\t<div {$tab_title_class}>{$tab_title}</div>";
            $tabs_title_html .= $toggle_icon_html;
			$tabs_title_html .= '</div>';

            if($index === 0){
                $first_item =  str_replace('id="'.$tab_id.'"', '', $tabs_title_html);
                $first_item =  str_replace('lakit-ntab-title ', 'lakit-ntab-title clone--item ', $first_item);
                if(!empty($animationClass)){
                    $first_item =  str_replace($animationClass, '', $first_item);
                }
            }

			// Tabs content.
			ob_start();
			$this->print_child( $index );
			$tab_content = ob_get_clean();

            $c_class = ['lakit-ntabs-content-item'];
            $c_class[] = 'lakit-ntab-content-' . $this->get_id();
            if($index === 0){
                $c_class[] = 'e-active';
            }

            $mobile_tabs_title_html .= '<div class="'.join(' ', $c_class).'">';
            if($tab_type !== 'tab'){
                $mobile_tabs_title_html .= "<div {$mobile_title_attributes}>";
                if(!empty($icon_html) || !empty($icon_active_html)){
                    $mobile_tabs_title_html .= "\t<div {$tab_icon_class}>{$icon_html}{$icon_active_html}</div>";
                }
                $mobile_tabs_title_html .= "\t<div {$tab_title_class}>{$tab_title}</div>";
	            $mobile_tabs_title_html .= $toggle_icon_html;
                $mobile_tabs_title_html .= '</div>';
            }
			$mobile_tabs_title_html .= $tab_content;
            $mobile_tabs_title_html .= '</div>';
		}

        $selectbox_icon = self::try_get_icon_html( $settings['selectbox_icon'], [ 'aria-hidden' => 'true' ] );
        $dd_icon = '';
        if($selectbox_icon){
            $dd_icon = sprintf('<span class="ntabs--selectboxicon">%1$s</span>', $selectbox_icon);
        }
		?>
		<div <?php $this->print_render_attribute_string( 'elementor-tabs' ); ?>>
            <?php if( $tab_type === 'tab' ): ?>
			<div class="lakit-ntabs-heading">
				<?php
                if( $settings['tab_as_selectbox'] === 'yes' ) {
                    $first_item .= $dd_icon;
                    $intro_text = !empty( $settings['tab_text_intro'] ) ? sprintf('<div class="ntabs-selectbox--intro">%1$s</div>', $settings['tab_text_intro']) : '';
                    echo sprintf(
                        '<div class="ntabs-selectbox">%1$s<div class="ntabs-selectbox--wrap"><div class="ntabs-selectbox--label">%2$s</div><div class="ntabs-selectbox--select">%3$s</div></div></div>',
                        $intro_text,
                        $first_item,
                        $tabs_title_html
                    );
                }
                else{
                    $tabs_title_html .= $dd_icon;
                    echo $tabs_title_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
			</div>
            <?php endif; ?>
			<div class="lakit-ntabs-content">
				<?php echo $mobile_tabs_title_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<div class="lakit-ntabs lakit-ntabs-{{{view.getID()}}}">
			<# if ( settings['tabs'] ) {

			var elementUid = view.getIDInt().toString().substr( 0, 3 );
            var _tabControlHTML = '',
                firstItem = '',
                selectboxIcon = elementor.helpers.renderIcon( view, settings.selectbox_icon, { 'aria-hidden': true }, 'i' , 'object' ),
                selectboxIconHTML = '';
            if(selectboxIcon.value){
                selectboxIconHTML = `<span class="ntabs--selectboxicon">${selectboxIcon.value}</span>`;
            }
            var hoverAnimationClass = settings['hover_animation'] ? `elementor-animation-${ settings['hover_animation'] }` : '';

            var toggleIcon = elementor.helpers.renderIcon( view, settings.toggle_icon, { 'aria-hidden': true }, 'i' , 'object' );
            var toggleIconActive = elementor.helpers.renderIcon( view, settings.toggle_icon_active, { 'aria-hidden': true }, 'i' , 'object' );
            var toggleIconHTML = '';
            if( settings.tab_type === 'accordion' && (toggleIcon.value || toggleIconActive.value)){
                toggleIconHTML = `<span class="lakit-ntab-t_icon">${toggleIcon.value}${toggleIconActive.value}</span>`;
            }
            #>
			<div class="lakit-ntabs-heading">
            <# _.each( settings['tabs'], function( item, index ) {
				let tabCount = index + 1,
					tabUid = elementUid + tabCount,
					tabWrapperKey = tabUid,
					tabTitleKey = 'tab-title-' + tabUid,
					tabIconKey = 'tab-icon-' + tabUid,
					tabIcon = elementor.helpers.renderIcon( view, item.tab_icon, { 'aria-hidden': true }, 'i' , 'object' ),
					tabActiveIcon = tabIcon,
					tabId = 'lakit-ntab-title-' + tabUid,
                    tabImageHTML = '',
                    iconHTML = '';

				if ( '' !== item.tab_icon_active.value ) {
					tabActiveIcon = elementor.helpers.renderIcon( view, item.tab_icon_active, { 'aria-hidden': true }, 'i' , 'object' );
				}

				if ( '' !== item.element_id ) {
					tabId = item.element_id;
				}

                if(tabIcon.value){
                    iconHTML += tabIcon.value;
                }
                if(tabActiveIcon.value){
                    iconHTML += tabActiveIcon.value;
                }

                if(item.use_image === 'yes' && item.tab_image.url){
                    let imageObj = {
                        id: item.tab_image.id,
                        url: item.tab_image.url,
                        size: 'full',
                        model: view.getEditModel()
                    };
                    let image_url = elementor.imagesManager.getImageUrl( imageObj );
                    tabImageHTML = '<img src="' + image_url + '"/>';
                    iconHTML = tabImageHTML;
                }

				view.addRenderAttribute( tabWrapperKey, {
					'id': tabId,
					'class': [ 'lakit-ntab-title','e-normal', hoverAnimationClass, 'lakit-ntab-controlid-' + view.getID() ],
                    'data-tabindex': tabCount,
				} );

				view.addRenderAttribute( tabTitleKey, {
					'class': [ 'lakit-ntab-title-text' ],
					'data-binding-type': 'repeater-item',
					'data-binding-repeater-name': 'tabs',
					'data-binding-setting': [ 'tab_title tab_subtitle' ],
					'data-binding-index': tabCount,
				} );

				view.addRenderAttribute( tabIconKey, {
					'class': [ 'lakit-ntab-icon' ],
					'data-binding-type': 'repeater-item',
					'data-binding-repeater-name': 'tabs',
					'data-binding-setting': [ 'tab_icon.value', 'tab_icon_active.value' ],
					'data-binding-index': tabCount,
				} );

                _tabControlHTML += `<div ${view.getRenderAttributeString( tabWrapperKey )}><div ${view.getRenderAttributeString( tabIconKey )}>${iconHTML}</div><div ${view.getRenderAttributeString( tabTitleKey )}><div class="ntabs--title">${item.tab_title}</div><div class="ntabs--subtitle">${item.tab_subtitle}</div></div>${toggleIconHTML}</div>`;
                if(index === 0){
                    firstItem = _tabControlHTML;
                }
            } )
                if(settings.tab_as_selectbox === 'yes'){
                    view.addRenderAttribute( 'introtext', 'class', [ 'ntabs-selectbox--intro' ] );
                    let tmpHtml = '<div class="ntabs-selectbox">';
                    if( settings.tab_text_intro ){
                        tmpHtml += `<div ${view.getRenderAttributeString("introtext")}>${settings.tab_text_intro}</div>`;
                    }
                    firstItem = firstItem.replace('class="lakit-ntab-title ', 'class="lakit-ntab-title e-active clone--item ');
                    tmpHtml += `<div class="ntabs-selectbox--wrap"><div class="ntabs-selectbox--label">${firstItem}${selectboxIconHTML}</div><div class="ntabs-selectbox--select">${_tabControlHTML}</div></div>`;
                    tmpHtml += '</div>';
                    _tabControlHTML = tmpHtml;
                }
                else{
                    _tabControlHTML += selectboxIconHTML;
                }
            #>
                {{{ _tabControlHTML }}}
			</div>
			<div class="lakit-ntabs-content"></div>
			<# } #>
		</div>
		<?php
	}

	/**
	 * @param $item
	 * @return bool
	 */
	private function is_active_icon_exist( $item ) {
		return array_key_exists( 'tab_icon_active', $item ) && ! empty( $item['tab_icon_active'] ) && ! empty( $item['tab_icon_active']['value'] );
	}
}
