<?php

// Load plugin textdomain.
add_action( 'plugins_loaded', 'resumecv_load_textdomain' );
function resumecv_load_textdomain() {
  load_plugin_textdomain( 'resume-cv', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

// Action
function resumecv_head() {
	do_action('resumecv-head');
}

function resumecv_footer() {
	do_action('resumecv-footer');
}

// check variable exist
function resumecv_data($options,$var1,$var2='') {
	if ( isset( $options ) ) {
		if ($var2 == '') {
			if ( isset( $options[$var1] ) ) {
				return $options[$var1];
			}
		} else {
			if ( isset( $options[$var1] ) ) {
				if ( isset( $options[$var1][$var2] ) ) {
					return $options[$var1][$var2];
				}
			}
		}
	}
	return '';
}

// output variable data
function resumecv_output($before='',$content='',$after='') {
	$output = '';
	if ($content) {
		$output =  $before . "\n" . $content . "\n" . $after . "\n";
		echo wp_kses_post($output);
	}
	
}

function resumecv_theme_get() {
	$resumecv_theme = array (
		RESUMECV_PLUGIN_DIR . '/themes/shark' => 'shark',
		RESUMECV_PLUGIN_DIR . '/themes/shark-2' => 'shark-2'
		
	);
	if(has_filter('resumecv_theme_filter')) {
		$resumecv_theme = apply_filters('resumecv_theme_filter', $resumecv_theme);
	}
	return $resumecv_theme;
}

// Get Dir Theme functions.php
function resumecv_theme_get_dir() {
	$resumecv_options = get_option( 'resumecv_options');
	$theme_dir = RESUMECV_PLUGIN_DIR . 'themes/shark';
	$theme_dir_temp = resumecv_data($resumecv_options,'theme_dir');
	if ( $theme_dir_temp != '' ) {
		$theme_dir = $theme_dir_temp;
	}

	// $_GET
	$template = filter_input(INPUT_GET, 'template', FILTER_SANITIZE_STRING);
	if ($template) {
		$theme_array = resumecv_theme_get();
		if ($theme_array) {
			foreach ($theme_array as $key => $value) {
				if ($template == $value) {
					$theme_dir = $key;
					break;
				}
			}
		}
	}
	return $theme_dir;
}

function resumecv_theme_include_functions() {
	$theme_functions_file = resumecv_theme_get_dir() . "/functions.php";
	if ( file_exists( $theme_functions_file ) ) {
		require_once $theme_functions_file;
	} 
}

add_action( 'plugins_loaded', 'resumecv_theme_include_functions');

// MENU
function resumecv_menu_setup() {
	register_nav_menus( array(
			'resume-cv-primary' => esc_html__( 'Resume CV Primary', 'resume-cv' ),
	) );
}


// FOOTER
function resumecv_footer_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Resume CV Footer 1', 'resume-cv' ),
		'id'            => 'resumecv-footer-1',
		'description'   => esc_html__( 'Add widgets here.', 'resume-cv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Resume CV Footer 2', 'resume-cv' ),
		'id'            => 'resumecv-footer-2',
		'description'   => esc_html__( 'Add widgets here.', 'resume-cv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => esc_html__( 'Resume CV Footer 3', 'resume-cv' ),
		'id'            => 'resumecv-footer-3',
		'description'   => esc_html__( 'Add widgets here.', 'resume-cv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}


if ( ! function_exists( 'resumecv_footer_widgets' ) ) :
	function resumecv_footer_widgets() {
		$footer_1 = ''; $footer_2 = ''; $footer_3 = '';  $footer_columns = 0; $footer_class = 'col-md-4';
		if ( is_active_sidebar( 'resumecv-footer-1') ) {
			$footer_1 = 1; $footer_columns++;
		}
		if ( is_active_sidebar( 'resumecv-footer-2' ) ) {
			$footer_2 = 1; $footer_columns++;
		}
		if ( is_active_sidebar( 'resumecv-footer-3') ) {
			$footer_3 = 1; $footer_columns++;
		}
		
		if ( $footer_columns == 1 ) {
			$footer_class = 'col-md-12';
		} 
		
		if ( $footer_1 || $footer_2 || $footer_3 ) {
			
				echo '<div class="container">';
					echo '<div class="row">';
						if ( $footer_1 ) {
							echo '<div class="' . esc_attr( $footer_class ) . '">' . "\n";
								dynamic_sidebar( 'resumecv-footer-1' );
							echo '</div>' . "\n";
							
							if ($footer_columns == 2) {
								$footer_class = 'col-md-8';
							}
						}
						if ($footer_2) {
							echo '<div class="' . esc_attr( $footer_class ) . '">' . "\n";
								dynamic_sidebar( 'resumecv-footer-2' );
							echo '</div>' . "\n";
							if ($footer_columns == 2) {
								$footer_class = 'col-md-8';
							}
						}
						if ($footer_3) {
							echo '<div class="' . esc_attr( $footer_class ) . '">' . "\n";
								dynamic_sidebar( 'resumecv-footer-3' );
							echo '</div>' . "\n";
						}
					echo '</div>';
				echo '</div>';
			
		}
	}
endif;
