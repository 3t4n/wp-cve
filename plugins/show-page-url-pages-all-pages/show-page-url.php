<?php
/*
Plugin Name: Show Page URL
Plugin URI: https://wordpress.org/plugins/show-page-url-pages-all-pages/
Description: Show the Page URL on Pages > All Pages to help with SEO Keyword Page Mapping
Version: 1.0.0
Author: Smarter Websites
Author URI: https://www.smarterwebsites.com.au
Text Domain: smarter-websites
Domain Path: /languages
*/

//GETTING THE URL DISPLAYED IN THE PAGES LIST

add_filter('manage_page_posts_columns', 'spurl_column', 10);
add_action('manage_page_posts_custom_column', 'add_spurl_column', 10, 2);


function spurl_column($defaults) {
  $defaults['url'] = 'Show Page URL';
  return $defaults;
}

function add_spurl_column($column_name, $post_id) {
  if ($column_name == 'url') {
    echo get_permalink( $post_id );
  }
} 

?>