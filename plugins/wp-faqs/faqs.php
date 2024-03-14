<?php
/**
 * Plugin Name: WP FAQs
 * Plugin URI: https://wordpress.org/plugins/wp-faqs/
 * Description: WP FAQ provides an simple way to add FAQ to your website.
 * Version: 1.1
 * Author: Jayanti Solanki
 * Author URI:  http://www.jayantisolanki.com
 * License: GPL2 license
 */
defined('ABSPATH') or die("No script please!");

function override_template( $page_template )
{

//if (is_page( 'faq-questions-frequentes' )) {
//$page_template = dirname( __FILE__ ) . '/page-faq.php';
//}

}

// CUSTOM POST TYPE WP FAQ
add_action('init', 'my_custom_post_init');

// Setting WP FAQ
function my_custom_post_init()
{
$labels = array(
   'name' => _x('FAQs', 'post type general name'),
   'singular_name' => _x('FAQ', 'post type singular name'),
   'add_new' => _x('Add FAQ', 'FAQ'),
   'add_new_item' => __('Add a FAQ'),
   'edit_item' => __('Edit a FAQ'),
   'new_item' => __('New FAQ'),
   'view_item' => __('See FAQ'),
   'search_items' => __('Search FAQs'),
   'not_found' =>  __('No FAQs found'),
   'not_found_in_trash' => __('No FAQs found in the Trash'),
   'parent_item_colon' => ''
 );
$args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array('title','editor')
  );
register_post_type('wp_faq',$args);
}

// Replace "Enter your title here
function my_custom_enter_title_here($title){
  $screen = get_current_screen();
 
  switch ($screen->post_type) {
    case 'post':
      $title = __('Enter your title here', 'TEXT_DOMAIN');
      break;
    case 'wp_faq':
      $title = __('Type the question here', 'TEXT_DOMAIN');
      break;
   }
   return $title;
}
add_filter('enter_title_here', 'my_custom_enter_title_here');


function faq_shortcode() {
	ob_start();
  ?>
  <style type="text/css">
  	.faqAccordion { clear:both}
	.accTrigger {border-bottom:2px solid #eee; padding:10px 0; cursor:pointer}
  </style>
  <script type="text/javascript">  	
    jQuery(document).ready(function() {
		if( jQuery(".faqAccordion").length){
		   var acDetail = jQuery('.faqAccordion .accordDetail');
		   var acTrigger = jQuery('.faqAccordion .accTrigger');
		   acDetail.hide();		   
		   acTrigger.click(function(){
			  if (jQuery(this).hasClass('active')) {
				   jQuery(this).removeClass('active');
				   jQuery(this).next().slideUp();
			  } else {
				   //acTrigger.removeClass('active');
				   jQuery(this).addClass('active');
				   //acDetail.slideUp();
				   jQuery(this).next().slideDown();
			  }
			  return false;
		   });
		};
    });
</script>
  <?php
  
// magic comes here
  $type = 'wp_faq';
  $args=array(
    'post_type' => $type,
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'caller_get_posts'=> 1,
    'orderby' => 'date',
    'order'   => $trie
    );
  $myQuery = null;
  $myQuery = new WP_Query($args);
  if( $myQuery->have_posts() ) { ?>
  <div class="faqAccordion">
  <?php 
    while ($myQuery->have_posts()) : $myQuery->the_post(); ?>
      <h3 class="accTrigger"><?php the_title(); ?></h3>
      <div class="accordDetail"><?php the_content();?></div>      
      <?php 
    endwhile;
  } else {
	  echo 'No FAQs. Please add some FAQs';
	  }
  ?>
  </div>
  <?php
  wp_reset_query();  // Global Restore post data stomped by the_post ().
  $html = ob_get_contents();
  ob_end_clean();
  return $html;
}
add_shortcode('wp_faq', 'faq_shortcode');
?>