<?php

namespace Codemanas\Typesense\Elementor\Widgets;

use Codemanas\Typesense\Backend\Admin;
use Codemanas\Typesense\Frontend\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class InstantSearch extends \Elementor\Widget_base {


	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		// Temporarily removed popup until better solution for first render of instant search
		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			remove_action( 'wp_footer', [ Frontend::getInstance(), 'load_popup' ] );
		}
	}

	public function get_name() {
		return 'cm-typesense-instant-search';
	}

	public function get_script_depends() {
		return [ 'cm-typesense-instant-search' ];
	}

	public function get_title() {
		return esc_html( 'Instant Search', 'search-with-typesense' );
	}

	public function get_icon() {
		return 'eicon-site-search';
	}

	public function get_custom_help_url() {
		return 'https://docs.wptypesense.com/shortcode/#instant-search';
	}

	public function get_categories() {
		return [ 'cm-typesense' ];
	}

	public function get_keywords() {
		return [ 'cm', 'typesense', 'instant search' ];
	}

	/**
	 * @return array
	 */
	public function get_formatted_available_post_types(): array {
		$search_config_settings      = Admin::get_search_config_settings();
		$available_post_type_choices = [];
		foreach ( $search_config_settings['available_post_types'] as $available_post_type ) {
			$available_post_type_choices[ $available_post_type['value'] ] = $available_post_type['label'];
		}

		return $available_post_type_choices;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Settings', 'search-with-typesense' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'search_width',
			[
				'label'      => esc_html__( 'Search box width', 'search-with-typesense' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .cmswt-SearchBox' => 'width: {{SIZE}}{{UNIT}};',
				],
			],

		);
		$this->add_control(
			'placeHolder',
			[
				'type'    => \Elementor\Controls_Manager::TEXT,
				'label'   => esc_html__( 'Search placeholder', 'search-with-typesense' ),
				'default' => esc_html__( 'Search for...', 'search-with-typesense' ),
			]
		);
		$this->add_control(
			'columns',
			[
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Columns', 'search-with-typesense' ),
				'default' => 3,
			]
		);

		$this->add_control(
			'post_type',
			[
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'label'    => esc_html__( 'Post Type', 'search-with-typesense' ),
				'options'  => $this->get_formatted_available_post_types(),
				'default'  => [ 'post' ],
				'multiple' => true,
			]
		);

		$this->add_control(
			'post_per_page',
			[
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'label'   => esc_html__( 'Posts per page', 'search-with-typesense' ),
				'default' => 3,

			]
		);

		$this->add_control(
			'filter',
			[
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Filter', 'search-with-typesense' ),
				'label_on'     => esc_html__( 'Show', 'search-with-typesense' ),
				'label_off'    => esc_html__( 'Hide', 'search-with-typesense' ),
				'return_value' => 'yes',
				'default'      => 'yes',

			],
		);

		$this->add_control(
			'sort_by',
			[
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Sort by', 'search-with-typesense' ),
				'label_on'     => esc_html__( 'Show', 'search-with-typesense' ),
				'label_off'    => esc_html__( 'Hide', 'search-with-typesense' ),
				'return_value' => 'yes',
				'default'      => 'yes',

			],
		);

		$this->add_control(
			'pagination',
			[
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Pagination', 'search-with-typesense' ),
				'label_on'     => esc_html__( 'Show', 'search-with-typesense' ),
				'label_off'    => esc_html__( 'Hide', 'search-with-typesense' ),
				'return_value' => 'yes',
				'default'      => 'yes',

			],
		);

		$this->add_control(
			'sticky_first',
			[
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Sticky First', 'search-with-typesense' ),
				'label_on'     => esc_html__( 'yes', 'search-with-typesense' ),
				'label_off'    => esc_html__( 'no', 'search-with-typesense' ),
				'return_value' => 'yes',
				'default'      => 'no',

			],
		);

		$this->add_control(
			'custom_class',
			[
				'type'  => \Elementor\Controls_Manager::TEXT,
				'label' => esc_html__( 'Custom class', 'search-with-typesense' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$placeHolder = $settings['placeHolder'];

		$columns = $settings['columns'];

		$post_type = implode( ',', $settings['post_type'] );

		$filter = $settings['filter'];
		if ( $filter ) {
			$filter = 'show';
		} else {
			$filter = 'hide';
		}

		$post_per_page = $settings['post_per_page'];

		$sort_by = $settings['sort_by'];
		if ( $sort_by ) {
			$sort_by = 'show';
		} else {
			$sort_by = 'hide';
		}

		$pagination = $settings['pagination'];
		if ( $pagination ) {
			$pagination = 'show';
		} else {
			$pagination = 'hide';
		}

		$sticky_first = $settings['sticky_first'];

		$custom_class = $settings['custom_class'];

		echo do_shortcode( '[cm_typesense_search placeholder="' . $placeHolder . '" columns="' . $columns . '" post_types="' . $post_type . '" filter="' . $filter . '" custom_class="' . $custom_class . '" per_page="' . $post_per_page . '"  sortby="' . $sort_by . '" pagination="' . $pagination . '" query_by="post_title,post_content" sticky_first="' . $sticky_first . '"]' );

	}
}

