<?php
namespace ERocket;

use WP_Query;

class FeaturedPosts {
	public $quantity = 15;

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	public function init() {
		$theme_support = get_theme_support( 'efp' );

		// Return early if theme does not support featured content.
		if ( ! $theme_support || ! isset( $theme_support[0]['filter'] ) ) {
			return;
		}

		if ( isset( $theme_support[0]['quantity'] ) ) {
			$this->quantity = absint( $theme_support[0]['quantity'] );
		}

		add_filter( $theme_support[0]['filter'], [ $this, 'get_featured_posts' ] );
		add_action( 'customize_register', [ $this, 'customize_register' ], 9 );
	}

	public function get_featured_posts() {
		$post_ids = $this->get_featured_post_ids();

		if ( empty( $post_ids ) ) {
			return [];
		}

		$featured_posts = get_posts( [
			'include'          => $post_ids,
			'posts_per_page'   => count( $post_ids ),
			'post_type'        => [ 'post' ],
			'suppress_filters' => false,
		] );

		return $featured_posts;
	}

	public function get_featured_post_ids() {
		$setting = $this->get_setting();
		if ( ! $setting['tag-name'] ) {
			return [];
		}
		$term = get_term_by( 'name', $setting['tag-name'], 'post_tag' );

		$tag = $term ? $term->term_id : [];

		$featured = new WP_Query( [
			'posts_per_page'   => $this->quantity,
			'post_type'        => [ 'post' ],
			'suppress_filters' => false,
			'fields'           => 'ids',
			'tax_query'        => [
				[
					'taxonomy' => 'post_tag',
					'terms'    => $tag,
				],
			],
		] );

		if ( ! $featured->have_posts() ) {
			return [];
		}

		return array_map( 'absint', $featured->posts );
	}

	public function get_setting() {
		$setting = get_option( 'efp' );
		$default = [
			'tag-name' => '',
		];

		$options = wp_parse_args( $setting, $default );
		return $options;
	}

	public function customize_register( $wp_customize ) {
		$wp_customize->add_section( 'efp', [
			'title'          => esc_html__( 'Featured Posts', 'erocket' ),
			// Translators: %1$s - URL, %2$s - quantity.
			'description'    => sprintf( __( 'Easily feature all posts with the <a href="%1$s">"featured" tag</a> or a tag of your choice. Your theme supports up to %2$s posts in its featured content area.', 'erocket' ), admin_url( '/edit.php?tag=featured' ), absint( $this->quantity ) ),
			'theme_supports' => 'efp',
		] );
		$wp_customize->add_setting( 'efp[tag-name]', [
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		] );
		$wp_customize->add_control( 'efp[tag-name]', [
			'label'          => esc_html__( 'Tag name', 'erocket' ),
			'section'        => 'efp',
			'theme_supports' => 'efp',
		] );
	}
}
