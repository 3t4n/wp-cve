<?php

/**
 * WP Post Nav activiation settings.
 *
 * @link:      https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 *
 * @package    wp_post_nav
 * @subpackage wp_post_nav/includes
 */

// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
} 

class wp_post_nav_Activator {

	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since    0.0.1
	 */
	public static function activate() {
      $current_version = '2.0.3';

		  //first see if any of the old options exist in the database (therefore and old version upgrade)
      $defaults = [];
    	$options = [];
    	$options[] = 'wp_post_nav_post_types';
			$options[] = 'wp_post_nav_same_category';
			$options[] = 'wp_post_nav_show_title';
			$options[] = 'wp_post_nav_show_category';
			$options[] = 'wp_post_nav_show_post_excerpt';
			$options[] = 'wp_post_nav_excerpt_length';
			$options[] = 'wp_post_nav_show_featured_image';
			$options[] = 'wp_post_nav_fallback_image';
      $options[] = 'wp_post_nav_out_of_stock';
      $options[] = 'wp_post_nav_switch_nav';
			$options[] = 'wp_post_nav_nav_button_width';
			$options[] = 'wp_post_nav_nav_button_height';
			$options[] = 'wp_post_nav_background_color';
			$options[] = 'wp_post_nav_open_background_color';
			$options[] = 'wp_post_nav_title_color';
			$options[] = 'wp_post_nav_title_size';
			$options[] = 'wp_post_nav_category_color';
			$options[] = 'wp_post_nav_category_size';
			$options[] = 'wp_post_nav_excerpt_color';
			$options[] = 'wp_post_nav_excerpt_size';	

			foreach ($options as $option) {
				//check if the option exists in the database
				$option_value = get_option ($option);
				
				//if any exist, create an array for updating them to the new version
				if ($option_value) {
					$defaults[$option] = $option_value; 
          //delete the old option
          delete_option ($option);
				}
			}

      //previous values were added to the new default option array
      if ($defaults) {
        //were making an update, add in the new version additional elements
        $defaults['wp_post_nav_heading_color'] = '#ffffff';
        $defaults['wp_post_nav_heading_size'] = '20';
        add_option('wp_post_nav_options', $defaults);
        add_option('wp_post_nav_version', $current_version);
        set_transient( 'wp-post-nav', true, 5 );
      }

      //no previous options found, now check if this is simply a reactivation, or we need to create a new set of options
      else {
        $current_ver = get_option ('wp_post_nav_version');
        //the current version is not set (added in 1.0.0) so its a fresh install
        if (!$current_ver) {
          $defaults = [];
          $defaults ['wp_post_nav_post_types'] = array('post' => 'post');
          $defaults ['wp_post_nav_show_title'] = 'yes';
          $defaults ['wp_post_nav_show_category'] = 'yes';
          $defaults ['wp_post_nav_show_post_excerpt'] = 'yes';
          $defaults ['wp_post_nav_excerpt_length'] = '300';
          $defaults ['wp_post_nav_show_featured_image'] = 'yes';
          $defaults ['wp_post_nav_fallback_image'] = plugin_dir_url(dirname(__file__) ) .'public/images/default_fallback.jpg';
          $defaults ['wp_post_nav_nav_button_width'] = '70';
          $defaults ['wp_post_nav_nav_button_height'] = '100';
          $defaults ['wp_post_nav_background_color'] = '#8358b0';
          $defaults ['wp_post_nav_open_background_color'] = '#8358b0';
          $defaults ['wp_post_nav_heading_color'] = '#ffffff';
          $defaults ['wp_post_nav_heading_size'] = '20';
          $defaults ['wp_post_nav_title_color'] = '#ffffff';
          $defaults ['wp_post_nav_title_size'] = '13';
          $defaults ['wp_post_nav_category_color'] = '#ffffff';
          $defaults ['wp_post_nav_category_size'] = '13';
          $defaults ['wp_post_nav_excerpt_color'] = '#ffffff';
          $defaults ['wp_post_nav_excerpt_size'] = '12';

          add_option ('wp_post_nav_options', $defaults);
          add_option ('wp_post_nav_version', $current_version);
        }
        else {
          //this is merely an activation of the plugin so do nothing and return
          return;
        }
      }
	}
}

