<?php
/*
 * class based on plugin:
 * 
 * Simply Show IDs
 * http://sivel.net/wordpress/simply-show-ids/
 * Description: Simply shows the ID of Posts, Pages, Media, Links, Categories, Tags and Users in the admin tables for easy access. Very lightweight.
 * Matt Martz ( http://sivel.net )
 * Ver: 1.3.3

	Copyright (c) 2009-2010 Matt Martz (http://sivel.net)
	Simply Show IDs is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl-2.0.txt

2012-09-01 - changed to class
2013-07-18 - add class to plugin remove from external css
*/

class tpg_show_ids {
	
	//default column width
	private $col_width=30;
	
	// standard constructor for tpg plugins	
	function __construct($col_width=Null) {
		//use value if passed,else accept default 
		if (!$col_width == Null) {
			$this->col_width=$col_width;
		}
		add_action('admin_init', array(&$this,'ssid_add'));
	}
	
	// Prepend the new column to the columns array
	function ssid_column($cols) {
		$cols['ssid'] = 'ID';
		return $cols;
	}
	
	// Echo the ID for the new column
	function ssid_value($column_name, $id) {
		if ($column_name == 'ssid')
			echo $id;
	}
	
	function ssid_return_value($value, $column_name, $id) {
		if ($column_name == 'ssid')
			$value = $id;
		return $value;
	}
	// Output CSS for width of new column
		function ssid_css() {
			$ssid_style='
			<style type="text/css">
				/* Simply Show IDs */
				#ssid { width: '.$this->col_width.'px; text-align:right; } 
				.column-ssid {text-align:right;} 
			</style>';
			echo $ssid_style;
		}
	
	// Actions/Filters for various tables and the css output
	function ssid_add() {
		add_action('admin_head', array(&$this,'ssid_css'));
	
		add_filter('manage_posts_columns', array(&$this,'ssid_column'));
		add_action('manage_posts_custom_column', array(&$this,'ssid_value'), 10, 2);
	
		add_filter('manage_pages_columns', array(&$this,'ssid_column'));
		add_action('manage_pages_custom_column', array(&$this,'ssid_value'), 10, 2);
	
		add_filter('manage_media_columns', array(&$this,'ssid_column'));
		add_action('manage_media_custom_column', array(&$this,'ssid_value'), 10, 2);
	
		add_filter('manage_link-manager_columns', array(&$this,'ssid_column'));
		add_action('manage_link_custom_column', array(&$this,'ssid_value'), 10, 2);
	
		add_action('manage_edit-link-categories_columns', array(&$this,'ssid_column'));
		add_filter('manage_link_categories_custom_column', array(&$this,'ssid_return_value'), 10, 3);
	
		foreach ( get_taxonomies() as $taxonomy ) {
			add_action("manage_edit-${taxonomy}_columns", array(&$this,'ssid_column'));			
			add_filter("manage_${taxonomy}_custom_column", array(&$this,'ssid_return_value'), 10, 3);
		}
	
		add_action('manage_users_columns', array(&$this,'ssid_column'));
		add_filter('manage_users_custom_column', array(&$this,'ssid_return_value'), 10, 3);
	
		add_action('manage_edit-comments_columns', array(&$this,'ssid_column'));
		add_action('manage_comments_custom_column', array(&$this,'ssid_value'), 10, 2);
	}
}
?>
