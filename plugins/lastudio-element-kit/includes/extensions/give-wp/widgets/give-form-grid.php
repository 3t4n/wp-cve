<?php

namespace LaStudioKitExtensions\GiveWp\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\LaStudioKit_Posts;
use Elementor\Repeater;
use LaStudioKitExtensions\Elementor\Controls\Group_Control_Related as Group_Control_Related;

class GiveFormGrid extends LaStudioKit_Posts{

	private $_query = null;

	public $item_counter = 0;

	public $cflag = false;

	public $css_file_name = 'givewp.min.css';

	protected function enqueue_addon_resources(){
		$this->add_script_depends( 'jquery-isotope' );
		if(!lastudio_kit_settings()->is_combine_js_css()) {
			$this->add_script_depends( 'lastudio-kit-base' );
			if(!lastudio_kit()->is_optimized_css_mode()) {
				wp_register_style( 'lakit-posts', lastudio_kit()->plugin_url( 'assets/css/addons/posts.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
				wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/' . $this->css_file_name ), [ 'lakit-posts' ], lastudio_kit()->get_version() );
				$this->add_style_depends( $this->get_name() );
			}
		}
	}

	public function get_inline_css_depends() {
		return [
			[
				'name' => 'lakit-posts'
			]
		];
	}

	public function get_widget_css_config($widget_name){

        $css_file_name = $widget_name === 'lakit-posts' ? 'posts.min.css' : $this->css_file_name;

		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/' . $css_file_name );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/' . $css_file_name );

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
		return 'lakit-give-form-grid';
	}

	public function get_widget_title() {
		return __('GiveWP Form Grid', 'lastudio-kit');
	}

	public function get_keywords() {
		return [ 'give', 'donation', 'grid', 'form' ];
	}

	protected function set_template_output(){
		return lastudio_kit()->plugin_path('includes/extensions/give-wp/widget-templates');
	}

	protected function preset_list() {
		$preset_type = apply_filters(
			'lastudio-kit/' . $this->get_lakit_name() . '/control/preset',
			array(
				'grid-1' => esc_html__( 'Grid 1', 'lastudio-kit' ),
				'grid-2' => esc_html__( 'Grid 2', 'lastudio-kit' ),
				'list-1' => esc_html__( 'List 1', 'lastudio-kit' ),
				'list-2' => esc_html__( 'List 2', 'lastudio-kit' ),
			)
		);

		return $preset_type;
	}

	protected function _register_section_meta( $css_scheme ) {
		$this->_start_controls_section(
			'section_meta',
			[
				'label' => __( 'Meta Data', 'lastudio-kit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->_add_control(
            'show_form_goal',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Show Form Goal', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );
        $this->add_control(
            'raised_text',
            array(
                'label' => esc_html__( 'Raised Text', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'show_form_goal' => 'yes'
                ],
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised span:first-child:before' => 'content: "{{VALUE}}";',
                ),
            )
        );
        $this->add_control(
            'goal_text',
            array(
                'label' => esc_html__( 'Goal Text', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'show_form_goal' => 'yes'
                ],
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised span:before' => 'content: "{{VALUE}}";',
                ),
            )
        );
        $this->_add_control(
            'show_progress_bar',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Show Progress Bar', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
                'condition' => [
                    'show_form_goal' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'form_goal_position',
            [
                'label'     => esc_html__( 'Form Goal Position', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'before_title'  => esc_html__( 'Before Title', 'lastudio-kit' ),
                    'after_title'   => esc_html__( 'After Title', 'lastudio-kit' ),
                    'after_content' => esc_html__( 'After Content', 'lastudio-kit' ),
                    'after_button'  => esc_html__( 'After Button', 'lastudio-kit' ),
                ],
                'default'   => 'before_title',
                'condition' => [
                    'show_form_goal' => 'yes',
                ],
                'separator'   => 'after',
            ]
        );

        $this->_add_control(
            'show_donate_btn',
            array(
                'type'         => 'switcher',
                'label'        => esc_html__( 'Show Donate Button', 'lastudio-kit' ),
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => '',
            )
        );

		$this->add_control(
			'donate_text',
			array(
				'label' => esc_html__( 'Donate Text', 'lastudio-kit' ),
				'type'  => Controls_Manager::TEXT,
                'condition' => [
                    'show_donate_btn' => 'yes'
                ],
                'separator'   => 'after',
			)
		);

		$this->_add_control(
			'show_meta',
			array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Meta Data', 'lastudio-kit' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'yes',
				'default'      => '',
			)
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
				'options' => apply_filters( 'lastudio-kit/' . $this->get_lakit_name() . '/metadata', [
					'category'      => esc_html__( 'Category', 'lastudio-kit' ),
                    'tag'           => esc_html__( 'Tags', 'lastudio-kit' ),
					'goal_amount'   => esc_html__( 'Goal Amount', 'lastudio-kit' ),
					'amount_raised' => esc_html__( 'Amount Raised', 'lastudio-kit' ),
					'number_donations' => esc_html__( 'Number of Donations', 'lastudio-kit' )
				])
			]
		);

		$this->_add_control(
			'metadata1',
			array(
				'label'         => esc_html__( 'MetaData 1', 'lastudio-kit' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ item_label }}}',
				'prevent_empty' => false,
				'condition'     => array(
					'show_meta' => 'yes'
				)
			)
		);

