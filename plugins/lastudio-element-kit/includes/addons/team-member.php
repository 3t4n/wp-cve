<?php
/**
 * Class: LaStudioKit_Team_Member
 * Name: Team Member
 * Slug: lakit-teammember
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

use LaStudioKitExtensions\Elementor\Controls\Group_Control_Query;
use LaStudioKitExtensions\Elementor\Classes\Query_Control as Module_Query;

/**
 * Team_Member Widget
 */
class LaStudioKit_Team_Member extends LaStudioKit_Base {

    private $_query = null;

    public $item_counter = 0;

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/team-member.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/team-member.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/team-member.min.css' );
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
        return 'lakit-team-member';
    }

    protected function get_widget_title() {
        return esc_html__( 'Team Member', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-person';
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'lastudio-kit/team-member/css-schema',
            array(
                'wrap'              => '.lakit-team-member__list',
                'column'            => '.lakit-team-member__item',
                'inner-box'         => '.lakit-team-member__inner-box',
                'content'           => '.lakit-team-member__content',
                'image_wrap'        => '.lakit-team-member__image',
                'image_instance'    => '.lakit-team-member__image-instance',
                'title'             => '.lakit-team-member__name',
                'desc'              => '.lakit-team-member__desc',
                'position'          => '.lakit-team-member__position',
                'socials'           => '.lakit-team-member__socials',
                'slick_list'        => '.lakit-team-member .slick-list'
            )
        );

        $preset_type = apply_filters(
            'lastudio-kit/team-member/control/preset',
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
                'type-list-a' => esc_html__( 'List 1', 'lastudio-kit' ),
            )
        );

        $datasource = apply_filters(
            'lastudio-kit/team-member/control/data-source',
            array(
                'custom' => __( 'Custom', 'lastudio-kit' ),
                //'post_type' => __( 'Member Post Type', 'lastudio-kit' ),
            )
        );
        /** Data Source section */
        $this->start_controls_section(
            'section_data_source',
            array(
                'label' => esc_html__( 'Data Source', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'data_source',
            array(
                'label'     => esc_html__( 'Data Source', 'lastudio-kit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'custom',
                'options'   => $datasource
            )
        );

        $repeater = new Repeater();
        $repeater->start_controls_tabs( 'items_repeater' );
        $repeater->start_controls_tab( 'general', [ 'label' => __( 'General', 'lastudio-kit' ) ] );
        $repeater->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Image', 'lastudio-kit' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array( 'active' => true )
            )
        );
        $repeater->add_control(
            'name',
            [
                'label' => __( 'Name', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Member #1', 'lastudio-kit' ),
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'role',
            [
                'label' => __( 'Role', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Role', 'lastudio-kit' ),
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'dynamic' => array( 'active' => true ),
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' ),
                'dynamic' => array( 'active' => true )
            ]
        );
        $repeater->end_controls_tab();
        $repeater->start_controls_tab( 'socials', [ 'label' => __( 'Social', 'lastudio-kit' ) ] );

        $repeater->add_control(
            's_icon_1',
            array(
                'label'       => esc_html__( 'Item Icon 1', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon()
            )
        );
        $repeater->add_control(
            's_link_1',
            [
                'label' => __( 'Item Link 1', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' )
            ]
        );
        $repeater->add_control(
            's_icon_2',
            array(
                'label'       => esc_html__( 'Item Icon 2', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_2',
            [
                'label' => __( 'Item Link 2', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' )
            ]
        );
        $repeater->add_control(
            's_icon_3',
            array(
                'label'       => esc_html__( 'Item Icon 3', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_3',
            [
                'label' => __( 'Item Link 3', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' )
            ]
        );
        $repeater->add_control(
            's_icon_4',
            array(
                'label'       => esc_html__( 'Item Icon 4', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_4',
            [
                'label' => __( 'Item Link 4', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' )
            ]
        );
        $repeater->add_control(
            's_icon_5',
            array(
                'label'       => esc_html__( 'Item Icon 5', 'lastudio-kit' ),
                'type'        => Controls_Manager::ICON,
                'include' => self::get_labrandicon(true),
                'options' => self::get_labrandicon(),
                'separator' => 'before'
            )
        );
        $repeater->add_control(
            's_link_5',
            [
                'label' => __( 'Item Link 5', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' )
            ]
        );
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $this->add_control(
            'items',
            [
                'label' => __( 'Custom Member List', 'lastudio-kit' ),
                'type' => Controls_Manager::REPEATER,
                'show_label' => true,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default' => [
                    [
                        'name' => __( 'Leila Henry', 'lastudio-kit' ),
                        'role' => __( 'Creative Director', 'lastudio-kit' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'lastudio-kit' ),
                        's_icon_1' => 'lastudioicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'lastudioicon-b-twitter-x',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ],
                    [
                        'name' => __( 'Leila Henry', 'lastudio-kit' ),
                        'role' => __( 'Creative Director', 'lastudio-kit' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'lastudio-kit' ),
                        's_icon_1' => 'lastudioicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'lastudioicon-b-twitter-x',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ],
                    [
                        'name' => __( 'Leila Henry', 'lastudio-kit' ),
                        'role' => __( 'Creative Director', 'lastudio-kit' ),
                        'description' => __( 'Maecenas at blandit leo. Morbi eget leo et justo sagittis maximus. Aliquam maximus rhoncus risus et dignissim', 'lastudio-kit' ),
                        's_icon_1' => 'lastudioicon-b-facebook',
                        's_link_1' => [
                            'url' => '#'
                        ],
                        's_icon_2' => 'lastudioicon-b-twitter-x',
                        's_link_2' => [
                            'url' => '#'
                        ]
                    ]
                ],
                'condition' => [
                    'data_source' => 'custom'
                ]
            ]
        );

        $this->end_controls_section();

        /** Layout section */
        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Layout', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'layout_type',
            array(
                'label'   => esc_html__( 'Layout type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => array(
                    'grid'    => esc_html__( 'Grid', 'lastudio-kit' )
                ),
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

        $this->add_control(
            'thumb_size',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Featured Image Size', 'lastudio-kit' ),
                'default'    => 'full',
                'options'    => lastudio_kit_helper()->get_image_sizes()
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
            'title_html_tag',
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
                'default' => 'h4',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'show_role',
            array(
                'label'        => esc_html__( 'Show Role/Position?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_social',
            array(
                'label'        => esc_html__( 'Show Social?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'show_excerpt',
            array(
                'label'        => esc_html__( 'Show Excerpt?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'true',
                'default'      => false,
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'excerpt_length',
            array(
                'label'   => esc_html__( 'Custom Excerpt Length', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 20,
                'min'     => 0,
                'max'     => 200,
                'step'    => 1,
                'condition' => array(
                    'show_excerpt' => 'true'
                )
            )
        );

        $this->end_controls_section();

        /** Query section */
        $this->start_controls_section(
            'section_query',
            [
                'label' => __( 'Query', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'data_source' => 'post_type',
                ]

            ]
        );

        $this->add_group_control(
            Group_Control_Query::get_type(),
            [
                'name' => 'query',
                'post_type' => 'la_team_member',
                'presets' => [ 'full' ],
                'fields_options' => [
                    'post_type' => [
                        'default' => 'la_team_member',
                        'options' => [
                            'current_query' => __( 'Current Query', 'lastudio-kit' ),
                            'la_team_member' => __( 'Latest', 'lastudio-kit' ),
                            'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio-kit' ),
                        ],
                    ],
                    'orderby' => [
                        'default' => 'date',
                        'options' => [
                            'date'  => __( 'Date', 'lastudio-kit' ),
                            'title' => __( 'Title', 'lastudio-kit' ),
                            'rand' => __( 'Random', 'lastudio-kit' ),
                            'menu_order' => __( 'Menu Order', 'lastudio-kit' ),
                        ],
                    ],
                    'exclude' => [
                        'options' => [
                            'current_post' => __( 'Current Post', 'lastudio-kit' ),
                            'manual_selection' => __( 'Manual Selection', 'lastudio-kit' ),
                        ],
                    ],
                    'posts_ids' => [
                        'object_type' => 'la_team_member'
                    ],
                    'exclude_ids' => [
                        'object_type' => 'la_team_member',
                    ],
                    'include_ids' => [
                        'object_type' => 'la_team_member',
                    ]
                ],
                'exclude' => [
                    'exclude_authors',
                    'authors',
                    'offset',
                    'related_fallback',
                    'related_ids',
                    'query_id',
                    'avoid_duplicates',
                    'ignore_sticky_posts'
                ],
            ]
        );

        $this->add_control(
            'paginate',
            [
                'label' => __( 'Pagination', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => ''
            ]
        );

        $this->add_control(
            'paginate_as_loadmore',
            [
                'label' => __( 'Use Load More', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loadmore_text',
            [
                'label' => __( 'Load More Text', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Load More',
                'condition' => [
                    'paginate' => 'yes',
                    'paginate_as_loadmore' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->register_carousel_section( [  ], 'columns');

        $this->start_controls_section(
            'section_column_style',
            array(
                'label'      => esc_html__( 'Column', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'column_padding',
            array(
                'label'       => esc_html__( 'Column Padding', 'lastudio-kit' ),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => array( 'px' ),
                'selectors'   => array(
                    '{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ' => '--lakit-carousel-item-top-space: {{TOP}}{{UNIT}}; --lakit-carousel-item-right-space: {{RIGHT}}{{UNIT}};--lakit-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-carousel-item-left-space: {{LEFT}}{{UNIT}};--lakit-gcol-top-space: {{TOP}}{{UNIT}}; --lakit-gcol-right-space: {{RIGHT}}{{UNIT}};--lakit-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-gcol-left-space: {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_box_style',
            array(
                'label'      => esc_html__( 'Item', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->add_responsive_control(
            'text_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'text-align: {{VALUE}};',
                )
            )
        );

        $this->add_responsive_control(
            'item_width',
            array(
                'label'      => esc_html__( 'Item Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', '%', 'vh', 'vw'),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'box_bg',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'box_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['inner-box'],
            )
        );

        $this->add_responsive_control(
            'box_border_radius',
            array(
                'label'      => __( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'inner_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'],
            )
        );

        $this->add_responsive_control(
            'box_padding',
            array(
                'label'      => esc_html__( 'Box Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_style',
            array(
                'label'      => esc_html__( 'Content', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_bg',
                'label' => __( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['content']
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => esc_html__( 'Content Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_margin',
            array(
                'label'      => esc_html__( 'Content Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} '  . $css_scheme['content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'content_border',
			    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['content'],
		    )
	    );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
            ]
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
		    'custom_image_width',
		    [
			    'label' => __( 'Image Width', 'lastudio-kit' ),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px', '%', 'custom' ],
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
				    'size' => 50,
			    ],
			    'condition' => [
				    'layout_type' => 'grid',
				    'preset' => ['type-list-a'],
			    ],
			    'selectors' => [
				    '{{WRAPPER}}' => '--team-image-width: {{SIZE}}{{UNIT}};'
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
                    '{{WRAPPER}} ' . $css_scheme['image_wrap'] => 'padding-bottom: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg',
                'label' => __( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .lakit-team-member__image_wrap',
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__image_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .lakit-team-member__image_wrap'
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__image_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} ' . $css_scheme['image_wrap'],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_thumb_overlay',
            array(
                'label'      => esc_html__( 'Image overlay', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->start_controls_tabs( 'image_overlay_tabs' );

        $this->start_controls_tab( 'image_overlay_normal',
            [
                'label' => __( 'Normal', 'lastudio-kit' ),
            ]
        );

	    $this->_add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'img_overlay',
			    'label' => __( 'Background Overlay', 'lastudio-kit' ),
			    'types' => [ 'classic', 'gradient' ],
			    'selector' => '{{WRAPPER}} .lakit-team-member__link:after',
		    ]
	    );

        $this->add_control(
            'overlay_blend_mode',
            [
                'label' => esc_html__( 'Blend Mode', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => lastudio_kit_helper()->get_blend_mode_options(),
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__link:after' => 'mix-blend-mode: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'opacity',
            [
                'label' => __( 'Opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.00,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__link:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} ' . $css_scheme['image_wrap'],
            ]
        );

        $this->add_responsive_control(
            'image_overlay_n_pos',
            [
                'label' => __( 'Position', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__link:after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_radius',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__link:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'overlay_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-team-member__link:after',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'image_overlay_hover',
            [
                'label' => __( 'Hover', 'lastudio-kit' ),
            ]
        );

	    $this->_add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'img_overlay_hover',
			    'label' => __( 'Background Overlay', 'lastudio-kit' ),
			    'types' => [ 'classic', 'gradient' ],
			    'selector' => '{{WRAPPER}} .lakit-team-member__inner:hover .lakit-team-member__link:after',
		    ]
	    );

        $this->add_control(
            'overlay_blend_mode_hover',
            [
                'label' => esc_html__( 'Blend Mode', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => lastudio_kit_helper()->get_blend_mode_options(),
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__inner:hover .lakit-team-member__link:after' => 'mix-blend-mode: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'opacity_hover',
            [
                'label' => __( 'Opacity', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.00,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__inner:hover .lakit-team-member__link:after' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .lakit-team-member__inner:hover .lakit-team-member__image',
            ]
        );

        $this->add_responsive_control(
            'image_overlay_h_pos',
            [
                'label' => __( 'Position', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__inner:hover .lakit-team-member__link:after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'overlay_radius_hover',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-team-member__inner:hover .lakit-team-member__link:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'overlay_border_hover',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-team-member__inner:hover .lakit-team-member__link:after',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Title Style Section
         */
        $this->start_controls_section(
            'section_title_style',
            array(
                'label'      => esc_html__( 'Title', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
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

        $this->add_control(
            'title_color_hover',
            array(
                'label'  => esc_html__( 'Color Hover', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] . ':hover' => 'color: {{VALUE}}',
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
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    array(
			    'name'        => 'title_border',
			    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} ' . $css_scheme['title'],
		    )
	    );

        $this->end_controls_section();

        /**
         * Position Style Section
         */
        $this->start_controls_section(
            'section_position_style',
            array(
                'label'      => esc_html__( 'Position/Role', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
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
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['position'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();


        /**
         * Desc Style Section
         */
        $this->start_controls_section(
            'section_desc_style',
            array(
                'label'      => esc_html__( 'Excerpt', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'desc_color',
            array(
                'label'  => esc_html__( 'Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->add_responsive_control(
            'desc_padding',
            array(
                'label'      => __( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'desc_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'desc_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['desc'],
            )
        );

        $this->end_controls_section();
        /**
         * Social Style Section
         */
        $this->start_controls_section('section_social_style', array(
            'label' => esc_html__('Social', 'lastudio-kit'),
            'tab' => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        ));
        $this->add_control('social_background', array(
            'label' => esc_html__('Background Color', 'lastudio-kit'),
            'type' => Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} .lakit-team-member__socials' => 'background-color: {{VALUE}}',
            ),
        ));
        $this->add_responsive_control(
            'social_padding',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-team-member__socials' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            'social_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'custom' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-team-member__socials' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->start_controls_tabs('social_style');
        $this->start_controls_tab('social_normal', [
                'label' => __('Normal', 'lastudio-kit'),
            ]);
        $this->add_control('social_bg_color', array(
                'label' => esc_html__('Item Background Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'background-color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_color', array(
                'label' => esc_html__('Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'color: {{VALUE}}',
                ),
            ));
        $this->add_responsive_control('social_fz', [
                'label' => __('Font Size', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'font-size: {{SIZE}}px;',
                ],
            ]);
        $this->add_responsive_control('social_pd', [
                'label' => __('Padding', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'padding: {{SIZE}}px;',
                ],
            ]);
        $this->add_responsive_control('social_bd_w', [
                'label' => __('Border Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                        'min' => 0,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item--social a' => 'border-width: {{SIZE}}px;',
                ],
            ]);
        $this->add_control('social_bd_c', array(
                'label' => esc_html__('Border Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'border-color: {{VALUE}}',
                ),
            ));
        $this->add_responsive_control('social_br', array(
                'label' => __('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%'
                ),
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ));
        $this->add_responsive_control('social_mr', array(
                'label' => __('Item Margin', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .item--social a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ));
        $this->end_controls_tab();
        $this->start_controls_tab('social_hover', [
                'label' => __('Hover', 'lastudio-kit'),
            ]);
        $this->add_control('social_bg_color_hover', array(
                'label' => esc_html__('Item Background Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'background-color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_color_hover', array(
                'label' => esc_html__('Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'color: {{VALUE}}',
                ),
            ));
        $this->add_control('social_bd_c_hover', array(
                'label' => esc_html__('Border Color', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .item--social a:hover' => 'border-color: {{VALUE}}',
                ),
            ));
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section('section_order', array(
            'label' => esc_html__('Content Order', 'lastudio-kit'),
            'tab' => Controls_Manager::TAB_STYLE,
            'show_label' => false,
        ));
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
            )
        );
        $this->add_control(
            'role_order',
            array(
                'label'   => esc_html__( 'Role Order', 'lastudio-kit' ),
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
            'desc_order',
            array(
                'label'   => esc_html__( 'Description Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['desc'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'socials_order',
            array(
                'label'   => esc_html__( 'Socials Order', 'lastudio-kit' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => -1,
                'max'     => 10,
                'step'    => 1,
                'selectors' => array(
                    '{{WRAPPER}} '. $css_scheme['socials'] => '-webkit-order: {{VALUE}};order: {{VALUE}};',
                ),
            )
        );
        $this->end_controls_section();
        /**
         * Pagination
         */
        $this->start_controls_section(
            'section_pagination_style',
            [
                'label' => __( 'Pagination', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'paginate' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => __( 'Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->add_control(
            'pagination_spacing',
            [
                'label' => __( 'Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'show_pagination_border',
            [
                'label' => __( 'Border', 'lastudio-kit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'lastudio-kit' ),
                'label_on' => __( 'Show', 'lastudio-kit' ),
                'default' => 'yes',
                'return_value' => 'yes',
                'prefix_class' => 'lakit-pagination-has-border-',
            ]
        );

        $this->add_control(
            'pagination_border_color',
            [
                'label' => __( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_pagination_border' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_padding',
            [
                'label' => __( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'size_units' => [ 'em' ],
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a, {{WRAPPER}} nav.la-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} nav.la-pagination',
            ]
        );

        $this->start_controls_tabs( 'pagination_style_tabs' );

        $this->start_controls_tab( 'pagination_style_normal',
            [
                'label' => __( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'pagination_link_color',
            [
                'label' => __( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color',
            [
                'label' => __( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'pagination_style_hover',
            [
                'label' => __( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'pagination_link_color_hover',
            [
                'label' => __( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_hover',
            [
                'label' => __( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li a:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} nav.la-pagination .pagination_ajax_loadmore a:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'pagination_style_active',
            [
                'label' => __( 'Active', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'pagination_link_color_active',
            [
                'label' => __( 'Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li span.current' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'pagination_link_bg_color_active',
            [
                'label' => __( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} nav.la-pagination ul li span.current' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->register_carousel_arrows_dots_style_section( [ 'enable_carousel' => 'yes' ] );

    }

    protected function the_query(){
        return $this->_query;
    }

    protected function render() {
        $this->_context = 'render';

        $data_source = $this->get_settings_for_display('data_source');
        if($data_source == 'custom'){
            $this->_open_wrap();
            include $this->_get_global_template( 'custom' );
            $this->_close_wrap();
        }
        else{
            $paged_key = 'member-page' . esc_attr($this->get_id());

            $page = absint( empty( $_GET[$paged_key] ) ? 1 : $_GET[$paged_key] );

            $query_args = [
                'posts_per_page' => $this->get_settings_for_display('query_posts_per_page'),
                'paged' => 1,
            ];

            if ( 1 < $page ) {
                $query_args['paged'] = $page;
            }

            $module_query = Module_Query::get_instance();
            $this->_query = $module_query->get_query( $this, 'query', $query_args, [] );

            $this->_open_wrap();
            include $this->_get_global_template( 'index' );
            $this->_close_wrap();
            wp_reset_postdata();
        }
    }

    public function _get_member_image( $image_item ) {



        $image_size = $this->get_settings_for_display('thumb_size');

        $item_settings = [];
        $item_settings['item_image'] = $image_item;
        $item_settings['item_image_size'] = $image_size;

        if(empty( $item_settings['item_image']['url'] )){
            return;
        }

        $img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

        $class = 'lakit-team-member__image-instance wp-post-image';

        $img_html = str_replace('class="', 'class="' . $class . ' ', $img_html);

        return sprintf('<figure class="figure__object_fit lakit-team-member__image">%1$s</figure>', $img_html);
    }

    public function _get_member_social( $member_item ){

        $html = '';
        $icon_lists = self::get_labrandicon();
        $uid = isset($member_item['_id']) ? $member_item['_id'] : uniqid();
        for ($i = 1; $i <=5; $i++ ){
            $icon_key = 's_icon_' . $i;
            $link_key = 's_link_' . $i;
            $att_uid = 'member_'.$uid . '_social_' . $i;
            if(!empty($member_item[$icon_key])){
                $icon_value = $member_item[$icon_key];
                $icon_name = $icon_lists[$icon_value];
                if ( !empty($member_item[$link_key]) && ! empty( $member_item[$link_key]['url'] ) ) {
                    $this->add_link_attributes( $att_uid, $member_item[$link_key] );
                    $this->add_render_attribute( $att_uid, 'title', esc_attr($icon_name) );
                }
                $this->add_render_attribute( $att_uid, 'class', sprintf('social-%1$s %1$s', strtolower($icon_name)) );
                $html .= sprintf('<a %1$s><i class="%2$s"></i></a>', $this->get_render_attribute_string( $att_uid ), $icon_value);
            }
        }
        if(!empty($html)){
            $html = '<div class="item--social member-social">'.$html.'</div>';
        }
        return $html;
    }

}