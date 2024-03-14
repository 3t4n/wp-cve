<?php
/**
Plugin Name: Conditional Widgets
Plugin URI:  http://wordpress.org/extend/plugins/conditional-widgets/
Description: Grants users advanced control over which pages and categories each widget is displayed on
Version:     3.1
Author:      Jason LeMahieu and Kevin Graeme
License:     GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: conditional-widgets
Domain Path: /languages
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* CHANGELOG 
	= 3.1 =
		Added message to readme explaining the future of Conditional widgets after WordPress 5.8
	= 3 =
		Improved string translatability (Props @alexclassroom)
	= 2.2 =
		* New Features: Hide on Desktop / Hide on Mobile.  (Note that using either of these options will immediately hide the widget when the condition is true, and any other options to 'show' will be ignored.
 		* Fixes lots of small bugs related to the display of the widget control form
	= 2.1 =
		* Major update of code formatting to better align with WordPress style and guidelines - props @cFoellmann
		* Support Conditional Widgets toggle JavaScript on the Customize screen
	2.0.5
	    - Further (properly?) addressed strict warnings with walker arguments
	2.0.4
		- Addressed strict warnings with walker arguments
	2.0.3
		- more tweaking to fix warnings
	2.0.2
		- Fixed warning with weird option selection, changed labels from object->taxonomy
	2.0.1
		- Fixed bug introduced in 2.0 surrounding empty term logic
	2.0
		- Added update framework
		- Added support for custom type/tax pairs via filter
		- refactored and split into a few separate files
*/

if ( is_admin() ) {
	require_once( dirname(__FILE__) . '/admin.php');
	require_once( dirname(__FILE__) . '/update.php');
	require_once( dirname(__FILE__) . '/form.php');
	require_once( dirname(__FILE__) . '/includes/class-walker-category-checklist.php');
	require_once( dirname(__FILE__) . '/includes/class-walker-page-checklist.php');
} else {
	require_once( dirname(__FILE__) . '/logic.php');
}

add_filter( 'conditional_widgets_type_tax_pairs', 'conditional_widgets_add_post_categories', 1 );

function conditional_widgets_add_post_categories( $pairs ) {
	$pairs[] = array( 'type' => 'post', 'tax' => 'category', );
	return $pairs;
} 

/**
 * Initializes a fresh widget instance
 */
function conditional_widgets_init_instance( $instance ) {

	//single values
	$keys = array(
		'cw_home_enable_checkbox',
		'cw_select_home_page',
		'cw_pages_enable_checkbox',
		'cw_select_pages',
		'cw_pages_sub_checkbox',
		'cw_404_hide',
		'cw_search_hide',
		'cw_author_archive_hide',
		'cw_date_archive_hide',
		'cw_tag_archive_hide',
		'cw_posts_page_hide',
		'cw_mobile_hide',
		'cw_desktop_hide'
	);

	foreach ( $keys as $key ) {
		if ( ! isset( $instance[ $key ] ) ) {
			$instance[ $key ] = '';
		}
	}
	
	//arrays
	$arraykeys = array( 'cw_selected_pages', 'cw_custom', );

	foreach ( $arraykeys as $arraykey ) {
		if ( ! isset( $instance[ $arraykey ] ) ) {
			$instance[ $arraykey ] = array();
		}
	}

	return $instance;

} // /function conditional_widgets_init_instance()

function conditional_widgets_get_default_custom_subdata() {
	$custom_subdata = array(
		'enable'       => 0,
		'select'       => 0,
		'selected_ids' => array(),
		'all'          => 0,
		'sub'          => 0,
	);
	
	return $custom_subdata;
	
} 
