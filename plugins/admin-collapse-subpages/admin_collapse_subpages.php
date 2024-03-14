<?php
/*
	Plugin Name: Admin Collapse Subpages
	Plugin URI: https://bravokeyl.com/admin-collapse-subpages/
	Description: Using this plugin one can easily collapse/expand pages / custom post types with children and grand children.
	Author: Alex Chalupka
	Author URI: https://bravokeyl.com
	Version: 2.4
	Text Domain: admin-collapse-subpages
	Domain Path: /languages
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html

	Admin Collapse Subpages is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.

	Admin Collapse Subpages is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Admin Collapse Subpages. If not, see http://www.gnu.org/licenses/gpl-2.0.html.

*/

if (!class_exists('Admin_Collapse_Subpages')) {

	class Admin_Collapse_Subpages {

	function __construct() {

		function acs_textdomain() {
		    load_plugin_textdomain( 'admin-collapse-subpages', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
		}
		add_action( 'plugins_loaded', 'acs_textdomain' );

		function acs_scripts(){
				 global $pagenow;

					if(isset($_GET['post_type']) || isset($_GET['taxonomy'])) {
						if(isset($_GET['post_type'])) {
							$post_type = $_GET['post_type'];
						 	if(is_post_type_hierarchical($post_type)) {
						 		add_filter( 'admin_body_class', 'acs_admin_body_class' );
						 	}
						}
						if(isset($_GET['taxonomy']) && isset($_GET['taxonomy']) == 'category') {
							add_filter( 'admin_body_class', 'acs_admin_body_class' );
						}
						function acs_admin_body_class( $classes ) {
						    $classes .= ' ' .'acs-hier';
						    return $classes;
						}
				 	}

					if ( is_admin() && ( (isset($_GET['post_type']) && $pagenow =='edit.php') || $pagenow =='edit-tags.php' ) ) {

							//make sure jquery is loaded
							wp_enqueue_script('jquery');

							//main collapse pages script
							wp_enqueue_script('acs-js',plugins_url('js/acs.js', __FILE__ ), array('jquery'), '2.0');

							//Load Styles
							wp_enqueue_style('acs-css', plugins_url('css/style.css', __FILE__ ), false, '2.0', 'screen');

							wp_localize_script( 'acs-js', 'acs_l10n_vars', array(
								'lexpandall'  => __( 'Expand All', 'admin-collapse-subpages' ),
								'lcollapseall' => __( 'Collapse All', 'admin-collapse-subpages' ),
							) );

							}
					}
				 add_action('admin_enqueue_scripts','acs_scripts');
			}

	}

	global $collapsePages;
	$collapsePages = new Admin_Collapse_Subpages();
}
