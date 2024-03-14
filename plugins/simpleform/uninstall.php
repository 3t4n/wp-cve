<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @since      1.0
 */

// Prevent direct access. Exit if file is not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$settings = get_option('sform_settings');
$table_post = $wpdb->prefix . 'posts';

// Confirm user has decided to remove all data, otherwise stop.
if ( isset( $settings['deletion_data'] ) && esc_attr($settings['deletion_data']) == 'false' ) {
	return;
}

if ( !is_multisite() )  {
// Search forms and remove them from content of any page or post
$pages = array();
$form_pages = $wpdb->get_col( "SELECT form_pages FROM {$wpdb->prefix}sform_shortcodes" );
foreach( $form_pages as $form_shortcode_pages ) {
  $form_pages_ids = ! empty($form_shortcode_pages) ? explode(',',$form_shortcode_pages) : array();
  foreach( $form_pages_ids as $shortcode_page_id ) {
    $pages[] = $shortcode_page_id;
   }
}

$ids = array_unique(array_map('absint', $pages));
$ids_count = count($ids);
$placeholders_array = array_fill(0, $ids_count, '%d');
$placeholders = implode(',', $placeholders_array);
$posts = ! empty($ids) ? $wpdb->get_results( $wpdb->prepare("SELECT ID,post_content FROM {$wpdb->posts} WHERE ID IN($placeholders)", $ids), 'ARRAY_A' ) : '';
if ( $posts ) {
  foreach ( $posts as $post ) { 
    $content = $post['post_content'];
    $post_id = $post['ID'];	
    if ( has_blocks($content) ) {
      $plugin_block = '/<!-- wp:simpleform(.*)\/-->/';
      preg_match_all($plugin_block, $content, $matches_block);     
      if ( $matches_block ) {
        foreach ( $matches_block[0] as $block ) {
          $content = str_replace($block, '', $content);
        }
      }
      $shortcode_block = '/<!-- wp:shortcode -->([^>]*)<!-- \/wp:shortcode -->/';
      preg_match_all($shortcode_block, $content, $matches_shortcode);
      if ( $matches_shortcode ) {
        foreach ( $matches_shortcode[0] as $shortcode_block ) {
		  if ( strpos($shortcode_block,'[simpleform') !== false ) { 
            $content = str_replace($shortcode_block, '', $content);
          }
        }
      }
    }
    // Remove any shortcode not included in a block
    $pattern = '/\[simpleform(.*?)\]/';
    preg_match_all($pattern, $content, $matches_simpleform);     
    if ( $matches_simpleform ) {
      foreach ( $matches_simpleform[0] as $shortcode ) {
        $content = str_replace($shortcode, '', $content);
      }
    }
    $wpdb->update( $table_post, array( 'post_content' => $content ), array( 'ID' => $post_id ) );
  }
}
// Delete pre-built pages
$form_page_ID = ! empty( $settings['form_pageid'] ) ? esc_attr($settings['form_pageid']) : '';  
$confirmation_page_ID = ! empty( $settings['confirmation_pageid'] ) ? esc_attr($settings['confirmation_pageid']) : '';	  
if ( ! empty($form_page_ID) && get_post_status($form_page_ID) ) { wp_delete_post( $form_page_ID, true); }
if ( ! empty($confirmation_page_ID) && get_post_status($confirmation_page_ID) ) { wp_delete_post( $confirmation_page_ID, true); }
// Delete block widgets
$sidebars_widgets = get_option("sidebars_widgets") != false ? get_option("sidebars_widgets") : array();
$widget_block = get_option("widget_block") != false ? get_option("widget_block") : '';
$widget_simpleform = get_option('widget_sform_widget');
if ( $widget_block ) {
  $pattern_block = '/<!-- wp:simpleform(.*)\/-->/';
  $pattern_shortcode = '/\[simpleform(.*?)\]/';
  foreach ( $widget_block as $widget_key => $widget_value ) {
    if ( isset($widget_value['content']) && ( preg_match($pattern_block,$widget_value['content']) || preg_match($pattern_shortcode,$widget_value['content']) ) ) { 
      foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	    if ( is_array( $widgets ) ) {
  	      foreach ( $widgets as $key => $value ) {
	  	    if ( strpos($value, 'block-'.$widget_key ) !== false ) {
              unset($sidebars_widgets[$sidebar][$key]);
              update_option('sidebars_widgets', $sidebars_widgets);
            }
          }
        }  
      }
      unset($widget_block[$widget_key]);
      update_option('widget_block', $widget_block);
    }
  }
}
// Delete options
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'sform\_%'" );
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'sform\-%'" );
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%\_sform\_%'" );
// Remove any transients we've left behind.
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE ('%\_transient\_sform\_%')" );
// Drop shortcodes table.
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'sform_shortcodes' );
// Drop submissions table.
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'sform_submissions' );
} 
else {
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
    $original_blog_id = get_current_blog_id();
    foreach ( $blog_ids as $blog_id ) {
      switch_to_blog( $blog_id );
      // Search forms and remove them from content of any page or post
      $pages = array();
      $form_pages = $wpdb->get_col( "SELECT form_pages FROM {$wpdb->prefix}sform_shortcodes" );
      foreach( $form_pages as $form_shortcode_pages ) {
        $form_pages_ids = ! empty($form_shortcode_pages) ? explode(',',$form_shortcode_pages) : array();
        foreach( $form_pages_ids as $shortcode_page_id ) {
          $pages[] = $shortcode_page_id;
        }
      }
      $ids = array_unique(array_map('absint', $pages));
      $ids_count = count($ids);
      $placeholders_array = array_fill(0, $ids_count, '%d');
      $placeholders = implode(',', $placeholders_array);
      $posts = ! empty($ids) ? $wpdb->get_results( $wpdb->prepare("SELECT ID,post_content FROM {$wpdb->posts} WHERE ID IN($placeholders)", $ids), 'ARRAY_A' ) : '';    
      if ( $posts ) {
        foreach ( $posts as $post ) { 
          $content = $post['post_content'];
          $post_id = $post['ID'];	
          if ( has_blocks($content) ) {
            $plugin_block = '/<!-- wp:simpleform(.*)\/-->/';
            preg_match_all($plugin_block, $content, $matches_block);     
            if ( $matches_block ) {
              foreach ( $matches_block[0] as $block ) {
                $content = str_replace($block, '', $content);
              }
            }
            $shortcode_block = '/<!-- wp:shortcode -->([^>]*)<!-- \/wp:shortcode -->/';
            preg_match_all($shortcode_block, $content, $matches_shortcode);
            if ( $matches_shortcode ) {
              foreach ( $matches_shortcode[0] as $shortcode_block ) {
		        if ( strpos($shortcode_block,'[simpleform') !== false ) { 
                  $content = str_replace($shortcode_block, '', $content);
                }
              }
            }
          }
          // Remove any shortcode not included in a block
          $pattern = '/\[simpleform(.*?)\]/';
          preg_match_all($pattern, $content, $matches_simpleform);     
          if ( $matches_simpleform ) {
            foreach ( $matches_simpleform[0] as $shortcode ) {
              $content = str_replace($shortcode, '', $content);
            }
          }
          $wpdb->update( $table_post, array( 'post_content' => $content ), array( 'ID' => $post_id ) );
        }
      }
      // Delete pre-built pages
      $form_page_ID = ! empty( $settings['form_pageid'] ) ? esc_attr($settings['form_pageid']) : '';  
      $confirmation_page_ID = ! empty( $settings['confirmation_pageid'] ) ? esc_attr($settings['confirmation_pageid']) : '';	  
      if ( ! empty($form_page_ID) && get_post_status($form_page_ID) ) { wp_delete_post( $form_page_ID, true); }
      if ( ! empty($confirmation_page_ID) && get_post_status($confirmation_page_ID) ) { wp_delete_post( $confirmation_page_ID, true); }
      // Delete block widgets
      $sidebars_widgets = get_option("sidebars_widgets") != false ? get_option("sidebars_widgets") : array();
      $widget_block = get_option("widget_block") != false ? get_option("widget_block") : '';
      $widget_simpleform = get_option('widget_sform_widget');
      if ( $widget_block ) {
        $pattern_block = '/<!-- wp:simpleform(.*)\/-->/';
        $pattern_shortcode = '/\[simpleform(.*?)\]/';
        foreach ( $widget_block as $widget_key => $widget_value ) {
          if ( isset($widget_value['content']) && ( preg_match($pattern_block,$widget_value['content']) || preg_match($pattern_shortcode,$widget_value['content']) ) ) { 
            foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	          if ( is_array( $widgets ) ) {
  	            foreach ( $widgets as $key => $value ) {
	  	          if ( strpos($value, 'block-'.$widget_key ) !== false ) {
                    unset($sidebars_widgets[$sidebar][$key]);
                    update_option('sidebars_widgets', $sidebars_widgets);
                  }
                }
              }  
            }
            unset($widget_block[$widget_key]);
            update_option('widget_block', $widget_block);
          }
        }
      }
      // Delete options
      $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'sform\_%'" );
      $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'sform\-%'" );
      $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%\_sform\_%'" );
      // Remove any transients we've left behind.
      $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE ('%\_transient\_sform\_%')" );
      // Drop shortcodes table.
      $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'sform_shortcodes' );
      // Drop submissions table.
      $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'sform_submissions' );
    }
    switch_to_blog( $original_blog_id );
}