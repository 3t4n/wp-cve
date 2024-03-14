<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ET_Theme_Demo_Influence_Blog extends ET_Theme_Demo {

	public static function import_files() {

		$server_url = 'https://demo.everestthemes.com/demo-data/influence-blog/free/';

		$demo_urls  = array(
			array(
				'import_file_name'           => __( 'Demo One', 'everest-toolkit' ),
				'import_file_url'            => $server_url . 'demo-one/content.xml',
				'import_widget_file_url'     => $server_url . 'demo-one/widgets.wie',
				'import_customizer_file_url' => $server_url . 'demo-one/customizer.dat',
				'import_preview_image_url'   => $server_url . 'demo-one/screenshot.png',
				'demo_url'                   => 'https://demo.everestthemes.com/influence-blog/free-demo-i/',
			)
		);

		return $demo_urls;
	}

	public static function after_import( $selected_import ) {

		// SET Menus
        $import_file_name = isset( $selected_import['import_file_name'] ) ? $selected_import['import_file_name'] : '';

	    if( !empty( $import_file_name ) ) {

            if( $import_file_name == 'Demo One' ) {

                $primary_menu 	= get_term_by( 'name', 'Main Menu', 'nav_menu' );
			    $top_menu 	= get_term_by( 'name', 'Top Menu', 'nav_menu' );

			    set_theme_mod(
			     	'nav_menu_locations',
			     	array(
			     		'menu-1' => $primary_menu->term_id,
			     		'menu-2' => $top_menu->term_id,
			     	)
			    );
            }
        }

        update_option( 'everest_themes', $installed_demos );
	}
}
