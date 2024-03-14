<?php
/*
Plugin Name: Ultimate Hover Effects
Plugin URI: https://codenpy.com/item/ultimate-hover-effects-pro/
Description: Ultimate Hover Effects is simple modern, yet stylish hover effects for image captions. Eye catching image effects with CSS3 transition for your website to improve your business.
Author: themebon
Author URI: http://codenpy.com/
Text Domain: uhe
Version: 2.9.4
*/

if ( ! defined( 'ABSPATH' ) ) { die; }



// add_filter('widget_text', 'do_shortcode');


require_once ('admin/google-fonts.php');

require_once ('admin/cmb2/init.php');

// Loading Metabox fields
require_once ('admin/cmb2/example-functions.php');


//Loading CSS
function ultimate_image_hover_effects_style() {

    // CSS
    wp_enqueue_style('uhe_grid', plugins_url( '/assets/css/grid.css' , __FILE__ ) );
    wp_enqueue_style('uhe_ultimate_hover', plugins_url( '/assets/css/ultimate-hover.css' , __FILE__ ) );
    wp_enqueue_style('uhe_i_hover', plugins_url( '/assets/css/ihover.css' , __FILE__ ) );
    wp_enqueue_style('uhe_caption', plugins_url( '/assets/css/caption.css' , __FILE__ ) );
    wp_enqueue_style('uhe_custom', plugins_url( '/assets/css/custom.css' , __FILE__ ) );
    wp_enqueue_style('uhe_responsive', plugins_url( '/assets/css/responsive.css' , __FILE__ ) );

    // JS
    wp_enqueue_script('jquery');
    wp_enqueue_script('uhe_ultimate_hover_js', plugins_url( '/assets/js/ultimate-hover.min.js' , __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'ultimate_image_hover_effects_style' );




// Registering Custom Post
add_action( 'init', 'ultimate_hover_effects_custom_post' );
function ultimate_hover_effects_custom_post() {
    register_post_type( 'u_hover_effect',
        array(
            'labels' => array(
                'name' => __( 'Hover Effects' ),
                'singular_name' => __( 'Hover Effect' ),
                'add_new_item' => __( 'Add New Hover Effect' )
            ),
            'public' => true,
            'supports' => array('title'),
            'has_archive' => true,
            'rewrite' => array('slug' => 'u-hover-effects'),
            'menu_icon' => 'dashicons-image-filter',
            'menu_position' => 20,
        )
    );

}



//Calling Shortcodes
require_once ('shortcodes/index.php');

//widget shortcode support
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

// Shortcode Generator
add_filter( 'manage_u_hover_effect_posts_columns', 'uhe_revealid_add_id_column', 10 );
add_action( 'manage_u_hover_effect_posts_custom_column', 'uhe_revealid_id_column_content', 10, 2 );


function uhe_revealid_add_id_column( $columns ) {
   $columns['u_hover_effect'] = 'Hover Shortcode';
   return $columns;
}

function uhe_revealid_id_column_content( $column, $id ) {
  if( 'u_hover_effect' == $column ) {


     $shortcode_render ='[u_hover_effect id="'.$id.'"]';

    echo '<input style="min-width:210px" type=\'text\' onClick=\'this.setSelectionRange(0, this.value.length)\' value=\''.$shortcode_render.'\' />';

  }
}

// Gallery custom messages
add_filter( 'post_updated_messages', 'uhe_updated_messages' );
function uhe_updated_messages( $messages ){

    global $post;

    $post_ID = get_the_ID();

 $messages['u_hover_effect'] = array(
            0 => '',
            1 => sprintf( __('Hover Effects published. Shortcode is: %s'), '[u_hover_effect id="'.$post_ID.'"]' ),
            6 => sprintf( __('Hover Effects published. Shortcode is: %s'), '[u_hover_effect id="'.$post_ID.'"]' ),
        );

    return $messages;

}
