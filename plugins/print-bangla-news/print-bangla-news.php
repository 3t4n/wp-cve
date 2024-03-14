<?php 

/**
 * Plugin Name: Print Bangla News
 * Description: Prity News Print Page For Bangla Newspaper
 * Plugin URI: https://rumi.pro/print-bangla-news
 * Author: SynthiaSoft
 * Author URI: https://synthasoft.com/
 * Version: 2.0.1
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: print-bangla-news
 */

include 'includes/settings.php';
include 'class.translateDate.php';
// Enqueue Plugin Css

add_action( 'wp_enqueue_scripts', 'printBanglaNewsCss' );
function printBanglaNewsCss() {
   
    wp_enqueue_style( 'print-css', plugins_url( 'assest/css/print.css', __FILE__ ), [], time(), 'all' );
    wp_register_script( 'html2canvas-js', '//cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'html2canvas-js' );
 

}





 function media_uploader_enqueue() {
        wp_enqueue_media();
    wp_enqueue_style( 'print-css', plugins_url( 'assest/css/admin.css', __FILE__ ), [], time(), 'all' );
    wp_enqueue_style( 'trumbowyg-css', plugins_url( 'trumbowyg/dist/ui/trumbowyg.min.css', __FILE__ ), [], time(), 'all' );

        wp_register_script('media-uploader', plugins_url('media-uploader.js' , __FILE__ ), array('jquery'),time(),true);
        wp_enqueue_script('media-uploader');
        wp_register_script('trumbowyg-js', plugins_url('trumbowyg/dist/trumbowyg.min.js' , __FILE__ ), array('jquery'),time(),true);
        wp_enqueue_script('trumbowyg-js');



    }
    add_action('admin_enqueue_scripts', 'media_uploader_enqueue');


// Add Print Button Before content

function pbnAdd_button( $content ) {

    $img_btn = plugin_dir_url( __FILE__ ).'assest/img/print-news.png';
    $custom_content = '<a href="'.get_the_permalink().'/print"><img style="width:100px;height:auto" src="'.$img_btn.'"></a>';
    $custom_content .= $content;
    if (is_singular( 'post' )) {
        return $custom_content;
    }else{
        return $content;
    }
    
}
add_filter( 'the_content', 'pbnAdd_button' );

// Create Prity Url For print Page


add_action( 'init', function() {
    add_rewrite_endpoint( 'print', EP_PERMALINK );
    flush_rewrite_rules();  
} );

add_action( 'template_redirect', function() {
    global $wp_query;
    if ( ! is_singular( 'post' ) || ! isset( $wp_query->query_vars['print'] ) ) {
        return;
    }

    include plugin_dir_path( __FILE__ ) . 'templates/print.php';
    die;
} );


function BanglaDatetoday(){
    $tcObj = new TranslateDate();

    $output = $tcObj->get_date("F j, Y, g:i a");
    return $output;
}


function BanglaDate($date){
    $tcObj = new TranslateDate();
 return $output = $tcObj->translate($date);

}



// This is free Plugin Do Not edit Or remove Main Developer's Name

add_filter( 'plugin_row_meta', 'Add_DeveloperName', 10, 2 );
 
function Add_DeveloperName( $links, $file ) {    
    if ( plugin_basename( __FILE__ ) == $file ) {
        $row_meta = array(
          'docs'    => '<a href="' . esc_url( 'https://rumi.pro' ) . '" target="_blank" aria-label="' . esc_attr__( 'Developer', 'domain' ) . '" style="color:green; font-weight:700">' . esc_html__( 'Developed By: Rashedul Haque Rumi', 'domain' ) . '</a>'
        );
 
        return array_merge( $links, $row_meta );
    }
    return (array) $links;
}


function PrintNews_active() {

      flush_rewrite_rules();  
}
register_activation_hook( __FILE__, 'PrintNews_active' );
