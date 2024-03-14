<?php
/**
 * Class: LaStudioKit_Image_Box
 * Name: Image Box
 * Slug: lakit-image-box
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Icon Box Widget
 */
class LaStudioKit_Image_Box extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if ( ! lastudio_kit()->is_optimized_css_mode() ) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/imagebox.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/imagebox.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/imagebox.min.css' );
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
        return 'lakit-image-box';
    }

    protected function get_widget_title() {
        return esc_html__( 'Image Box', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-image-box';
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'lastudio-kit/imagebox/css-schema',
            array(
                'box'                   => '.lakit-imagebox',
                'box_header'            => '.lakit-imagebox__header',
                'box_image'       		=> '.lakit-imagebox__main_img',
                'box_body'              => '.lakit-imagebox__body',
                'box_body_inner'        => '.lakit-imagebox__body_inner',
                'box_title'             => '.lakit-imagebox__title',
                'box_title_icon'        => '.lakit-imagebox__title_icon',
                'box_desc'             	=> '.lakit-imagebox__desc',
                'button_wrap'           => '.lakit-iconbox__button_wrapper',
                'button'           		=> '.elementor-button',
                'button_icon'           => '.elementor-button .elementor-button-icon',
                'box_icon'              => '.lakit-imagebox__top_icon',
                'box_icon_inner'        => '.lakit-imagebox__top_icon_inner',
            )
        );

        // start content section for set Image
        $this->_start_controls_section(
            'box_section_infoboxwithimage',
            [
                'label' => esc_html__( 'Image', 'lastudio-kit' ),
            ]
        );

        // Image insert
        $this->_add_control(
            'box_image',
            [
                'label' => esc_html__( 'Choose Image', 'lastudio-kit' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'box_thumbnail',
                'default' => 'full',
                'separator' => 'none',
            ]
        );

        //  simple  style

        $this->_add_control(
            'box_style_simple',
            [
                'label' => esc_html__( 'Content Area', 'lastudio-kit' ),
                'type' =>  Controls_Manager::SELECT,
                'default' => 'simple-card',
                'options' => [
                    'simple-card'  => esc_html__( 'Simple', 'lastudio-kit' ),
                    'style-modern' => esc_html__( 'Classic Curves', 'lastudio-kit' ),
                    'floating-style' => esc_html__( 'Floating box', 'lastudio-kit' ),
                    'hover-border-bottom' => esc_html__( 'Hover Border', 'lastudio-kit' ),
                    'style-sideline' => esc_html__( 'Side Line', 'lastudio-kit' ),
                    'shadow-line' => esc_html__( 'Shadow line', 'lastudio-kit' ),
                ],
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
                    '{{WRAPPER}}.lakit-equal-height-enable .lakit-imagebox' => 'height: 100%;',
                ],
                'condition' => [
                    'box_style_simple!'   => 'floating-style'
                ]
            ]
        );

        $this->_add_control(
            'box_enable_link',
            [
                'label' => esc_html__( 'Enable Link', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );

        $this->_add_control(
            'box_website_link',
            [
                'label' => esc_html__( 'Link', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'show_external' => true,
                'condition' => [
                    'box_enable_link' => 'yes'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        // end content section for set Image
        $this->_end_controls_section();

        //Body Icon style
        $this->_start_controls_section(
            'section_style_body_icon',
            [
                'label' => esc_html__( 'Icon', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_top_icons__switch' => 'yes'
                ]
            ]
        );

        $this->_add_responsive_control(
            'body_icon_size',
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
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->_add_responsive_control(
            'body_icon_space',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'body_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->_add_responsive_control(
            'body_icon_rotate',
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
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );

        $this->_add_responsive_control(
            'body_icon_height',
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
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->_add_responsive_control(
            'body_icon_width',
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
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'width: {{SIZE}}{{UNIT}};',
                ],


            ]
        );

        $this->_add_responsive_control(
            'body_icon_line_height',
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
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'line-height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->_add_responsive_control(
            'body_icon_vertical_align',
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
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => '-webkit-transform: translateY({{SIZE}}{{UNIT}}); -ms-transform: translateY({{SIZE}}{{UNIT}}); transform: translateY({{SIZE}}{{UNIT}});',
                ]
            ]
        );

        $this->_start_controls_tabs( 'section_body_icon_tabs' );

        $this->_start_controls_tab(
            'section_body_icon__normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'body_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->_add_control(
            'body_icon_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'body_icon_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_icon_inner'],
            ]
        );

        $this->_add_responsive_control(
            'body_icon_radius',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_icon_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'body_icon_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_icon_inner'],
            ]
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'section_body_icon__hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'body_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_icon_inner'] => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->_add_control(
            'body_icon_bgcolor_hover',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_icon_inner'] => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'body_icon_border_hover',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_icon_inner'],
            ]
        );

        $this->_add_control(
            'body_icon_hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'lastudio-kit' ),
                'type' =>   Controls_Manager::HOVER_ANIMATION,
            ]
        );
        $this->_add_responsive_control(
            'body_icon_radius_hover',
            [
                'label' => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_icon_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'body_icon_shadow_hover',
                'selector' => '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_icon_inner'],
            ]
        );
        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();
        // start content section for image title and sub title
        $this->_start_controls_section(
            'box_section_for_image_title',
            [
                'label' => esc_html__( 'Body', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'box_top_icons__switch',
            [
                'label' => esc_html__('Add Icon? ', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' =>esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' =>esc_html__( 'No', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'box_top_icons__pos',
            [
                'label' => esc_html__( 'Icon Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top'       =>    esc_html__( 'Top', 'lastudio-kit' ),
                    'bottom'    =>    esc_html__( 'Bottom', 'lastudio-kit' ),
                ],
                'condition' => [
                    'box_top_icons__switch'  => 'yes'
                ]
            ]
        );


        $this->_add_advanced_icon_control(
            'box_top_icons',
            [
                'label' => esc_html__( 'Top Icon', 'lastudio-kit' ),
                'condition' => [
                    'box_top_icons__switch'  => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'box_title_text',
            [
                'label' => esc_html__( 'Title ', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'This is the heading', 'lastudio-kit' ),
                'placeholder' => esc_html__( 'Enter your title', 'lastudio-kit' ),
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'box_front_title_icons__switch',
            [
                'label' => esc_html__('Add title icon? ', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' =>esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' =>esc_html__( 'No', 'lastudio-kit' ),
            ]
        );

        $this->_add_advanced_icon_control(
            'box_front_title_icons',
            [
                'label' => esc_html__( 'Title Icon', 'lastudio-kit' ),
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );

        $this->_add_control(
            'box_front_title_icon_position',
            [
                'label' => esc_html__( 'Title Icon Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' =>esc_html__( 'Before', 'lastudio-kit' ),
                    'right' =>esc_html__( 'After', 'lastudio-kit' ),
                ],
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );

        // title tag
        $this->_add_control(
            'box_title_size',
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
            ]
        );

        $this->_add_control(
            'box_description_text',
            [
                'label' => esc_html__( 'Description', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Click edit  to change this text. Lorem ipsum dolor sit amet, cctetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'lastudio-kit' ),
                'placeholder' => esc_html__( 'Enter your description', 'lastudio-kit' ),
                'separator' => 'none',
                'rows' => 10,
                'show_label' => false,
            ]
        );

        // Text aliment

        $this->_add_control(
            'box_content_text_align',
            [
                'label' => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
            ]
        );

        // end content section for image title and sub title
        $this->_end_controls_section();

        // start content section for button
        //  Section Button

        $this->_start_controls_section(
            'box_section_button',
            [
                'label' => esc_html__( 'Button', 'lastudio-kit' ),
            ]
        );
        $this->_add_control(
            'box_enable_btn',
            [
                'label' => esc_html__( 'Enable Button', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->_add_control(
            'box_btn_text',
            [
                'label' =>esc_html__( 'Label', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' =>esc_html__( 'Learn more ', 'lastudio-kit' ),
                'placeholder' =>esc_html__( 'Learn more ', 'lastudio-kit' ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'box_enable_btn' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'box_btn_url',
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
                    'box_enable_btn' => 'yes',
                ]
            ]
        );

        $this->_add_control(
            'box_icons__switch',
            [
                'label' => esc_html__('Add icon? ', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' =>esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' =>esc_html__( 'No', 'lastudio-kit' ),
                'condition' => [
                    'box_enable_btn' => 'yes',
                ]
            ]
        );
        $this->_add_advanced_icon_control(
            'box_icons',
            [
                'label' =>esc_html__( 'Icon', 'lastudio-kit' ),
                'label_block' => true,
                'condition' => [
                    'box_enable_btn' => 'yes',
                    'box_icons__switch' => 'yes'
                ]
            ]
        );
        $this->_add_control(
            'box_icon_align',
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
                    'box_icons__switch' => 'yes',
                    'box_enable_btn' => 'yes',
                ],
            ]
        );
        // end content section for button
        $this->_end_controls_section();

        // start style section here


        // start floating box style
        $this->_start_controls_section(
            'box_image_floating_box',
            [
                'label' => esc_html__( 'Floating Style', 'lastudio-kit' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'floating-style',
                ]
            ]
        );

        $this->_start_controls_tabs(
            'box_image_floating_box_heights'
        );

        $this->_start_controls_tab(
            'box_image_floating_box_normal_height_tab',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_height',
            [
                'label' => esc_html__( 'Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_image_floating_box_hover_height_tab',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_hover_height',
            [
                'label' => esc_html__( 'Hover Height', 'lastudio-kit' ),
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
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_body'] => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'box_image_floating_box_tab_separetor',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_margin_top',
            [
                'label' => esc_html__( 'Margin Top', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_floating_box_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_end_controls_section();

        // start classic curves style
        $this->_start_controls_section(
            'box_image_classic_curves',
            [
                'label' => esc_html__( 'Classic Curves', 'lastudio-kit' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'style-modern',
                ]
            ]
        );

        $this->_add_responsive_control(
            'box_image_classic_curves_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_classic_curves_margin',
            [
                'label' => esc_html__( 'Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_end_controls_section();

        // start border bottom hover style
        $this->_start_controls_section(
            'box_border_bottom_hover',
            [
                'label' => esc_html__( 'Hover Border Bottom', 'lastudio-kit' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_add_responsive_control(
            'box_border_hover_height',
            [
                'label' => esc_html__( 'Border Bottom Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_border_hover_background',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before',
                'condition' => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_add_control(
            'box_border_hover_background_direction',
            [
                'label' => esc_html__( 'Hover Direction', 'lastudio-kit' ),
                'type' =>   Controls_Manager::CHOOSE,
                'options' => [
                    'hover_from_left' => [
                        'title' => esc_html__( 'From Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'hover_from_center' => [
                        'title' => esc_html__( 'From Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'hover_from_right' => [
                        'title' => esc_html__( 'From Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                ],
                'default' => 'hover_from_right',
                'toggle' => true,
                'condition'  => [
                    'box_style_simple' => 'hover-border-bottom',
                ]
            ]
        );

        $this->_end_controls_section();

        // start side line style
        $this->_start_controls_section(
            'box_image_side_line',
            [
                'label' => esc_html__( 'Side Line', 'lastudio-kit' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'style-sideline',
                ]
            ]
        );

        $this->_add_responsive_control(
            'box_image_side_line_border_width',
            [
                'label' => esc_html__( 'Border Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body_inner'] => 'border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_side_line_border_type',
            [
                'label' => esc_html__( 'Border Type', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'none' =>esc_html__( 'None', 'lastudio-kit' ),
                    'solid' =>esc_html__( 'Solid', 'lastudio-kit' ),
                    'double' =>esc_html__( 'Double', 'lastudio-kit' ),
                    'dotted' =>esc_html__( 'Dotted', 'lastudio-kit' ),
                    'dashed' =>esc_html__( 'Dashed', 'lastudio-kit' ),

                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body_inner'] => 'border-style: {{VALUE}}',
                ],
            ]
        );

        $this->_start_controls_tabs(
            'side_line_tabs'
        );
        $this->_start_controls_tab(
            'side_line_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );
        $this->_add_responsive_control(
            'box_image_side_line_border',
            [
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body_inner'] => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'side_line_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );
        $this->_add_responsive_control(
            'side_line_hover_color',
            [
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_body_inner'] => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_end_controls_section();

        // start line shadow style
        $this->_start_controls_section(
            'box_image_shadow_line',
            [
                'label' => esc_html__( 'Shadow Line', 'lastudio-kit' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_style_simple' => 'shadow-line',
                ]
            ]
        );

        $this->_start_controls_tabs(
            'box_image_shadow_line_tabs'
        );

        $this->_start_controls_tab(
            'box_image_shadow_line_left_tab',
            [
                'label' => esc_html__( 'Left Line', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_shadow_left_line_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow_left_line_shadow',
                'label' => esc_html__( 'Box Shadow', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before',
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_image_shadow_left_line_background',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':before',
            ]
        );

        $this->_end_controls_tab();

        // right line
        $this->_start_controls_tab(
            'box_image_shadow_line_right_tab',
            [
                'label' => esc_html__( 'Right Line', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_image_shadow_right_line_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ':after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow_right_line_shadow',
                'label' => esc_html__( 'Box Shadow', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'] . ':after',
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_image_shadow_right_line_background',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' =>'{{WRAPPER}} ' . $css_scheme['box_body'] . ':after',
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        // start image section style
        $this->_start_controls_section(
            'box_image_section',
            [
                'label' => esc_html__( 'Image', 'lastudio-kit' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_control(
            'box_image_height__switch',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
                'prefix_class' => 'active-object-fit active-object-fit-',
            )
        );

        $this->_add_responsive_control(
            'box_image_height',
            array(
                'label'      => esc_html__( 'Image Height', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                    )
                ),
                'size_units' => [ 'px', '%', 'vh', 'vw' ],
                'default'    => [
                    'size' => 300,
                    'unit' => 'px'
                ],
                'selectors'  => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ],
                'condition'  => [
                    'box_image_height__switch!' => ''
                ]
            )
        );

        $this->_add_responsive_control(
            'box_image_width',
            [
                'label' => esc_html__( 'Image Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'box_image_height__switch!' => 'true'
                ]
            ]
        );

        $this->_start_controls_tabs(
            'box_style_tabs_image'
        );

        $this->_start_controls_tab(
            'box_style_normal_tab_image',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_border_radius',
            [
                'label' => esc_html__( 'Border radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_image_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_header'],
            ]
        );

        $this->_add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_opacity',
            [
                'label' => esc_html__( 'Image opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => .01,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_image'] => 'opacity: {{SIZE}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow',
                'label' => esc_html__( 'Box Shadow', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_header'],
            ]
        );

        $this->_add_control(
            'box_image_overlay_heading1',
            array(
                'label'     => esc_html__( 'Overlay', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'box_image_overlay_bg',
            [
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] . ':before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_image_overlay_border',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_header'] . ':before',
            ]
        );

        $this->_add_responsive_control(
            'box_image_overlay_pos',
            [
                'label' => esc_html__( 'Position', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] . ':before' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_responsive_control(
            'box_image_overlay_radius',
            [
                'label' => esc_html__( 'Border radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_header'] . ':before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_style_hover_tab_image',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_border_radius_hover',
            [
                'label' => esc_html__( 'Border radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_image_border_hover',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'],
            ]
        );

        $this->_add_responsive_control(
            'box_padding_hover',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'box_image_opacity_hover',
            [
                'label' => esc_html__( 'Image opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => .01,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_image'] => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_image_shadow_hover',
                'label' => esc_html__( 'Box Shadow', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'],
            ]
        );

        $this->_add_responsive_control(
            'box_image_scale_on_hover',
            [
                'label' => esc_html__( 'Image Scale on Hover', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 2,
                        'step' => .1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1.1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_image'] => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->_add_control(
            'box_image_overlay_heading2',
            array(
                'label'     => esc_html__( 'Overlay', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'box_image_overlay_bg_hover',
            [
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'] . ':before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_image_overlay_border_hover',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'] . ':before',
            ]
        );

        $this->_add_responsive_control(
            'box_image_overlay_pos_hover',
            [
                'label' => esc_html__( 'Position', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'] . ':before' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_responsive_control(
            'box_image_overlay_radius_hover',
            [
                'label' => esc_html__( 'Border radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_header'] . ':before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();
        //end image section style

        // start body style section
        $this->_start_controls_section(
            'box_style_body_section',
            [
                'label' => esc_html__( 'Body', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_control(
            'imagebox_general_border_heading_title',
            [
                'label' => esc_html__( 'General', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'imagebox_container_border_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'],
            ]
        );

        $this->_add_responsive_control(
            'body_radius',
            [
                'label'         => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%', 'em' ],
                'selectors'     => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] . ',{{WRAPPER}} ' . $css_scheme['box_body'] . ':before,{{WRAPPER}} ' . $css_scheme['box_body'] . ':after'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'imagebox_container_background',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'],
            ]
        );

        $this->_add_responsive_control(
            'imagebox_container_spacing',
            [
                'label' => esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_body'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_body'],
            ]
        );

        // title
        $this->_add_control(
            'imagebox_title_border_heading_title',
            [
                'label' => esc_html__( 'Title', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_responsive_control(
            'box_title_bottom_space',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => 'true',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'box_title_typography',
                'label' => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_title'],
            ]
        );

        $this->_start_controls_tabs('box_style_heading_tabs');

        $this->_start_controls_tab(
            'box_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color_hover',
            [
                'label' => esc_html__( 'Color (Hover)', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_title'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        // Title icon
        $this->_add_control(
            'imagebox_title_icon__divider',
            [
                'label' => esc_html__( 'Title Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );
        $this->_add_control(
            'box_image_floating_box_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title_icon'] => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );
        $this->_add_responsive_control(
            'box_image_floating_box_icon_font_size',
            [
                'label' => esc_html__( 'Icon Font Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_title_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );


        $this->_add_responsive_control(
            'box_image_floating_box_icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] => '--lakit-imagebox-icon-spacing: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );
        $this->_add_responsive_control(
            'box_image_floating_box_icon_vspacing',
            [
                'label' => esc_html__( 'Icon Vertical Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box'] => '--lakit-imagebox-icon-vspacing: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'box_front_title_icons__switch'  => 'yes'
                ]
            ]
        );


        // sub Description
        $this->_add_control(
            'imagebox_description_border_heading_title',
            [
                'label' => esc_html__( 'Description', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->_add_responsive_control(
            'box_title_bottom_space_description',
            [
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '14',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => 'true',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'box_title_typography_description',
                'label' => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['box_desc'],
            ]
        );

        $this->_start_controls_tabs('box_style_description_tabs');

        $this->_start_controls_tab(
            'box_style_normal_tab_description',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color_description',
            [
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['box_desc'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_style_hover_tab_description',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_heading_color_hover_description',
            [
                'label' => esc_html__( 'Color (Hover)', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-imagebox:hover ' . $css_scheme['box_desc'] => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();

        // start style section for the button
        // Button

        $this->_start_controls_section(
            'box_section_style',
            [
                'label' => esc_html__( 'Button', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'box_enable_btn' => 'yes',
                ]
            ]
        );
        $this->_add_control(
            'box_btn_fullwidth',
            [
                'label' => esc_html__( 'Enable Fullwidth Button', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'width: 100%',
                ]
            ]
        );
        $this->_add_responsive_control(
            'box_btn_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'condition' => array(
                    'box_btn_fullwidth' => 'yes'
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'justify-content: {{VALUE}};',
                ),
            )
        );
        $this->_add_responsive_control(
            'box_text_padding',
            [
                'label' =>esc_html__( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'box_typography_group',
                'label' =>esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );
        $this->_add_responsive_control(
            'box_btn_icon_font_size',
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
                    '{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => [
                    'box_icons__switch' => 'yes',
                ]
            )
        );

        $this->_add_responsive_control(
            'btn_icon__gap',
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
                'condition' => [
                    'box_icons__switch' => 'yes',
                ]
            ]
        );

        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'box_tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_btn_background_group',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_button_border_color_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );
        $this->_add_responsive_control(
            'box_btn_border_radius',
            [
                'label' =>esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '' ,
                    'left' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'box_tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_responsive_control(
            'box_btn_hover_color',
            [
                'label' => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_btn_background_hover_group',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_button_border_hv_color_group',
                'label' => esc_html__( 'Border', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            ]
        );
        $this->_add_responsive_control(
            'box_btn_hover_border_radius',
            [
                'label' =>esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px'],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['button'] . ':hover' =>  'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_button_box_shadow_hover_group',
                'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

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

    public function get_main_image( $main_format = '%s', $echo = false ) {

        $settings = $this->get_settings_for_display();

        $main_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'box_thumbnail', 'box_image' );

        if(empty($main_image)){
            return;
        }

        if(false === strpos($main_image, 'class="')){
            $main_image = str_replace('<img', '<img class=""', $main_image);
        }

        $main_image = str_replace('class="', 'class="lakit-imagebox__main_img ', $main_image);

        if(!$echo){
            return sprintf( $main_format, $main_image );
        }
        else{
            echo sprintf( $main_format, $main_image );
        }
    }

    public function get_main_icon( $main_format = '<span class="lakit-imagebox__top_icon">%s</span>' ){
        if( !filter_var($this->get_settings_for_display('box_top_icons__switch'), FILTER_VALIDATE_BOOLEAN) ){
            return;
        }
        return $this->_get_icon( 'box_top_icons', $main_format );
    }

    public function get_title_icon( $main_format = '<span class="lakit-imagebox__title_icon">%s</span>' ){
        if( !filter_var($this->get_settings_for_display('box_front_title_icons__switch'), FILTER_VALIDATE_BOOLEAN) ){
            return;
        }
        return $this->_get_icon( 'box_front_title_icons', $main_format );
    }

    public function get_button_icon( $main_format = '%s' ){
        return $this->_get_icon( 'box_icons', $main_format, 'lakit-imagebox__btn_icon' );
    }

}