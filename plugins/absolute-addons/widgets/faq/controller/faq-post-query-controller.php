<?php
/**
 * Template for Post Query Controller
 *
 * @package AbsoluteAddons
 */

use Elementor\Controls_Manager;

$this->start_controls_section(
	'query_section',
	array(
		'label'     => __( 'Query Section', 'absolute-addons' ),
		'condition' => [
			'absolute_faq!' => [ 'three', 'seven', 'eight' ],
		],
	)
);

$this->add_control(
	'number_of_posts',
	[
		'label'        => esc_html__( 'Posts Count', 'absolute-addons' ),
		'type'         => Controls_Manager::NUMBER,
		'min'          => 1,
		'max'          => 200,
		'step'         => 1,
		'default'      => 8,
		'descriptions' => esc_html__( 'If You need to show all post to input "-1"', 'absolute-addons' ),
	]
);

$this->add_control(
	'select_faq_post',
	[
		'label'    => esc_html__( 'Select Category', 'absolute-addons' ),
		'type'     => Controls_Manager::SELECT,
		'multiple' => true,
		'options'  => [
			'recent_post' => esc_html__( 'Recent Post', 'absolute-addons' ),
			'category'    => esc_html__( 'Category Post', 'absolute-addons' ),
		],
		'default'  => esc_html__( 'recent_post', 'absolute-addons' ),
	]
);

$all_terms = get_terms( 'faq_category', [ 'hide_empty' => true ] );

$args = [
	'post_type'      => 'faq',
	'post_status'    => 'publish',
	'posts_per_page' => - 1,
];

// we get an array of posts objects


$faq_terms = [];

foreach ( (array) $all_terms as $single_terms ) {
	$faq_terms[ $single_terms->slug . '|' . $single_terms->name ] = $single_terms->name;
}

$this->add_control(
	'faq_category_post',
	[
		'label'     => esc_html__( 'Select Category', 'absolute-addons' ),
		'type'      => Controls_Manager::SELECT2,
		'multiple'  => true,
		'options'   => $faq_terms,
		'condition' => [
			'select_faq_post' => [ 'category' ],
		],
	]
);

$this->add_control(
	'faq_posts_offset',
	[
		'label'   => esc_html__( 'Offset', 'absolute-addons' ),
		'type'    => Controls_Manager::NUMBER,
		'min'     => 0,
		'max'     => 20,
		'default' => 0,
	]
);

$this->add_control(
	'faq_posts_order_by',
	[
		'label'   => esc_html__( 'Order by', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => [
			'date'          => esc_html__( 'Date', 'absolute-addons' ),
			'title'         => esc_html__( 'Title', 'absolute-addons' ),
			'author'        => esc_html__( 'Author', 'absolute-addons' ),
			'modified'      => esc_html__( 'Modified', 'absolute-addons' ),
			'comment_count' => esc_html__( 'Comments', 'absolute-addons' ),
		],
		'default' => 'date',
	]
);

$this->add_control(
	'faq_posts_sort',
	[
		'label'   => esc_html__( 'Order', 'absolute-addons' ),
		'type'    => Controls_Manager::SELECT,
		'options' => [
			'ASC'  => esc_html__( 'ASC', 'absolute-addons' ),
			'DESC' => esc_html__( 'DESC', 'absolute-addons' ),
		],
		'default' => 'DESC',
	]
);

$this->end_controls_section();
