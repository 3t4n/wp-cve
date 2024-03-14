<?php
/******************************************************************************
Plugin Name: Pages are Posts
Plugin URI: http://tarmo.fi/blog/2013/09/pages-are-posts/
Description: Include pages to most views where normally only posts are shown. Adds tags, categories and excerpts to pages.
Author: Tarmo Toikkanen <tarmo@iki.fi>
Author URI: http://tarmo.fi
Version: 1.1
License: GPLv2 or later
******************************************************************************/

/*  Copyright 2014
Pages are Posts is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

Pages are Posts is distributed in the hope that it will be useful
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Add tags and categories to pages. Also add excerpts to pages.
 */
if( ! function_exists('pagesareposts_register_taxonomy') ){
    function pagesareposts_register_taxonomy()
    {
        register_taxonomy_for_object_type('post_tag', 'page');
	register_taxonomy_for_object_type( 'category', 'page' );
        add_post_type_support( 'page', 'excerpt' );
    }
    add_action('admin_init', 'pagesareposts_register_taxonomy');
}

/**
 * Display pages and posts on most important views.
 * Follows best practices in avoiding messing up admin views, sub queries and such.
 */
if( ! function_exists('pagesareposts_query_mod') ){
    function pagesareposts_query_mod($query)
    {
    	if ( is_admin()) // || ! $query->is_main_query() )
        	return;
	$types = $query->query_vars['post_type'];
	// Only modify if no specific type has been requested (meaning the default of 'post' would be in effect)
	if ( empty($types) ) {
		if ( $query->is_home() || $query->is_feed() || $query->is_archive() )
			$query->set( 'post_type', array( 'post', 'page') ); 
	}
    }
    add_action('pre_get_posts', 'pagesareposts_query_mod');
}
?>
