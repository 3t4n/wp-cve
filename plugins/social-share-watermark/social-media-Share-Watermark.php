<?php
/*
Plugin Name: Social Share Watermark
Plugin URI: https://synthiasoft.com/social_media-Share-Watermark
Description: Use our plugin to add watermarks to the pictures of links shared on social_media like Prothom Alo, Jago News, Dhaka Post..
Version: 2.1.0
Author: SynthiaSoft
Author URI: https://synthiasoft.com/
Text Domain: social-share-watermark
WC tested up to: 6.1.0
*/
require_once plugin_dir_path( __FILE__ ) . 'includes/admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/share_image.php';

$setting = get_option('fb_watermark_options');
global $setting; 
function SynthiaSoft_media_uloader_script() {
    	wp_enqueue_media();
    	wp_register_script('social-media-uploader', plugins_url('social-media-uploader.js' , __FILE__ ),array( 'jquery' ),
        '1.0.0',
        true
    );
    	wp_enqueue_script('social-media-uploader');
    }
    add_action('admin_enqueue_scripts', 'SynthiaSoft_media_uloader_script');


// This is free Plugin Don Not edit Or remove Main Developers Name
add_filter( 'plugin_row_meta', 'Synthiasoft_Developer_Rumi', 10, 2 );
 
function Synthiasoft_Developer_Rumi( $links, $file ) {    
    if ( plugin_basename( __FILE__ ) == $file ) {
        $row_meta = array(
          'docs'    => '<a href="' . esc_url( 'https://www.facebook.com/rumi.rp' ) . '" target="_blank" aria-label="' . esc_attr__( 'Developer', 'domain' ) . '" style="color:green;">' . esc_html__( 'Developed By: Rashedul Hague Rumi', 'domain' ) . '</a>'
        );
 
        return array_merge( $links, $row_meta );
    }
    return (array) $links;
}

//Virtual Page Fo Open Graph Image

function Synthiasoft_Image_ver( $qvars ) {
    $qvars[] = 'ogimage';
    return $qvars;
}
add_filter( 'query_vars', 'Synthiasoft_Image_ver' );


add_action( 'template_redirect', function(){
    $ogimage = intval( get_query_var( 'ogimage' ) );
    if ( $ogimage ) {
        SynthiaSoft_MakeWatermark(get_query_var( 'ogimage' ));
        die;
    }
} );

// Default Open Graph Image


function Synthiasoft_InsertOG() {
    global $post;
    global $setting; 
    if ( !is_singular()){
        $default_image= $setting['fb_default']; 
        echo '<meta property="og:image" content="' . esc_attr($default_image) . '"/>';
    } //if it is not a post or a page
       
        
    if(!has_post_thumbnail( $post->ID )) { 
        $default_image= $setting['fb_default'];  
        echo '<meta property="og:image" content="' . esc_attr($default_image) . '"/>';
    }
    else{
        $thumbnail_src = get_home_url().'/wp-content/uploads/social-share-watermark/'.$post->ID.'_social-share-watermark.jpg';

        echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src ) . '"/>';
    }

}

// Yoast SEO Opengraph Image

function SynthiaSoft_yoast_opengraph_image( $url ) {
    global $post;
    $thumbnail_src = get_home_url().'/wp-content/uploads/social-share-watermark/'.$post->ID.'_social-share-watermark.jpg';
    return $url = $thumbnail_src;
}

// Rank Math Opengraph Image
function SynthiaSoft_rankmath_opengraph_image( $attachment_url ) {
    global $post;
    $thumbnail_src = get_home_url().'/wp-content/uploads/social-share-watermark/'.$post->ID.'_social-share-watermark.jpg';
    return $attachment_url = $thumbnail_src;
}



if (isset($setting['enable_overlay'])) {

add_filter( 'wpseo_opengraph_image', 'SynthiaSoft_yoast_opengraph_image' );
add_filter( "rank_math/opengraph/facebook/image", 'SynthiaSoft_rankmath_opengraph_image');
add_action( 'wp_head', 'Synthiasoft_InsertOG', 0 );
add_filter( 'aioseo_facebook_tags', 'SynthiaSoft_aios' );
}


// Alin one seo



function SynthiaSoft_aios( $facebookMeta ) {

   if ( is_singular() ) {
      $thumbnail_src = get_home_url().'/wp-content/uploads/social-share-watermark/'.$post->ID.'_social-share-watermark.jpg';
      $facebookMeta['og:image'] = $thumbnail_src;
      $facebookMeta['og:image:secure_url']  = $thumbnail_src;
   }

   return $facebookMeta;

}

add_action( 'save_post', 'SynthiaSoft_save_post', 10, 3 );

function SynthiaSoft_save_post( $post_ID, $post, $update ) {

if ( ! is_dir( ABSPATH . 'wp-content/uploads/social-share-watermark' ) ) {
    wp_mkdir_p( ABSPATH . 'wp-content/uploads/social-share-watermark' );
}
$response = wp_remote_get( get_home_url().'?ogimage='.$post->ID,
    array(
        'timeout'     => 120,
        'httpversion' => '1.1',
    )
);
  
  
}


add_action( 'wp_head', 'Synthiasoft_Check_image', 10 );
function Synthiasoft_Check_image(){

    // Run code only for Single post page
if ( is_single() && 'post' == get_post_type() ) {
    $id = get_the_ID();

    $response = wp_remote_get( get_home_url().'?ogimage='.$id,
    array(
        'timeout'     => 120,
        'httpversion' => '1.1',
    ));
   
}

}