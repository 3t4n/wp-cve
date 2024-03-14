<?php

namespace LaStudioKitExtensions\Portfolios\Widgets;

if (!defined('WPINC')) {
    die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\LaStudioKit_Base;
use Elementor\Repeater;


/**
 * Post Title Widget
 */
class Portfolio_Meta extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-portfolio-meta';
    }

    protected function get_widget_title() {
        return esc_html__( 'Portfolio Meta', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-bullet-list';
    }

    public function get_categories() {
        return [ 'lastudiokit-builder' ];
    }

    protected function set_template_output(){
        return lastudio_kit()->plugin_path('includes/extensions/portfolios/widget-templates');
    }

    protected function register_controls() {

	    $css_scheme = apply_filters(
		    'lastudio-kit/'.$this->get_lakit_name().'/css-schema',
		    array(
			    'meta'         => '.lakit-pf-metalist',
			    'label'         => '.lakit-pf-meta__item .meta--label',
			    'value'         => '.lakit-pf-meta__item .meta--value',
			    'icon'         => '.lakit-pf-meta__item .meta--icon',
			    'meta-item'    => '.lakit-pf-metalist .lakit-pf-meta__item',
		    )
	    );
        
        $this->_start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Setting', 'lastudio-kit' ),
            ]
        );

	    $this->_add_control(
		    'layout_type',
		    [
			    'label'   => esc_html__( 'Layout Type', 'lastudio-kit' ),
			    'type'    => Controls_Manager::SELECT,
			    'default'    => 'block',
			    'options' => [
				    'inline' => esc_html__( 'Inline', 'lastudio-kit' ),
				    'block' => esc_html__( 'Block', 'lastudio-kit' ),
			    ],
			    'prefix_class' => 'lakit-pf-layout-type-'
		    ]
	    );

	    $repeater = new Repeater();

	    $repeater->add_control(
		    'item_label',
		    array(
			    'label' => esc_html__( 'Label', 'lastudio-kit' ),
			    'type'  => Controls_Manager::TEXT,
		    )
	    );
	    $repeater->add_control(
		    'item_icon',
		    [
			    'label'            => __( 'Icon', 'lastudio-kit' ),
			    'type'             => Controls_Manager::ICONS,
			    'fa4compatibility' => 'icon',
			    'skin'             => 'inline',
			    'label_block'      => false,
		    ]
	    );

	    $repeater->add_control(
		    'item_type',
		    [
			    'label'   => esc_html__( 'Type', 'lastudio-kit' ),
			    'type'    => Controls_Manager::SELECT2,
			    'options' => apply_filters( 'lastudio-kit/'.$this->get_lakit_name().'/metadata', [
				    'description'       => esc_html__( 'Description', 'lastudio-kit' ),
				    'client'            => esc_html__( 'Client', 'lastudio-kit' ),
				    'date'              => esc_html__( 'Date', 'lastudio-kit' ),
				    'location'          => esc_html__( 'Location', 'lastudio-kit' ),
				    'designer'          => esc_html__( 'Designer', 'lastudio-kit' ),
				    'awards'            => esc_html__( 'Awards', 'lastudio-kit' ),
                    'custom_field'      => esc_html__( 'Custom Field', 'lastudio-kit' ),
                    'category'          => esc_html__( 'Category', 'lastudio-kit' ),
                    'tag'               => esc_html__( 'Tag', 'lastudio-kit' ),
			    ] )
		    ]
	    );
        $repeater->add_control(
            'item_ckey',
            array(
                'label' => esc_html__( 'Field Key', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'item_type' => 'custom_field'
                ]
            )
        );
        $repeater->add_control(
            'item_fb',
            array(
                'label' => esc_html__( 'Fallback', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
            )
        );

	    $this->_add_control(
		    'metadata',
		    array(
			    'label'         => esc_html__( 'MetaData', 'lastudio-kit' ),
			    'type'          => Controls_Manager::REPEATER,
			    'fields'        => $repeater->get_controls(),
			    'title_field'   => '{{{ item_label }}}',
			    'prevent_empty' => false
		    )
	    );

        $this->_end_controls_section();
	    $this->_start_controls_section(
		    'section_list',
		    array(
			    'label'     => esc_html__( 'List', 'lastudio-kit' ),
			    'tab'       => Controls_Manager::TAB_STYLE,
		    )
	    );
	    $this->_add_responsive_control(
		    'space',
		    array(
			    'label'       => esc_html__( 'Space Between', 'lastudio-kit' ),
			    'type'        => Controls_Manager::SLIDER,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'   => [
				    '{{WRAPPER}}' => '--pf-meta-space: {{SIZE}}{{UNIT}};'
			    ],
		    )
	    );
	    $this->_add_responsive_control(
		    'alignment',
		    array(
			    'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
			    'type'      => Controls_Manager::CHOOSE,
			    'options'   => array(
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
				    '{{WRAPPER}} ' . $css_scheme['meta'] => 'text-align: {{VALUE}};',
			    ),
		    )
	    );
        $this->_end_controls_section();

	    $this->_start_controls_section(
		    'section_label',
		    array(
			    'label'     => esc_html__( 'Label', 'lastudio-kit' ),
			    'tab'       => Controls_Manager::TAB_STYLE,
		    )
	    );

	    $this->_add_group_control(
		    Group_Control_Typography::get_type(),
		    array(
			    'name'     => 'label_typography',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
		    )
	    );
	    $this->_add_control(
		    'label_color',
		    array(
			    'label'     => esc_html__( 'Label Color', 'lastudio-kit' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
			    ),
		    )
	    );
	    $this->_add_responsive_control(
		    'label_width',
		    array(
			    'label'       => esc_html__( 'Label width', 'lastudio-kit' ),
			    'type'        => Controls_Manager::SLIDER,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'   => [
				    '{{WRAPPER}}' => '--pf-label-width: {{SIZE}}{{UNIT}};'
			    ],
		    )
	    );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'label_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['label'],
            )
        );
	    $this->_add_responsive_control(
		    'label_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );
	    $this->_add_responsive_control(
		    'label_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );
	    $this->_end_controls_section();

	    $this->_start_controls_section(
		    'section_value',
		    array(
			    'label'     => esc_html__( 'Value', 'lastudio-kit' ),
			    'tab'       => Controls_Manager::TAB_STYLE,
		    )
	    );
	    $this->_add_group_control(
		    Group_Control_Typography::get_type(),
		    array(
			    'name'     => 'value_typography',
			    'selector' => '{{WRAPPER}} ' . $css_scheme['value'],
		    )
	    );
	    $this->_add_control(
		    'value_color',
		    array(
			    'label'     => esc_html__( 'Value Color', 'lastudio-kit' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['value'] => 'color: {{VALUE}}',
			    ),
		    )
	    );

	    $this->_add_responsive_control(
		    'value_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['value'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );
	    $this->_add_responsive_control(
		    'value_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['value'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );
	    $this->_end_controls_section();

	    $this->_start_controls_section(
		    'section_icon',
		    array(
			    'label'     => esc_html__( 'Icon', 'lastudio-kit' ),
			    'tab'       => Controls_Manager::TAB_STYLE,
		    )
	    );
	    $this->_add_control(
		    'icon_color',
		    array(
			    'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => array(
				    '{{WRAPPER}} ' . $css_scheme['icon'] => 'color: {{VALUE}}',
			    ),
		    )
	    );
	    $this->_add_responsive_control(
		    'icon_size',
		    array(
			    'label'       => esc_html__( 'Icon Size', 'lastudio-kit' ),
			    'type'        => Controls_Manager::SLIDER,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'   => [
				    '{{WRAPPER}} ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}};'
			    ],
		    )
	    );
	    $this->_add_responsive_control(
		    'icon_padding',
		    array(
			    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['icon'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );
	    $this->_add_responsive_control(
		    'icon_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
			    'selectors'  => array(
				    '{{WRAPPER}} ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
		    )
	    );
	    $this->_end_controls_section();
    }

    protected function render() {

	    $this->_context = 'render';

	    $this->_open_wrap();
	    include $this->_get_global_template( 'index' );
	    $this->_close_wrap();
    }
    
}