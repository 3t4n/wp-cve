<?php

/*

Plugin name: Shortcode
Plugin URI: http://www.maxpagels.com/projects/shortcode
Description: A plugin that adds a bunch of useful shortcodes that you can use in your blog posts and pages.
Version: 0.8.1
Author: Max Pagels
Author URI: http://www.maxpagels.com

Copyright 2009 Max Pagels (email : max.pagels1@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

function post_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(id)
   FROM $wpdb->posts
   WHERE post_type = 'post'
   AND post_status = 'publish'");
}

function post_count_br() {
  $res = post_count();
  return number_format($res);
}

function future_post_count() {
  global $wpdb;
  $res = $wpdb->get_var("SELECT count(id)
   FROM $wpdb->posts
   WHERE post_type = 'post'
   AND post_status = 'future'");
  return $res;
}

function draft_post_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(id)
   FROM $wpdb->posts
   WHERE post_type = 'post'
   AND post_status = 'draft'");
}

function name_of_longest_post() {
  global $wpdb;
  return $wpdb->get_var("SELECT DISTINCT post_title FROM $wpdb->posts
   WHERE LENGTH(post_content) in (SELECT MAX(LENGTH(post_content)) 
    FROM $wpdb->posts 
    WHERE post_type = 'post' 
    AND post_status = 'publish')");
}

function length_of_longest_post() {
  global $wpdb;
  return $wpdb->get_var("SELECT MAX(LENGTH(post_content)) 
   FROM $wpdb->posts 
   WHERE post_type = 'post' 
   AND post_status = 'publish'");
}

function name_of_shortest_post() {
  global $wpdb;
  return $wpdb->get_var("SELECT DISTINCT post_title FROM $wpdb->posts
   WHERE LENGTH(post_content) in (SELECT MIN(LENGTH(post_content)) 
    FROM $wpdb->posts 
    WHERE post_type = 'post' 
    AND post_status = 'publish')");
}

function length_of_shortest_post() {
  global $wpdb;
  return $wpdb->get_var("SELECT MIN(LENGTH(post_content)) 
   FROM $wpdb->posts 
   WHERE post_type = 'post' 
   AND post_status = 'publish'");
}

function length_of_all_posts() {
  global $wpdb;
  return $wpdb->get_var("SELECT SUM(LENGTH(post_content)) 
   FROM $wpdb->posts 
   WHERE post_type = 'post' 
   AND post_status = 'publish'");
}

function total_words() {
  global $wpdb;
  return $wpdb->get_var("SELECT SUM(LENGTH(post_content) - LENGTH(REPLACE(post_content, ' ', '')) + 1) 
   FROM $wpdb->posts 
   WHERE post_type = 'post' 
   AND post_status = 'publish'");
}

function total_words_br() {
  $res = total_words();
  return number_format($res);
}

function page_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(id)
   FROM $wpdb->posts
   WHERE post_type = 'page'
   AND post_status = 'publish'");
}

function page_count_br() {
  $res = page_count();
  return number_format($res);
}

function category_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT count(*)
   FROM $wpdb->term_taxonomy
   WHERE taxonomy = 'category'
   AND count > 0");
}

function category_count_br() {
  $res = category_count();
  return number_format($res);
}

function category_per_post_avg() {
  global $wpdb;
  
  $catcount = $wpdb->get_var("SELECT SUM(count)
    FROM $wpdb->term_taxonomy
    WHERE taxonomy = 'category'
    AND count > 0");

  $postcount = $wpdb->get_var("SELECT count(id)
   FROM $wpdb->posts
   WHERE post_type = 'post'
   AND post_status = 'publish'");

  return round(($catcount / $postcount), 2);
}


function tag_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
   FROM $wpdb->term_taxonomy
   WHERE taxonomy = 'post_tag'
   AND count > 0");
}

function tag_count_br() {
  $res = tag_count();
  return number_format($res);
}

function tag_per_post_avg() {
  global $wpdb;
  
  $tagcount = $wpdb->get_var("SELECT SUM(count)
    FROM $wpdb->term_taxonomy
    WHERE taxonomy = 'post_tag'
    AND count > 0");

  $postcount = $wpdb->get_var("SELECT count(id)
   FROM $wpdb->posts
   WHERE post_type = 'post'
   AND post_status = 'publish'");

  return round(($tagcount / $postcount), 2);
}

function comment_count() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
   FROM $wpdb->comments
   WHERE comment_approved = 1");
}

function age_in_days() {
  global $wpdb;
  $date = $wpdb->get_var("SELECT MIN(post_date)
   FROM $wpdb->posts
   WHERE post_status = 'publish'");
  $then = strtotime($date);
  $now = strtotime(gmdate("M d Y H:i:s", time()));
  return round(($now-$then) / (24*60*60));
}

function age_in_months() {
  return (int)(age_in_days() / (365.25 / 12));
}

function age_in_years() {
  return (int)(age_in_days() / 365);
}

function age_in_days_with_comma() {
  $res = number_format(age_in_days(), $thousands_sep=',');
  return $res;
}

function posts_per_day_avg() {
  return round((post_count() / age_in_days()), 2);
}

function characters_per_post_avg() {
  return round(length_of_all_posts() / post_count());
}

function photos_in_gallery() {
  global $wpdb;
  return $wpdb->get_var("SELECT COUNT(*)
   FROM $wpdb->posts
   WHERE post_mime_type like 'image%'");
}

function ngg_pictures_now() {
  global $wpdb;
  return intval( $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->nggpictures") );
}
function ngg_galleries_now() {
  global $wpdb;
  return intval( $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->nggallery") );
}

function ngg_albums_now() {
  global $wpdb;
  return intval( $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->nggalbum") );
}

function wp_archive($atts) {
  $s = shortcode_atts( array('type'            => 'monthly',
   'limit'           => '',
   'format'          => 'html', 
   'before'          => '',
   'after'           => '',
   'show_post_count' => false,
   'echo'            => false,
   'order'           => 'DESC'), $atts );
  return wp_get_archives($s);
}

function wp_categories($atts) {
  if(isset($atts['echo'])) {
    $atts['echo'] = 0;
  }
  $s =  shortcode_atts(array(
    'show_option_all'    => '',
    'orderby'            => 'name',
    'order'              => 'ASC',
    'style'              => 'list',
    'show_count'         => 0,
    'hide_empty'         => 1,
    'use_desc_for_title' => 1,
    'child_of'           => 0,
    'feed'               => '',
    'feed_type'          => '',
    'feed_image'         => '',
    'exclude'            => '',
    'exclude_tree'       => '',
    'include'            => '',
    'hierarchical'       => 1,
    'title_li'           => '',
    'show_option_none'   => __( 'No categories' ),
    'number'             => null,
    'echo'               => false,
    'depth'              => 0,
    'current_category'   => 0,
    'pad_counts'         => 0,
    'taxonomy'           => 'category',
    'walker'             => null
    ), $atts); 
  return "<span class='archive-list'>".wp_list_categories($s)."</span>"; 
}

add_shortcode('postcount', 'post_count');
add_shortcode('postcountbr', 'post_count_br');
add_shortcode('nameoflongestpost', 'name_of_longest_post');
add_shortcode('longestpostlength', 'length_of_longest_post');
add_shortcode('allpostslength', 'length_of_all_posts');
add_shortcode('pagecount', 'page_count');
add_shortcode('pagecountbr', 'page_count_br');
add_shortcode('catcount', 'category_count');
add_shortcode('catcountbr', 'category_count_br');
add_shortcode('catperpostavg', 'category_per_post_avg');
add_shortcode('tagcount', 'tag_count');
add_shortcode('tagcountbr', 'tag_count_br');
add_shortcode('tagperpostavg', 'tag_per_post_avg');
add_shortcode('commentcount', 'comment_count');
add_shortcode('ageindays', 'age_in_days');
add_shortcode('ageinmonths', 'age_in_months');
add_shortcode('ageinyears', 'age_in_years');
add_shortcode('postsperdayavg', 'posts_per_day_avg');
add_shortcode('charsperpostavg', 'characters_per_post_avg');
add_shortcode('futpostcount', 'future_post_count');
add_shortcode('draftpostcount', 'draft_post_count');
add_shortcode('photosingallery', 'photos_in_gallery');
add_shortcode('totalwords', 'total_words');
add_shortcode('totalwordsbr', 'total_words_br');
add_shortcode('ageindayscomma', 'age_in_days_with_comma');
add_shortcode('shortestpostlength', 'length_of_shortest_post');
add_shortcode('nameofshortestpost', 'name_of_shortest_post');
add_shortcode('nggpictures', 'ngg_pictures_now');
add_shortcode('nggalleries', 'ngg_galleries_now');
add_shortcode('nggalbums', 'ngg_albums_now');
add_shortcode('wparchive', 'wp_archive');
add_shortcode('wpcategories', 'wp_categories');
?>