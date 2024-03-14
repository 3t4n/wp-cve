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

class CoronaCountryWiseElementor extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'ce-countrywise-corona';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'Corona - Country-wise', 'ce-corona' );
	}
    public function get_keywords() {
        return [
            'corona',
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
        return Shortcode::countries();
    }
	/**
	 * Retrieve the list of scripts the widget depended on.
	 */
	public function get_script_depends() {
		return [ 'ce-elementor-country-corona', 'ce-elementor-corona-nformat' ];
	}
	public function get_style_depends() {
		return [ 'ce-elementor-country-corona' ];
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

    public function new_title( $key, $title, $default ){
        return $this->add_control(
			'cec_show_items_title_' . $key ,
			[
				'label'       => __( $title, 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
                'default'     => __( $default, 'ce-corona' ),
                'condition' => [
                    'cec_show_items_' . $key => 'yes'
                ]
			]
        );
    }

	/**
	 * Register the widget controls.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'cec_corona_cn_general_settings',
			[
				'label' => __( 'General Settings', 'ce-corona' ),
			]
        );

        $this->add_control(
			'cec_cn_groupd_country',
			[
				'label' => __( 'Grouped Country', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
			]
        );
        
        $this->add_control(
			'cec_country_id',
			[
				'label'   => __( 'Select Country', 'ce-corona' ),
                'type'    => Controls_Manager::SELECT2,
                'label_block' => true,
				'options' => $this->countries(),
                'default' => 'BD',
                'condition' => [
                    'cec_cn_groupd_country!' => 'yes'
                ]
			]
        );

        $this->add_control(
			'cec_country_ids',
			[
				'label'   => __( 'Select Countries', 'ce-corona' ),
                'type'    => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
				'options' => $this->countries(),
                'default' => 'BD',
                'condition' => [
                    'cec_cn_groupd_country' => 'yes'
                ]
			]
        );

        $this->add_control(
			'cec_cn_states',
			[
				'label' => __( 'Show States', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'cec_country_id' => 'US'
                ]
			]
        );

        $this->add_control(
			'cec_states_table_style',
			[
				'label'   => __( 'Data Table Style', 'ce-corona' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
                    'default' => __( 'Default', 'ce-corona' ),
                    'one'     => __( 'Table Style One', 'ce-corona' ),
                ],
                'default' => 'default',
                'condition' => [
                    'cec_country_id' => 'US'
                ]
			]
        );

        $this->add_control(
			'cec_table_columns',
			[
				'label'       => __( 'Data Table Columns', 'ce-corona' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => [
                    'cases'               => __( 'Total Cases', 'ce-corona' ),
                    'todayCases'          => __( 'New Cases', 'ce-corona' ),
                    'deaths'              => __( 'Total Deaths', 'ce-corona' ),
                    'todayDeaths'         => __( 'New Deaths', 'ce-corona' ),
                    'active'              => __( 'Active', 'ce-corona' ),
                    'tests'               => __( 'Total Tests', 'ce-corona' ),
                    'testsPerOneMillion'  => __( 'Tests/1M', 'ce-corona' ),
                ],
                'default' => [
                    'cases',
                    'todayCases',
                    'deaths',
                    'todayDeaths',
                    'active',
                    'tests',
                ],
                'condition'   => [
                    'cec_cn_states' => 'yes',
                    'cec_states_table_style' => 'default'
                ]
			]
        );

        $this->new_switcher( 'flag', 'Show Flag' );
        $this->new_switcher( 'country_name', 'Show Country Name' );

        $this->add_control(
			'cec_cn_hide_all_title',
			[
				'label' => __( 'Hide All Title', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
			]
        );

		$this->add_control(
			'cec_cn_updated_time',
			[
				'label' => __( 'Update Time Show', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
			]
        );

        $this->new_switcher( 'confirmed', 'Show Confirmed' );
        $this->new_switcher( 'todayCases', 'Show Today Cases' );
        $this->new_switcher( 'deaths', 'Show Deaths' );
        $this->new_switcher( 'todayDeaths', 'Show Today Deaths' );
        $this->new_switcher( 'recovered', 'Show Recovered' );
        $this->new_switcher( 'active', 'Show Active Cases' );
        $this->new_switcher( 'critical', 'Show Critical Cases' );
        $this->new_switcher( 'case_per_m', 'Show Case Per Million' );
        $this->new_switcher( 'deaths_per_m', 'Show Deaths Per Million' );
        $this->new_switcher( 'tests', 'Show Tests' );
        $this->new_switcher( 'tests_per_m', 'Show Tests Per Million' );

        $this->end_controls_section();
        
		$this->start_controls_section(
			'cec_cn_corona_content_settings',
			[
				'label' => __( 'Content', 'ce-corona' ),
			]
        );

        $this->add_control(
			'cec_cn_update_title',
			[
				'label'       => __( 'Update Title', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Last Updated', 'ce-corona' ),
                'condition' => [
                    'cec_cn_updated_time' => 'yes'
                ]
			]
        );

        $this->new_title( 'confirmed', 'Confirmed Title', 'Total Cases' );
        $this->new_title( 'todayCases', 'Today Cases Title', 'New Cases' );
        $this->new_title( 'deaths', 'Deaths Title', 'Total Deaths' );
        $this->new_title( 'todayDeaths', 'Today Deaths Title', 'New Deaths' );
        $this->new_title( 'recovered', 'Recovered Title', 'Total Recovered' );
        $this->new_title( 'active', 'Active Cases Title', 'Active Cases' );
        $this->new_title( 'critical', 'Critical Cases Title', 'Critical' );
        $this->new_title( 'case_per_m', 'Case Per Million Title', 'Case/1M' );
        $this->new_title( 'deaths_per_m', 'Deaths Per Million Title', 'Deaths/1M' );
        $this->new_title( 'tests', 'Total Tests Title', 'Total Tests' );
        $this->new_title( 'tests_per_m', 'Tests Per Million Title', 'Tests/1M' );

        
        $this->end_controls_section();

        $this->start_controls_section(
			'cec_cn_common_style',
			[
				'label' => __( 'Common Style', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

		$this->add_responsive_control(
			'cec_box_margin',
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
			'cec_box_padding',
			[
				'label' => __( 'Padding', 'ce-corona' ),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'cec_cn_style',
			[
				'label' => __( 'Style', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
            'cec_cn_color',
            [
                'label' => __('Country Name Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-elementor-country-wise .cec-elementor-country-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Country Name Typography', 'ce-corona'),
                'name' => 'cec_cn_typo',
                'selector' => '{{WRAPPER}} .cec-elementor-country-wise .cec-elementor-country-name',
            ]
        );

        $this->add_control(
            'cec_cn_updated_color',
            [
                'label' => __('Updated Title Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-elementor-country-wise .cec-elementor-updated-time-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Update Typography', 'ce-corona'),
                'name' => 'cec_cn_updated_typo',
                'selector' => '{{WRAPPER}} .cec-elementor-country-wise .cec-elementor-updated-time-wrapper',
                'condition' => [
                    'cec_cn_updated_time' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Box Title Typography', 'ce-corona'),
                'name' => 'cec_cn_box_title_typo',
                'selector' => '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe .cec-cn-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Box Number Typography', 'ce-corona'),
                'name' => 'cec_cn_box_number_typo',
                'selector' => '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe .cec-cn-number',
            ]
        );

        $this->start_controls_tabs('cec_stats_box_tabs');

        $this->start_controls_tab('cec_stats_box_tabs_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);

        $this->add_control(
            'cec_cn_box_text_align',
            [
                'label' => esc_html__('Text Align', 'ce-corona'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'ce-corona'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ce-corona'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'ce-corona'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cec_cn_box_title_color',
            [
                'label' => __('Box Title Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe .cec-cn-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cec_cn_box_number_color',
            [
                'label' => __('Box Number Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe .cec-cn-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Box Background', 'ce-corona'),
                'name' => 'cec_cn_box_bg',
                'types' => ['classic', 'gradient'],
                'default' => '#999',
                'selector' => '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div',
            ]
        );

        $this->end_controls_tab(); // normal end

        $this->start_controls_tab('cec_stats_box_tabs_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);

        $this->add_control(
            'cec_cn_box_title_color_hover',
            [
                'label' => __('Box Title Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe:hover .cec-cn-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cec_cn_box_number_color_hover',
            [
                'label' => __('Box Number Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe:hover .cec-cn-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Box Background', 'ce-corona'),
                'name' => 'cec_cn_box_bg_hover',
                'types' => ['classic', 'gradient'],
                'default' => '#999',
                'selector' => '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe:hover > div',
            ]
        );

        $this->add_control(
            'cec_cn_box_transition',
            [
                'label' => esc_html__('Hover Transition', 'ce-corona'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'max' => 4000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe' => 'transition: {{SIZE}}ms;',
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe .cec-cn-title' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_tab(); // hover end
        $this->end_controls_tabs(); // Tabs End
        $this->end_controls_section(); // Section End
        $this->start_controls_section(
			'cec_box_background_overlay_section',
			[
				'label' => __( 'Box Background Overlay', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'cec_cn_box_bg_background' => [ 'classic', 'gradient' ],
				],
			]
        );

        $this->start_controls_tabs('cec_box_background_overlay_tabs');
        $this->start_controls_tab('cec_box_background_overlay_tabs_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cec_cn_box_bg_overlay',
				'selector' => '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div::after',
			]
        );

        $this->add_control(
			'cec_cn_box_bg_overlay_opacity',
			[
				'label' => __( 'Opacity', 'ce-corona' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div::after' => 'opacity: {{SIZE}};',
				],
			]
		);

        $this->end_controls_tab(); // hover end
        $this->start_controls_tab('cec_box_background_overlay_tabs_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cec_cn_box_bg_overlay_hover',
				'selector' => '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div::after',
			]
        );
        
        $this->add_control(
			'cec_cn_box_bg_overlay_opacity_hover',
			[
				'label' => __( 'Opacity', 'ce-corona' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe:hover > div::after' => 'opacity: {{SIZE}};',
				],
			]
        );
        
        $this->add_control(
            'cec_cn_box_bg_overlay_transition',
            [
                'label' => esc_html__('Hover Transition', 'ce-corona'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                ],
                'range' => [
                    'px' => [
                        'max' => 4000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cec-cn-case-wrapper .cec-cn-case-singe > div::after' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_tab(); // hover end
        $this->end_controls_tabs(); // Tabs End

        $this->end_controls_section(); // Section End
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('cec-elementor-country-wise', 'class', 'cec-elementor-country-wise');
        $this->add_render_attribute('cec-elementor-country-wise', 'class', 'cec-elementor-country-wise-loading');
        if( isset( $settings['cec_country_id'] ) && ! empty( $settings['cec_country_id'] ) ) {
            $this->add_render_attribute('cec-elementor-country-wise', 'country_name', $settings['cec_country_id'] );
        }
        if( isset( $settings['cec_country_ids'] ) && ! empty( $settings['cec_country_ids'] ) ) {
            $this->add_render_attribute('cec-elementor-country-wise', 'country_name', \implode(',', $settings['cec_country_ids'] ) );
        }
        if( isset( $settings['cec_states_table_style'] ) && ! empty( $settings['cec_states_table_style'] ) ) {
            $this->add_render_attribute('cec-elementor-country-wise', 'table_style', $settings['cec_states_table_style'] );
        }
        if( isset( $settings['cec_table_columns'] ) && ! empty( $settings['cec_table_columns'] ) ) {
            $this->add_render_attribute('cec-elementor-country-wise', 'cec_columns', \json_encode( $settings['cec_table_columns'] ) );
        }

        $is_grouped = ( isset( $settings['cec_cn_groupd_country'] ) && $settings['cec_cn_groupd_country'] === 'yes' ) ? true : false;

        $output = '<div '.  $this->get_render_attribute_string('cec-elementor-country-wise') .'>';
            if( $is_grouped ) {
                
                if( isset( $settings['cec_country_ids'] ) && ! empty( $settings['cec_country_ids'] ) ) {
                    foreach( $settings['cec_country_ids'] as $country_name ) {
                        $output .= $this->single_country_output( $settings, $country_name );
                    }
                }

            } else {
                $output .= $this->single_country_output( $settings, $settings['cec_country_id'] );
            }
        $output .= '</div>';
        
        echo $output;
    }

    protected function single_country_output( $settings, $country_name = null ){
        $output = '';
        $output .= '<div class="cec-elementor-country-wise-inner cec-country-'. $country_name .'">';
            if( $this->check_switch( 'cec_show_items_flag', 'yes', $settings ) ||  $this->check_switch( 'cec_show_items_country_name', 'yes', $settings ) ||  $this->check_switch( 'cec_cn_updated_time', 'yes', $settings ) ) {
                $output .= '<div class="cec-elementor-country">';
                    if( $this->check_switch( 'cec_show_items_flag', 'yes', $settings ) ) {
                        $output .= '<img class="cec-elementor-country-flag" src="https://disease.sh/assets/img/flags/unknown.png" />';
                    }
                    if( $this->check_switch( 'cec_show_items_country_name', 'yes', $settings ) || $this->check_switch( 'cec_cn_updated_time', 'yes', $settings ) ) {
                        $output .= '<div class="cec-elementor-country-name-wrapper">';
                            if( $this->check_switch( 'cec_show_items_country_name', 'yes', $settings ) ) {
                                $output .= '<span class="cec-elementor-country-name">'. $this->countries()[ $country_name ] .'</span>';
                            }
                            if( $this->check_switch( 'cec_cn_updated_time', 'yes', $settings ) ) {
                                $output .= '<span class="cec-elementor-updated-time-wrapper">'. $this->get_box_title( $settings, 'cec_cn_update_title', 'Last Updated' ) .': <span class="cec-elementor-updated-time">Loading...</span></span>';
                            }
                        $output .= '</div>';
                    }
                $output .= '</div>';
            }
            $output .= '<div class="clearfix cec-cn-case-wrapper">';
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_confirmed', 'yes', $settings ), 'confirmed', $this->get_box_title( $settings, 'cec_show_items_title_confirmed', 'Total Confirmed' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_todayCases', 'yes', $settings ), 'todayCases', $this->get_box_title( $settings, 'cec_show_items_title_todayCases', 'New Cases' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_deaths', 'yes', $settings ), 'deaths', $this->get_box_title( $settings, 'cec_show_items_title_deaths', 'Total Deaths' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_todayDeaths', 'yes', $settings ), 'todayDeaths', $this->get_box_title( $settings, 'cec_show_items_title_todayDeaths', 'New Deaths' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_recovered', 'yes', $settings ), 'recovered', $this->get_box_title( $settings, 'cec_show_items_title_recovered', 'Total Recovered' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_active', 'yes', $settings ), 'active', $this->get_box_title( $settings, 'cec_show_items_title_active', 'Total active' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_critical', 'yes', $settings ), 'critical', $this->get_box_title( $settings, 'cec_show_items_title_critical', 'in Critical' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_case_per_m', 'yes', $settings ), 'case_per_m', $this->get_box_title( $settings, 'cec_show_items_title_case_per_m', 'Case/1M' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_deaths_per_m', 'yes', $settings ), 'deaths_per_m', $this->get_box_title( $settings, 'cec_show_items_title_deaths_per_m', 'Deaths/1M' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_tests', 'yes', $settings ), 'tests', $this->get_box_title( $settings, 'cec_show_items_title_tests', 'Total Tests' ), 0 );
            $output .= $this->case_box( $settings, $this->check_switch( 'cec_show_items_tests_per_m', 'yes', $settings ), 'tests_per_m', $this->get_box_title( $settings, 'cec_show_items_title_tests_per_m', 'Tests/1M' ), 0 );
            $output .= '</div>';
        $output .= '</div>';
        if( isset( $settings['cec_cn_states'] ) && $settings['cec_cn_states'] === 'yes' ) {
            $output .= '<div id="cecStatesElementor"></div>';
        }
        return $output;
    }
    
    public function check_switch( $key, $with, $settings ){
        return isset( $settings[ $key ] ) && $settings[ $key ] === $with;
    }

    public function get_box_title( $settings, $key, $default = '' ){
        if( empty( $key ) ) {
            return $default;
        }
        if( isset( $settings[ $key ] ) && ! empty( $settings[ $key ] ) ) {
            return $settings[ $key ];
        }
        return $default;
    }

    public function case_box( $settings = null, $checked, $key, $title, $value ){
        $output = '';
        if( $checked ) {
            $output .= '<div class="cec-cn-case-singe cec-cn-case-'. $key .'">';
                $output .= '<div>';
                    if( isset( $settings ['cec_cn_hide_all_title'] ) && $settings ['cec_cn_hide_all_title'] !== 'yes' ) {
                        $output .= '<span class="cec-cn-title">' . __( $title, 'ce-corona' ) . '</span>';
                    }
                    $output .= '<span class="cec-cn-number">'. $value .'</span>';
                $output .= '</div>';
            $output .= '</div>';
        }
        return $output;
    }
}