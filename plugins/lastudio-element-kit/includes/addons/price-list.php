<?php

/**
 * Class: LaStudioKit_Price_List
 * Name: Price List
 * Slug: lakit-price-list
 */

namespace Elementor;

if (!defined('WPINC')) {
	die;
}

/**
 * Price_List Widget
 */
class LaStudioKit_Price_List extends LaStudioKit_Base {

	protected function enqueue_addon_resources(){
		if(!lastudio_kit_settings()->is_combine_js_css()) {
			if ( ! lastudio_kit()->is_optimized_css_mode() ) {
				wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/price-list.min.css' ), [], lastudio_kit()->get_version() );
				$this->add_style_depends( $this->get_name() );
			}
		}
	}

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/price-list.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/price-list.min.css' );
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
        return 'lakit-price-list';
    }

    protected function get_widget_title() {
        return esc_html__( 'Price List', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-price-list';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'lastudio-kit/price-list/css-schema',
            array(
                'price_list'       => '.lakit-price-list',
                'item'             => '.price-list__item',
                'item_inner'       => '.price-list__item-inner',
                'item_title'       => '.price-list__item-title',
                'item_price'       => '.price-list__item-price',
                'item_description' => '.price-list__item-desc',
                'item_separator'   => '.price-list__item-separator',
                'item_image_wrap'  => '.price-list__item-img-wrap',
            )
        );

        $this->start_controls_section(
            'section_general',
            array(
                'label'      => esc_html__( 'General', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_CONTENT,
                'show_label' => false,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_price',
            array(
                'label'   => esc_html__( 'Price', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_text',
            array(
                'label'   => esc_html__( 'Description', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio-kit' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => '',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_url',
            array(
                'label'   => esc_html__( 'URL', 'lastudio-kit' ),
                'type'    => Controls_Manager::URL,
                'default' => array(
                    'url' => '',
                ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'price_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_title' => esc_html__( 'Item #1', 'lastudio-kit' ),
                        'item_price' => esc_html__( '$12', 'lastudio-kit' ),
                        'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-kit' ),
                    ),
                    array(
                        'item_title' => esc_html__( 'Item #1', 'lastudio-kit' ),
                        'item_price' => esc_html__( '$12', 'lastudio-kit' ),
                        'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-kit' ),
                    ),
                    array(
                        'item_title' => esc_html__( 'Item #1', 'lastudio-kit' ),
                        'item_price' => esc_html__( '$12', 'lastudio-kit' ),
                        'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-kit' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            array(
                'label'      => esc_html__( 'Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'item_space_between',
            array(
                'label'      => esc_html__( 'Space Between Items (px)', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 150,
                    ),
                ),
                'default'    => array(
                    'size' => 15,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '+ .price-list__item' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'item_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
            )
        );

        $this->add_control(
            'item_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
            )
        );

        $this->add_responsive_control(
            'item_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_content_direction',
            array(
                'label'   => esc_html__( 'Content Direction', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'row' => [
                        'title' => esc_html_x( 'Row - horizontal', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column' => [
                        'title' => esc_html_x( 'Column - vertical', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-arrow-down',
                    ],
                    'row-reverse' => [
                        'title' => esc_html_x( 'Row - reversed', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-arrow-left',
                    ],
                    'column-reverse' => [
                        'title' => esc_html_x( 'Column - reversed', 'Flex Container Control', 'elementor' ),
                        'icon' => 'eicon-arrow-up',
                    ],
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_inner'] => 'flex-direction: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_content_alignment',
            array(
                'label'   => esc_html__( 'Content Vertical Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_inner'] => 'align-items: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'item_text_alignment',
            array(
                'label'     => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => '',
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_inner'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'first_item_heading',
            array(
                'label'     => esc_html__( 'First Item', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'first_item_border_width',
            array(
                'label'      => esc_html__( 'Border Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':first-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'first_item_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':first-child' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'last_item_heading',
            array(
                'label'     => esc_html__( 'Last Item', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'last_item_border_width',
            array(
                'label'      => esc_html__( 'Border Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'last_item_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':last-child' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->start_controls_tabs('section_item_tabs');
        $this->start_controls_tab(
            'section_item_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            ]
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg_normal',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'section_item_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            ]
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg_hover',
                'label' => esc_html__( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .price-list__item:hover',
            ]
        );
        $this->add_control(
            'item_border_color_hover',
            array(
                'label'     => esc_html__( 'Item Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .price-list__item:hover'=> 'border-color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'title_color_hover',
            array(
                'label'     => esc_html__( 'Title Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .price-list__item:hover ' . $css_scheme['item_title'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'price_color_hover',
            array(
                'label'     => esc_html__( 'Price Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .price-list__item:hover ' . $css_scheme['item_price'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'desc_color_hover',
            array(
                'label'     => esc_html__( 'Description Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .price-list__item:hover ' . $css_scheme['item_description'] => 'color: {{VALUE}}',
                ),
            )
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
	    $this->add_responsive_control(
		    'title_min_width',
		    array(
			    'label'      => esc_html__( 'Title Minimal Width', 'lastudio-kit' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => array(
				    'px', '%', 'custom'
			    ),
			    'range'      => array(
				    'px' => array(
					    'min' => 0,
					    'max' => 400,
				    ),
			    ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['item_title'] => 'min-width: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_title'],
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'title_vertical_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_title'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_alignment',
            array(
                'label'     => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => '',
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_title'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_price_style',
            array(
                'label'      => esc_html__( 'Price', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'price_after_content',
            [
                'label' => esc_html__( 'Move price after content', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
            ]
        );

		$this->_add_control(
            'hide_price_hover',
            [
                'label' => esc_html__( 'Hide price when hover', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'prefix_class'  => 'price_list__price-onhover-',
            ]
        );

        $this->add_responsive_control(
            'price_min_width',
            array(
                'label'      => esc_html__( 'Price Minimal Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%', 'custom'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 400,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'min-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_price'],
            )
        );

        $this->add_control(
            'price_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'price_background',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'price_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_price'],
            )
        );

        $this->add_control(
            'price_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'price_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_price'],
            )
        );

        $this->add_responsive_control(
            'price_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'price_vertical_alignment',
            array(
                'label'     => esc_html__( 'Vertical Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'price_alignment',
            array(
                'label'     => esc_html__( 'Text Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => '',
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_price'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_description_style',
            array(
                'label'      => esc_html__( 'Description', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_description'],
            )
        );

        $this->add_control(
            'description_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_description'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'description_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_description'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'description_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => 'left',
                'options'   => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center'  => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'   => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_description'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_separator_style',
            array(
                'label'      => esc_html__( 'Separator', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'price_after_content!' => 'yes'
                ]
            )
        );

        $this->add_control(
            'separator_border_type',
            array(
                'label'     => esc_html__( 'Separator Type', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'dotted',
                'options'   => array(
                    'none'   => esc_html__( 'None', 'lastudio-kit' ),
                    'solid'  => esc_html__( 'Solid', 'lastudio-kit' ),
                    'double' => esc_html__( 'Double', 'lastudio-kit' ),
                    'dotted' => esc_html__( 'Dotted', 'lastudio-kit' ),
                    'dashed' => esc_html__( 'Dashed', 'lastudio-kit' ),
                    'groove' => esc_html__( 'Groove', 'lastudio-kit' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'border-style: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'separator_border_width',
            array(
                'label'      => esc_html__( 'Separator Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 15,
                    ),
                ),
                'default'    => array(
                    'size' => 1,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'border-bottom-width: {{SIZE}}{{UNIT}}; border-top-width:0; border-right-width:0; border-left-width:0;',
                ),
                'condition' => [
                    'separator_border_type!' => 'none'
                ]
            )
        );

        $this->add_control(
            'separator_border_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'border-color: {{VALUE}}',
                ),
                'condition' => [
                    'separator_border_type!' => 'none'
                ]
            )
        );

        $this->add_responsive_control(
            'separator_vertical_alignment',
            array(
                'label'     => esc_html__( 'Vertical Alignment', 'lastudio-kit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'align-self: {{VALUE}};',
                ),
                'condition' => [
                    'separator_border_type!' => 'none'
                ]
            )
        );

        $this->add_responsive_control(
            'separator_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_separator'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => [
                    'separator_border_type!' => 'none'
                ]
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_style',
            array(
                'label'      => esc_html__( 'Image', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'show_image_when_hover',
            [
                'label' => esc_html__( 'Show on hover', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off' => esc_html__( 'No', 'lastudio-kit' ),
                'prefix_class'  => 'price_list__image-onhover-',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Image_Size::get_type(),
		    [
			    'name' => 'image_size',
			    'default' => 'full',
		    ]
	    );

        $this->add_responsive_control(
            'image_offset',
            array(
                'label'      => esc_html__( 'Image Offset (px)', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .price-list__item-inner' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_width',
            array(
                'label'      => esc_html__( 'Image Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', '%', 'custom'
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 1000,
                    ),
                    '%'  => array(
                        'min' => 0,
                        'max' => 80,
                    ),
                ),
                'default'    => array(
                    'size' => 150,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_image_wrap'] => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'enable_custom_image_height',
            array(
                'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'fit',
                'default'      => '',
                'prefix_class' => 'active-object-'
            )
        );

        $this->add_control(
            'custom_image_height',
            [
                'label' => __( 'Custom Image Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 75,
                ],
                'condition' => [
                    'enable_custom_image_height!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} ' . $css_scheme['item_image_wrap'] . ':before' => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

	    $this->add_responsive_control(
		    'image_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', '%', 'custom' ),
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['item_image_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_image_wrap'],
            )
        );

        $this->add_responsive_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_image_wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_image_wrap'],
            )
        );

        $this->end_controls_section();

    }

    public function _open_price_item_link( $url_key ) {
        call_user_func( array( $this, sprintf( '_open_price_item_link_%s', $this->_context ) ), $url_key );
    }

    public function _open_price_item_link_format() {
        return '<a href="%1$s" class="price-list__item-link"%2$s%3$s>';
    }

    public function _open_price_item_link_render( $url_key ) {

        $item = $this->_processed_item;

        if ( empty( $item[ $url_key ]['url'] ) ) {
            return;
        }

        printf(
            $this->_open_price_item_link_format(),
            $item[ $url_key ]['url'],
            ( ! empty( $item[ $url_key ]['is_external'] ) ? ' target="_blank"' : '' ),
            ( ! empty( $item[ $url_key ]['nofollow'] ) ? ' rel="nofollow"' : '' )
        );

    }

    public function _open_price_item_link_edit( $url_key ) {

        echo '<# if ( item.' . $url_key . '.url ) { #>';
        printf(
            $this->_open_price_item_link_format(),
            '{{{ item.' . $url_key . '.url }}}',
            '<# if ( item.' . $url_key . '.is_external ) { #> target="_blank"<# } #>',
            '<# if ( item.' . $url_key . '.nofollow ) { #> rel="nofollow"<# } #>'
        );
        echo '<# } #>';

    }

    public function _close_price_item_link( $url_key ) {

        call_user_func( array( $this, sprintf( '_close_price_item_link_%s', $this->_context ) ), $url_key );

    }

    public function _close_price_item_link_render( $url_key ) {

        $item = $this->_processed_item;

        if ( empty( $item[ $url_key ]['url'] ) ) {
            return;
        }

        echo '</a>';

    }

    public function _close_price_item_link_edit( $url_key ) {

        echo '<# if ( item.' . $url_key . '.url ) { #>';
        echo '</a>';
        echo '<# } #>';

    }

	public function get_price_image( $format = '%s', $class = '' ) {

		$settings = $this->get_settings_for_display();
		$size     = isset( $settings['image_size'] ) ? $settings['image_size'] : 'full';

		$item_settings = $this->_processed_item;
		$item_settings['item_image_size'] = $size;

		if(empty( $item_settings['item_image']['url'] )){
			return;
		}

		$img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

		$img_html = str_replace('class="', 'class="' . $class . ' ', $img_html);

		return sprintf( $format, $img_html );

	}

    public function get_item_inline_editing_attributes( $settings_item_key, $repeater_item_key, $index, $classes ) {
        $item_key = $this->get_repeater_setting_key( $settings_item_key, $repeater_item_key, $index );
        $this->add_render_attribute( $item_key, [ 'class' => $classes ] );
        $this->add_inline_editing_attributes( $item_key, 'basic' );

        return $this->get_render_attribute_string( $item_key );
    }

    protected function render() {
        $this->_context = 'render';
        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

}