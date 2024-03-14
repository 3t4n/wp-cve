<?php

function bc_hotelone_theme_init(){
	include('theme-functions.php');

	// Slider Section
	include('customizer/sections/customizer-slider.php');
	include('section-all/section-slider.php');

	// Service Section
	include('customizer/sections/customizer-service.php');
	include('section-all/section-service.php');

	// Room Section
	include('customizer/sections/customizer-room.php');
	include('section-all/section-room.php');

	// Callout Section
	include('customizer/sections/customizer-calltoaction.php');
	include('section-all/section-callout.php');

	// Testimonial Section
	include('customizer/sections/customizer-testimonial.php');
	include('section-all/section-testimonial.php');

	// Team Section
	include('customizer/sections/customizer-team.php');
	include('section-all/section-team.php');
}
add_action('init','bc_hotelone_theme_init');


// Demo importing
function bc_hotelone_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'Hotelone Data' , 'bc' );
	$default_settings['menu_title']  = esc_html__( 'Import Demo Data' , 'bc' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'one-click-demo-import';
	return $default_settings;
}
add_filter( 'ocdi/plugin_page_setup', 'bc_hotelone_plugin_page_setup' );

// Set home page and blog page
function bc_hotelone_after_import_setup() {

	$bc_menus = get_term_by( 'name', 'Main Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
		'primary' => $bc_menus->term_id,
		)
	);

	$frontpage_id = get_page_by_title( 'Home' );
	$blogpage_id  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $frontpage_id->ID );
	update_option( 'page_for_posts', $blogpage_id->ID );
}
add_action( 'ocdi/after_import', 'bc_hotelone_after_import_setup' );

// Demo import file links
function bc_hotelone_demo_content_files() {

	return array(
		array(
			'import_file_name' => 'Default Data',
			'import_file_url' => bc_plugin_url.'inc/hotelone/demo/theme-contents.xml',
			'import_widget_file_url' => bc_plugin_url.'inc/hotelone/demo/theme-widgets.wie',
			'import_customizer_file_url' => bc_plugin_url.'inc/hotelone/demo/theme-customizer.dat',

			'import_preview_image_url' => bc_plugin_url. 'inc/hotelone/demo/screenshot.png',
			'import_notice'              => __( 'Now click on the bottom button to import theme data, After you import this demo, Enjoy the theme.', 'bc' ),
			'preview_url' => '//www.britetechs.com/demo/themes/hotelone-pro/',
		)
	);
}
add_filter( 'ocdi/import_files', 'bc_hotelone_demo_content_files' );

// Recommanded plugins

if(file_exists( bc_plugin_dir . "inc/hotelone/required-plugin/index.php")){

	require(bc_plugin_dir . "inc/hotelone/required-plugin/index.php");

}