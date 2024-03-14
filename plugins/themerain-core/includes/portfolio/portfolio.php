<?php

final class ThemeRainPortfolio {

	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		add_action( 'init', array( $this, 'portfolio_init' ) );
		add_action( 'admin_init', array( $this, 'custom_slug' ) );
		add_filter( 'manage_edit-project_columns', array( $this, 'add_thumbnail_column' ), 10, 1 );
		add_action( 'manage_project_posts_custom_column', array( $this, 'display_thumbnail' ), 10, 1 );
	}

	public function plugin_activation() {
		flush_rewrite_rules();
	}

	public function is_portfolio_showing() {
		$show_portfolio = true;
		$show_portfolio = apply_filters( 'show_portfolio', $show_portfolio );

		return $show_portfolio;
	}

	public function portfolio_init() {
		if ( ! $this->is_portfolio_showing() ) {
			return;
		}

		$labels = array(
			'name'          => esc_html__( 'Projects', 'themerain' ),
			'singular_name' => esc_html__( 'Project', 'themerain' ),
			'all_items'     => esc_html__( 'All Projects', 'themerain' ),
		);

		$args = array(
			'labels'        => $labels,
			'public'        => true,
			'show_in_rest'  => true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-portfolio',
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom_fields' )
		);

		if ( get_option( 'themerain_portfolio_slug' ) ) {
			$args['rewrite']['slug'] = get_option( 'themerain_portfolio_slug' );
		}

		register_post_type( 'project', $args );

		$tax_args = array(
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'hierarchical'      => true
		);

		register_taxonomy( 'project-category', 'project', $tax_args );
	}

	public function add_thumbnail_column( $columns ) {
		$column_thumb = array( 'thumbnail' => 'Thumbnail' );
		$columns      = array_slice( $columns, 0, 2, true ) + $column_thumb + array_slice( $columns, 1, NULL, true );

		return $columns;
	}

	public function display_thumbnail( $column ) {
		global $post;

		switch ( $column ) {
			case 'thumbnail' :
				echo get_the_post_thumbnail( $post->ID, array( 35, 35 ) );
				break;
		}
	}

	public function custom_slug() {
		if ( ! $this->is_portfolio_showing() ) {
			return;
		}

		if ( isset( $_POST['permalink_structure'] ) && isset( $_POST['themerain_portfolio_slug'] ) ) {
			update_option( 'themerain_portfolio_slug', sanitize_title_with_dashes( $_POST['themerain_portfolio_slug'] ) );
		}

		add_settings_field( 'themerain_permalink_portfolio', 'Portfolio base', array( $this, 'custom_slug_callback' ), 'permalink', 'optional' );
	}

	public function custom_slug_callback() {
		$slug = get_option( 'themerain_portfolio_slug' );
		echo '<input type="text" name="themerain_portfolio_slug" class="regular-text code" value="' . esc_attr( $slug ) . '">';
	}

}

new ThemeRainPortfolio();
