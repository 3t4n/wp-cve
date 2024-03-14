<?php

namespace LaStudioKitExtensions\Events\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Elementor\Controls_Manager;
use Elementor\LaStudioKit_Posts;
use Elementor\Repeater;
use LaStudioKitExtensions\Elementor\Controls\Group_Control_Related as Group_Control_Related;

class Events extends LaStudioKit_Posts{

	private $_query = null;

	public $item_counter = 0;

	public $cflag = false;

	public $css_file_name = 'events.min.css';

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

        $css_file_name = $widget_name === 'lakit-posts' ? 'posts.min.css' : 'events.min.css';

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
		return 'lakit-events';
	}

	public function get_widget_title() {
		return __('Events', 'lastudio-kit');
	}

	public function get_keywords() {
		return [ 'events' ];
	}

	protected function set_template_output(){
		return lastudio_kit()->plugin_path('includes/extensions/events/widget-templates');
	}

	protected function preset_list() {
		$preset_type = apply_filters(
			'lastudio-kit/' . $this->get_lakit_name() . '/control/preset',
			array(
				'grid-1' => esc_html__( 'Grid 1', 'lastudio-kit' ),
				'grid-2' => esc_html__( 'Grid 2', 'lastudio-kit' ),
				'list-1' => esc_html__( 'List 1', 'lastudio-kit' ),
				'list-2' => esc_html__( 'List 2', 'lastudio-kit' ),
				'evt-1'  => esc_html__( 'List 3', 'lastudio-kit' ),
				'evt-2'  => esc_html__( 'List 4', 'lastudio-kit' ),
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

//		$this->add_control(
//			'text_buy',
//			array(
//				'label' => esc_html__( 'Buy Ticket Text', 'lastudio-kit' ),
//				'type'  => Controls_Manager::TEXT,
//			)
//		);

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
					'start_date'    => esc_html__( 'Start Date', 'lastudio-kit' ),
					'end_date'      => esc_html__( 'End Date', 'lastudio-kit' ),
					'date'          => esc_html__( 'Dates', 'lastudio-kit' ),
					'status'        => esc_html__( 'Status', 'lastudio-kit' ),
					'location'      => esc_html__( 'Location', 'lastudio-kit' ),
					'stage'         => esc_html__( 'Stage', 'lastudio-kit' ),
					'organized_by'  => esc_html__( 'Organized By', 'lastudio-kit' ),
				] )
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
				'object_type'   => 'la_event',
				'post_type'     => 'la_event',
				'presets'       => [ 'full' ],
				'fields_options' => [
					'post_type' => [
						'default' => 'la_event',
						'options' => [
							'current_query' => __( 'Current Query', 'lastudio-kit' ),
							'la_event' => __( 'Latest Events', 'lastudio-kit' ),
							'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio-kit' ),
							'related' => _x( 'Related', 'Posts Query Control', 'lastudio-kit' ),
						],
					],
					'orderby' => [
						'default' => 'date',
						'options' => [
							'date'          => __( 'Date', 'lastudio-kit' ),
							'title'         => __( 'Title', 'lastudio-kit' ),
							'rand'          => __( 'Random', 'lastudio-kit' ),
							'menu_order'    => __( 'Menu Order', 'lastudio-kit' ),
							'post__in'      => __( 'Manual Selection', 'lastudio-kit' ),
						],
					],
					'exclude' => [
						'options' => [
							'current_post' => __( 'Current Post', 'lastudio-kit' ),
							'manual_selection' => __( 'Manual Selection', 'lastudio-kit' ),
							'terms' => __( 'Events Terms', 'lastudio-kit' ),
						],
					],
					'exclude_ids' => [
						'object_type' => 'la_event',
					],
					'include_ids' => [
						'object_type' => 'la_event',
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

	}

	protected function _register_section_style_floating_category( $css_scheme ){

	}

	protected function _register_section_style_floating_postformat( $css_scheme ){

	}

	protected function register_controls() {
		parent::register_controls();
	}
}