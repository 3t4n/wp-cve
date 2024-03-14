<?php 
defined('ABSPATH') or die("No direct script access!");

//Simple list assets
if(!function_exists('qcilist_check_for_shortcode')){
    function qcilist_check_for_shortcode($posts) {
        if ( empty($posts) )
            return $posts;
     
        // false because we have to search through the posts first
        $found = false;
     
        // search through each post
        foreach ($posts as $post) {
            // check the post content for the short code
            if ( stripos($post->post_content, 'qcld-ilist') )
                // Found a post with the short code
                $found = true;
                // stop the search
                break;
            }

        if ($found){
           //Load Script and Stylesheets
           add_action('wp_enqueue_scripts', 'qcilist_load_all_scripts');
        }

        return $posts;
    }
}

//perform the check when the_posts() function is called
add_action('the_posts', 'qcilist_check_for_shortcode');

//add_action( 'admin_enqueue_scripts', 'qcilist_load_all_scripts' );
if(!function_exists('qcilist_load_all_scripts')){
    function qcilist_load_all_scripts(){
        wp_enqueue_style( 'ilist_fontawesome-css',  QCOPD_ASSETS_URL1 . '/css/font-awesome.css');
        wp_enqueue_style( 'ilist_custom-css',       QCOPD_ASSETS_URL1 . '/css/sl-directory-style.css');
        wp_enqueue_style( 'ilist_custom-rwd-css',   QCOPD_ASSETS_URL1 . '/css/sl-directory-style-rwd.css');

        //Scripts
        wp_enqueue_script( 'jquery', 'jquery');
    	wp_enqueue_script( 'ilist-chart-js',        QCOPD_ASSETS_URL1 . '/js/Chart.js', array('jquery'));
        wp_enqueue_script( 'ilist_grid-packery',    QCOPD_ASSETS_URL1 . '/js/packery.pkgd.js', array('jquery'));
        wp_enqueue_script( 'ilist_custom-script',   QCOPD_ASSETS_URL1 . '/js/directory-script.js', array('jquery', 'ilist_grid-packery'));
    	
        
    }
}

if(!function_exists('ilist_admin_style_script')){
    function ilist_admin_style_script(){
        global $post_type;

    	wp_enqueue_style( 'admin-fontawesome-css',     QCOPD_ASSETS_URL1 . '/css/font-awesome.css');
    	wp_enqueue_style( 'ilist_admin_light_css',     QCOPD_ASSETS_URL1 . "/css/lightbox.min.css");
    	wp_enqueue_style( 'ilist-admin-css',           QCOPD_ASSETS_URL1 . '/css/ilist_custom_style.css');
    	wp_enqueue_style( 'ilist-chart-field-css',     QCOPD_ASSETS_URL1 . '/css/chart-field.css' );
    	wp_enqueue_style( 'ilist-fa-field-css',        QCOPD_ASSETS_URL1 . '/css/fa-field.css' );
        wp_enqueue_style( 'ilist_img5_fancyboxes_css', QCOPD_ASSETS_URL1 . "/fancybox/jquery.fancybox.css");

        wp_enqueue_style( 'ilist_free_admin_css',      QCOPD_ASSETS_URL1 . "/css/style.css");
        
        wp_enqueue_style( 'jq-slick.css-css',          QCOPD_ASSETS_URL1 . '/css/slick.css');
        wp_enqueue_style( 'jq-slick-theme-css',        QCOPD_ASSETS_URL1 . '/css/slick-theme.css', array(), '1.0.1');
    	wp_enqueue_style( 'jq-slick.css-carousel',     QCOPD_ASSETS_URL1 . '/css/owl.carousel.min.css');
    	wp_enqueue_style( 'jq-slick.css-default',      QCOPD_ASSETS_URL1 . '/css/owl.theme.default.css');


        if( ($post_type == 'ilist') ){

            wp_enqueue_script( 'ilist_img5_fancybox_js', QCOPD_ASSETS_URL1 . "/fancybox/jquery.fancybox.pack.js", array('jquery'));
            wp_enqueue_script( 'ilist-quicksearch-js',   QCOPD_ASSETS_URL1 . '/js/jquery.quicksearch.js', array( 'jquery' ) );

            wp_enqueue_script( 'ilist-fa-field-js',     QCOPD_ASSETS_URL1 . '/js/fa-field.js', array( 'jquery' ) );
            wp_enqueue_script( 'ilist-chart-field-js',  QCOPD_ASSETS_URL1 . '/js/chart-field.js', array( 'jquery' ) );

        	wp_enqueue_script( 'jq-slick.min-js',       QCOPD_ASSETS_URL1 . '/js/slick.min.js', array('jquery'));

            wp_enqueue_style( 'wp-color-picker' ); 
            wp_enqueue_script( 'wp-color-picker');

        }
        
        wp_enqueue_script( 'ilist_img5_fancybox_js',           QCOPD_ASSETS_URL1 . "/fancybox/jquery.fancybox.pack.js", array('jquery'));
        wp_enqueue_script( 'ilist_admin_light',                QCOPD_ASSETS_URL1 . "/js/lightbox-plus-jquery.min.js", array('jquery'));
    	wp_enqueue_script( 'ilist-admin-script-01',            QCOPD_ASSETS_URL1 . '/js/ilist_custom_admin.js', array('jquery') );
    	wp_enqueue_script( 'ilist-admin-script-owl.carousel',  QCOPD_ASSETS_URL1 . '/js/owl.carousel.min.js', array('jquery') );


        $qcld_ajax = array(
                    'admin_url'          => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
                    'qcld_ajax_nonce'    => wp_create_nonce( 'qcld-ilist' ),
                );
        wp_localize_script( 'ilist-admin-script-01', 'qcld_ilist_ajax', $qcld_ajax );
    	
    }
}
add_action( 'admin_enqueue_scripts', 'ilist_admin_style_script' );


?>