<?php

namespace AbsoluteAddons;

class Absp_Post_types {

	/**
	 * Hook in methods.
	 */
	public static function init() {

		add_action( 'init', [ __CLASS__, 'register_taxonomies' ], 5 );
		add_action( 'init', [ __CLASS__, 'register_post_types' ], 5 );
		add_action( 'absp/flush_rewrite_rules', [ __CLASS__, 'flush_rewrite_rules' ] );
	}

	public static function register_taxonomies() {

		if ( ! is_blog_installed() ) {
			return;
		}

		do_action( 'absp/register_taxonomies' );

		// Portfolio Taxonomies

		if ( ! taxonomy_exists( 'portfolio_category' ) ) {

			$args = [
				'hierarchical' => true,
				'labels'       => [
					'name'          => _x( 'Portfolios Categories', 'taxonomy general name', 'absolute-addons' ),
					'singular_name' => _x( 'Portfolio Categories', 'taxonomy singular name', 'absolute-addons' ),
				],
				'show_in_rest' => true,
			];

			register_taxonomy( 'portfolio_category', 'portfolio', $args );
		}

		if ( ! taxonomy_exists( 'portfolio_tag' ) ) {

			$args = [
				'hierarchical'          => false,
				'labels'                => [
					'name'          => _x( 'Portfolio Tags', 'taxonomy general name', 'absolute-addons' ),
					'singular_name' => _x( 'Portfolio Tag', 'taxonomy singular name', 'absolute-addons' ),
				],
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'show_in_rest'          => true,
			];

			register_taxonomy( 'portfolio_tag', 'portfolio', $args );
		}

		// FAQ Taxonomies

		if ( ! taxonomy_exists( 'faq_category' ) ) {

			$args = [
				'hierarchical' => true,
				'labels'       => [
					'name'          => _x( 'FAQs Categories', 'taxonomy general name', 'absolute-addons' ),
					'singular_name' => _x( 'FAQs Categories', 'taxonomy singular name', 'absolute-addons' ),
				],
				'show_in_rest' => true,
			];

			register_taxonomy( 'faq_category', 'faq', $args );
		}

		if ( ! taxonomy_exists( 'faq_tag' ) ) {
			$args = [
				'hierarchical'          => false,
				'labels'                => [
					'name'          => _x( 'FAQs Tags', 'taxonomy general name', 'absolute-addons' ),
					'singular_name' => _x( 'FAQs Tag', 'taxonomy singular name', 'absolute-addons' ),
				],
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'show_in_rest'          => true,
			];

			register_taxonomy( 'faq_tag', 'faq', $args );
		}

		do_action( 'absp/after/register_taxonomies' );

	}

	public static function register_post_types() {

		if ( ! is_blog_installed() ) {
			return;
		}

		do_action( 'absp/register_post_type' );

		if ( ! post_type_exists( 'portfolio' ) ) {
			$args = [
				'public'       => true,
				'label'        => __( 'Portfolio', 'absolute-addons' ),
				'show_in_rest' => true,
				'supports'     => [ 'title', 'editor', 'thumbnail' ],
				'taxonomies'   => [ 'portfolio_category', 'portfolio_tag' ],
			];
			register_post_type( 'portfolio', $args );
		}

		if ( ! post_type_exists( 'faq' ) ) {
			$args = [
				'public'       => true,
				'label'        => __( 'FAQs', 'absolute-addons' ),
				'show_in_rest' => true,
				'supports'     => [ 'title', 'editor', 'thumbnail' ],
				'taxonomies'   => [ 'faq_category', 'faq_tag' ],
			];
			register_post_type( 'faq', $args );
		}

		do_action( 'absp/after/register_post_type' );
	}

	/**
	 * Flush rewrite rules.
	 */
	public static function flush_rewrite_rules() {
		flush_rewrite_rules(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
	}
}

Absp_Post_types::init();

// End of file class-absp-post-types.php.
