<?php

// -- Include css files
if ( ! function_exists('gs_enqueue_envato_styles') ) {
	function gs_enqueue_envato_styles() {
		if (!is_admin()) {
			$media = 'all';

			if(!wp_style_is('gsenvato-fa-icons','registered')){
				wp_register_style('gsenvato-fa-icons', GSENVATO_FILES_URI . '/assets/fa-icons/css/font-awesome.min.css','', GSENVATO_VERSION, $media);
			}
			if(!wp_style_is('gsenvato-fa-icons','enqueued')){
				wp_enqueue_style('gsenvato-fa-icons');
			}

			wp_register_style('gs-envato-custom-bootstrap', GSENVATO_FILES_URI . '/assets/css/gs-envato-custom-bootstrap.css','', GSENVATO_VERSION, $media);
			wp_enqueue_style('gs-envato-custom-bootstrap');

			// Plugin main stylesheet
			wp_register_style('gs_envato_csutom_css', GSENVATO_FILES_URI . '/assets/css/gs-envato-custom.css','', GSENVATO_VERSION, $media);
			wp_enqueue_style('gs_envato_csutom_css');			
		}
	}
	add_action( 'init', 'gs_enqueue_envato_styles' );
}

// -- Admin css
function gsenvato_enque_admin_style() {
    $media = 'all';
    wp_register_style('gsenvato_switchButton_css', GSENVATO_FILES_URI . '/admin/css/gsenvato.jquery.switchButton.css','', GSENVATO_VERSION );
    wp_enqueue_style('gsenvato_switchButton_css');

    wp_register_style( 'gsenvato-free-pligin-style', GSENVATO_FILES_URI . '/admin/css/gs_free_plugins.css', '', GSENVATO_VERSION, $media );
    wp_enqueue_style( 'gsenvato-free-pligin-style' );
    
    wp_register_style( 'gsenvato-admin-style', GSENVATO_FILES_URI . '/admin/css/gsenvato_admin_style.css', '', GSENVATO_VERSION, $media );
    wp_enqueue_style( 'gsenvato-admin-style' );

    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-effects-core');

    wp_register_script( 'gs_envato_SwitchButtonJs', GSENVATO_FILES_URI . '/admin/js/gsenvato.jquery.switchButton.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-effects-core' ), GSENVATO_VERSION, false );
    wp_enqueue_script('gs_envato_SwitchButtonJs');
}
add_action( 'admin_enqueue_scripts', 'gsenvato_enque_admin_style' );