<?php

add_action( 'wp_enqueue_scripts', 'csr_include_scripts' );
add_action( 'admin_enqueue_scripts', 'csr_include_scripts' );
add_action( 'init','csr_plugin_status_check' );

function csr_install() {

    $csr_votes_table = CSRVOTESTBL;
    $sql_yasr_votes_table = "CREATE TABLE IF NOT EXISTS $csr_votes_table (
  		id bigint(20) NOT NULL AUTO_INCREMENT,
  		post_id bigint(20) NOT NULL,
 	 	reviewer_id bigint(20) NOT NULL,
 	 	overall_rating decimal(2,1) NOT NULL,
 	 	number_of_votes bigint(20) NOT NULL,
  		sum_votes decimal(11,1) NOT NULL,
  		review_type VARCHAR(10),
 		PRIMARY KEY  (id),
 		UNIQUE KEY post_id (post_id)	
	);";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql_yasr_votes_table);

    //Makesure the posttype registered before flush rewrite
    csr_post_reviewinit();
    csr_rewriterules();
}

function csr_uninstall() {
    csr_rewriterules();
}

function csr_include_scripts() {
    wp_enqueue_script( 'csr-jqrate', plugins_url('/asset/jRate.min.js', __FILE__), array('jquery'), CSRVERSION, true );
    wp_enqueue_script( 'csr-jqmain', plugins_url('/asset/main.js', __FILE__), array('jquery', 'csr-jqrate'), CSRVERSION, true );    
    wp_enqueue_style( 'csr-style', plugins_url('/asset/csr.css', __FILE__), array(), CSRVERSION );
}

function csr_rewriterules() {
    flush_rewrite_rules();
}

function csr_plugin_status_check() {
    
    $gf_addon       = 'cosmick-star-rating-gf/csr-feedaddon.php';    
    $importer_addon = 'reviews-importer/reviews-importer.php'; 
    
    $plugins = get_plugins();
    
    if( isset($plugins[$gf_addon]) || isset($plugins[$importer_addon]) ){
  
        if( !function_exists('request_filesystem_credentials') ){
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        }

        deactivate_plugins( array($gf_addon, $importer_addon) );      
        delete_plugins( array($gf_addon, $importer_addon) );
    }
}

require ( dirname(__FILE__) . '/star-rating-post.php' );
require ( dirname(__FILE__) . '/star-rating-widget.php' );
require ( dirname(__FILE__) . '/star-rating-settings.php' );
require ( dirname(__FILE__) . '/includes/csr-feedaddon.php' );
require ( dirname(__FILE__) . '/includes/reviews-importer.php' );
