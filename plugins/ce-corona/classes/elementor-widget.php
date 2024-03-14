<?php
namespace CoderExpert\Corona;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CoronaElementor extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'ce-corona';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return __( 'Corona', 'ce-corona' );
	}
    public function get_keywords()
    {
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

	/**
	 * Retrieve the list of scripts the widget depended on.
	 */
	public function get_script_depends() {
		return [ 'ce-elementor-corona' ];
	}
	public function get_style_depends() {
		return [ 'ce-elementor-corona' ];
	}

	/**
	 * Register the widget controls.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'cec_corona_general_settings',
			[
				'label' => __( 'General Settings', 'ce-corona' ),
			]
        );

		$this->add_control(
			'cec_total_stats',
			[
				'label' => __( 'Global Data', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
			]
        );

		$this->add_control(
			'cec_data_table',
			[
				'label' => __( 'Data Table', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
			]
        );

        $this->add_control(
			'cec_table_countries',
			[
				'label'       => __( 'Select Countries', 'ce-corona' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->countries(),
				'default'     => '',
				'condition'   => [
                    'cec_data_table' => 'yes'
                ]
			]
        );

        $this->add_control(
			'cec_table_style',
			[
				'label'   => __( 'Data Table Style', 'ce-corona' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
                    'default' => __( 'Default', 'ce-corona' ),
                    'one'     => __( 'Table Style One', 'ce-corona' ),
                ],
				'default' => 'default',
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
                    'recovered'           => __( 'Recovered', 'ce-corona' ),
                    'active'              => __( 'Active', 'ce-corona' ),
                    'critical'            => __( 'in Critical', 'ce-corona' ),
                    'tests'               => __( 'Total Tests', 'ce-corona' ),
                    'casesPerOneMillion'  => __( 'Cases/1M', 'ce-corona' ),
                    'deathsPerOneMillion' => __( 'Deaths/1M', 'ce-corona' ),
                    'testsPerOneMillion'  => __( 'Tests/1M', 'ce-corona' ),
                    'population'          => __( 'Population', 'ce-corona' ),
                ],
                'default' => [
                    'cases',
                    'todayCases',
                    'deaths',
                    'todayDeaths',
                    'recovered',
                    'active',
                    'critical',
                    'tests',
                ],
                'condition'   => [
                    'cec_data_table' => 'yes',
                    'cec_table_style' => 'default'
                ]
			]
        );

        $this->add_control(
			'cec_compareCountry',
			[
				'label' => __( 'Compare Country', 'ce-corona' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __( 'Compare Data for Different country on a specific date.', 'ce-corona' ),
			]
        );

        $this->add_control(
			'cec_last_update',
			[
				'label'   => __( 'Last Update Time', 'ce-corona' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
        );

        $this->add_control(
			'cec_button_position',
			[
				'label'   => __( 'Comparison Button Position', 'ce-corona' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
                    'above_data_table' => __( 'Above Data Table', 'ce-corona' ),
                    'inside_stats' => __( 'Inside Stats Box', 'ce-corona' ),
                ],
				'default' => 'above_data_table',
			]
        );

        $this->end_controls_section();

		$this->start_controls_section(
			'cec_corona_content_settings',
			[
				'label' => __( 'Content', 'ce-corona' ),
			]
        );

        $this->add_control(
			'cec_stats_title',
			[
				'label'       => __( 'Title', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Total Stats', 'ce-corona' ),
			]
		);

        // $this->add_control(
		// 	'cec_select_stats_fields',
		// 	[
		// 		'label' => __( 'Stats Fields', 'ce-corona' ),
        //         'type' => Controls_Manager::SELECT2,
        //         'options'	=> [
        //             'all'       => __( 'All', 'ce-corona'),
        //             'cases'     => __( 'Confirmed Cases', 'ce-corona'),
        //             'recovered' => __( 'Total Recovered', 'ce-corona'),
        //             'deaths'    => __( 'Total Deaths', 'ce-corona'),
        //             'active'    => __( 'Active Cases', 'ce-corona'),
        //             'affectedCountries'    => __( 'Affected Countries', 'ce-corona'),
        //         ],
        //         'multiple'    => true,
        //         'label_block' => true,
        //         'default' => 'all',
		// 	]
        // );

        $this->add_control(
			'cec_stats_cases_title',
			[
				'label'       => __( 'Confirmed Title', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Confirmed Cases', 'ce-corona' ),
			]
		);
        $this->add_control(
			'cec_stats_recovered_title',
			[
				'label'       => __( 'Recovered Title', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Total Recovered', 'ce-corona' ),
			]
		);
        $this->add_control(
			'cec_stats_deaths_title',
			[
				'label'       => __( 'Deaths Title', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Total Deaths', 'ce-corona' ),
			]
		);
        $this->add_control(
			'cec_stats_active_title',
			[
				'label'       => __( 'Active Title', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Active Cases', 'ce-corona' ),
			]
		);
        $this->add_control(
			'cec_stats_affected_title',
			[
				'label'       => __( 'Affected Countries Title', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Affected Countries', 'ce-corona' ),
			]
		);


        $this->add_control(
			'cec_stats_recent_btn_text',
			[
				'label'       => __( 'Recent Button Text', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Recent', 'ce-corona' ),
                'condition' => [
                    'cec_data_table' => 'yes',
                ]
			]
        );

        $this->add_control(
			'cec_stats_compare_btn_text',
			[
				'label'       => __( 'Compare Button Text', 'ce-corona' ),
				'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Compare Data by Country', 'ce-corona' ),
                'condition' => [
                    'cec_compareCountry' => 'yes'
                ]
			]
		);

        // $this->add_control(
		// 	'cec_stats_title_cases',
		// 	[
		// 		'label'       => __( 'Confirmed Title', 'ce-corona' ),
		// 		'type'        => Controls_Manager::TEXT,
        //         'default'     => __( 'Total Cases', 'ce-corona' ),
		// 	]
        // );

        // $this->add_control(
		// 	'cec_stats_title_recovered',
		// 	[
		// 		'label'       => __( 'Recovered Title', 'ce-corona' ),
		// 		'type'        => Controls_Manager::TEXT,
        //         'default'     => __( 'Total Recovered', 'ce-corona' ),
		// 	]
        // );

        // $this->add_control(
		// 	'cec_stats_title_deaths',
		// 	[
		// 		'label'       => __( 'Deaths Title', 'ce-corona' ),
		// 		'type'        => Controls_Manager::TEXT,
        //         'default'     => __( 'Total Deaths', 'ce-corona' ),
		// 	]
		// );

        $this->end_controls_section();
		$this->start_controls_section(
			'cec_total_stats_style',
			[
				'label' => __( 'Total Stats Style', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
            'cec_stats_title_color',
            [
                'label' => __('Stats Title Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-corona-total-stats .cec-ts-header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Stats Title Typography', 'ce-corona'),
                'name' => 'cec_stats_title_typo',
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-header',
            ]
        );

        $this->add_control(
            'cec_stats_last_updated_color',
            [
                'label' => __('Updated Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-corona-total-stats .cec-ts-last-updated' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Update Text Typography', 'ce-corona'),
                'name' => 'cec_stats_last_updated_typo',
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-last-updated',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Total Stats Background', 'ce-corona'),
                'name' => 'cec_total_stats_bg',
                'types' => ['classic', 'gradient'],
                'default' => '#999',
                'selector' => '{{WRAPPER}} .ce-corona-total-stats',
            ]
        );

        $this->start_controls_tabs('cec_stats_box_tabs');

        $this->start_controls_tab('cec_stats_box_tabs_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);

        $this->add_control(
            'cec_stats_box_text_color',
            [
                'label' => __('Case Box Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Case Box Background', 'ce-corona'),
                'name' => 'cec_stats_box_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'label' => __('Case Box Border', 'ce-corona'),
                'name' => 'cec_stats_box_border',
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single',
            ]
        );
        $this->add_control(
			'cec_stats_box_border_radius',
			[
				'label'      => __( 'Box Border Radius', 'essential-addons-for-elementor-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ce-corona-total-stats .cec-ts-single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label' => __('Case Box Box Shadow', 'ce-corona'),
                'name' => 'cec_stats_box_box_shadow',
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Case Title Typography', 'ce-corona'),
                'name' => 'cec_stats_box_title_typo',
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single .cec-tss-header',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Case Number Typography', 'ce-corona'),
                'name' => 'cec_stats_box_number_typo',
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single > strong > span',
            ]
        );

        $this->end_controls_tab(); // normal end

        $this->start_controls_tab('cec_stats_box_tabs_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);

        $this->add_control(
            'cec_stats_box_text_hover_color',
            [
                'label' => __('Case Box Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Case Box Background', 'ce-corona'),
                'name' => 'cec_stats_box_hover_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single:hover',
            ]
        );

        $this->add_control(
            'cec_stats_box_border_hover_color',
            [
                'label' => __('Border Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cec_stats_box_hover_transition',
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
                    '{{WRAPPER}} .ce-corona-total-stats .cec-ts-single' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_tab(); // hover end
        $this->end_controls_tabs(); // Tabs End
        $this->end_controls_section(); // Section End
        $this->start_controls_section(
			'cec_compare_button_style',
			[
				'label' => __( 'Input & Button Design', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
            'cec_com_search_bg_color',
            [
                'label' => __('Search Background Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-comparison .components-base-control.cec-search-country input.components-text-control__input' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cec_com_search_border_color',
            [
                'label' => __('Search Border Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-comparison .components-base-control.cec-search-country input.components-text-control__input' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cec_com_search_color',
            [
                'label' => __('Search Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-comparison .components-base-control.cec-search-country input.components-text-control__input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('cec_compare_btn_tabs');; // Tabs Start
        $this->start_controls_tab('cec_compare_btn_tabs_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);

        $this->add_control(
            'cec_com_btn_bg_color',
            [
                'label' => __('Background Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-comparison .components-button.cec-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'label' => __('Border', 'ce-corona'),
                'name' => 'cec_com_btn_border',
                'selector' => '{{WRAPPER}} .cec-comparison .components-button.cec-btn',
            ]
        );

        $this->add_control(
			'cec_com_btn_border_radius',
			[
				'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .cec-comparison .components-button.cec-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab(); // hover end
        $this->start_controls_tab('cec_compare_btn_tabs_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);

        $this->add_control(
            'cec_com_btn_bg_color_hover',
            [
                'label' => __('Background Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-comparison .components-button.cec-btn:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .cec-comparison .components-button.cec-btn.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cec_com_btn_border_color_hover',
            [
                'label' => __('Border Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-comparison .components-button.cec-btn:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
			'cec_com_btn_border_radius_hover',
			[
				'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .cec-comparison .components-button.cec-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'cec_com_btn_bg_color_hover_transition',
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
                    '{{WRAPPER}} .cec-comparison .components-button.cec-btn' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_tab(); // hover end
        $this->end_controls_tabs(); // Tabs End

        $this->end_controls_section();

        $this->start_controls_section(
			'cec_data_table_two_style',
			[
				'label' => __( 'Data Table One - Style', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->start_controls_tabs('cec_data_table_two_style_tabs');
        $this->start_controls_tab('cec_data_table_two_style_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Background', 'ce-corona'),
                'name' => 'cec_data_table_two_box_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cec-table-country-box .cec-table-country-item, {{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single, {{WRAPPER}} .cec-table-country-box > header > div.cec-table-country-box-name-wrapper',
                'fields_options' => [
					'image' => [],
				]
            ]
        );
        $this->add_control(
            'cec_data_table_two_box_border_color',
            [
                'label' => __('Border Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-item' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box > header > div.cec-table-country-box-name-wrapper' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cec_data_table_two_box_text_color',
            [
                'label' => __('Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-item' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box > header > div.cec-table-country-box-name-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab(); // hover end
        $this->start_controls_tab('cec_data_table_two_style_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Background', 'ce-corona'),
                'name' => 'cec_data_table_two_box_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cec-table-country-box .cec-table-country-item:hover, {{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single:hover, {{WRAPPER}} .cec-table-country-box:hover > header > div.cec-table-country-box-name-wrapper',
            ]
        );
        $this->add_control(
            'cec_data_table_two_box_border_color_hover',
            [
                'label' => __('Border Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-item:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box:hover > header > div.cec-table-country-box-name-wrapper' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cec_data_table_two_box_text_color_hover',
            [
                'label' => __('Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-item:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-table-country-box:hover > header > div.cec-table-country-box-name-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cec_data_table_two_box_hover_transition',
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
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-item' => 'transition: {{SIZE}}ms;',
                    '{{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single' => 'transition: {{SIZE}}ms;',
                    '{{WRAPPER}} .cec-table-country-box > header > div.cec-table-country-box-name-wrapper' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );

        $this->end_controls_tab(); // hover end
        $this->end_controls_tabs(); // Tabs End

        $this->end_controls_section();

        // $this->start_controls_section(
		// 	'cec_data_table_two_style_overlay',
		// 	[
		// 		'label' => __( 'Data Table One - Style Overlay', 'ce-corona' ),
        //         'tab' => Controls_Manager::TAB_STYLE,
        //         'condition' => [
        //             'cec_data_table_two_box_bg_background' => [ 'classic', 'gradient' ]
        //         ]
		// 	]
        // );

        // $this->start_controls_tabs('cec_data_table_two_style_overlay_tabs');
        // $this->start_controls_tab('cec_data_table_two_style_overlay_tabs_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);

        // $this->add_group_control(
        //     Group_Control_Background::get_type(),
        //     [
        //         'label' => __('Background', 'ce-corona'),
        //         'name' => 'cec_data_table_two_style_overlay_bg',
        //         'types' => ['classic', 'gradient'],
        //         'selector' => '{{WRAPPER}} .cec-table-country-box .cec-table-country-item::after, {{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single::after, {{WRAPPER}} .cec-table-country-box > header > div.cec-table-country-box-name-wrapper::after',
        //     ]
        // );

        // $this->end_controls_tab(); // hover end
        // $this->start_controls_tab('cec_data_table_two_style_overlay_tabs_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);

        // $this->add_group_control(
        //     Group_Control_Background::get_type(),
        //     [
        //         'label' => __('Background', 'ce-corona'),
        //         'name' => 'cec_data_table_two_style_overlay_bg_hover',
        //         'types' => ['classic', 'gradient'],
        //         'selector' => '{{WRAPPER}} .cec-table-country-box .cec-table-country-item:hover::after, {{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single:hover::after, {{WRAPPER}} .cec-table-country-box:hover > header > div.cec-table-country-box-name-wrapper::after',
        //     ]
        // );

        // $this->add_control(
        //     'cec_data_table_two_style_overlay_tabs_hover_transition',
        //     [
        //         'label' => esc_html__('Hover Transition', 'ce-corona'),
        //         'type' => Controls_Manager::SLIDER,
        //         'default' => [
        //             'size' => 500,
        //         ],
        //         'range' => [
        //             'px' => [
        //                 'max' => 4000,
        //             ],
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}} .cec-table-country-box .cec-table-country-item::after' => 'transition: {{SIZE}}ms;',
        //             '{{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single::after' => 'transition: {{SIZE}}ms;',
        //             '{{WRAPPER}} .cec-table-country-box > header > div.cec-table-country-box-name-wrapper::after' => 'transition: {{SIZE}}ms;',
        //         ],
        //     ]
        // );

        // $this->add_control(
		// 	'cec_data_table_two_style_overlay_tabs_hover_opacity',
		// 	[
		// 		'label' => __( 'Opacity', 'ce-corona' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'default' => [
		// 			'size' => .5,
		// 		],
		// 		'range' => [
		// 			'px' => [
		// 				'max' => 1,
		// 				'step' => 0.01,
		// 			],
		// 		],
		// 		'selectors' => [
        //             '{{WRAPPER}} .cec-table-country-box .cec-table-country-item::after' => 'opacity: {{SIZE}};',
        //             '{{WRAPPER}} .cec-table-country-box .cec-table-country-box-meta-single::after' => 'opacity: {{SIZE}};',
        //             '{{WRAPPER}} .cec-table-country-box > header > div.cec-table-country-box-name-wrapper::after' => 'opacity: {{SIZE}};',
		// 		],
		// 	]
		// );

        // $this->end_controls_tab(); // hover end
        // $this->end_controls_tabs(); // Tabs End

        // $this->end_controls_section();

        $this->start_controls_section(
			'cec_cn_card_name_section',
			[
				'label' => __( 'Compare Country Card Style', 'ce-corona' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'cec_compareCountry' => 'yes'
                ]
			]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __('Country Name Typography', 'ce-corona'),
                'name' => 'cec_cn_card_name_type',
                'selector' => '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box .cec-country-name',
            ]
        );

        $this->add_control(
            'cec_cn_card_list_item_margin_bottom',
            [
                'label' => esc_html__('Margin Bottom', 'ce-corona'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box > ul > li:not(:last-of-type)' => 'margin-bottom: {{SIZE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs('cec_cn_card_tab');
        $this->start_controls_tab('cec_cn_card_tab_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);
        $this->add_control(
            'cec_cn_card_name_color',
            [
                'label' => __('Country Name Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box .cec-country-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cec_cn_card_text_color',
            [
                'label' => __('Box Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box .cec-danger' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box .cec-success' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Background', 'ce-corona'),
                'name' => 'cec_cn_card_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'label' => __('Border', 'ce-corona'),
                'name' => 'cec_cn_card_border',
                'selector' => '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label' => __('Box Shadow', 'ce-corona'),
                'name' => 'cec_cn_card_box_shadow',
                'selector' => '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box',
            ]
        );
        //TODO: Current
        $this->end_controls_tab(); // hover end
        $this->start_controls_tab('cec_cn_card_tab_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);
        $this->add_control(
            'cec_cn_card_name_color_hover',
            [
                'label' => __('Country Name Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover .cec-country-name' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cec_cn_card_text_color_hover',
            [
                'label' => __('Box Text Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover .cec-danger' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover .cec-success' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Background', 'ce-corona'),
                'name' => 'cec_cn_card_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover',
            ]
        );

        $this->add_control(
            'cec_cn_card_border_color_hover',
            [
                'label' => __('Border Color', 'ce-corona'),
                'type'        => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cec_cn_card_hover_transition',
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
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box' => 'transition: {{SIZE}}ms;',
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover .cec-country-name' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );
        $this->end_controls_tab(); // hover end
        $this->end_controls_tabs(); // Tabs End

        $this->end_controls_section();

        //TODO: Overlay
        $this->start_controls_section(
			'cec_cn_card_bg_overlay',
			[
				'label' => __( 'Card Background Overlay', 'ce-corona' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'cec_cn_card_bg_background' => [ 'classic', 'gradient' ],
				],
			]
        );

        $this->start_controls_tabs('cec_cn_card_bg_overlay_tab');
        $this->start_controls_tab('cec_cn_card_bg_overlay_tab_normal', [ 'label' => __( 'Normal', 'ce-corona' ) ]);
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Background', 'ce-corona'),
                'name' => 'cec_cn_card_bg_overlay_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box::after',
            ]
        );
        $this->add_control(
			'cec_cn_card_bg_overlay_bg_opacity',
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
					'{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box::after' => 'opacity: {{SIZE}};',
				],
			]
		);
        $this->end_controls_tab(); // hover end
        $this->start_controls_tab('cec_cn_card_bg_overlay_tab_hover', [ 'label' => __( 'Hover', 'ce-corona' ) ]);
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'label' => __('Background', 'ce-corona'),
                'name' => 'cec_cn_card_bg_overlay_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover::after',
            ]
        );
        $this->add_control(
			'cec_cn_card_bg_overlay_bg_hover_opacity',
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
					'{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box:hover::after' => 'opacity: {{SIZE}};',
				],
			]
		);
        $this->add_control(
            'cec_cn_card_bg_overlay_transition',
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
                    '{{WRAPPER}} .cec-compare.cec-compare-by-date .cec-country-box::after' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );
        $this->end_controls_tab(); // hover end
        $this->end_controls_tabs(); // Tabs End

        $this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('cec-elementor', 'class', 'cec-elementor');
        $this->add_render_attribute('cec-elementor', 'global_data',
            isset( $settings['cec_total_stats'] ) ? ! empty( $settings['cec_total_stats'] ) ? $settings['cec_total_stats'] : 'no' : 'no'
        );
        $this->add_render_attribute('cec-elementor', 'data_table',
            isset( $settings['cec_data_table'] ) ? ! empty( $settings['cec_data_table'] ) ? $settings['cec_data_table'] : 'no' : 'no'
        );
        $this->add_render_attribute('cec-elementor', 'countries',
            isset( $settings['cec_table_countries'] ) ? ! empty( $settings['cec_table_countries'] ) ? \implode(',', $settings['cec_table_countries'] ) : '' : ''
        );
        $this->add_render_attribute('cec-elementor', 'columns',
            isset( $settings['cec_table_columns'] ) ? ! empty( $settings['cec_table_columns'] ) ? \json_encode( $settings['cec_table_columns'] ) : '' : ''
        );
        $this->add_render_attribute('cec-elementor', 'btn_position',
            isset( $settings['cec_button_position'] ) ? ! empty( $settings['cec_button_position'] ) ? $settings['cec_button_position'] : '' : ''
        );
        $this->add_render_attribute('cec-elementor', 'table_style',
            isset( $settings['cec_table_style'] ) ? ! empty( $settings['cec_table_style'] ) ? $settings['cec_table_style'] : 'default' : 'default'
        );
        $this->add_render_attribute('cec-elementor', 'lastupdate',
            isset( $settings['cec_last_update'] ) ? ! empty( $settings['cec_last_update'] ) ? $settings['cec_last_update'] : 'no' : 'no'
        );
        $this->add_render_attribute('cec-elementor', 'compareCountry',
            isset( $settings['cec_compareCountry'] ) ? ! empty( $settings['cec_compareCountry'] ) ? $settings['cec_compareCountry'] : 'no' : 'no'
        );
        $this->add_render_attribute('cec-elementor', 'stats_title',
            isset( $settings['cec_stats_title'] ) ? ! empty( $settings['cec_stats_title'] ) ? $settings['cec_stats_title'] : 'Total Stats' : 'Total Stats'
        );
        $this->add_render_attribute('cec-elementor', 'compare_text',
            isset( $settings['cec_stats_compare_btn_text'] ) ? ! empty( $settings['cec_stats_compare_btn_text'] ) ? $settings['cec_stats_compare_btn_text'] : 'Total Stats' : 'Total Stats'
        );
        $this->add_render_attribute('cec-elementor', 'recent_text',
            isset( $settings['cec_stats_recent_btn_text'] ) ? ! empty( $settings['cec_stats_recent_btn_text'] ) ? $settings['cec_stats_recent_btn_text'] : 'Total Stats' : 'Total Stats'
        );
        // $this->add_render_attribute('cec-elementor', 'stats_fields',
        //     isset( $settings['cec_select_stats_fields'] ) ? ! empty( $settings['cec_select_stats_fields'] ) ? implode( ',', $settings['cec_select_stats_fields'] ) : 'all' : 'all'
        // );
        $this->add_render_attribute('cec-elementor', 'affected_title',
            isset( $settings['cec_stats_affected_title'] ) ?
            ! empty( $settings['cec_stats_affected_title'] ) ? $settings['cec_stats_affected_title']
            : '' : __( 'Affected Countries', 'ce-corona' )
        );

        $this->add_render_attribute('cec-elementor', 'active_title',
            isset( $settings['cec_stats_active_title'] ) ?
            ! empty( $settings['cec_stats_active_title'] ) ? $settings['cec_stats_active_title']
            : '' : __( 'Active Cases', 'ce-corona' )
        );

        $this->add_render_attribute('cec-elementor', 'deaths_title',
            isset( $settings['cec_stats_deaths_title'] ) ?
            ! empty( $settings['cec_stats_deaths_title'] ) ? $settings['cec_stats_deaths_title']
            : '' : __( 'Total Deaths', 'ce-corona' )
        );

        $this->add_render_attribute('cec-elementor', 'recovered_title',
            isset( $settings['cec_stats_recovered_title'] ) ?
            ! empty( $settings['cec_stats_recovered_title'] ) ? $settings['cec_stats_recovered_title']
            : '' : __( 'Total Recovered', 'ce-corona' )
        );

        $this->add_render_attribute('cec-elementor', 'confirmed_title',
            isset( $settings['cec_stats_cases_title'] ) ?
            ! empty( $settings['cec_stats_cases_title'] ) ? $settings['cec_stats_cases_title']
            : '' : __( 'Total Cases', 'ce-corona' )
        );

		echo '<div '.  $this->get_render_attribute_string('cec-elementor') .'>';
            echo '<div id="ce-elementor-corona" class="alignwide"></div>';
		echo '</div>';
    }

    public function countries(){
        return array(
            "AF"  => "Afghanistan",
            "AL"  => "Albania",
            "DZ"  => "Algeria",
            "AS"  => "American Samoa",
            "AD"  => "Andorra",
            "AO"  => "Angola",
            "AI"  => "Anguilla",
            "AQ"  => "Antarctica",
            "AG"  => "Antigua and Barbuda",
            "AR"  => "Argentina",
            "AM"  => "Armenia",
            "AW"  => "Aruba",
            "AU"  => "Australia",
            "AT"  => "Austria",
            "AZ"  => "Azerbaijan",
            "BS"  => "Bahamas",
            "BH"  => "Bahrain",
            "BD"  => "Bangladesh",
            "BB"  => "Barbados",
            "BY"  => "Belarus",
            "BE"  => "Belgium",
            "BZ"  => "Belize",
            "BJ"  => "Benin",
            "BM"  => "Bermuda",
            "BT"  => "Bhutan",
            "BO"  => "Bolivia",
            "BA"  => "Bosnia and Herzegovina",
            "BW"  => "Botswana",
            "BV"  => "Bouvet Island",
            "BR"  => "Brazil",
            "IO"  => "British Indian Ocean Territory",
            "BN"  => "Brunei Darussalam",
            "BG"  => "Bulgaria",
            "BF"  => "Burkina Faso",
            "BI"  => "Burundi",
            "KH"  => "Cambodia",
            "CM"  => "Cameroon",
            "CA"  => "Canada",
            "CV"  => "Cape Verde",
            "KY"  => "Cayman Islands",
            "CF"  => "Central African Republic",
            "TD"  => "Chad",
            "CL"  => "Chile",
            "CN"  => "China",
            "CX"  => "Christmas Island",
            "CC"  => "Cocos (Keeling) Islands",
            "CO"  => "Colombia",
            "KM"  => "Comoros",
            "CG"  => "Congo",
            "CD"  => "Congo, the Democratic Republic of the",
            "CK"  => "Cook Islands",
            "CR"  => "Costa Rica",
            "CI"  => "Cote D'Ivoire",
            "HR"  => "Croatia",
            "CU"  => "Cuba",
            "CY"  => "Cyprus",
            "CZ"  => "Czech Republic",
            "DK"  => "Denmark",
            "DJ"  => "Djibouti",
            "DM"  => "Dominica",
            "DO"  => "Dominican Republic",
            "EC"  => "Ecuador",
            "EG"  => "Egypt",
            "SV"  => "El Salvador",
            "GQ"  => "Equatorial Guinea",
            "ER"  => "Eritrea",
            "EE"  => "Estonia",
            "ET"  => "Ethiopia",
            "FK"  => "Falkland Islands (Malvinas)",
            "FO"  => "Faroe Islands",
            "FJ"  => "Fiji",
            "FI"  => "Finland",
            "FR"  => "France",
            "GF"  => "French Guiana",
            "PF"  => "French Polynesia",
            "TF"  => "French Southern Territories",
            "GA"  => "Gabon",
            "GM"  => "Gambia",
            "GE"  => "Georgia",
            "DE"  => "Germany",
            "GH"  => "Ghana",
            "GI"  => "Gibraltar",
            "GR"  => "Greece",
            "GL"  => "Greenland",
            "GD"  => "Grenada",
            "GP"  => "Guadeloupe",
            "GU"  => "Guam",
            "GT"  => "Guatemala",
            "GN"  => "Guinea",
            "GW"  => "Guinea-Bissau",
            "GY"  => "Guyana",
            "HT"  => "Haiti",
            "HM"  => "Heard Island and Mcdonald Islands",
            "VA"  => "Holy See (Vatican City State)",
            "HN"  => "Honduras",
            "HK"  => "Hong Kong",
            "HU"  => "Hungary",
            "IS"  => "Iceland",
            "IN"  => "India",
            "ID"  => "Indonesia",
            "IR"  => "Iran, Islamic Republic of",
            "IQ"  => "Iraq",
            "IE"  => "Ireland",
            "IL"  => "Israel",
            "IT"  => "Italy",
            "JM"  => "Jamaica",
            "JP"  => "Japan",
            "JO"  => "Jordan",
            "KZ"  => "Kazakhstan",
            "KE"  => "Kenya",
            "KI"  => "Kiribati",
            "KP"  => "Korea, Democratic People's Republic of",
            "KR"  => "Korea, Republic of",
            "KW"  => "Kuwait",
            "KG"  => "Kyrgyzstan",
            "LA"  => "Lao People's Democratic Republic",
            "LV"  => "Latvia",
            "LB"  => "Lebanon",
            "LS"  => "Lesotho",
            "LR"  => "Liberia",
            "LY"  => "Libyan Arab Jamahiriya",
            "LI"  => "Liechtenstein",
            "LT"  => "Lithuania",
            "LU"  => "Luxembourg",
            "MO"  => "Macao",
            "MK"  => "Macedonia, the Former Yugoslav Republic of",
            "MG"  => "Madagascar",
            "MW"  => "Malawi",
            "MY"  => "Malaysia",
            "MV"  => "Maldives",
            "ML"  => "Mali",
            "MT"  => "Malta",
            "MH"  => "Marshall Islands",
            "MQ"  => "Martinique",
            "MR"  => "Mauritania",
            "MU"  => "Mauritius",
            "YT"  => "Mayotte",
            "MX"  => "Mexico",
            "FM"  => "Micronesia, Federated States of",
            "MD"  => "Moldova, Republic of",
            "MC"  => "Monaco",
            "MN"  => "Mongolia",
            "MS"  => "Montserrat",
            "MA"  => "Morocco",
            "MZ"  => "Mozambique",
            "MM"  => "Myanmar",
            "NA"  => "Namibia",
            "NR"  => "Nauru",
            "NP"  => "Nepal",
            "NL"  => "Netherlands",
            "AN"  => "Netherlands Antilles",
            "NC"  => "New Caledonia",
            "NZ"  => "New Zealand",
            "NI"  => "Nicaragua",
            "NE"  => "Niger",
            "NG"  => "Nigeria",
            "NU"  => "Niue",
            "NF"  => "Norfolk Island",
            "MP"  => "Northern Mariana Islands",
            "NO"  => "Norway",
            "OM"  => "Oman",
            "PK"  => "Pakistan",
            "PW"  => "Palau",
            "PS"  => "Palestinian Territory, Occupied",
            "PA"  => "Panama",
            "PG"  => "Papua New Guinea",
            "PY"  => "Paraguay",
            "PE"  => "Peru",
            "PH"  => "Philippines",
            "PN"  => "Pitcairn",
            "PL"  => "Poland",
            "PT"  => "Portugal",
            "PR"  => "Puerto Rico",
            "QA"  => "Qatar",
            "RE"  => "Reunion",
            "RO"  => "Romania",
            "RU"  => "Russian Federation",
            "RW"  => "Rwanda",
            "SH"  => "Saint Helena",
            "KN"  => "Saint Kitts and Nevis",
            "LC"  => "Saint Lucia",
            "PM"  => "Saint Pierre and Miquelon",
            "VC"  => "Saint Vincent and the Grenadines",
            "WS"  => "Samoa",
            "SM"  => "San Marino",
            "ST"  => "Sao Tome and Principe",
            "SA"  => "Saudi Arabia",
            "SN"  => "Senegal",
            "CS"  => "Serbia and Montenegro",
            "SC"  => "Seychelles",
            "SL"  => "Sierra Leone",
            "SG"  => "Singapore",
            "SK"  => "Slovakia",
            "SI"  => "Slovenia",
            "SB"  => "Solomon Islands",
            "SO"  => "Somalia",
            "ZA"  => "South Africa",
            "GS"  => "South Georgia and the South Sandwich Islands",
            "ES"  => "Spain",
            "LK"  => "Sri Lanka",
            "SD"  => "Sudan",
            "SR"  => "Suriname",
            "SJ"  => "Svalbard and Jan Mayen",
            "SZ"  => "Swaziland",
            "SE"  => "Sweden",
            "CH"  => "Switzerland",
            "SY"  => "Syrian Arab Republic",
            "TW"  => "Taiwan",
            "TJ"  => "Tajikistan",
            "TZ"  => "Tanzania, United Republic of",
            "TH"  => "Thailand",
            "TL"  => "Timor-Leste",
            "TG"  => "Togo",
            "TK"  => "Tokelau",
            "TO"  => "Tonga",
            "TT"  => "Trinidad and Tobago",
            "TN"  => "Tunisia",
            "TR"  => "Turkey",
            "TM"  => "Turkmenistan",
            "TC"  => "Turks and Caicos Islands",
            "TV"  => "Tuvalu",
            "UG"  => "Uganda",
            "UA"  => "Ukraine",
            "AE"  => "United Arab Emirates",
            "GB"  => "United Kingdom",
            "US"  => "United States",
            "UM"  => "United States Minor Outlying Islands",
            "UY"  => "Uruguay",
            "UZ"  => "Uzbekistan",
            "VU"  => "Vanuatu",
            "VE"  => "Venezuela",
            "VN"  => "Viet Nam",
            "VG"  => "Virgin Islands, British",
            "VI"  => "Virgin Islands, U.s.",
            "WF"  => "Wallis and Futuna",
            "EH"  => "Western Sahara",
            "YE"  => "Yemen",
            "ZM"  => "Zambia",
            "ZW"  => "Zimbabwe"
        );
    }
}