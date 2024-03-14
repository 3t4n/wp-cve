<?php
//-------------- Enqueue Latest jQuery------------
function gs_portfolio_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'gs_portfolio_jquery');

//-------------- Include js files---------------
function gs_portfolio_enq_scripts() {
	if (!is_admin()) {
		wp_register_script('gsp-vendor', GSPORTFOLIO_FILES_URI . '/assets/js/gs-vendor.js', array('jquery'), GSPORTFOLIO_VERSION, true);
		wp_register_script('gsp-custom-js', GSPORTFOLIO_FILES_URI . '/assets/js/gs-custom.js', array('jquery'), GSPORTFOLIO_VERSION, true);
		wp_enqueue_script('masonry');
		wp_enqueue_script('gsp-vendor');
		wp_enqueue_script('gsp-custom-js');
	}
}
add_action( 'wp_enqueue_scripts', 'gs_portfolio_enq_scripts' ); 

//------------ Include css files-----------------
function gs_portfolio_adding_style() {
	if (!is_admin()) {
		
		$media = 'all';

		wp_enqueue_style('gsp-vendor', GSPORTFOLIO_FILES_URI . '/assets/css/gs-vendor.css','', GSPORTFOLIO_VERSION, $media );
		wp_register_style('gsp-font-awesome', GSPORTFOLIO_FILES_URI . '/assets/fa-icons/css/font-awesome.min.css', '', GSPORTFOLIO_VERSION, $media );
		wp_register_style('gsp-style', GSPORTFOLIO_FILES_URI . '/assets/css/gsp-style.css','', GSPORTFOLIO_VERSION, $media );		
		wp_enqueue_style('gsp-font-awesome');
		wp_enqueue_style('gsp-magnific-pop');
		wp_enqueue_style('gsp-style');	
	}
}
add_action( 'init', 'gs_portfolio_adding_style' );


function gsp_admin_scripts() {
    wp_register_script('gsp-init-js', GSPORTFOLIO_FILES_URI . '/admin/js/gsp-init.js', array('jquery'), GSPORTFOLIO_VERSION, true);
    wp_enqueue_script('gsp-init-js');
    wp_enqueue_style('gsp-custom-style',GSPORTFOLIO_FILES_URI . '/admin/css/gs-portfolio-custom.css','', GSPORTFOLIO_VERSION, 'all'  );
    wp_enqueue_style('gsp-free-plugins',GSPORTFOLIO_FILES_URI . '/admin/css/gs_free_plugins.css','', GSPORTFOLIO_VERSION, 'all'  );
}
add_action('admin_enqueue_scripts', 'gsp_admin_scripts');