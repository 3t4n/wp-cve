<?php
namespace CoderExpert\Corona;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use Elementor\Core\Schemes;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CoronaGraphElementor extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'cec-graph';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'Corona - Graph', 'ce-corona' );
	}
    public function get_keywords() {
        return [
            'corona',
            'graph',
            'coronavirus',
            'covid',
            'corona outbreak',
            'covid19',
        ];
    }
	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'icon-coronavirus-covid-19-flu-influenza-mers-sars-virus';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
    }
    public function countries(){
        return array_merge( array( 'all' => __('Global', 'ce-corona') ), Shortcode::countries() );
    }
	/**
	 * Retrieve the list of scripts the widget depended on.
	 */
	public function get_script_depends() {
		return [ 'cec-graph' ];
	}
	public function get_style_depends() {
		return [ 'cec-graph' ];
    }

    public function new_switcher( $key, $title ){
        return $this->add_control(
			'cec_show_items_' . $key,
			[
				'label' => __( $title, 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
			]
        );
    }

    public function new_title( $key, $title, $default, $condition = [] ){
        return $this->add_control(
			'cecg_' . $key ,
			[
				'label'       => __( $title, 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
                'default'     => __( $default, 'ce-corona' ),
                'condition' => $condition
			]
        );
    }

	/**
	 * Register the widget controls.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'cec_corona_cng_general_settings',
			[
				'label' => __( 'General Settings', 'ce-corona' ),
			]
        );

        $this->add_control(
			'cecg_country_ids',
			[
				'label'   => __( 'Select Countries', 'ce-corona' ),
                'type'    => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
				'options' => $this->countries(),
                'default' => 'all',
			]
        );

        $this->add_control(
			'cecg_lastdays',
			[
				'label'   => __( 'Data for Last', 'ce-corona' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 7,
                'description' => __( 'Data for last 30 days', 'ce-corona' )
			]
        );

        $this->add_control(
			'cecg_graph_type',
			[
				'label'   => __( 'Graph Type', 'ce-corona' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'area' => __('Area', 'ce-corona'),
                    'line' => __('Line', 'ce-corona'),
                    'bar' => __('Bar', 'ce-corona'),
                ],
                'default' => 'area',
			]
        );

        $this->add_control(
			'cecg_graph_legend_position',
			[
				'label'   => __( 'Legend Position', 'ce-corona' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'left' => __('Left', 'elementor'),
                    'right' => __('Right', 'elementor'),
                    'top' => __('Top', 'elementor'),
                    'bottom' => __('Bottom', 'elementor'),
                ],
                'default' => 'top',
			]
        );

        $this->add_control(
			'cecg_data_labels',
			[
				'label' => __( 'Data Labels', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
			]
        );

        $this->end_controls_section();
        
		$this->start_controls_section(
			'cec_cng_corona_content_settings',
			[
				'label' => __( 'Content', 'ce-corona' ),
			]
        );

        $this->new_title( 'title', 'Graph Title', 'Compare Cases by Region', [ 'cecg_country_ids!' => 'all' ] );
        $this->new_title( 'case_title', 'Case Title', 'New Cases' );
        $this->new_title( 'deaths_title', 'Deaths Title', 'New Deaths' );
        $this->new_title( 'recovered_title', 'Recovered Title', 'Recovered' );
        
        $this->end_controls_section();

        $this->start_controls_section(
			'cec_cng_common_style',
			[
				'label' => __( 'Common Style', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

		$this->add_responsive_control(
			'cecg_box_margin',
			[
				'label' => __( 'Margin', 'ce-corona' ),
				'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
		$this->add_responsive_control(
			'cecg_box_padding',
			[
				'label' => __( 'Padding', 'ce-corona' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'cecg_cn_color',
            [
                'label' => __('Foreground Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-elementor-graph .cec-graph .cec-graph-category' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .cec-elementor-graph .cec-graph .apexcharts-yaxis-title-text' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .cec-elementor-graph .cec-graph .apexcharts-legend-text' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cec_cng_box_bg',
				'selector' => '{{WRAPPER}} .cec-elementor-graph .cec-graph',
			]
        );
        $this->end_controls_section(); // Section End
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('cec-elementor-graph', 'class', 'cec-elementor-graph');
        if( isset( $settings['cecg_country_ids'] ) && ! empty( $settings['cecg_country_ids'] ) ) {
            if( is_array( $settings['cecg_country_ids'] ) ) {
                $this->add_render_attribute('cec-elementor-graph', 'countries', implode(',', array_values( $settings['cecg_country_ids'] ) ) );
            } else {
                $this->add_render_attribute('cec-elementor-graph', 'countries', $settings['cecg_country_ids'] );
            }
        }
        if( isset( $settings['cecg_lastdays'] ) && ! empty( $settings['cecg_lastdays'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'last', $settings['cecg_lastdays'] );
        }
        if( isset( $settings['cecg_graph_type'] ) && ! empty( $settings['cecg_graph_type'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'type', $settings['cecg_graph_type'] );
        }
        if( isset( $settings['cecg_graph_legend_position'] ) && ! empty( $settings['cecg_graph_legend_position'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'legend', $settings['cecg_graph_legend_position'] );
        }
        if( isset( $settings['cecg_data_labels'] ) && ! empty( $settings['cecg_data_labels'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'labels', $settings['cecg_data_labels'] === 'yes' );
        }

        if( isset( $settings['cecg_title'] ) && ! empty( $settings['cecg_title'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'title', $settings['cecg_title'] );
        }
        if( isset( $settings['cecg_case_title'] ) && ! empty( $settings['cecg_case_title'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'case_title', $settings['cecg_case_title'] );
        }
        if( isset( $settings['cecg_deaths_title'] ) && ! empty( $settings['cecg_deaths_title'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'deaths_title', $settings['cecg_deaths_title'] );
        }
        if( isset( $settings['cecg_recovered_title'] ) && ! empty( $settings['cecg_recovered_title'] ) ) {
            $this->add_render_attribute('cec-elementor-graph', 'recovered_title', $settings['cecg_recovered_title'] );
        }
        $output = '<div '.  $this->get_render_attribute_string('cec-elementor-graph') .'>';
            $output .= '<div class="cec-graph"></div>';
        $output .= '</div>';
        echo $output;
    }
}