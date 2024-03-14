<?php

/**
 * Class: LaStudioKit_Breadcrumbs
 * Name: Breadcrumbs
 * Slug: lakit-breadcrumbs
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Breadcrumbs Widget
 */
class LaStudioKit_Breadcrumbs extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/breadcrumbs.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url( 'assets/css/addons/breadcrumbs.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/breadcrumbs.min.css' );
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
        return 'lakit-breadcrumbs';
    }

    protected function get_widget_title() {
        return esc_html__( 'Breadcrumbs', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'lastudio-kit-icon-breadcrumbs';
    }

    public function is_reload_preview_required() {
        return true;
    }

    protected function register_controls() {
        $css_scheme = apply_filters(
            'lakit-blocks/lakit-breadcrumbs/css-schema',
            array(
                'module'  => '.lakit-breadcrumbs',
                'title'   => '.lakit-breadcrumbs__title',
                'content' => '.lakit-breadcrumbs__content',
                'browse'  => '.lakit-breadcrumbs__browse',
                'item'    => '.lakit-breadcrumbs__item',
                'sep'     => '.lakit-breadcrumbs__item-sep',
                'link'    => '.lakit-breadcrumbs__item-link',
                'target'  => '.lakit-breadcrumbs__item-target',
            )
        );

        $this->start_controls_section(
            'section_breadcrumbs_settings',
            array(
                'label' => esc_html__( 'General Settings', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'show_on_front',
            array(
                'label'   => esc_html__( 'Show on Front Page', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
                'prefix_class' => 'lakit-breadcrumbs-on-front-',
            )
        );

        $this->add_control(
            'show_title',
            array(
                'label'   => esc_html__( 'Show Page Title', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
                'render_type'  => 'template',
                'prefix_class' => 'lakit-breadcrumbs-page-title-',
            )
        );

        $this->add_control(
            'custom_page_title',
            array(
                'label'       => esc_html__( 'Custom Page Title', 'lastudio-kit' ),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'condition' => array(
                    'show_title' => 'yes',
                ),
            )
        );

        $this->add_control(
            'title_tag',
            array(
                'label' => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
                'type'  => Controls_Manager::SELECT,
                'options' => array(
                    'h1'  => 'H1',
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ),
                'default' => 'h3',
                'condition' => array(
                    'show_title' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_browse',
            array(
                'label'   => esc_html__( 'Show Prefix', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

        $this->add_control(
            'browse_label',
            array(
                'label'       => esc_html__( 'Prefix for the breadcrumb path', 'lastudio-kit' ),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Browse:', 'lastudio-kit' ),
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            )
        );

        $this->add_control(
            'enabled_custom_home_page_label',
            array(
                'label'   => esc_html__( 'Custom Home Page Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

        $this->add_control(
            'custom_home_page_label',
            array(
                'label'       => esc_html__( 'Label for home page', 'lastudio-kit' ),
                'label_block' => true,
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Home', 'lastudio-kit' ),
                'condition' => array(
                    'enabled_custom_home_page_label' => 'yes',
                ),
            )
        );

        $this->add_control(
            'separator_type',
            array(
                'label' => esc_html__( 'Separator Type', 'lastudio-kit' ),
                'type'  => Controls_Manager::SELECT,
                'options' => array(
                    'icon'   => esc_html__( 'Icon', 'lastudio-kit' ),
                    'custom' => esc_html__( 'Custom', 'lastudio-kit' ),
                ),
                'default' => 'custom',
            )
        );

        $this->_add_advanced_icon_control(
            'icon_separator',
            array(
                'label'   => esc_html__( 'Icon Separator', 'lastudio-kit' ),
                'type'    => Controls_Manager::ICON,
                'default' => 'lastudioicon-right-arrow',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-right-arrow',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'separator_type' => 'icon',
                ),
            )
        );

        $this->add_control(
            'custom_separator',
            array(
                'label'   => esc_html__( 'Custom Separator', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '/',
                'condition' => array(
                    'separator_type' => 'custom',
                ),
            )
        );

        $this->add_control(
            'path_type',
            array(
                'label'   => esc_html__( 'Path type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'full',
                'options' => array(
                    'full'     => esc_html__( 'Full', 'lastudio-kit' ),
                    'minified' => esc_html__( 'Minified', 'lastudio-kit' ),
                ),
            )
        );

        $this->add_responsive_control(
            'alignment',
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
                    '{{WRAPPER}} ' . $css_scheme['module'] => 'text-align: {{VALUE}};',
                ),
                'prefix_class' => 'lakit-breadcrumbs-align%s-',
            )
        );

        $this->add_control(
            'order',
            array(
                'label'       => esc_html__( 'Order', 'lastudio-kit' ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => '-1',
                'options' => array(
                    '-1' => esc_html__( 'Page Title / Breadcrumbs Items', 'lastudio-kit' ),
                    '1'  => esc_html__( 'Breadcrumbs Items / Page Title', 'lastudio-kit' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'order: {{VALUE}};',
                ),
                'condition' => array(
                    'show_title' => 'yes',
                ),
                'separator' => 'before',
            )
        );

        $this->add_control(
            'breadcrumbs_settings_desc',
            array(
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'separator'       => 'before',
                'raw'             => sprintf(
                    esc_html__( 'Additional settings are available in the %s', 'lastudio-kit' ),
                    '<a target="_blank" href="' . lastudio_kit_settings()->get_settings_page_link( 'general' ) . '">' . esc_html__( 'Settings page', 'lastudio-kit' ) . '</a>'
                ),
            )
        );

        $this->end_controls_section();

        /**
         * `Page Title` Section
         */
        $this->_start_controls_section(
            'title_style',
            array(
                'label'      => esc_html__( 'Page Title', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'show_title' => 'yes',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
            ),
            50
        );

        $this->_add_control(
            'title_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'title_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'background-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'title_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'title_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} ' . $css_scheme['title'],
            ),75
        );

        $this->_add_responsive_control(
            'title_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),75
        );

        $this->_end_controls_section();

        /**
         * `Breadcrumbs` Section
         */
        $this->_start_controls_section(
            'breadcrumbs_style',
            array(
                'label'      => esc_html__( 'Breadcrumbs', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'breadcrumbs_crumbs_heading',
            array(
                'label' => esc_html__( 'Crumbs Style', 'lastudio-kit' ),
                'type'  => Controls_Manager::HEADING,
            ),
            25
        );

        $this->_start_controls_tabs( 'breadcrumbs_item_style' );

        $this->_start_controls_tab(
            'breadcrumbs_item_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'breadcrumbs_item_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' > *',
            ),
            50
        );

        $this->_add_control(
            'breadcrumbs_link_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['link'] => 'color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'breadcrumbs_link_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['link'] => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'breadcrumbs_item_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'breadcrumbs_link_hover_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['link'] . ':hover',
            ),
            50
        );

        $this->_add_control(
            'breadcrumbs_link_hover_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['link'] . ':hover' => 'color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'breadcrumbs_link_hover_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['link'] . ':hover' => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'breadcrumbs_link_hover_border_color',
            array(
                'label'  => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'condition' => array(
                    'breadcrumbs_item_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['link'] . ':hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'breadcrumbs_item_target',
            array(
                'label' => esc_html__( 'Current', 'lastudio-kit' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'breadcrumbs_target_item_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['target'],
            ),
            50
        );

        $this->_add_control(
            'breadcrumbs_target_item_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['target'] => 'color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'breadcrumbs_target_item_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['target'] => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'breadcrumbs_target_item_border_color',
            array(
                'label'  => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'condition' => array(
                    'breadcrumbs_item_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['target'] => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'breadcrumbs_item_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['target'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'breadcrumbs_item_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['link'] . ', {{WRAPPER}} ' . $css_scheme['target'],
            ),
            75
        );

        $this->_add_responsive_control(
            'breadcrumbs_item_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' . $css_scheme['target'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'breadcrumbs_sep_heading',
            array(
                'label'     => esc_html__( 'Separators Style', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'breadcrumbs_sep_gap',
            array(
                'label'      => esc_html__( 'Gap', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['sep'] => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'breadcrumbs_sep_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 5,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['sep'] => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'separator_type' => 'icon',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'breadcrumbs_sep_typography',
                'selector'  => '{{WRAPPER}} ' . $css_scheme['sep'],
                'condition' => array(
                    'separator_type' => 'custom',
                ),
            ),
            50
        );

        $this->_add_control(
            'breadcrumbs_sep_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['sep'] => 'color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'breadcrumbs_sep_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['sep'] => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'breadcrumbs_sep_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['sep'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'breadcrumbs_sep_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['sep'],
            ),
            75
        );

        $this->_add_responsive_control(
            'breadcrumbs_sep_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['sep'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'breadcrumbs_browse_heading',
            array(
                'label'     => esc_html__( 'Prefix Style', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'breadcrumbs_browse_gap',
            array(
                'label'      => esc_html__( 'Gap', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['browse'] => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'breadcrumbs_browse_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['browse'],
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            50
        );

        $this->_add_control(
            'breadcrumbs_browse_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['browse'] => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            25
        );

        $this->_add_control(
            'breadcrumbs_browse_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['browse'] => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'breadcrumbs_browse_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['browse'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'breadcrumbs_browse_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['browse'],
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'breadcrumbs_browse_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['browse'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'show_browse' => 'yes',
                ),
            ),
            75
        );

        $this->_end_controls_section();

    }

    protected function render() {

        $this->_open_wrap();

        $settings = $this->get_settings();

        $title_format = '<' . $settings['title_tag'] . ' class="lakit-breadcrumbs__title">%s</' . $settings['title_tag'] . '>';

        $custom_page_title = $this->get_settings_for_display('custom_page_title');
        if(!empty($custom_page_title)){
            $title_format = '<' . $settings['title_tag'] . ' class="lakit-breadcrumbs__title">'.$custom_page_title.'</' . $settings['title_tag'] . '>';
        }

        $custom_home_page_enabled = ! empty( $settings['enabled_custom_home_page_label'] ) ? $settings['enabled_custom_home_page_label'] : false;
        $custom_home_page_enabled = filter_var( $custom_home_page_enabled, FILTER_VALIDATE_BOOLEAN );
        $custom_home_page_label   = ( $custom_home_page_enabled && ! empty( $settings['custom_home_page_label'] ) ) ? $settings['custom_home_page_label'] : esc_html__( 'Home', 'lastudio-kit' );

        $args = array(
            'wrapper_format'    => '%1$s%2$s',
            'page_title_format' => $title_format,
            'separator'         => $this->_get_separator(),
            'show_on_front'     => filter_var( $settings['show_on_front'], FILTER_VALIDATE_BOOLEAN ),
            'show_title'        => filter_var( $settings['show_title'], FILTER_VALIDATE_BOOLEAN ),
            'show_browse'       => filter_var( $settings['show_browse'], FILTER_VALIDATE_BOOLEAN ),
            'path_type'         => $settings['path_type'],
            'action'            => 'lakit_breadcrumbs/render',
            'strings' => array(
                'browse'         => $settings['browse_label'],
                'home'           => $custom_home_page_label,
                'error_404'      => esc_html__( '404 Not Found', 'lastudio-kit' ),
                'archives'       => esc_html__( 'Archives', 'lastudio-kit' ),
                'search'         => esc_html__( 'Search results for &#8220;%s&#8221;', 'lastudio-kit' ),
                'paged'          => esc_html__( 'Page %s', 'lastudio-kit' ),
                'archive_minute' => esc_html__( 'Minute %s', 'lastudio-kit' ),
                'archive_week'   => esc_html__( 'Week %s', 'lastudio-kit' ),
            ),
            'date_labels' => array(
                'archive_minute_hour' => esc_html_x( 'g:i a', 'minute and hour archives time format', 'lastudio-kit' ),
                'archive_minute'      => esc_html_x( 'i', 'minute archives time format', 'lastudio-kit' ),
                'archive_hour'        => esc_html_x( 'g a', 'hour archives time format', 'lastudio-kit' ),
                'archive_year'        => esc_html_x( 'Y', 'yearly archives date format', 'lastudio-kit' ),
                'archive_month'       => esc_html_x( 'F', 'monthly archives date format', 'lastudio-kit' ),
                'archive_day'         => esc_html_x( 'j', 'daily archives date format', 'lastudio-kit' ),
                'archive_week'        => esc_html_x( 'W', 'weekly archives date format', 'lastudio-kit' ),
            ),
            'css_namespace' => array(
                'module'    => 'lakit-breadcrumbs',
                'content'   => 'lakit-breadcrumbs__content',
                'wrap'      => 'lakit-breadcrumbs__wrap',
                'browse'    => 'lakit-breadcrumbs__browse',
                'item'      => 'lakit-breadcrumbs__item',
                'separator' => 'lakit-breadcrumbs__item-sep',
                'link'      => 'lakit-breadcrumbs__item-link',
                'target'    => 'lakit-breadcrumbs__item-target',
            ),
            'post_taxonomy' => apply_filters(
                'lakit_breadcrumbs/trail_taxonomies',
                lastudio_kit_helper()->get_breadcrumbs_post_taxonomy_settings()
            ),
        );

        if ( $custom_home_page_enabled ) {
            add_filter( 'lakit_breadcrumbs/custom_home_title', array( $this, 'static_home_page_title_off' ) );
        }

        $breadcrumbs = new \LaStudio_Kit_Breadcrumbs( $args );

        if ( $custom_home_page_enabled ) {
            remove_filter( 'lakit_breadcrumbs/custom_home_title', array( $this, 'static_home_page_title_off' ) );
        }

        $breadcrumbs->get_trail();

        $this->_close_wrap();
    }

    /**
     * [_get_separator description]
     * @return [type] [description]
     */
    public function _get_separator() {
        $separator = '';
        $settings  = $this->get_settings();

        $separator_type = $settings['separator_type'];

        if ( 'icon' === $separator_type ) {
            $separator = $this->_get_icon( 'icon_separator', '<span class="lakit-blocks-icon">%s</span>' );
        } else {
            $separator = sprintf( '<span>%s</span>', $settings['custom_separator'] );
        }

        return $separator;
    }

    /**
     * Disables getting the title of the home page if a static page is selected.
     *
     * @return boolean
     */
    public function static_home_page_title_off() {
        return false;
    }

}