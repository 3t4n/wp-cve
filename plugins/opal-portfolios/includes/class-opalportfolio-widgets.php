<?php

add_action( 'widgets_init', 'opalportfolio_widgets_init' );

if ( ! function_exists( 'opalportfolio_widgets_init' ) ) {
	/**
	 * Initializes themes widgets.
	 */
	function opalportfolio_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Right Sidebar Portfolio', 'opalportfolios' ),
			'id'            =>  'right-sidebar-portfolio',
			'description'   => esc_html__( 'Right sidebar portfolio widget area', 'opalportfolios' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Left Sidebar Portfolio', 'opalportfolios' ),
			'id'            => 'left-sidebar-portfolio',
			'description'   => esc_html__( 'Left sidebar portfolio widget area', 'opalportfolios' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
} // endif function_exists( 'opalportfolio_widgets_init' ).