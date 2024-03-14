<?php
/**
 * Class: LaStudioKit_Timeline_Horizontal
 * Name: Timeline Horizontal
 * Slug: lakit-timeline-horizontal
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use LaStudioKitExtensions\Elementor\Controls\Group_Control_Box_Style;

/**
 * LaStudioKit_Timeline_Horizontal Widget
 */
class LaStudioKit_Timeline_Horizontal extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    wp_register_script( $this->get_name(), lastudio_kit()->plugin_url( 'assets/js/addons/timeline-horizontal.js' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version(), true );
		    $this->add_script_depends( $this->get_name() );

		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/timeline-horizontal.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/timeline-horizontal.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/timeline-horizontal.min.css' );
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
        return 'lakit-timeline-horizontal';
    }

    public function get_widget_title() {
        return esc_html__( 'Timeline Horizontal', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'lastudio-kit-icon-htimeline';
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'lastudio-kit/timeline-horizontal/css-schema',
            array(
                'track'              => '.lakit-htimeline-track',
                'line'               => '.lakit-htimeline__line',
                'progress'           => '.lakit-htimeline__line-progress',
                'item'               => '.lakit-htimeline-item',
                'item_point'         => '.lakit-htimeline-item__point',
                'item_point_content' => '.lakit-htimeline-item__point-content',
                'item_meta'          => '.lakit-htimeline-item__meta',
                'card'               => '.lakit-htimeline-item__card',
                'card_inner'         => '.lakit-htimeline-item__card-inner',
                'card_img'           => '.lakit-htimeline-item__card-img',
                'card_title'         => '.lakit-htimeline-item__card-title',
                'card_desc'          => '.lakit-htimeline-item__card-desc',
                'card_arrow'         => '.lakit-htimeline-item__card-arrow',
                'arrow'              => '.lakit-htimeline .lakit-arrow',
                'prev_arrow'         => '.lakit-htimeline .lakit-arrow.prev-arrow',
                'next_arrow'         => '.lakit-htimeline .lakit-arrow.next-arrow',
            )
        );

        $this->start_controls_section(
            'section_items',
            array(
                'label' => esc_html__( 'Items', 'lastudio-kit' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'is_item_active',
            array(
                'label'   => esc_html__( 'Active', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

        $repeater->add_control(
            'show_item_image',
            array(
                'label'   => esc_html__( 'Show Image', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
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
                'dynamic' => array( 'active' => true ),
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
                'type'    => Controls_Manager::TEXTAREA,
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

        $this->add_control(
            'cards_list',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'is_item_active'  => 'yes',
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

        $this->add_control(
            'item_title_size',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'h1'   => esc_html__( 'H1', 'lastudio-kit' ),
                    'h2'   => esc_html__( 'H2', 'lastudio-kit' ),
                    'h3'   => esc_html__( 'H3', 'lastudio-kit' ),
                    'h4'   => esc_html__( 'H4', 'lastudio-kit' ),
                    'h5'   => esc_html__( 'H5', 'lastudio-kit' ),
                    'h6'   => esc_html__( 'H6', 'lastudio-kit' ),
                    'div'  => esc_html__( 'div', 'lastudio-kit' ),
                    'span' => esc_html__( 'span', 'lastudio-kit' ),
                    'p'    => esc_html__( 'p', 'lastudio-kit' ),
                ),
                'default' => 'h5',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'show_card_arrows',
            array(
                'label'   => esc_html__( 'Show Card Arrows', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'lastudio-kit' ),
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => esc_html__( 'Columns', 'lastudio-kit' ),
                'type'           => Controls_Manager::NUMBER,
                'min'            => 1,
                'max'            => 6,
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'selectors'      => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'flex: 0 0 calc(100%/{{VALUE}}); max-width: calc(100%/{{VALUE}});',
                ),
                'render_type'    => 'template',
            )
        );

        $this->add_control(
            'vertical_layout',
            array(
                'label'   => esc_html__( 'Layout', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'toggle'  => false,
                'default' => 'top',
                'options' => array(
                    'top' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'chess' => array(
                        'title' => esc_html__( 'Chess', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
            )
        );

        $this->add_control(
            'layout_chess_reverse',
            array(
                'label'   => esc_html__( 'Chess Layout', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'column',
                'options' => array(
                    'column'            => esc_html__( 'Default', 'lastudio-kit' ),
                    'column-reverse'    => esc_html__( 'Reverse', 'lastudio-kit' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-htimeline-track' => 'flex-flow: {{VALUE}}',
                ),
                'condition' => array(
                    'vertical_layout' => 'chess'
                ),
                'render_type' => 'ui',
            )
        );

        $this->add_control(
            'horizontal_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'toggle'  => false,
                'default' => 'left',
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

        $this->add_control(
            'navigation_type',
            array(
                'label'   => esc_html__( 'Navigation Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'scroll-bar',
                'options' => array(
                    'scroll-bar' => esc_html__( 'Scroll Bar', 'lastudio-kit' ),
                    'arrows-nav' => esc_html__( 'Arrows Navigation', 'lastudio-kit' ),
                )
            )
        );
        $this->_add_icon_control(
            'prev_arrow_icon',
            [
                'label' => __( 'Prev Icon', 'lastudio-kit' ),
                'label_block' => true,
                'default'     => 'lastudioicon lastudioicon-left-arrow',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-left-arrow',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'navigation_type' => 'arrows-nav',
                ),
            ]
        );

        $this->_add_icon_control(
            'next_arrow_icon',
            [
                'label' => __( 'Next Icon', 'lastudio-kit' ),
                'label_block' => true,
                'default'     => 'lastudioicon lastudioicon-right-arrow',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-right-arrow',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'navigation_type' => 'arrows-nav',
                ),
            ]
        );

        $this->end_controls_section();

        /**
         * `General` Style Section
         */
        $this->start_controls_section(
            'section_general_style',
            array(
                'label' => esc_html__( 'General', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'items_gap',
            array(
                'label' => esc_html__( 'Items Gap', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
                ),
                'render_type' => 'template',
            )
        );

        $this->end_controls_section();

        /**
         * `Cards` Style Section
         */
        $this->start_controls_section(
            'section_cards_style',
            array(
                'label' => esc_html__( 'Cards', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'cards_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['card_arrow'],
            )
        );

        $this->add_responsive_control(
            'cards_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_spacing',
            array(
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-htimeline-list--top ' . $css_scheme['card'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-htimeline-list--bottom ' . $css_scheme['card'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'cards_style_tabs' );

        $this->start_controls_tab(
            'cards_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'cards_background_normal',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_normal',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['card_arrow'],
                'exclude'  => array(
                    'box_shadow_position',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cards_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'cards_background_hover',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'cards_border_color_hover',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card'] => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};'
                ),
                'condition' => array(
                    'cards_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_hover',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'],
                'exclude'  => array(
                    'box_shadow_position',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'cards_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'cards_background_active',
            array(
                'label'     => esc_html__( 'Background', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'cards_border_color_active',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card'] => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'cards_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'cards_box_shadow_active',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'],
                'exclude'  => array(
                    'box_shadow_position',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'cards_arrow_heading',
            array(
                'label'     => esc_html__( 'Arrow', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_card_arrows' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_arrow_width',
            array(
                'label' => esc_html__( 'Size', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_arrow'] => 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_card_arrows' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'cards_arrow_offset',
            array(
                'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-htimeline--align-left ' . $css_scheme['card_arrow'] => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-htimeline--align-right ' . $css_scheme['card_arrow'] => 'right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_card_arrows' => 'yes',
                    'horizontal_alignment!' => 'center',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Cards Content` Style Section
         */
        $this->start_controls_section(
            'section_image_style',
            array(
                'label' => esc_html__( 'Cards Content', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'cards_content_align',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
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
                    'justify' => array(
                        'title' => esc_html__( 'Justified', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_inner'] => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'image_heading',
            array(
                'label'     => esc_html__( 'Image', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'move_image_to_meta',
            array(
                'label'   => esc_html__( 'Show Image In Meta', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

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

        $this->add_responsive_control(
            'image_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-htimeline:not(.lakit-htimeline--layout-chess) ' . $css_scheme['card_img'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-htimeline--layout-chess .lakit-htimeline-list--top ' . $css_scheme['card_img'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-htimeline--layout-chess .lakit-htimeline-list--bottom ' . $css_scheme['card_img'] => 'margin: {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}} {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
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
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_img'] . ' img',
            )
        );

        $this->add_control(
            'title_heading',
            array(
                'label'     => esc_html__( 'Title', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_title'],
            )
        );

        $this->add_responsive_control(
            'card_title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'card_title_style_tabs' );

        $this->start_controls_tab(
            'card_title_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'card_title_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_title_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'card_title_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_title_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'card_title_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'card_desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['card_desc'],
            )
        );

        $this->add_responsive_control(
            'card_desc_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'card_desc_style_tabs' );

        $this->start_controls_tab(
            'card_desc_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'card_desc_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_desc_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'card_desc_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'card_desc_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'card_desc_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'orders_heading',
            array(
                'label'     => esc_html__( 'Orders', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'image_order',
            array(
                'label' => esc_html__( 'Image Order', 'lastudio-kit' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'max'   => 10,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_img'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'title_order',
            array(
                'label' => esc_html__( 'Title Order', 'lastudio-kit' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'max'   => 10,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_title'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'desc_order',
            array(
                'label' => esc_html__( 'Description Order', 'lastudio-kit' ),
                'type'  => Controls_Manager::NUMBER,
                'min'   => 0,
                'max'   => 10,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['card_desc'] => 'order: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Meta` Style Section
         */
        $this->start_controls_section(
            'section_meta_style',
            array(
                'label' => esc_html__( 'Meta', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'meta_typography',
                'selector' => '{{WRAPPER}} ' .  $css_scheme['item_meta'],
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'meta_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->add_control(
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

        $this->add_responsive_control(
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

        $this->add_responsive_control(
            'meta_spacing',
            array(
                'label' => esc_html__( 'Spacing', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-htimeline-list--top ' . $css_scheme['item_meta'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-htimeline-list--bottom ' . $css_scheme['item_meta'] => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'meta_style_tabs' );

        $this->start_controls_tab(
            'meta_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'meta_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_normal_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_meta'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'meta_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'meta_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'meta_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'meta_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'meta_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'meta_border_border!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'meta_active_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'],
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * `Point` Style Section
         */
        $this->start_controls_section(
            'section_point_style',
            array(
                'label' => esc_html__( 'Point', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'point_type_style_tabs' );

        $this->start_controls_tab(
            'point_type_text_styles',
            array(
                'label' => esc_html__( 'Text', 'lastudio-kit' ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'point_text_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'point_type_icon_styles',
            array(
                'label' => esc_html__( 'Icon', 'lastudio-kit' ),
            )
        );

        $this->add_responsive_control(
            'point_type_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em'),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'point_size',
            array(
                'label' => esc_html__( 'Point Size', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'point_offset',
            array(
                'label' => esc_html__( 'Offset', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-htimeline--align-left ' . $css_scheme['item_point_content'] => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-htimeline--align-right ' . $css_scheme['item_point_content'] => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'horizontal_alignment!' => 'center',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'point_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
            )
        );

        $this->add_control(
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

        $this->start_controls_tabs( 'point_style_tabs' );

        $this->start_controls_tab(
            'point_normal_styles',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'point_normal_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_normal_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'point_hover_styles',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'point_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_hover_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'point_border_border!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'point_active_styles',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'point_active_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'point_active_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'point_border_border!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * `Line` Style Section
         */
        $this->start_controls_section(
            'section_line_style',
            array(
                'label' => esc_html__( 'Line', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'line_fullscreen',
            array(
                'label'   => esc_html__( 'Enable 100% browser width', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
                'prefix_class'  => 'lakit-htimeline-line100-',
            )
        );

        $this->add_control(
            'line_background_color',
            array(
                'label'     => esc_html__( 'Line Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .lakit-htimeline-inner:before' => 'background-color: {{VALUE}};',
                ),
            )
        );

//		$this->add_control(
//			'progress_background_color',
//			array(
//				'label'     => esc_html__( 'Progress Color', 'lastudio-kit' ),
//				'type'      => Controls_Manager::COLOR,
//				'selectors' => array(
//					'{{WRAPPER}} ' . $css_scheme['progress'] => 'background-color: {{VALUE}};',
//				),
//			)
//		);

        $this->add_responsive_control(
            'line_height',
            array(
                'label' => esc_html__( 'Height', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 15,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['line'] => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-htimeline-inner:before' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Scrollbar` Style Section
         */
        $this->start_controls_section(
            'section_scrollbar_style',
            array(
                'label' => esc_html__( 'Scrollbar', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'navigation_type' => 'scroll-bar',
                ),
            )
        );

        $this->add_control(
            'non_webkit_notice',
            array(
                'type' => Controls_Manager::RAW_HTML,
                'raw'  => esc_html__( 'Currently works only in -webkit- browsers', 'lastudio-kit' ),
                'content_classes' => 'elementor-descriptor',
            )
        );

        $this->add_control(
            'scrollbar_bg',
            array(
                'label'     => esc_html__( 'Scrollbar Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_thumb_bg',
            array(
                'label'     => esc_html__( 'Scrollbar Thumb Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar-thumb' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_height',
            array(
                'label' => esc_html__( 'Scrollbar Height', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_offset',
            array(
                'label' => esc_html__( 'Scrollbar Offset', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'scrollbar_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Arrows` Style Section
         */
        $this->start_controls_section(
            'section_arrows_style',
            array(
                'label'     => esc_html__( 'Arrows', 'lastudio-kit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'navigation_type' => 'arrows-nav',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_prev',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'     => 'arrows_style',
                'label'    => esc_html__( 'Arrows Style', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['arrow'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_next_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name'     => 'arrows_hover_style',
                'label'    => esc_html__( 'Arrows Style', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} ' . $css_scheme['arrow'] . ':not(.arrow-disabled):hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'prev_arrow_position',
            array(
                'label'     => esc_html__( 'Prev Arrow Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'prev_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio-kit' ),
                    'right' => esc_html__( 'Right', 'lastudio-kit' ),
                ),
                'render_type'=> 'ui',
            )
        );

        $this->add_responsive_control(
            'prev_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['prev_arrow'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'prev_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'prev_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['prev_arrow'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->add_control(
            'next_arrow_position',
            array(
                'label'     => esc_html__( 'Next Arrow Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'next_hor_position',
            array(
                'label'   => esc_html__( 'Horizontal Position by', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'lastudio-kit' ),
                    'right' => esc_html__( 'Right', 'lastudio-kit' ),
                ),
                'render_type'=> 'ui',
            )
        );

        $this->add_responsive_control(
            'next_left_position',
            array(
                'label'      => esc_html__( 'Left Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'left',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['next_arrow'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->add_responsive_control(
            'next_right_position',
            array(
                'label'      => esc_html__( 'Right Indent', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'next_hor_position' => 'right',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' .  $css_scheme['next_arrow'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

    public function _render_image( $item_settings ) {
        $show_image = filter_var( $item_settings['show_item_image'], FILTER_VALIDATE_BOOLEAN );

        if ( ! $show_image || empty( $item_settings['item_image']['url'] ) ) {
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $image_format = apply_filters( 'lastudio-kit/timeline-horizontal/image-format', '<div class="lakit-htimeline-item__card-img">%s</div>' );

        printf( $image_format, $img_html );
    }

    public function _render_point_content( $item_settings ) {
        echo '<div class="lakit-htimeline-item__point">';
        echo '<div class="lakit-htimeline-item__point-content">';
        switch ( $item_settings['item_point_type'] ) {
            case 'icon':
                echo $this->_get_icon_setting( $item_settings['item_point_icon'], '%s' );
                break;
            case 'text':
                echo $this->_loop_item( array( 'item_point_text' ), '%s' );
                break;
        }
        echo '</div>';
        echo '</div>';
    }

}