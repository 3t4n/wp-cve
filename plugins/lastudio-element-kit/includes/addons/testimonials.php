<?php
/**
 * Class: LaStudioKit_Testimonials
 * Name: Testimonials
 * Slug: lakit-testimonials
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}



/**
 * Testimonials Widget
 */
class LaStudioKit_Testimonials extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/testimonials.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/testimonials.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/testimonials.min.css' );
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
        return 'lakit-testimonials';
    }

    protected function get_widget_title() {
        return esc_html__( 'Testimonials', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }

	public function get_keywords() {
		return [ 'testimonial', 'happycustomer', 'review'];
	}

    protected function register_controls() {
        $css_scheme = apply_filters(
            'lastudio-kit/testimonials/css-schema',
            array(
                'item'       => '.lakit-testimonials__item',
                'item_inner' => '.lakit-testimonials__item-inner',
                'image'      => '.lakit-testimonials__figure',
                'image_tag'  => '.lakit-testimonials__tag-img',
                'content'    => '.lakit-testimonials__content',
                'icon'       => '.lakit-testimonials__icon',
                'icon_inner' => '.lakit-testimonials__icon-inner',
                'title'      => '.lakit-testimonials__title',
                'comment'    => '.lakit-testimonials__comment',
                'name'       => '.lakit-testimonials__name',
                'position'   => '.lakit-testimonials__position',
                'date'       => '.lakit-testimonials__date',
                'star'       => '.lakit-testimonials__rating',
            )
        );

        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-kit' ),
            )
        );

        $preset_type = apply_filters(
            'lastudio-kit/testimonials/control/preset',
            array(
                'type-1' => esc_html__( 'Type 1', 'lastudio-kit' ),
                'type-2' => esc_html__( 'Type 2', 'lastudio-kit' ),
                'type-3' => esc_html__( 'Type 3', 'lastudio-kit' ),
                'type-4' => esc_html__( 'Type 4', 'lastudio-kit' ),
                'type-5' => esc_html__( 'Type 5', 'lastudio-kit' ),
                'type-6' => esc_html__( 'Type 6', 'lastudio-kit' ),
                'type-7' => esc_html__( 'Type 7', 'lastudio-kit' ),
                'type-8' => esc_html__( 'Type 8', 'lastudio-kit' ),
                'type-9' => esc_html__( 'Type 9', 'lastudio-kit' ),
                'type-10' => esc_html__( 'Type 10', 'lastudio-kit' ),
                'type-11' => esc_html__( 'Type 11', 'lastudio-kit' ),
                'type-12' => esc_html__( 'Type 12', 'lastudio-kit' ),
                'type-13' => esc_html__( 'Type 13', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'preset',
            array(
                'label'   => esc_html__( 'Preset', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'type-1',
                'options' => $preset_type
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'   => esc_html__( 'Columns', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 3,
                'options' => lastudio_kit_helper()->get_select_range( 6 )
            )
        );

        $this->add_control(
            'use_title_field',
            array(
                'label'        => esc_html__( 'Use Title field', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_items_data',
            array(
                'label' => esc_html__( 'Items', 'lastudio-kit' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio-kit' ),
                'type'    => Controls_Manager::MEDIA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_comment',
            array(
                'label'   => esc_html__( 'Comment', 'lastudio-kit' ),
                'type'    => Controls_Manager::WYSIWYG,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_name',
            array(
                'label'   => esc_html__( 'Name', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_position',
            array(
                'label'   => esc_html_x( 'Position/Role', 'Position at work', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_rating',
            array(
                'label'     => esc_html__( 'Rating', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '10',
                'options'   => lastudio_kit_helper()->get_select_range( 10 ),
                'dynamic' => array( 'active' => true )
            )
        );

	    $repeater->add_control(
		    'el_class',
		    array(
			    'label'   => esc_html_x( 'CSS Classes', 'Item CSS Classes', 'lastudio-kit' ),
			    'type'    => Controls_Manager::TEXT,
			    'dynamic' => array( 'active' => true ),
		    )
	    );
	    $repeater->add_control(
		    'bg_color',
		    [
			    'label' => __( 'Background Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => '--e_i_bg: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'name_color',
		    [
			    'label' => __( 'Name Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['name'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );

		$repeater->add_control(
		    'role_color',
		    [
			    'label' => __( 'Role Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['position'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );

        $this->add_control(
            'item_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'lastudio-kit' ),
                        'item_name'     => esc_html__( 'Mary Scott', 'lastudio-kit' ),
                        'item_position' => esc_html__( 'Founder & CEO', 'lastudio-kit' ),
                        'item_rating'     => 10,
                    ),
                    array(
                        'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'lastudio-kit' ),
                        'item_name'     => esc_html__( 'John Borthwick', 'lastudio-kit' ),
                        'item_position' => esc_html__( 'Founder & CEO', 'lastudio-kit' ),
                        'item_rating'     => 10,
                    )
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->end_controls_section();

        $this->register_carousel_section( [  ], 'columns');


        $this->start_controls_section(
            'section_item_style',
            array(
                'label'      => esc_html__( 'Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

	    $this->_add_responsive_control(
		    'item_space',
		    array(
			    'label'       => esc_html__( 'Column Padding', 'lastudio-kit' ),
			    'type'        => Controls_Manager::DIMENSIONS,
			    'selectors'   => array(
				    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				    '{{WRAPPER}} '                         => '--lakit-carousel-item-top-space: {{TOP}}{{UNIT}}; --lakit-carousel-item-right-space: {{RIGHT}}{{UNIT}};--lakit-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-carousel-item-left-space: {{LEFT}}{{UNIT}};--lakit-gcol-top-space: {{TOP}}{{UNIT}}; --lakit-gcol-right-space: {{RIGHT}}{{UNIT}};--lakit-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-gcol-left-space: {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'item_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_inner'],
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_inner'],
            )
        );

        $this->add_responsive_control(
            'item_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_inner'],
            )
        );

        $this->add_responsive_control(
            'item_margin',
            array(
                'label'       => esc_html__( 'Item Margin', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['item_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ),
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'       => esc_html__( 'Item Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['item_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'content_alignment',
            array(
                'label'   => esc_html__( 'Content Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['content'] => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_thumb_style',
            array(
                'label'      => esc_html__( 'Image', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'thumb_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'enable_custom_image_width',
            array(
                'label'        => esc_html__( 'Enable Custom Image Width', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_responsive_control(
            'custom_image_width',
            [
                'label' => __( 'Custom Image Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units'  => ['px', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 70,
                ],
                'condition' => [
                    'enable_custom_image_width!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e_i_img_width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} ' . $css_scheme['image_tag'] . ' span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

		$this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => '',
            )
        );

        $this->add_responsive_control(
            'custom_image_height',
            [
                'label' => __( 'Custom Image Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'custom'],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['image_tag'] . ' span' => 'padding-bottom: {{SIZE}}{{UNIT}}; height: 0;'
                ]
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'default' => array(
                    'top'    => '',
                    'right'  => '',
                    'bottom' => '',
                    'left'   => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image_tag'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'default' => array(
                    'top'    => '',
                    'right'  => '',
                    'bottom' => '',
                    'left'   => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}}' => '--e_i_img_margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'image_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['image_tag'],
            )
        );

        $this->add_responsive_control(
            'image_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['image_tag'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_title_style',
            array(
                'label'      => esc_html_x( 'Title', 'Title at work', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'use_title_field' => 'yes'
                ]
            )
        );

        $this->add_control(
            'title_custom_width',
            array(
                'label'        => esc_html__( 'Custom width', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->add_responsive_control(
            'title_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 350,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'title_custom_width' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'title_bgcolor',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title']
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            )
        );

        $this->add_responsive_control(
            'title_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Comment Style Section
         */
        $this->start_controls_section(
            'section_comment_style',
            array(
                'label'      => esc_html__( 'Comment', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'comment_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'comment_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['comment'],
            )
        );

        $this->add_responsive_control(
            'comment_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'use_comment_corner',
            array(
                'label'        => esc_html__( 'Use comment corner', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->add_control(
            'use_comment_corner_as_line',
            array(
                'label'        => esc_html__( 'Use corner as line', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
                'condition' => array(
                    'use_comment_corner' => 'yes',
                ),
            )
        );

        $this->add_control(
            'comment_corner_line_color',
            array(
                'label'   => esc_html__( 'Corner Line Color', 'lastudio-kit' ),
                'type'    => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'use_comment_corner' => 'yes',
                    'use_comment_corner_as_line' => 'yes'
                ),
            )
        );

        $this->add_control(
            'comment_corner_color',
            array(
                'label'   => esc_html__( 'Corner Color', 'lastudio-kit' ),
                'type'    => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-color: {{VALUE}} transparent transparent transparent;',
                ),
                'condition' => array(
                    'use_comment_corner' => 'yes',
                    'use_comment_corner_as_line!' => 'yes'
                ),
            )
        );

        $this->add_responsive_control(
            'comment_corner_position',
            array(
                'label'      => esc_html__( 'Corner Position Left', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'custom' ),
                'range'      => array(
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 50,
                    'unit' => '%',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'left: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'use_comment_corner' => 'yes',
                ),
            )
        );

		$this->add_responsive_control(
            'comment_corner_position_t',
            array(
                'label'      => esc_html__( 'Corner Position Bottom', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'use_comment_corner' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'comment_corner_width',
            array(
                'label'      => esc_html__( 'Corner Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px',  'custom' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'default' => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-right-width: {{SIZE}}{{UNIT}}; margin-left: calc({{SIZE}}{{UNIT}}/-2);',
                ),
                'condition' => array(
                    'use_comment_corner' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'comment_corner_height',
            array(
                'label'      => esc_html__( 'Corner Height', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px',  'custom' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'default' => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'use_comment_corner' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'comment_corner_skew',
            array(
                'label'      => esc_html__( 'Corner Skew', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'default' => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'use_comment_corner' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'comment_background',
                'selector' => '{{WRAPPER}} ' . $css_scheme['comment']
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'comment_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['comment'],
            )
        );

        $this->add_control(
            'comment_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'comment_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['comment'],
            )
        );

        $this->add_responsive_control(
            'comment_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'default' => array(
                    'top'    => '',
                    'right'  => '',
                    'bottom' => '',
                    'left'   => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'comment_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'comment_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'comment_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['comment'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Name Style Section
         */
        $this->start_controls_section(
            'section_name_style',
            array(
                'label'      => esc_html__( 'Name', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'name_custom_width',
            array(
                'label'        => esc_html__( 'Custom width', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->add_responsive_control(
            'name_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 350,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'name_custom_width' => 'yes',
                ),
            )
        );

        $this->add_control(
            'name_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['name'],
            )
        );

        $this->add_responsive_control(
            'name_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'name_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'name_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'name_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['name'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * Position Style Section
         */
        $this->start_controls_section(
            'section_position_style',
            array(
                'label'      => esc_html_x( 'Position/Role', 'Position at work', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'position_custom_width',
            array(
                'label'        => esc_html__( 'Custom width', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->add_responsive_control(
            'position_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 350,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'position_custom_width' => 'yes',
                ),
            )
        );

        $this->add_control(
            'position_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'position_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['position'],
            )
        );

        $this->add_responsive_control(
            'position_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'position_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'position_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
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
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'position_text_alignment',
            array(
                'label'   => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();


        /**
         * Star Style Section
         */
        $this->start_controls_section(
            'section_star_style',
            array(
                'label'      => esc_html__( 'Star',  'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'replace_star',
            array(
                'label'        => esc_html__( 'Replace by quote icon', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => ''
            )
        );

		$this->_add_icon_control(
			'custom_star_icon',
			[
				'label'       => __( 'Custom Icon', 'lastudio-kit' ),
				'skin'        => 'inline',
				'label_block' => false,
				'condition' => [
					'replace_star!' => ''
				],
			]
		);

        $this->add_control(
            'star_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .star-rating' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_responsive_control(
            'star_size',
            array(
                'label'      => esc_html__( 'Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( '%', 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'star_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['star'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'star_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => '',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['star'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();


        /**
         * Order Style Section
         */
        $this->start_controls_section(
            'section_order_style',
            array(
                'label'      => esc_html__( 'Content Order', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'title_order',
            array(
                'label'   => esc_html__( 'Title Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['title'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
                'condition' => [
                    'use_title_field' => 'yes'
                ]
            )
        );

        $this->add_control(
            'avatar_order',
            array(
                'label'   => esc_html__( 'Avatar Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['image'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'name_order',
            array(
                'label'   => esc_html__( 'Name Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['name'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'position_order',
            array(
                'label'   => esc_html__( 'Position Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['position'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'comment_order',
            array(
                'label'   => esc_html__( 'Comment Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['comment'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'star_order',
            array(
                'label'   => esc_html__( 'Star Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['star'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->register_carousel_arrows_dots_style_section( [ 'enable_carousel' => 'yes' ] );
    }

    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

	public function maybe_render_quote_icon(){
		$custom_icon_html = $this->_get_icon_setting($this->get_settings_for_display('selected_custom_star_icon'), '%1$s');
		echo '<div class="lakit-testimonials__rating has-replace"><span class="star-rating">';
		if(!empty($custom_icon_html)){
			echo $custom_icon_html;
		}
		elseif (has_action('lastudio-kit/testimonials/output/star_rating')){
			$preset = $this->get_settings_for_display( 'preset' );
			do_action('lastudio-kit/testimonials/output/star_rating', $preset);
		}
		else{
			echo '<svg width="19" height="16" viewBox="0 0 19 16" focusable="false" xmlns="http://www.w3.org/2000/svg"><path d="M4.203 16c2.034 0 3.594-1.7 3.594-3.752 0-2.124-1.356-3.61-3.255-3.61-.339 0-.813.07-.881.07C3.864 6.442 5.831 3.611 8 2.124L5.492 0C2.372 2.336 0 6.3 0 10.62 0 14.087 1.966 16 4.203 16zm11 0c2.034 0 3.661-1.7 3.661-3.752 0-2.124-1.423-3.61-3.322-3.61-.339 0-.813.07-.881.07.271-2.266 2.17-5.097 4.339-6.584L16.492 0C13.372 2.336 11 6.3 11 10.62c0 3.468 1.966 5.38 4.203 5.38z" fill="currentColor" fill-rule="nonzero"/></svg>';
		}
		echo '</span></div>';
	}

}