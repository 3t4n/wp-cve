<?php
/*
Plugin Name: Category For Pages
Plugin URI: http://www.radeet.com/wordpress/downloads/plugins/category_for_pages.zip
Description: Adds categories and tags functionality for your pages.
Version: 1.0
Author: A.H.M. Anawar Parvez
Author URI: http://www.radeet.com/
License: GPLv3 or later

Copyright 2017 Radeet Tech

This program is free software:
you can redistribute it and/or modify
it under the terms of the
GNU General Public License as published by
the Free Software Foundation,
either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope
that it will be useful,
but WITHOUT ANY WARRANTY;
without even the implied warranty of
MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE.

See the GNU General Public License
for more details.

You should have received a copy of the
GNU General Public License
along with this program.

If not, see http://www.gnu.org/licenses/
*/

/*
 * Add the 'post_tag' and the 'category' taxonomies, which are the names of
 * the existing taxonomies used for tags and categories the Post type 'page'.
 */
if ( ! function_exists('cat4pages_register_taxonomy') ) {
    function cat4pages_register_taxonomy() {
        // register tag taxonomy for pages
        register_taxonomy_for_object_type('post_tag', 'page');
        // register category taxonomy for pages
        register_taxonomy_for_object_type('category', 'page');
    }
    add_action('init', 'cat4pages_register_taxonomy');
}

/*
 * Display all post_types on the tags archive page and on the categories
 * archive page. 
 */
if( ! function_exists('cat4pages_search') ){
    function cat4pages_search($wp_query) {
        // tag query
        if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
        // category query
        if ($wp_query->get('category_name')) $wp_query->set('post_type', 'any');
    }
    add_action('pre_get_posts', 'cat4pages_search');
}

?>