		$this->_add_control(
			'meta_position1',
			[
				'label'     => esc_html__( 'MetaData 1 Position', 'lastudio-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before_title'  => esc_html__( 'Before Title', 'lastudio-kit' ),
					'after_title'   => esc_html__( 'After Title', 'lastudio-kit' ),
					'after_content' => esc_html__( 'After Content', 'lastudio-kit' ),
					'after_button'  => esc_html__( 'After Button', 'lastudio-kit' ),
				],
				'default'   => 'before_title',
				'condition' => [
					'show_meta' => 'yes',
				]
			]
		);

		$this->_add_control(
			'metadata2',
			array(
				'label'         => esc_html__( 'MetaData 2', 'lastudio-kit' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ item_label }}}',
				'prevent_empty' => false,
				'condition'     => array(
					'show_meta' => 'yes'
				)
			)
		);
		$this->_add_control(
			'meta_position2',
			[
				'label'     => esc_html__( 'MetaData 2 Position', 'lastudio-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before_title'  => esc_html__( 'Before Title', 'lastudio-kit' ),
					'after_title'   => esc_html__( 'After Title', 'lastudio-kit' ),
					'after_content' => esc_html__( 'After Content', 'lastudio-kit' ),
					'after_button'  => esc_html__( 'After Button', 'lastudio-kit' ),
				],
				'default'   => 'after_title',
				'condition' => [
					'show_meta' => 'yes',
				]
			]
		);


		$this->_end_controls_section();
	}

	protected function _register_section_query( $css_scheme ) {
		/** Query section */
		$this->_start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'lastudio-kit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->_add_group_control(
			Group_Control_Related::get_type(),
			[
				'name'          => 'query',
				'object_type'   => 'give_forms',
				'post_type'     => 'give_forms',
				'presets'       => [ 'full' ],
				'fields_options' => [
					'post_type' => [
						'default' => 'give_forms',
						'options' => [
							'current_query' => __( 'Current Query', 'lastudio-kit' ),
							'give_forms' => __( 'Latest Forms', 'lastudio-kit' ),
							'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio-kit' ),
							'related' => _x( 'Related', 'Posts Query Control', 'lastudio-kit' ),
						],
					],
					'orderby' => [
						'default' => 'date',
						'options' => [
							'date'              => __( 'Date Created', 'lastudio-kit' ),
							'title'             => __( 'Title', 'lastudio-kit' ),
							'amount_donated'    => __( 'Amount Donated', 'lastudio-kit' ),
							'name'              => __( 'Form Name', 'lastudio-kit' ),
							'number_donations'  => __( 'Number of Donations', 'lastudio-kit' ),
							'closest_to_goal'   => __( 'Closest to Goal', 'lastudio-kit' ),
							'rand'              => __( 'Random', 'lastudio-kit' ),
							'menu_order'        => __( 'Menu Order', 'lastudio-kit' ),
							'post__in'          => __( 'Manual Selection', 'lastudio-kit' ),
						],
					],
					'exclude' => [
						'options' => [
							'current_post' => __( 'Current Post', 'lastudio-kit' ),
							'manual_selection' => __( 'Manual Selection', 'lastudio-kit' ),
							'terms' => __( 'Form Terms', 'lastudio-kit' ),
						],
					],
					'exclude_ids' => [
						'object_type' => 'give_forms',
					],
					'include_ids' => [
						'object_type' => 'give_forms',
					],
				],
				'exclude' => [
					'exclude_authors',
					'authors',
					'offset',
					'ignore_sticky_posts',
				],
			]
		);

        $this->_add_control(
            'enable_ajax_load',
            [
                'label'     => __( 'Enable Ajax Load', 'lastudio-kit' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => '',
                'condition' => [
                    'query_post_type!' => 'current_query',
                ],
            ]
        );

		$this->_add_control(
			'paginate',
			[
				'label'   => __( 'Pagination', 'lastudio-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->_add_control(
			'paginate_as_loadmore',
			[
				'label'     => __( 'Use Load More', 'lastudio-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);
		$this->_add_control(
			'paginate_infinite',
			[
				'label'     => esc_html__( 'Infinite loading', 'lastudio-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'paginate'             => 'yes',
					'paginate_as_loadmore' => 'yes',
				],
			]
		);

		$this->_add_control(
			'loadmore_text',
			[
				'label'     => __( 'Load More Text', 'lastudio-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Load More',
				'condition' => [
					'paginate'             => 'yes',
					'paginate_as_loadmore' => 'yes',
				]
			]
		);

		$this->_add_control(
			'nothing_found_message',
			[
				'label'       => esc_html__( 'Nothing Found Message', 'lastudio-kit' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
			]
		);

		$this->_end_controls_section();
	}

	protected function _register_section_style_floating_date( $css_scheme ){

	}

	protected function _register_section_style_floating_counter( $css_scheme ){
        $css_scheme['donate_btn'] = '.lakit-posts .lakit-posts__btn-donate';
        /** Floating Post Format **/
        $this->_start_controls_section(
            'section_form_goal',
            array(
                'label'     => esc_html__( 'Form Goal', 'lastudio-kit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_form_goal' => 'yes'
                ],
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'goal_typography',
                'selector' => '{{WRAPPER}} .lakit-goal-progress .raised span',
            )
        );
        $this->_add_control(
            'goal_text_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised' => 'color: {{VALUE}}',
                )
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'goal_label_typography',
                'label'    => esc_html__( 'Label Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-goal-progress .raised span:before',
            )
        );
        $this->_add_control(
            'goal_label_color',
            array(
                'label'     => esc_html__( 'Label Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-goal-progress .raised span:before' => 'color: {{VALUE}}',
                )
            )
        );
        $this->_add_responsive_control(
            'goal_raise_gap',
            array(
                'label'       => esc_html__( 'Amount Spacing', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'selectors'   => [
                    '{{WRAPPER}} .lakit-goal-progress .raised' => 'gap: {{SIZE}}{{UNIT}};'
                ],
            )
        );
        $this->add_responsive_control(
            'goal_amount_justify',
            [
                'label' => esc_html_x( 'Justify Content', 'Flex Container Control', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html_x( 'Flex Start', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-start-h',
                    ],
                    'center' => [
                        'title' => esc_html_x( 'Center', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-center-h',
                    ],
                    'flex-end' => [
                        'title' => esc_html_x( 'Flex End', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-end-h',
                    ],
                    'space-between' => [
                        'title' => esc_html_x( 'Space Between', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html_x( 'Space Around', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html_x( 'Space Evenly', 'Flex Container Control', 'lastudio-kit' ),
                        'icon' => 'eicon-flex eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-goal-progress .raised' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        $this->_add_control(
            'heading__goal_percent',
            [
                'label'       => esc_html__( 'Percent Progress', 'lastudio-kit' ),
                'type'        => Controls_Manager::HEADING,
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'goal_percent_typography',
                'selector' => '{{WRAPPER}} .progress-percent',
            )
        );
        $this->_add_control(
            'goal_percent_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .progress-percent' => 'color: {{VALUE}}',
                )
            )
        );
        $this->_add_control(
            'heading__goal_processbar',
            [
                'label'       => esc_html__( 'Progress Bar', 'lastudio-kit' ),
                'type'        => Controls_Manager::HEADING,
                'label_block' => true,
                'separator'   => 'before',
            ]
        );
        $this->_add_responsive_control(
            'goal_processbar_height',
            array(
                'label'       => esc_html__( 'Process bar height', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'selectors'   => [
                    '{{WRAPPER}} .lakit-posts' => '--lakit-progress-bar-height: {{SIZE}}{{UNIT}};'
                ],
            )
        );
        $this->_add_responsive_control(
            'goal_processbar_radius',
            array(
                'label'       => esc_html__( 'Border radius', 'lastudio-kit' ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => [ 'px' ],
                'selectors'   => [
                    '{{WRAPPER}} .lakit-posts' => '--lakit-progress-bar-radius: {{SIZE}}{{UNIT}};'
                ],
            )
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'goal_processbar_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .give-progress-bar',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html__('Normal Background', 'pixel-gallery')
                    ]
                ],
                'exclude'  => array(
                    'image',
                    'position',
                    'xpos',
                    'ypos',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                    'bg_width'
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'goal_processbar_active_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .give-progress-bar span',
                'fields_options' => [
                    'background' => [
                        'label' => esc_html__('Active Background', 'pixel-gallery')
                    ]
                ],
                'exclude'  => array(
                    'image',
                    'position',
                    'xpos',
                    'ypos',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                    'bg_width'
                ),
            )
        );
        $this->_add_responsive_control(
            'goal_processbar_margin',
            array(
                'label'      => esc_html__( 'Progress Bar Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-goal-progress .give-progress-bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'goal_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-goal-progress' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator'   => 'before',
            )
        );

        $this->_add_responsive_control(
            'goal_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-goal-progress' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'goal_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-goal-progress',
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_donate_btn',
            array(
                'label'      => esc_html__( 'Donate Button', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_donate_btn' );

        $this->_start_controls_tab(
            'tab_donate_btn_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );
        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'donate_btn_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['donate_btn'],
                'exclude'  => array(
                    'image',
                    'position',
                    'xpos',
                    'ypos',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                    'bg_width'
                ),
            )
        );

        $this->_add_control(
            'donate_btn_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] => 'color: {{VALUE}}',
                )
            )
        );


        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'donate_btn_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['donate_btn'],
            )
        );

        $this->_add_responsive_control(
            'donate_btn_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'donate_btn_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'donate_btn_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'donate_btn_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['donate_btn'],
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'donate_btn_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['donate_btn'],
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_donate_btn_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'donate_btn_h_bg',
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover',
                'exclude'  => array(
                    'image',
                    'position',
                    'xpos',
                    'ypos',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                    'bg_width'
                ),
            )
        );

        $this->_add_control(
            'donate_btn_h_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'donate_btn_h_typography',
                'label'    => esc_html__( 'Typography', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}}  ' . $css_scheme['donate_btn'] . ':hover',
            )
        );

        $this->_add_control(
            'donate_btn_h_text_decor',
            array(
                'label'     => esc_html__( 'Text Decoration', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'none'      => esc_html__( 'None', 'lastudio-kit' ),
                    'underline' => esc_html__( 'Underline', 'lastudio-kit' ),
                ),
                'default'   => 'none',
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover' => 'text-decoration: {{VALUE}}',
                ),
            )
        );

        $this->_add_responsive_control(
            'donate_btn_h_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'donate_btn_h_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'donate_btn_h_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'donate_btn_h_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover',
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'donate_btn_h_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['donate_btn'] . ':hover',
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();
	}

	protected function _register_section_style_floating_category( $css_scheme ){

	}

	protected function _register_section_style_floating_postformat( $css_scheme ){

	}

	protected function register_controls() {
		parent::register_controls();
	}
}