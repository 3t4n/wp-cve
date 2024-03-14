<?php
/*
Plugin Name: Kento Testimonial Slider
Plugin URI: http://pluginspoint.com/
Description: Slide Your Unlimited Testimonial or Clients Feedback By using Shortcode Anywhere With Clients Thumbnail.
Version: 1.0
Author: KentoThemes
Author URI: http://pluginspoint.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


/*Some Set-up*/
define('KENTO_TESTIMONIAL_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );


/* Adding Latest jQuery from Wordpress */
function kento_testimonial_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'kento_testimonial_latest_jquery');

/* Adding plugin javascript active file */
wp_enqueue_script('kento-testimonial-plugin-main-active', KENTO_TESTIMONIAL_PLUGIN_PATH.'js/jquery.quote_rotator.js', array('jquery'));
/* Adding plugin javascript active file */
wp_enqueue_script('kento-testimonial-plugin-script-active', KENTO_TESTIMONIAL_PLUGIN_PATH.'js/kento-testimonial-active.js', array('jquery'));

/* Adding Plugin custm CSS file */
wp_enqueue_style('kento-testimonial-plugin-style', KENTO_TESTIMONIAL_PLUGIN_PATH.'css/kento-testimonial-plugin-style.css');

add_filter('widget_text', 'do_shortcode');





add_filter('mce_external_plugins', "kentotestimonial_register");
add_filter('mce_buttons', 'kentotestimonial_add_button', 0);
function kentotestimonial_add_button($buttons){
array_push($buttons, "separator", "kentotestimonial_button_plugin");
return $buttons;
}
function kentotestimonial_register($plugin_array){
$url = KENTO_TESTIMONIAL_PLUGIN_PATH."/js/editor_plugin.js";
$plugin_array['kentotestimonial_button_plugin'] = $url;
return $plugin_array;
}






/*Files to Include*/
/* Some setup */
define('KENTO_TESTIMONIAL_NAME', "Testimonials");
define('KENTO_TESTIMONIAL_SINGLE', "Testimonial");
define('KENTO_TESTIMONIAL_TYPE', "kento-testimonial");
define('KENTO_TESTIMONIAL_ADD_NEW_ITEM', "Add New Testimonial");
define('KENTO_TESTIMONIAL_EDIT_ITEM', "Edit Testimonial");
define('KENTO_TESTIMONIAL_NEW_ITEM', "New Testimonial");
define('KENTO_TESTIMONIAL_VIEW_ITEM', "View Testimonial");

/* Register custom post for Testimonial*/
function Kento_Testimonial_Post_Register() {  
    $args = array(  
        'labels' => array (
			'name' => __( KENTO_TESTIMONIAL_NAME ),
			'singular_label' => __(KENTO_TESTIMONIAL_SINGLE),  
			'add_new_item' => __(KENTO_TESTIMONIAL_ADD_NEW_ITEM),
			'edit_item' => __(KENTO_TESTIMONIAL_EDIT_ITEM),
			'new_item' => __(KENTO_TESTIMONIAL_NEW_ITEM),
			'view_item' => __(KENTO_TESTIMONIAL_VIEW_ITEM),
		), 
        'public' => true,  
        'show_ui' => true,  
        'capability_type' => 'post',  
        'hierarchical' => false,  
        'rewrite' => true,  
        'supports' => array('title', 'editor', 'thumbnail'),
		'menu_icon' => KENTO_TESTIMONIAL_PLUGIN_PATH.'/testimonial.png',
       );
    register_post_type(KENTO_TESTIMONIAL_TYPE , $args );  
}
add_action('init', 'Kento_Testimonial_Post_Register');

/* Testimonial Loop */
function KentoTestimonial_list(){
	$KentoTestimonial= '<div class="kento-testimonial"><ul id="kento_quotes">';
	query_posts('post_type=kento-testimonial&posts_per_page=-1');
	if (have_posts()) : while (have_posts()) : the_post(); 
		$author= get_the_title();
		$content= get_the_content();
		$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$KentoTestimonial.='<li><div class="kento-testimonial-author"><img class="kento-testimonial-author-img" width="150px" height="150px" src="'.$url.'" /><p class="kento-testimonial-author-name">'.$author.'</p></div><div class="kento-testimonial-author-comments">'.$content.'</div></li>';
	endwhile; endif; wp_reset_query();
	$KentoTestimonial.= '</ul></div>';
	return $KentoTestimonial;
}


/**add the shortcode for the Testimonial- for use in editor**/
function KentoTestimonial_shortcodes($atts, $content=null){
	$KentoTestimonial= KentoTestimonial_list();
	return $KentoTestimonial;
}
add_shortcode('KentoTestimonial', 'KentoTestimonial_shortcodes');

/**add template tag- for use in themes**/
function Kento_Testimonial(){
	print KentoTestimonial_list();
}
?>