<?php 
/**
 * Create Menus
 * 
 * Create menus for plugins
 *
 * Author: Tung Pham
 */
	add_action( 'admin_menu', 'tnt_create_menu' );
    
    /**
     * Create Menus
     */
	function tnt_create_menu() {
	            
	    //Create topmenu
	    add_menu_page( 'Video List Manager', 'Videos Manager', 'manage_options', 'tnt_video_manage_page', 'tnt_video_manage', TNT_IMG_URL."/menu_icon.png");

	    //Create submenu
	    add_submenu_page('tnt_video_manage_page', 'Add Video', 'Add Video', 'manage_options','tnt_video_add_page', 'tnt_video_add');
	    add_submenu_page('tnt_video_manage_page', 'Edit Video', 'Edit Video', 'manage_options', 'tnt_video_edit_page', 'tnt_video_edit');
	    add_submenu_page('tnt_video_manage_page', 'Delete Video', 'Delete Video', 'manage_options', 'tnt_video_del_page', 'tnt_video_del');

	    // add_submenu_page('tnt_video_setting_page', 'Video Settings', 'Video Settings', 'manage_options','tnt_video_setting_page', 'tnt_video_setting');
	    add_submenu_page('tnt_video_manage_page', 'Categories', 'Categories', 'manage_options', 'tnt_video_cat_manager_page', 'tnt_video_cat_manager');
	    add_submenu_page('tnt_video_manage_page', 'Add Category', 'Add Cateogry', 'manage_options', 'tnt_video_cat_add_page', 'tnt_video_cat_add');
	    add_submenu_page('tnt_video_manage_page', 'Edit Category', 'Edit Category', 'manage_options', 'tnt_video_cat_edit_page', 'tnt_video_cat_edit');
	    add_submenu_page('tnt_video_manage_page', 'Delete Category', 'Delete Category', 'manage_options', 'tnt_video_cat_del_page', 'tnt_video_cat_del');

	    add_submenu_page('tnt_video_manage_page', 'Video Manage Settings', 'Settings', 'manage_options', 'tnt_video_option_page', 'tnt_video_option');
	    add_submenu_page('tnt_video_manage_page', 'About Author', 'About Author', 'manage_options', 'tnt_video_rate_page', 'tnt_video_rate');
	}
 ?>