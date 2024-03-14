<?php
/**
 * Class: LaStudioKit_Icon_Box
 * Name: Icon Box
 * Slug: lakit-image-box
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Icon Box Widget
 */
class LaStudioKit_Icon_Box extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){

	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if ( ! lastudio_kit()->is_optimized_css_mode() ) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/iconbox.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/iconbox.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/iconbox.min.css' );
		return [
			'key' => $widget_name,
			'version' => lastudio_kit()->get_version(true),
			'file_path' => $file_path,
			'data' => [
				'file_url' => $file_url
			]
		];
	}

    public function get_name() {
        return 'lakit-icon-box';
    }

    protected function get_widget_title() {
        return esc_html__( 'Icon Box', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-icon-box';
    }

    protected function register_controls() {


        $css_scheme = apply_filters(
            'lastudio-kit/iconbox/css-schema',
            array(
                'box'                   => '.lakit-iconbox',
                'box_header'            => '.lakit-iconbox__box_header',
                'header_box_icon'       => '.lakit-iconbox__box_icon',
                'main_icon'             => '.lakit-iconbox__main_icon',
                'header_icon'           => '.lakit-iconbox__icon',
                'box_body'              => '.lakit-iconbox__box_body',
                'box_title'             => '.lakit-iconbox__title',
                'box_subtitle'          => '.lakit-iconbox__subtitle',
                'box_desc'              => '.lakit-iconbox__desc',
                'box_divider'           => '.lakit-iconbox__divider',
                'button_wrap'           => '.lakit-iconbox__button_wrapper',
                'button'                => '.lakit-iconbox__button_wrapper .elementor-button',
                'button_text'           => '.lakit-iconbox__button_wrapper .elementor-button-text',
                'button_icon'           => '.lakit-iconbox__button_wrapper .elementor-button-icon',
                'box_badge'             => '.lakit-iconbox__badge',
                'badge'                 => '.lakit__badge',
                'water_box_icon'        => '.lakit-iconbox__icon-hover',
                'water_box_img'         => '.lakit-iconbox__image-hover',
            )
        );

        $this->_start_controls_section(
            'section_box',
            [
                'label' => esc_html__( 'Icon Box', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'enable_equal_height',
            [
                'label'     => esc_html__( 'Equal Height?', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'enable' => esc_html__( 'Enable', 'lastudio-kit' ),
                    'disable' => esc_html__( 'Disable', 'lastudio-kit' ),
                ],
                'default'   => 'disable',
                'prefix_class'  => 'lakit-equal-height-',
                'selectors' => [
                    '{{WRAPPER}}.lakit-equal-height-enable .lakit-iconbox' => 'height: 100%;',
                ],
            ]
        );

        $this->_add_control(
            'overflow',
            [
                'label'     => esc_html__( 'Overflow', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'initial' => esc_html__( 'Visible', 'lastudio-kit' ),
                    'hidden' => esc_html__( 'Hidden', 'lastudio-kit' ),
                ],
                'default'   => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox' => 'overflow: {{VALUE}}',
                ],
            ]
        );

        $this->_add_control(
            'enable_header_icon', [
                'label'       => esc_html__( 'Icon Type', 'lastudio-kit' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'none' => [
                        'title' => esc_html__( 'None', 'lastudio-kit' ),
                        'icon'  => 'eicon-ban',
                    ],
                    'icon' => [
                        'title' => esc_html__( 'Icon', 'lastudio-kit' ),
                        'icon'  => 'eicon-paint-brush',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'lastudio-kit' ),
                        'icon'  => 'eicon-image',
                    ],
                ],
                'default'       => 'none',
            ]
        );

        $this->_add_advanced_icon_control(
            'header_icons',
            [
                'label' => esc_html__( 'Header Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => [
                    'enable_header_icon'    => 'icon'
                ]
            ]
        );

        $this->_add_control(
            'header_image',
            [
                'label' => esc_html__( 'Choose Image', 'lastudio-kit' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'enable_header_icon' => 'image',
                ]
            ]
        );

        $this->_add_control(
            'title_text',
            [
                'label' => esc_html__( 'Title ', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Strategy and  Planning', 'lastudio-kit' ),
                'placeholder' => esc_html__( 'Enter your title', 'lastudio-kit' ),
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'enable_divider',
            [
                'label' => esc_html__( 'Enable Divider', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );

        $this->_add_responsive_control(
            'divider_width',
            [
                'label' => esc_html__( 'Divider Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-iconbox-divider-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_divider'  => 'yes'
                ]
            ]
        );

        $this->_add_responsive_control(
            'divider_height',
            [
                'label' => esc_html__( 'Divider Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-iconbox-divider-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_divider'  => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'divider_color',
            [
                'label' => esc_html__( 'Divider Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--lakit-iconbox-divider-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_divider'  => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'subtitle_text',
            [
                'label' => esc_html__( 'Sub Title ', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Enter your sub title', 'lastudio-kit' ),
                'label_block' => true,
            ]
        );

        $this->_add_control(
            'description_text',
            [
                'label' => esc_html__( 'Content', 'lastudio-kit' ),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'We bring the right people together to challenge established thinking and drive transform in 2021', 'lastudio-kit' ),
                'placeholder' => esc_html__( 'Enter your description', 'lastudio-kit' ),
                'rows' => 10,
            ]
        );

        $this->_end_controls_section();

        //  Section Button

        $this->_start_controls_section(
            'section_button',
            [
                'label' => esc_html__( 'Read More', 'lastudio-kit' ),
            ]
        );
        $this->_add_control(
            'enable_btn',
            [
                'label' => esc_html__( 'Enable Button', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );
        $this->_add_control(
            'floating_btn',
            [
                'label' => esc_html__( 'Floating Button ?', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'lakit--icon-floating-button',
	            'prefix_class' => '',
                'condition' => [
	                'enable_btn' => 'yes',
                ]
            ]
        );
	    $this->_add_control(
		    'floating_btn_pos',
		    [
			    'label' => esc_html__( 'Position', 'lastudio-kit' ),
			    'type' => Controls_Manager::CHOOSE,
			    'default' => 'right',
			    'options' => [
				    'left' => [
					    'title' => esc_html__( 'Left', 'lastudio-kit' ),
					    'icon' => 'eicon-h-align-left',
				    ],
				    'right' => [
					    'title' => esc_html__( 'Right', 'lastudio-kit' ),
					    'icon' => 'eicon-h-align-right',
				    ],
			    ],
			    'prefix_class' => 'lakit--icon-floating-button--',
			    'condition' => [
				    'enable_btn' => 'yes',
				    'floating_btn!' => '',
			    ]
		    ]
	    );
        $this->_add_control(
            'enable_hover_btn',
            [
                'label' => esc_html__( 'Enable Button on Hover', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'separator' => 'after',
                'condition' => [
                    'enable_btn' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'btn_text',
            [
                'label' =>esc_html__( 'Label', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' =>esc_html__( 'Learn more ', 'lastudio-kit' ),
                'placeholder' =>esc_html__( 'Learn more ', 'lastudio-kit' ),
                'dynamic'     => array( 'active' => true ),
                'condition' => [
                    'enable_btn' => 'yes',
                ]
            ]
        );


        $this->_add_control(
            'btn_url',
            [
                'label' =>esc_html__( 'URL', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'enable_btn' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'icons__switch',
            [
                'label' => esc_html__('Add icon?', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' =>esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' =>esc_html__( 'No', 'lastudio-kit' ),
                'condition' => [
                    'enable_btn' => 'yes',
                ]
            ]
        );

        $this->_add_advanced_icon_control(
            'btn_icon',
            [
                'label' =>esc_html__( 'Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => '',
                ],
                'label_block' => true,
                'condition' => [
                    'enable_btn' => 'yes',
                    'icons__switch'   => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'icon_align',
            [
                'label' =>esc_html__( 'Icon Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' =>esc_html__( 'Before', 'lastudio-kit' ),
                    'right' =>esc_html__( 'After', 'lastudio-kit' ),
                    'top' =>esc_html__( 'Top', 'lastudio-kit' ),
                    'bottom' =>esc_html__( 'Bottom', 'lastudio-kit' ),
                ],
                'condition' => [
                    'icons__switch'   => 'yes',
                    'enable_btn'      => 'yes',
                ],
            ]
        );

        $this->_add_control(
            'show_global_link',
            [
                'label' => esc_html__( 'Global Link', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->_add_control(
            'global_link',
            [
                'label' => esc_html__( 'Link', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'show_external' => true,
                'default' => [
                    'url' => '#',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_global_link' => 'yes'
                ],
            ]
        );

        $this->_end_controls_section();

        //  Settings
        $this->_start_controls_section(
            'section_settings',
            [
                'label' => esc_html__( 'Settings', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'enable_water_mark',
            [
                'label' => esc_html__( 'Enable Hover Water Mark ', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );

        $this->_add_advanced_icon_control(
            'water_mark_icons',
            [
                'label' => esc_html__( 'Social Icons', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'label_block' => true,
                'condition' => [
                    'enable_water_mark' => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'  => esc_html__( 'Top', 'lastudio-kit' ),
                    'left'  => esc_html__( 'Left', 'lastudio-kit' ),
                    'lefttitle'  => esc_html__( 'Left Title', 'lastudio-kit' ),
                    'right'  => esc_html__( 'Right', 'lastudio-kit' ),
                    'righttitle'  => esc_html__( 'Right Title', 'lastudio-kit' ),
                ],
                'separator' => 'before',
                'condition' => [
                    'enable_header_icon!' => 'none'
                ]
            ]
        );
        $this->_add_responsive_control(
            'icon_alignment',
            [
                'label' => esc_html__( 'Icon Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] => 'text-align: {{VALUE}}',
                ],
                'condition' => [
                    'enable_header_icon!' => 'none',
                    'icon_position' => 'top'
                ],
            ]
        );

        $this->_add_control(
            'move_divider_to_header',
            [
                'label' => esc_html__( 'Move divider to header', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'condition' => [
                    'enable_header_icon!' => 'none',
                    'icon_position' => ['lefttitle', 'righttitle']
                ],
                'return_value' => 'yes',
            ]
        );
        $this->_add_control(
            'move_subtitle_to_header',
            [
                'label' => esc_html__( 'Move subtitle to header', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'condition' => [
                    'enable_header_icon!' => 'none',
                    'icon_position' => ['lefttitle', 'righttitle']
                ],
                'return_value' => 'yes',
            ]
        );

        $this->_add_control(
            'title_size',
            [
                'label' => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
                'separator' => 'before',
            ]
        );
        $this->_end_controls_section();

        $this->_start_controls_section(
            'badge_control_tab',
            [
                'label' => esc_html__( 'Badge', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->_add_control(
            'badge_control',
            [
                'label' => esc_html__( 'Show Badge', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'lastudio-kit' ),
                'label_off' => esc_html__( 'Hide', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );
	    $this->_add_control(
		    'badge_in_header',
		    [
			    'label' => esc_html__( 'Badge In Header', 'lastudio-kit' ),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
			    'label_off' => esc_html__( 'No', 'lastudio-kit' ),
			    'return_value' => 'yes',
			    'condition' => [
				    'enable_header_icon!' => 'none',
				    'badge_control' => 'yes'
			    ]
		    ]
	    );
        $this->_add_control(
            'badge_title',
            [
                'label' => esc_html__( 'Title', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'EXCLUSIVE', 'lastudio-kit' ),
                'placeholder' => esc_html__( 'Type your title here', 'lastudio-kit' ),
                'condition' => [
                    'badge_control' => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'badge_position',
            [
                'label' => esc_html__( 'Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top_left',
                'options' => [
                    'top_left'  => esc_html__( 'Top Left', 'lastudio-kit' ),
                    'top_center' => esc_html__( 'Top Center', 'lastudio-kit' ),
                    'top_right' => esc_html__( 'Top Right', 'lastudio-kit' ),
                    'center_left' => esc_html__( 'Center Left', 'lastudio-kit' ),
                    'center_right' => esc_html__( 'Center Right', 'lastudio-kit' ),
                    'bottom_left' => esc_html__( 'Bottom Left', 'lastudio-kit' ),
                    'bottom_center' => esc_html__( 'Bottom Center', 'lastudio-kit' ),
                    'bottom_right' => esc_html__( 'Bottom Right', 'lastudio-kit' ),
                    'custom' => esc_html__( 'Custom', 'lastudio-kit' ),
                ],
                'condition' => [
                    'badge_control' => 'yes'
                ]
            ]
        );

        $this->_add_responsive_control(
            'badge_arrow_horizontal_position',
            [
                'label' => esc_html__( 'Horizontal Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_badge'] => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_position'  => 'custom'
                ]
            ]
        );

        $this->_add_responsive_control(
            'badge_arrow_horizontal_position_vertial',
            [
                'label' => esc_html__( 'Vertical Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -1000,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_badge'] => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'badge_position'  => 'custom'
                ]
            ]
        );

        $this->_end_controls_section();

        // start style for Icon Box Container
        $this->_start_controls_section(
            'section_background_style',
            [
                'label' => esc_html__( 'Icon Box Container', 'lastudio-kit' ),
                'tab' => controls_Manager::TAB_STYLE,
            ]
        );

        $this->_start_controls_tabs('section_background_style_tab');
        $this->_start_controls_tab(
            'section_background_style_n_tab',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'infobox_bg_group',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box'],
            ]
        );
        $this->_add_responsive_control(
            'infobox_bg_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'infobox_box_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box'],
            ]
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'iocnbox_border_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box'],
            ]
        );
        $this->_add_responsive_control(
            'infobox_border_radious',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_end_controls_tab();
        $this->_start_controls_tab(
            'section_background_style_n_hv_tab',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'infobox_bg_hover_group',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' =>  '{{WRAPPER}} ' . $css_scheme['box'] . ':hover',
            ]
        );
        $this->_add_responsive_control(
            'infobox_bg_padding_inner',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],

                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'infobox_box_shadow_hv_group',
                'label' => esc_html__( 'Box Shadow', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box'] . ':hover',
            ]
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_box_border_hv_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box'] . ':hover',
            ]
        );
        $this->_add_responsive_control(
            'infobox_border_radious_hv',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_control(
            'info_box_hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'lastudio-kit' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_end_controls_section();

        // start content style
        $this->_start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
	    $this->_add_responsive_control(
		    'content_width',
		    [
			    'label' => esc_html__( 'Content Width', 'lastudio-kit' ),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px', '%', 'em' ],
			    'selectors' => [
				    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->_add_control(
            'content_show_on_hover',
            [
                'label'     => esc_html__( 'Show on hover?', 'lastudio-kit' ),
                'type'      => Controls_Manager::SWITCHER,
                'prefix_class'  => 'show-content-hover-',
            ]
        );

	    $this->_add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'content_border',
			    'label' => esc_html__( 'Border', 'lastudio-kit' ),
			    'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'],
		    ]
	    );
        $this->_add_responsive_control(
            'content_alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
	                'left'    => 'text-align:left; margin-right: auto;',
	                'center' => 'text-align:center; margin-left: auto; margin-right: auto;',
	                'right' => 'text-align:right; margin-left: auto;',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => '{{VALUE}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'text_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'content_valign',
            [
                'label' => esc_html__( 'Vertical Alignment', 'lastudio-kit' ),
                'type'  => Controls_Manager::CHOOSE,
                'options' => [
                    'top'    => [
                        'title' => __( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top'    => '-webkit-box-align: start; -ms-flex-align: start; -ms-grid-row-align: flex-start; align-items: flex-start;',
                    'middle' => '-webkit-box-align: center; -ms-flex-align: center; -ms-grid-row-align: center; align-items: center;',
                    'bottom' => '-webkit-box-align: end; -ms-flex-align: end; -ms-grid-row-align: flex-end; align-items: flex-end;',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] => '{{VALUE}}',
                ],
                'condition' => [
                    'icon_position'           => ['left', 'right'],
                ],
            ]
        );

        $this->_add_control(
            'heading_title',
            [
                'label' => esc_html__( 'Title', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_responsive_control(
            'title_bottom_space',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title'] => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->_add_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['box_title'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_title'],
            ]
        );

        $this->_add_control(
            'heading_subtitle',
            [
                'label' => esc_html__( 'Sub Title', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->_add_responsive_control(
            'subtitle_bottom_space',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_subtitle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'subtitle_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_subtitle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'subtitle_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_subtitle'] => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->_add_control(
            'subtitle_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['box_subtitle'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_subtitle'],
            ]
        );

        $this->_add_control(
            'heading_description',
            [
                'label' => esc_html__( 'Description', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_desc'] => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->_add_control(
            'description_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['box_desc'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_desc'],
            ]
        );


        $this->_add_responsive_control(
            'margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->_add_control(
            'watermark',
            [
                'label' => esc_html__( 'Water Mark', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_water_mark' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'watermark_color',
            [
                'label' => esc_html__( 'Water Mark Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['water_box_icon'] => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_water_mark' => 'yes',
                ]
            ]
        );

        $this->_add_responsive_control(
            'watermark_font_size',
            [
                'label' => esc_html__( 'Water Mark Font Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['water_box_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_water_mark' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'order_section',
            [
                'label' => esc_html__( 'Content Order', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_order',
            array(
                'label'   => esc_html__( 'Title Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['box_title'] => 'order: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'subtitle_order',
            array(
                'label'   => esc_html__( 'SubTitle Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['box_subtitle'] => 'order: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'desc_order',
            array(
                'label'   => esc_html__( 'Description Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['box_desc'] => 'order: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'divider_order',
            array(
                'label'   => esc_html__( 'Divider Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 0,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['box_divider'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->_end_controls_section();

        // Icon style
        $this->_start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon/Image', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_header_icon!' => 'none'
                ]
            ]
        );

        $this->_start_controls_tabs( 'section_icon_colors' );

        $this->_start_controls_tab(
            'icon_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'icon_primary_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_header_icon' => 'icon'
                ]
            ]
        );

	    $this->_add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'icon_secondary_color_normal',
			    'label' => esc_html__( 'Background', 'lastudio-kit' ),
			    'types' => [ 'classic', 'gradient' ],
			    'selector' => '{{WRAPPER}} ' . $css_scheme['header_box_icon'],
		    ]
	    );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['header_box_icon'],
            ]
        );

        $this->_add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__( 'Wrap Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'icon__main_radius',
            [
                'label' => esc_html__( 'Icon/Image Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['main_icon'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow_normal_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['header_box_icon'],
            ]
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'icon_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'hover_primary_color',
            [
                'label' => esc_html__( 'Icon Hover Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['header_box_icon'] => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_header_icon' => 'icon'
                ]
            ]
        );

	    $this->_add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'hover_background_color',
			    'label' => esc_html__( 'Background', 'lastudio-kit' ),
			    'types' => [ 'classic', 'gradient' ],
			    'selector' => '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['header_box_icon'],
		    ]
	    );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border_icon_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['header_box_icon'],
            ]
        );

        $this->_add_control(
            'icons_hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'lastudio-kit' ),
                'type' =>   Controls_Manager::HOVER_ANIMATION,
            ]
        );
        $this->_add_responsive_control(
            'icons_hover_border_radius',
            [
                'label' => esc_html__( 'Wrap Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['header_box_icon'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'icon__main_radius_hover',
            [
                'label' => esc_html__( 'Icon/Image Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['main_icon'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow_group',
                'selector' => '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['header_box_icon'],
            ]
        );
        $this->_end_controls_tab();

        $this->_end_controls_tabs();

	    $this->_add_control(
		    'icon_box__style',
		    array(
			    'label'     => esc_html__( 'Styles', 'lastudio-kit' ),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    )
	    );

        $this->_add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_header_icon' => 'icon'
                ]
            ]
        );

        $this->_add_responsive_control(
            'icon_space',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->_add_responsive_control(
            'rotate',
            [
                'label' => esc_html__( 'Rotate', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range'			 => [
                    'deg' => [
                        'min'	 => 0,
                        'max'	 => 360,
                        'step'	 => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );

        $this->_add_responsive_control(
            'icon_height',
            [
                'label' => esc_html__( 'Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->_add_responsive_control(
            'icon_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'width: {{SIZE}}{{UNIT}};',
                ],


            ]
        );

        $this->_add_responsive_control(
            'icon_line_height',
            [
                'label' => esc_html__( 'Line Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => 'line-height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->_add_responsive_control(
            'icon_vertical_align',
            [
                'label' => esc_html__( 'Vertical Position ', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['header_box_icon'] => ' -webkit-transform: translateY({{SIZE}}{{UNIT}}); -ms-transform: translateY({{SIZE}}{{UNIT}}); transform: translateY({{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'icon_position!' => 'top'
                ]

            ]
        );
        $this->_end_controls_section();

        // start Button style
        $this->_start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Button', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_btn' => 'yes',
                ]
            ]
        );

        $this->_add_responsive_control(
            'button_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button_wrap'] => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'text_padding',
            [
                'label' =>esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_responsive_control(
            'text_margin',
            [
                'label' =>esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_group',
                'label' =>esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );
        $this->_add_responsive_control(
            'btn_icon_font_size',
            array(
                'label'      => esc_html__( 'Icon Font Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', 'rem',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] . ' svg'=> 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => [
                    'icons__switch'   => 'yes',
                ],
            )
        );

        $this->_add_responsive_control(
            'btn_icon_indent',
            [
                'label' => __( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'btn_icon_vert_spacing',
            [
                'label' => __( 'Vertical Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'button_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_background_group',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border_color_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );
        $this->_add_responsive_control(
            'btn_border_radius',
            [
                'label' =>esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'btn_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox__button_wrapper .elementor-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'btn_hover_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox__button_wrapper .elementor-button:hover .elementor-button-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_background_hover_group',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .lakit-iconbox__button_wrapper .elementor-button:hover',
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border_hv_color_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-iconbox__button_wrapper .elementor-button:hover',
            ]
        );
        $this->_add_responsive_control(
            'btn_hover_border_radius',
            [
                'label' =>esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox__button_wrapper .elementor-button:hover' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow_hover_group',
                'selector' => '{{WRAPPER}} .lakit-iconbox__button_wrapper .elementor-button:hover',
            ]
        );

        $this->_add_control(
            'button_hover_animation',
            [
                'label' => esc_html__( 'Animation', 'lastudio-kit' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();


        $this->_end_controls_section();

        // Background Overlay style
        $this->_start_controls_section(
            'section_bg_overlay_style',
            [
                'label' => esc_html__( 'Background Overlay ', 'lastudio-kit' ),
                'tab' => controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_control(
            'show_image_overlay',
            [
                'label' => esc_html__( 'Enable Image Overlay', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes'
            ]
        );

        $this->_add_control(
            'show_image',
            [
                'label' => esc_html__( 'Choose Image', 'lastudio-kit' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'show_image_overlay' => 'yes',
                ]
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_overlay_color',
                'label' => esc_html__( 'Background Overlay Color', 'lastudio-kit' ),
                'types' => [ 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .lakit-iconbox.image-active::before',
                'condition' => [
                    'show_image_overlay' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'show_overlay',
            [
                'label' => esc_html__( 'Enable Overlay', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );

        $this->_start_controls_tabs('style_bg_overlay_tab', [
            'condition' => [
                'show_overlay' => 'yes',
            ],
        ]);
        $this->_start_controls_tab(
            'section_bg_ov_style_n_tab',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' )
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bg_overlay_color',
                'label' => esc_html__( 'Background Overlay Color', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .lakit-iconbox.gradient-active::before',
            ]
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'section_bg_ov_style_n_hv_tab',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' )
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bg_ovelry_color_hv',
                'label' => esc_html__( 'Background Overlay Color', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .lakit-iconbox.gradient-active:hover::before',
            ]
        );

        $this->_end_controls_tab();
        $this->_end_controls_tabs();
        $this->_add_control(
            'section_bg_hover_color_direction',
            [
                'label' => esc_html__( 'Hover Direction', 'lastudio-kit' ),
                'type' =>   Controls_Manager::CHOOSE,
                'options' => [
                    'hover_none' => [
                        'title' => esc_html__( 'None', 'lastudio-kit' ),
                        'icon' => 'eicon-ban',
                    ],
                    'hover_from_left' => [
                        'title' => esc_html__( 'From Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'hover_from_top' => [
                        'title' => esc_html__( 'From Top', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'hover_from_right' => [
                        'title' => esc_html__( 'From Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'hover_from_bottom' => [
                        'title' => esc_html__( 'From Bottom', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-top',
                    ],

                ],
                'default' => 'hover_from_left',
                'toggle' => true,
                'condition'  => [
                    'show_overlay' => 'yes'
                ]
            ]
        );
        $this->_end_controls_section();

        /** Badge section **/
        $this->_start_controls_section(
            'badge_style_tab',
            [
                'label' => esc_html__( 'Badge', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'badge_control' => 'yes',
                    'badge_title!' => ''
                ]
            ]
        );

        $this->_add_control(
            'enable_badge_text_outline',
            [
                'label' => esc_html__( 'Enable Text Outline', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'badge_text_outline',
            array(
                'label'      => esc_html__( 'Stroke Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['badge'] => '    -webkit-text-fill-color: transparent;-webkit-text-stroke-width: {{SIZE}}{{UNIT}};-webkit-text-stroke-color: currentColor;',
                ),
                'condition' => [
	                'enable_badge_text_outline' => 'yes'
                ]
            )
        );

	    $this->_add_responsive_control(
		    'badge_width',
		    array(
			    'label'      => esc_html__( 'Width', 'lastudio-kit' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => array(
				    'px'
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['badge'] => 'width: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );

	    $this->_add_responsive_control(
		    'badge_height',
		    array(
			    'label'      => esc_html__( 'Height', 'lastudio-kit' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => array(
				    'px'
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['badge'] => 'height: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );

        $this->_add_responsive_control(
            'badge_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['badge'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'badge_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['badge'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'badge_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['badge'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_control(
            'badge_text_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['badge'] => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->_add_control(
            'badge_text_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-iconbox:hover ' . $css_scheme['badge'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'badge_background',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['badge'],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'badge_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['badge'],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'label' => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['badge'],
            ]
        );

        $this->_end_controls_section();

    }

    /**
     * [render description]
     * @return [type] [description]
     */
    protected function render() {
        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    public function get_main_image( $main_format = '%s' ) {

        if( $this->get_settings_for_display('enable_header_icon') !== 'image' ){
            return;
        }

        $image = $this->get_settings_for_display( 'header_image' );

        if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
            return;
        }

        if ( empty( $image['id'] ) ) {
	        $format = apply_filters( 'lastudio-kit/iconbox/main-image-format', '<img src="%1$s" alt="%2$s" class="lakit-iconbox__main_icon lakit-iconbox__main_img" loading="lazy">' );
            $main_image = sprintf( $format, $image['url'], '' );
            return sprintf( $main_format, $main_image );
        }
        else{
	        $main_image = wp_get_attachment_image( $image['id'], 'full', false, [ 'class' => 'lakit-iconbox__main_icon lakit-iconbox__main_img' ] );
	        return sprintf( $main_format, $main_image );
        }
    }

    public function get_main_icon( $main_format = '%s' ){
        if( $this->get_settings_for_display('enable_header_icon') !== 'icon' ){
            return;
        }
        return $this->_get_icon( 'header_icons', $main_format, 'lakit-iconbox__icon lakit-iconbox__main_icon' );
    }

    public function get_button_icon( $main_format = '%s' ){
        return $this->_get_icon( 'btn_icon', $main_format, 'lakit-iconbox__btn_icon' );
    }

    public function get_water_icon( $main_format = '%s' ){
        if( !filter_var($this->get_settings_for_display('enable_water_mark'), FILTER_VALIDATE_BOOLEAN) ){
            return;
        }
        return $this->_get_icon( 'water_mark_icons', $main_format, 'lakit-iconbox__water_icon' );
    }

    public function get_overlay_image( $main_format = '%s' ) {

        if( !filter_var($this->get_settings_for_display('show_image_overlay'), FILTER_VALIDATE_BOOLEAN) ){
            return;
        }

        $image = $this->get_settings_for_display( 'show_image' );

        if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
            return;
        }

        $format = apply_filters( 'lastudio-kit/iconbox/overlay-image-format', '<img src="%1$s" alt="%2$s" class="lakit-iconbox__overlay_img" loading="lazy">' );

        if ( empty( $image['id'] ) ) {
            $main_image = sprintf( $format, $image['url'], '' );
            return sprintf( $main_format, $main_image );
        }

        $size = 'full';

        $image_url = wp_get_attachment_image_url( $image['id'], $size );
        $alt       = esc_attr( Control_Media::get_image_alt( $image ) );

        $main_image = sprintf( $format, $image_url, $alt );
        return sprintf( $main_format, $main_image );
    }

    public function get_badge(){
        $badge_control = $this->get_settings_for_display('badge_control');
        $badge_title = $this->get_settings_for_display('badge_title');
        $badge_position = $this->get_settings_for_display('badge_position');
        if(filter_var($badge_control, FILTER_VALIDATE_BOOLEAN) && !empty($badge_title)){
            return sprintf('<div class="lakit-iconbox__badge lakit_position_%2$s"><span class="lakit__badge">%1$s</span></div>', esc_html($badge_title), esc_attr($badge_position));
        }
        return '';
    }

}