<?php
/*
Plugin Name: Category Excluder Alpha
Plugin URI: http://wordpress.org/extend/plugins/category-excluder/
Description: This plugin allows you to exclude certain categories from showing on your WordPress. All other categories will be displayed. 
Version: 1.1
Author: Cody Peters
Author URI: http://codysplugin.net63.net/wordpress/?page_id=14
License: GPL2

Copyright 2012  Cody Peters  (email : BukLau13@hotmail.ca)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//----------------------------
register_activation_hook	(	__FILE__,			array('category_excluder', 'activate')	);
register_deactivation_hook	(	__FILE__,			array('category_excluder', 'deactivate')	);
add_action					(	"widgets_init",		array('category_excluder', 'register')	);
//----------------------------
class category_excluder
{
//----------------------------
	function activate()
	{
		if( get_option( 'category_excluder_w_title' ) === FALSE ) {
			update_option( 'category_excluder_w_title', 'Category Excluder' );
		}
		if( get_option( 'category_excluder_w_categories' ) === FALSE ) {
			update_option( 'category_excluder_w_categories', '' );
		}
	}
	
//----------------------------	
	function deactivate()
	{
		delete_option( 'category_excluder_w_title' );
		delete_option( 'category_excluder_w_categories' );
	}
//----------------------------	
	function register()
	{
		wp_register_sidebar_widget( 'category-excluder', 'Category Excluder', array('category_excluder', 'widget'));
		wp_register_widget_control( 'category-excluder', 'Category Excluder', array('category_excluder', 'control'));
	}
//----------------------------
	function control()
	{
		if (isset($_POST['category_excluder_w_title']))			update_option(	'category_excluder_w_title',		attribute_escape($_POST['category_excluder_w_title'])		);
		if (isset($_POST['category_excluder_w_categories']))	update_option(	'category_excluder_w_categories',	attribute_escape($_POST['category_excluder_w_categories'])	);
		?>
		<p><label>
			<strong>Widget Title:</strong><br />
			<input class="widefat" type="text" name="category_excluder_w_title" value="<?php echo get_option( 'category_excluder_w_title' ); ?>" />
		</label></p>
		<p><label>
			<strong>Categories To Exclude:</strong><br />
			<input class="widefat" type="text" name="category_excluder_w_categories" value="<?php echo get_option( 'category_excluder_w_categories' ); ?>"	 />
		</label></p>
		<p>Enter a comma-seperated list of category ID numbers, e.g. <code>3,8,10.</code> (This widget will display all of your categories except these category ID numbers).</p>
		<?php
	}
//----------------------------
	function configureMN()
	{
		$succ = 0;
		echo 'Settings Configured Successfully';
		return $succ;
	}
	
//----------------------------
	function widget( $args )
	{
		echo $args['before_widget'];
		echo $args['before_title'] . get_option( 'category_excluder_w_title' ) . $args['after_title'];
		echo '<ul id="category_excluder_widget">';
			$cat_params = Array(
					'hide_empty'	=>	FALSE,
					'title_li'		=>	''
				);
			if( strlen( trim( get_option( 'category_excluder_w_categories' ) ) ) > 0 ){
				$cat_params['exclude'] = trim( get_option( 'category_excluder_w_categories' ) );
			}
			wp_list_categories( $cat_params );
		echo '</ul>';
		echo $args['after_widget'];
	}
	
}
//--------------!--------------