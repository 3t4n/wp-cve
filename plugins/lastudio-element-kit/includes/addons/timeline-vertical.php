<?php
/**
 * Class: LaStudioKit_Timeline_Vertical
 * Name: Timeline Vertical
 * Slug: lakit-timeline-vertical
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * LaStudioKit_Timeline_Vertical Widget
 */
class LaStudioKit_Timeline_Vertical extends LaStudioKit_Base {

    public $_processed_item_index = 0;

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    wp_register_script( $this->get_name(), lastudio_kit()->plugin_url( 'assets/js/addons/timeline-vertical.js' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version(), true );
		    $this->add_script_depends( $this->get_name() );
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/timeline-vertical.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/timeline-vertical.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/timeline-vertical.min.css' );
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
        return 'lakit-timeline-vertical';
    }

    public function get_widget_title() {
        return esc_html__( 'Timeline Vertical', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'lastudio-kit-icon-vtimeline';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'lastudio-kit/timeline-vertical/css-schema',
            array(
                'line'               => '.lakit-vtimeline__line',
                'progress'           => '.lakit-vtimeline__line-progress',
                'item'               => '.lakit-vtimeline-item',
                'item_point'         => '.lakit-vtimeline-item__point',
                'item_point_content' => '.lakit-vtimeline-item__point-content',
                'item_meta'          => '.lakit-vtimeline-item__meta-content',
                'card'               => '.lakit-vtimeline-item__card',
                'card_inner'         => '.lakit-vtimeline-item__card-inner',
                'card_img'           => '.lakit-vtimeline-item__card-img',
                'card_content'       => '.lakit-vtimeline-item__card-content',
                'card_title'         => '.lakit-vtimeline-item__card-title',
                'card_subtitle'      => '.lakit-vtimeline-item__card-subtitle',
                'card_desc'          => '.lakit-vtimeline-item__card-desc',
                'card_arrow'         => '.lakit-vtimeline-item__card-arrow',
            )
        );

        $this->_start_controls_section(
            'section_cards',
            array(
                'label' => esc_html__( 'Cards', 'lastudio-kit' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'show_item_image',
            array(
                'label'        => esc_html__( 'Show Image', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $repeater->add_control(
            'item_image',
            array(
                'label'     => esc_html__( 'Image', 'lastudio-kit' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'condition' => array(
                    'show_item_image' => 'yes'
                ),
                'dynamic'  => array( 'active' => true ),
            )
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'      => 'item_image',
                'default'   => 'full',
                'condition' => array(
                    'show_item_image' => 'yes'
                ),
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
            'item_subtitle',
            array(
                'label'   => esc_html__( 'Sub Title', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_meta',
            array(
                'label'   => esc_html__( 'Meta', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_desc',
            array(
                'label'   => esc_html__( 'Description', 'lastudio-kit' ),
                'type'    => Controls_Manager::WYSIWYG,
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_point',
            array(
                'label'     => esc_html__( 'Point', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $repeater->add_control(
            'item_point_type',
            array(
                'label'   => esc_html__( 'Point Content Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => array(
                    'icon' => esc_html__( 'Icon', 'lastudio-kit' ),
                    'text' => esc_html__( 'Text', 'lastudio-kit' ),
                ),
            )
        );

        $repeater->add_control(
            'item_point_icon',
            array(
                'label'       => esc_html__( 'Point Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
                'condition'   => array(
                    'item_point_type' => 'icon'
                ),
            )
        );

        $repeater->add_control(
            'item_point_text',
            array(
                'label'     => esc_html__( 'Point Text', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'A',
                'condition' => array(
                    'item_point_type' => 'text'
                )
            )
        );

	    $repeater->add_control(
		    'item_cstyle',
		    array(
			    'label'     => esc_html__( 'Custom Style', 'lastudio-kit' ),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    )
	    );
	    $repeater->add_control(
		    'item_bgcolor',
		    [
			    'label' => __( 'Background Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card'] => 'background-color: {{VALUE}}',
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_titlecolor',
		    [
			    'label' => __( 'Title Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_stitlecolor',
		    [
			    'label' => __( 'SubTitle Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_metacolor',
		    [
			    'label' => __( 'Meta Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_desccolor',
		    [
			    'label' => __( 'Description Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );
	    $repeater->add_control(
		    'item_pointcolor',
		    [
			    'label' => __( 'Point Color', 'lastudio-kit' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
			    ],
		    ]
	    );

        $this->_add_control(
            'cards_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_title'      => esc_html__( 'Card #1', 'lastudio-kit' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-kit' ),
                        'item_meta'       => esc_html__( 'Thursday, August 31, 2018', 'lastudio-kit' ),
                    ),
                    array(
                        'item_title'      => esc_html__( 'Card #2', 'lastudio-kit' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-kit' ),
                        'item_meta'       => esc_html__( 'Thursday, August 29, 2018', 'lastudio-kit' ),
                    ),
                    array(
                        'item_title'      => esc_html__( 'Card #3', 'lastudio-kit' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-kit' ),
                        'item_meta'       => esc_html__( 'Thursday, August 28, 2018', 'lastudio-kit' ),
                    ),
                    array(
                        'item_title'      => esc_html__( 'Card #4', 'lastudio-kit' ),
                        'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'lastudio-kit' ),
                        'item_meta'       => esc_html__( 'Thursday, August 27, 2018', 'lastudio-kit' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'animate_cards',
            array(
                'label'        => esc_html__( 'Animate Cards', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'lakit-vtimeline-item--animated',
                'default'      => '',
            )
        );

        $this->_add_control(
            'image_in_meta',
            array(
                'label'        => esc_html__( 'Display image in meta', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            )
        );

        $this->_add_control(
            'horizontal_alignment',
            array(
                'label'   => esc_html__( 'Horizontal Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
            )
        );

        $this->_add_control(
            'vertical_alignment',
            array(
                'label'   => esc_html__( 'Vertical Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'middle',
                'options' => array(
                    'top'    => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'middle' => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
            )
        );

        $this->_add_responsive_control(
            'horizontal_space',
            array(
                'label'      => esc_html__( 'Horizontal Space', 'lastudio-kit' ),
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
                    'size' => 20,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['item_point'] => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .lakit-vtimeline--align-left ' . $css_scheme['item_point']   => 'margin-right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .lakit-vtimeline--align-right ' . $css_scheme['item_point']  => 'margin-left: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'vertical_space',
            array(
                'label'      => esc_html__( 'Vertical Space', 'lastudio-kit' ),
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
                    'size' => 30,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '+' . $css_scheme['item'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_cards_style',
            array(
                'label'      => esc_html__( 'Cards', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_cards( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_image_style',
            array(
                'label'      => esc_html__( 'Image', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_image( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_meta_style',
            array(
                'label'      => esc_html__( 'Meta', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_meta( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_card_content_style',
            array(
                'label'      => esc_html__( 'Content', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_content( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_card_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_title( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_card_subtitle_style',
            array(
                'label'      => esc_html__( 'Sub Title', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_subtitle( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_desc_style',
            array(
                'label'      => esc_html__( 'Description', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_card_desc( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_point_style',
            array(
                'label'      => esc_html__( 'Point', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_points( $css_scheme );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_line_style',
            array(
                'label'      => esc_html__( 'Line', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_control_section_line( $css_scheme );

        $this->_end_controls_section();
    }

    public function _control_section_cards( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'cards_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['card'] . ',' . '{{WRAPPER}} ' . $css_scheme['card_arrow'],
            )
        );

        $this->_add_responsive_control(
            'cards_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card']       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;'
                ),
            )
        );

        $this->_add_responsive_control(
            'cards_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'cards_style_tabs' );

        $this->_start_controls_tab(
            'cards_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'cards_background_normal',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_normal',
                'selector' => '{{WRAPPER}} '. $css_scheme['card'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'cards_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'cards_background_hover',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'cards_border_color_hover',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card']       => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};'
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'cards_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'cards_background_active',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'cards_border_color_active',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card']       => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card'],
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'cards_arrow_heading',
            array(
                'label'     => esc_html__( 'Arrow', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_responsive_control(
            'cards_arrow_width',
            array(
                'label'      => esc_html__( 'Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 60,
                    ),
                ),
                'default'    => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] => 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop){{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop){{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop) .rtl {{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '(desktop) .rtl {{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .lakit-vtimeline--align-left ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .lakit-vtimeline--align-right ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
                ),
            )
        );

    }

    public function _control_section_image( $css_scheme ) {

        $this->add_responsive_control(
            'image_size',
            array(
                'label' => esc_html__( 'Image Size', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em', 'vw', 'vh' ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] . ' img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'image_alignment',
            array(
                'label'   => esc_html__( 'Image Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'text-align: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'image_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 200,
                    ),
                ),
                'default'    => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] . ' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

    }

    public function _control_section_meta( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'meta_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'meta_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->_add_control(
            'meta_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->_add_responsive_control(
            'meta_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'meta_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'meta_style_tabs' );

        $this->_start_controls_tab(
            'meta_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'meta_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_normal_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'meta_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'meta_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'meta_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'meta_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'meta_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_active_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'],
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_card_content( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'card_content_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'],
            )
        );

        $this->_add_control(
            'card_content_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
            )
        );

        $this->_add_responsive_control(
            'card_content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_content_style_tabs' );

        $this->_start_controls_tab(
            'card_content_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_content_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'card_content_normal_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_content'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_content_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_content_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'card_content_hover_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'],
            )
        );

        $this->_add_control(
            'card_content_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_content_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_content_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'card_content_active_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'],
            )
        );

        $this->_add_control(
            'card_content_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'] => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'card_arrow_heading',
            array(
                'label'     => esc_html__( 'Card Arrow', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'vertical_alignment!' => 'middle'
                )
            )
        );

        $this->_add_responsive_control(
            'card_arrow_offset',
            array(
                'label'      => esc_html__( 'Card Arrow Offset', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                    '%'  => array(
                        'min' => 0,
                        'max' => 80,
                    ),
                ),
                'default'    => array(
                    'size' => 12,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-vtimeline--align-top ' . $css_scheme['card_arrow']    => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-vtimeline--align-bottom ' . $css_scheme['card_arrow'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'vertical_alignment!' => 'middle'
                )
            )
        );
    }

    public function _control_section_card_title( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_title'],
            )
        );

        $this->_add_responsive_control(
            'card_title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_title_style_tabs' );

        $this->_start_controls_tab(
            'card_title_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_title_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_title_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_title_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_title_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_title_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_card_subtitle( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_subtitle_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_subtitle'],
            )
        );

        $this->_add_responsive_control(
            'card_subtitle_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_subtitle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_subtitle_style_tabs' );

        $this->_start_controls_tab(
            'card_subtitle_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_subtitle_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_subtitle_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_subtitle_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_subtitle_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_subtitle_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_subtitle'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_card_desc( $css_scheme ) {

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_desc'],
            )
        );

        $this->_add_responsive_control(
            'card_desc_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'  => 'after'
            )
        );

        $this->_start_controls_tabs( 'card_desc_style_tabs' );

        $this->_start_controls_tab(
            'card_desc_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_desc_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_desc_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_desc_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'card_desc_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'card_desc_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_points( $css_scheme ) {

        $this->_start_controls_tabs( 'point_type_style_tabs' );

        $this->_start_controls_tab(
            'point_type_text_styles',
            array(
                'label' => esc_html__( 'Text', 'lastudio-kit' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'point_text_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_point_content'] . '.lakit-vtimeline-item__point-content--text',
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'point_type_icon_styles',
            array(
                'label' => esc_html__( 'Icon', 'lastudio-kit' ),
            )
        );

        $this->_add_responsive_control(
            'point_type_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 5,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'size' => 16,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'point_size',
            array(
                'label'      => esc_html__( 'Point Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'size' => 40,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content']               => 'height:{{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-vtimeline--align-center ' . $css_scheme['line'] => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .lakit-vtimeline--align-left ' . $css_scheme['line']   => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}} .lakit-vtimeline--align-right ' . $css_scheme['line']   => 'margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'point_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
            )
        );

        $this->_add_control(
            'point_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_start_controls_tabs( 'point_style_tabs' );

        $this->_start_controls_tab(
            'point_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'point_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'point_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'point_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'point_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'point_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'point_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

    }

    public function _control_section_line( $css_scheme ) {

        $this->_add_control(
            'line_background_color',
            array(
                'label'     => esc_html__( 'Line Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_control(
            'progress_background_color',
            array(
                'label'     => esc_html__( 'Progress Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['progress'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'line_width',
            array(
                'label'      => esc_html__( 'Thickness', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 15,
                    ),
                ),
                'default'    => array(
                    'size' => 2,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'line_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['line'],
            )
        );

        $this->_add_control(
            'line_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

    }

    public function _generate_point_content( $item_settings ) {
        echo '<div class="lakit-vtimeline-item__point">';
        switch ( $item_settings['item_point_type'] ) {
            case 'icon':
                echo $this->_get_icon_setting( $item_settings['item_point_icon'], '<div class="lakit-vtimeline-item__point-content timeline-item__point-content--icon">%s</div>' );
                break;
            case 'text':
                echo $this->_loop_item( array( 'item_point_text' ), '<div class="lakit-vtimeline-item__point-content timeline-item__point-content--text">%s</div>' );
                break;
        }
        echo '</div>';
    }

    public function _render_image( $item_settings ) {
        $show_image = filter_var( $item_settings['show_item_image'], FILTER_VALIDATE_BOOLEAN );

        if ( ! $show_image || empty( $item_settings['item_image']['url'] ) ) {
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $image_format = apply_filters( 'lastudio-kit/timeline-vertical/image-format', '<div class="lakit-vtimeline-item__card-img">%s</div>' );

        printf( $image_format, $img_html );
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

        $this->_processed_item_index = 0;
    }

}