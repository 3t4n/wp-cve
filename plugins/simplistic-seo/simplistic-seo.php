<?php
/*
* Plugin Name: Simplistic SEO
* Description: Everything you need for basic SEO in one simple plugin.
* Version: 2.3.0
* Author: Kevin Walker, Roman Peterhans
* Author URI: https://clus.ch
* Text Domain: simplistic-seo
* Domain Path: /lang
* License: GPL2
*/

// Include Settings Page
include plugin_dir_path( __FILE__ ) . "includes/settings.php";
include plugin_dir_path( __FILE__ ) . "includes/metabox.php";
include plugin_dir_path( __FILE__ ) . "includes/twittercards.php";
include plugin_dir_path( __FILE__ ) . "includes/sitemap.php";
include plugin_dir_path( __FILE__ ) . "includes/handling_post.php";

// LOAD LANGUAGE
//-----------------------------------------------------------------------

add_action('plugins_loaded', 'sseo_plugin_init');

function sseo_plugin_init() {
	load_plugin_textdomain( 'simplistic-seo', false, dirname(plugin_basename(__FILE__)).'/lang/' );
}


// AJAX ACTIONS
//-----------------------------------------------------------------------

add_action('wp_ajax_generate_title', 'sseo_ajax_generate_title');

function sseo_ajax_generate_title() {
	$titlestring = sanitize_text_field($_POST['string']);
	$titlepageid = sanitize_key($_POST['pageid']);
	echo sseo_generate_title($titlestring, $titlepageid);
	exit();
}


// GENERATE TITLE & DESCRIPTION
//-----------------------------------------------------------------------


function sseo_generate_title($title, $pageid = NULL, $termid = NULL) {
 
  if ($pageid){
    $pagetitle = get_the_title($pageid);
  }
  if ($termid){
    $pagetitle =  get_term( $termid )->name;
  }
	$variables = array(
		'sitetitle' => get_bloginfo('title'),
		'sitedesc' => get_bloginfo('description'),
		'pagetitle' => $pagetitle ?? ''
	);

	foreach($variables as $key => $value){
		$title = str_replace('{'.$key.'}', $value, $title);
	}

	return $title;
}

function sseo_generate_metadescription($postid) {

	$content = get_post_field('post_content', $postid);

	// Strip headings h1-h6
	$content = preg_replace('/<h[1-6][^>]*>([\s\S]*?)<\/h[1-6][^>]*>/', '', $content);
	// Strip line breaks
	$content = preg_replace('/\r|\n/', '', $content);
	// Strip all remaining tags
	$content = wp_strip_all_tags($content);
	// Check if there is a description...
	if(empty($content)){
		return '';
	} else {
		// Limit to 152 characters
		$content = substr($content, 0, 152);
		// Add "..." to the end of the string
		$content .= '...';

		return $content;
	}
}


// ADD METATAGS TO THE HEAD
//-----------------------------------------------------------------------

function sseo_title() {

  $val = isSeoActiveOnPostType();

  if ($val->type === "post" && $val->id !== -1) {
    // Get title from post meta
    $sseo_title_string = get_post_meta($val->id, '_sseo_title', true);
    
    // If empty, get default title pattern
    if(empty($sseo_title_string)) {
      $sseo_title_string = esc_attr(get_option('sseo_title_pattern', '{pagetitle} – {sitetitle}'));
    }
    $sseo_title = sseo_generate_title($sseo_title_string,$val->id);
    return $sseo_title;
  }
  
  if ($val->type === "tax" && $val->id !== -1) {
    // Get title from post meta
    $sseo_title_string = ___get_term_meta_text( $val->id, 'sseo_title' );
   
    // If empty, get default title pattern
    if(empty($sseo_title_string)) {
      $sseo_title_string = esc_attr(get_option('sseo_title_pattern', '{pagetitle} – {sitetitle}'));
    }
 
    $sseo_title = sseo_generate_title($sseo_title_string,NULL,$val->id );

    return $sseo_title;
  }

}

add_filter('pre_get_document_title', 'sseo_title', 10, 1);


function sseo_metadescription() {

  $val = isSeoActiveOnPostType();
   
    if ($val->type === "post" && $val->id !== -1) {
     
      // Get description from post meta
      $sseo_description = get_post_meta($val->id, '_sseo_metadescription', true);
      // If empty, get default meta description
      if(empty($sseo_description)) {
        $sseo_description = sseo_generate_metadescription($val->id);
      }
      if(!empty($sseo_description)){
        echo '<meta name="description" content="'.esc_attr($sseo_description).'"/>'."\n";
        if(esc_attr(get_option('sseo_activate_twittercard'))){
          echo '<meta name="twitter:description" content="'.esc_attr($sseo_description).'"/>'."\n";
        }
      }
    }
    if ($val->type === "tax" && $val->id !== -1) {
      // Get title from post meta
      $sseo_description = ___get_term_meta_text( $val->id, 'sseo_metadescription' );
      
      // If empty, get default title pattern
      // If empty, get default meta description
      if(empty($sseo_description)) {
        //$sseo_description = sseo_generate_metadescription($id);
      }
      if(!empty($sseo_description)){
        echo '<meta name="description" content="'.esc_attr($sseo_description).'"/>'."\n";
        if(esc_attr(get_option('sseo_activate_twittercard'))){
          echo '<meta name="twitter:description" content="'.esc_attr($sseo_description).'"/>'."\n";
        }
      }
    }
	
}

add_filter( 'wp_head', 'sseo_metadescription', 1 );


// ADD CSS TO THE ADMIN
//-----------------------------------------------------------------------

function sseo_adminassets() {
	// CSS
	wp_register_style( 'sseo_admin_css', plugin_dir_url( __FILE__ ) . 'dist/styles.min.css', false, '1' );
	wp_enqueue_style( 'sseo_admin_css' );
	// JS
	wp_register_script( 'sseo_admin_js', plugin_dir_url( __FILE__ ) . 'dist/functions.min.js', false, '1' );
	wp_enqueue_script( 'sseo_admin_js' );
}

add_action( 'admin_enqueue_scripts', 'sseo_adminassets' );

?>