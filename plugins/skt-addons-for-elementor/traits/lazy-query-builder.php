<?php
namespace Skt_Addons_Elementor\Elementor\Traits;

use Elementor\Controls_Manager;
use Skt_Addons_Elementor\Elementor\Controls\Lazy_Select;
use Skt_Addons_Elementor\Lazy_Query_Manager;

defined('ABSPATH') || die();

trait Lazy_Query_Builder {

	protected $query_args = [];

	protected function register_query_controls() {
		$this->add_control(
			'posts_post_type',
			[
				'label' => __( 'Post Type', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => Lazy_Query_Manager::get_post_types_list(),
				'default' => 'post',
			]
		);

		$this->add_control(
			'posts_selected_ids',
			[
				'label' => __( 'Search & Select', 'skt-addons-elementor' ),
				'type' => Lazy_Select::TYPE,
				'multiple' => true,
				'label_block' => true,
				'lazy_args' => [
					'query' => Lazy_Query_Manager::QUERY_POSTS,
				],
				'condition' => [
					'posts_post_type' => 'manual_selection'
				]
			]
		);

		$this->start_controls_tabs(
			'_tabs_posts_include_exclude',
			[
				'condition' => [
					'posts_post_type!' => 'manual_selection'
				]
			]
		);
		$this->start_controls_tab(
            '_tab_posts_include',
            [
				'label' => __( 'Include', 'skt-addons-elementor' ),
				'condition' => [
					'posts_post_type!' => 'manual_selection'
				]
            ]
		);

		$this->add_control(
			'posts_include_by',
			[
				'label' => __( 'Include By', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'options' => [
					'authors' => __( 'Authors', 'skt-addons-elementor' ),
					'terms' => __( 'Terms', 'skt-addons-elementor' ),
				],
				'condition' => [
					'posts_post_type!' => 'manual_selection'
				]
			]
		);

		$this->add_control(
			'posts_include_author_ids',
			[
				'label' => __( 'Authors', 'skt-addons-elementor' ),
				'type' => Lazy_Select::TYPE,
				'multiple' => true,
				'label_block' => true,
				'lazy_args' => [
					'query' => Lazy_Query_Manager::QUERY_AUTHORS,
				],
				'condition' => [
					'posts_include_by' => 'authors',
					'posts_post_type!' => 'manual_selection'
				]
			]
		);

		$this->add_control(
			'posts_include_term_ids',
			[
				'label' => __( 'Terms', 'skt-addons-elementor' ),
				'description' => __( 'Terms are items in a taxonomy. The available taxonomies are: Categories, Tags, Formats and custom taxonomies.', 'skt-addons-elementor' ),
				'type' => Lazy_Select::TYPE,
				'multiple' => true,
				'label_block' => true,
				'placeholder' => __( 'Type and select terms', 'skt-addons-elementor' ),
				'lazy_args' => [
					'query' => Lazy_Query_Manager::QUERY_TERMS,
					'widget_props' => [
						'post_type' => 'posts_post_type'
					]
				],
				'condition' => [
					'posts_include_by' => 'terms',
					'posts_post_type!' => 'manual_selection'
				]
			]
		);

		$this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_posts_exclude',
            [
				'label' => __( 'Exclude', 'skt-addons-elementor' ),
				'condition' => [
					'posts_post_type!' => 'manual_selection'
				]
            ]
		);

		$this->add_control(
			'posts_exclude_by',
			[
				'label' => __( 'Exclude By', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'options' => [
					'authors'           => __( 'Authors', 'skt-addons-elementor' ),
					'current_post'      => __( 'Current Post', 'skt-addons-elementor' ),
					'manual_selection'  => __( 'Manual Selection', 'skt-addons-elementor' ),
					'terms'             => __( 'Terms', 'skt-addons-elementor' ),
				],
				'condition' => [
					'posts_post_type!' => 'manual_selection'
				]
			]
		);

		$this->add_control(
			'posts_exclude_ids',
			[
				'label' => __( 'Search & Select', 'skt-addons-elementor' ),
				'type' => Lazy_Select::TYPE,
				'multiple' => true,
				'label_block' => true,
				'lazy_args' => [
					'query' => Lazy_Query_Manager::QUERY_POSTS,
					'widget_props' => [
						'post_type' => 'posts_post_type'
					]
				],
				'condition' => [
					'posts_exclude_by' => 'manual_selection',
					'posts_post_type!' => 'manual_selection'
				]
			]
		);

		$this->add_control(
			'posts_exclude_author_ids',
			[
				'label' => __( 'Authors', 'skt-addons-elementor' ),
				'type' => Lazy_Select::TYPE,
				'multiple' => true,
				'label_block' => true,
				'lazy_args' => [
					'query' => Lazy_Query_Manager::QUERY_AUTHORS,
				],
				'condition' => [
					'posts_exclude_by' => 'authors',
					'posts_post_type!' => 'manual_selection'
				]
			]
		);

		$this->add_control(
			'posts_exclude_term_ids',
			[
				'label' => __( 'Terms', 'skt-addons-elementor' ),
				'description' => __( 'Terms are items in a taxonomy. The available taxonomies are: Categories, Tags, Formats and custom taxonomies.', 'skt-addons-elementor' ),
				'type' => Lazy_Select::TYPE,
				'multiple' => true,
				'label_block' => true,
				'placeholder' => __( 'Type and select terms', 'skt-addons-elementor' ),
				'lazy_args' => [
					'query' => Lazy_Query_Manager::QUERY_TERMS,
					'widget_props' => [
						'post_type' => 'posts_post_type'
					]
				],
				'condition' => [
					'posts_exclude_by' => 'terms',
					'posts_post_type!' => 'manual_selection'
				]
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'_tabs_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'posts_select_date',
			[
				'label' => __( 'Date', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'anytime',
				'options' => [
					'anytime' => __( 'All', 'skt-addons-elementor' ),
					'today'   => __( 'Past Day', 'skt-addons-elementor' ),
					'week'    => __( 'Past Week', 'skt-addons-elementor' ),
					'month'   => __( 'Past Month', 'skt-addons-elementor' ),
					'quarter' => __( 'Past Quarter', 'skt-addons-elementor' ),
					'year'    => __( 'Past Year', 'skt-addons-elementor' ),
					'exact'   => __( 'Custom', 'skt-addons-elementor' ),
				],
				'condition' => [
					'posts_post_type!' => 'manual_selection',
				]
			]
		);

		$this->add_control(
			'posts_date_before',
			[
				'label' => __( 'Before', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'description' => __( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'skt-addons-elementor' ),
				'condition' => [
					'posts_select_date' => 'exact',
					'posts_post_type!' => 'manual_selection',
				]
			]
		);

		$this->add_control(
			'posts_date_after',
			[
				'label' => __( 'After', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DATE_TIME,
				'description' => __( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'skt-addons-elementor' ),
				'condition' => [
					'posts_select_date' => 'exact',
					'posts_post_type!' => 'manual_selection',
				]
			]
		);

		$this->add_control(
			'posts_orderby',
			[
				'label' => __( 'Order By', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'author'        => __( 'Author', 'skt-addons-elementor' ),
					'comment_count' => __( 'Comment Count', 'skt-addons-elementor' ),
					'date'          => __( 'Date', 'skt-addons-elementor' ),
					'ID'            => __( 'ID', 'skt-addons-elementor' ),
					'menu_order'    => __( 'Menu Order', 'skt-addons-elementor' ),
					'rand'          => __( 'Random', 'skt-addons-elementor' ),
					'title'         => __( 'Title', 'skt-addons-elementor' ),
				]
			]
		);

		$this->add_control(
			'posts_order',
			[
				'label' => __( 'Order', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'skt-addons-elementor' ),
					'desc' => __( 'DESC', 'skt-addons-elementor' ),
				]
			]
		);

		$this->add_control(
			'posts_ignore_sticky_posts',
			[
				'label' => __( 'Ignore Sticky Posts', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'posts_post_type' => 'post'
				]
			]
		);

		$this->add_control(
			'posts_only_with_featured_image',
			[
				'label' => __( 'With Featured Image', 'skt-addons-elementor' ),
				'description' => __( 'Enable to display posts only when featured image is set.', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'posts_post_type!' => 'manual_selection'
				]
			]
		);
	}

	protected function get_query_args() {
		$this->setup_query_base();
		$this->setup_query_post_type();
		$this->setup_query_ignore_sticky();
		$this->setup_query_exclude_post();
		$this->setup_query_authors();
		$this->setup_query_taxonomy();
		$this->setup_query_with_featured_image();
		$this->setup_query_date();

		$this->setup_query_include_manual();

		$this->setup_query_order();

		return $this->query_args;
	}

	protected function setup_query_base() {
		$this->query_args['post_status'] = 'publish';
		$this->query_args['suppress_filters'] = false;
	}

	protected function get_query_post_type() {
		return $this->get_settings_for_display( 'posts_post_type' );
	}

	protected function is_query_manual() {
		return ( $this->get_query_post_type() === 'manual_selection' );
	}

	protected function setup_query_post_type() {
		if ( ! $this->is_query_manual() ) {
			$this->query_args['post_type'] = $this->get_query_post_type();
		} elseif ( $this->is_query_manual() ) {
			$this->query_args['post_type'] = 'any';
		}
	}

	protected function setup_query_date() {
		if ( $this->is_query_manual() ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$selected_date = $settings['posts_select_date'];

		if ( ! empty( $selected_date ) ) {
			$date_query = [];

			switch ( $selected_date ) {
				case 'today':
					$date_query['after'] = '-1 day';
					break;

				case 'week':
					$date_query['after'] = '-1 week';
					break;

				case 'month':
					$date_query['after'] = '-1 month';
					break;

				case 'quarter':
					$date_query['after'] = '-3 month';
					break;

				case 'year':
					$date_query['after'] = '-1 year';
					break;

				case 'exact':
					$after_date = $settings['posts_date_after'];
					if ( ! empty( $after_date ) ) {
						$date_query['after'] = $after_date;
					}

					$before_date = $settings['posts_date_before'];
					if ( ! empty( $before_date ) ) {
						$date_query['before'] = $before_date;
					}

					$date_query['inclusive'] = true;
					break;
			}

			if ( ! empty( $date_query ) ) {
				$this->query_args['date_query'] = $date_query;
			}
		}
	}

	protected function setup_query_include_manual() {
		if ( ! $this->is_query_manual() ) {
			return;
		}

		$selected_ids = $this->get_settings_for_display( 'posts_selected_ids' );
		$selected_ids = wp_parse_id_list( $selected_ids );

		if ( ! empty( $selected_ids ) ) {
			$this->query_args['post__in'] = $selected_ids;
		}
	}

	protected function setup_query_ignore_sticky() {
		if ( $this->get_query_post_type() === 'post' &&
			$this->get_settings_for_display( 'posts_ignore_sticky_posts' ) === 'yes'
			) {
			$this->query_args['ignore_sticky_posts'] = true;
		}
	}

	protected function setup_query_with_featured_image() {
		if ( $this->is_query_manual() ) {
			return;
		}

		if ( $this->get_settings_for_display( 'posts_only_with_featured_image' ) === 'yes' ) {
			$this->query_args['meta_key'] = '_thumbnail_id';
		}
	}

	protected function setup_query_order() {
		$this->query_args['order'] = $this->get_settings_for_display( 'posts_order' );
		$this->query_args['orderby'] = $this->get_settings_for_display( 'posts_orderby' );
	}

	protected function get_query_param_by( $by = 'exclude' ) {
		$by_map = [
			'exclude' => 'posts_exclude_by',
			'include' => 'posts_include_by',
		];

		$_setting = $this->get_settings_for_display( $by_map[ $by ] );

		return ( ! empty( $_setting ) ? $_setting : [] );
	}

	protected function setup_query_exclude_post() {
		if ( $this->is_query_manual() ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$exclude_by = $this->get_query_param_by( 'exclude' );
		$current_post = [];

		if ( in_array( 'current_post', $exclude_by ) && is_singular() ) {
			$current_post = [ get_the_ID() ];
		}

		if ( in_array( 'manual_selection', $exclude_by ) ) {
			$exclude_ids = $settings['posts_exclude_ids'];
			$this->query_args['post__not_in'] = array_merge( $current_post, wp_parse_id_list( $exclude_ids ) );
		}
	}

	protected function setup_query_taxonomy() {
		if ( $this->is_query_manual() ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$include_by = $this->get_query_param_by( 'include' );
		$exclude_by = $this->get_query_param_by( 'exclude' );
		$include_terms = [];
		$exclude_terms = [];
		$terms_query = [];

		if ( in_array( 'terms', $include_by ) ) {
			$include_terms = wp_parse_id_list( $settings['posts_include_term_ids'] );
		}

		if ( in_array( 'terms', $exclude_by ) ) {
			$exclude_terms = wp_parse_id_list( $settings['posts_exclude_term_ids'] );
			$include_terms = array_diff( $include_terms, $exclude_terms );
		}

		if ( ! empty( $include_terms ) ) {
			$tax_terms_map = self::get_query_tax_terms_map( $include_terms );

			foreach ( $tax_terms_map as $tax => $terms ) {
				$terms_query[] = [
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => $terms,
					'operator' => 'IN',
				];
			}
		}

		if ( ! empty( $exclude_terms ) ) {
			$tax_terms_map = self::get_query_tax_terms_map( $exclude_terms );

			foreach ( $tax_terms_map as $tax => $terms ) {
				$terms_query[] = [
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => $terms,
					'operator' => 'NOT IN',
				];
			}
		}

		if ( ! empty( $terms_query ) ) {
			$this->query_args['tax_query'] = $terms_query;
			$this->query_args['tax_query']['relation'] = 'AND';
		}
	}

	protected static function get_query_tax_terms_map( $term_tax_ids = [] ) {
		$terms = get_terms( [
			'term_taxonomy_id' => $term_tax_ids,
			'hide_empty' => false,
		] );

		$tax_terms_map = [];

		foreach ( $terms as $term ) {
			$taxonomy = $term->taxonomy;
			$tax_terms_map[ $taxonomy ][] = $term->term_id;
		}

		return $tax_terms_map;
	}

	protected function setup_query_authors() {
		if ( $this->is_query_manual() ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$include_by = $this->get_query_param_by( 'include' );;
		$exclude_by = $this->get_query_param_by( 'exclude' );;
		$include_users = [];
		$exclude_users = [];

		if ( in_array( 'authors', $include_by ) ) {
			$include_users = wp_parse_id_list( $settings['posts_include_author_ids'] );
		}

		if ( in_array( 'authors', $exclude_by ) ) {
			$exclude_users = wp_parse_id_list( $settings['posts_exclude_author_ids'] );
			$include_users = array_diff( $include_users, $exclude_users );

		}

		if ( ! empty( $include_users ) ) {
			$this->query_args['author__in'] = $include_users;
		}

		if ( ! empty( $exclude_users ) ) {
			$this->query_args['author__not_in'] = $exclude_users;;
		}
	}
}