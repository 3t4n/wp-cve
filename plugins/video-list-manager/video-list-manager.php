<?php 
/*
Plugin Name: Video List Manager
Plugin URI: http://videolistmanager.blogspot.com/
Description: Video List Manager is the video list plugin for WordPress, created by Tung Pham (email: tungpham.bh@gmail.com). It helps your site display videos from YOUTUBE, VIMEO, DAILYMOTION with lightbox effect easily. Especially, all your videos will be fitted on all themes.
Version: 1.7
Author: Tung Pham
Author URI: http://videolistmanager.blogspot.com
License: GPLv2
*/

/*  Copyright © 2012  TUNG PHAM  (email : tungpham.bh@gmail.com)
       
    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
       
    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
       
    You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

    /**
     * Define url
     */
    define("TNT_PLUG_PATH", plugin_dir_path(__FILE__));
    define("TNT_INC_PATH", TNT_PLUG_PATH."/includes");
    define("TNT_CLASS_PATH", TNT_PLUG_PATH."/includes/models");

    define("TNT_PLUG_URL", plugins_url()."/video-list-manager");
    define("TNT_IMG_URL", TNT_PLUG_URL."/images");
    define("TNT_CSS_URL", TNT_PLUG_URL."/css");
    define("TNT_JS_URL", TNT_PLUG_URL."/js");

    /**
     * Class Require
     */
    require_once(TNT_CLASS_PATH . '/video.php');
    require_once(TNT_CLASS_PATH . '/videocat.php');
    require_once(TNT_CLASS_PATH . '/videotype.php');
    require_once(TNT_CLASS_PATH . '/pagination.php');


    /**
     * Create tables
     */
    require_once(TNT_INC_PATH . '/create-table.php');
    register_activation_hook(__FILE__,'tnt_install_videos_table');
    register_activation_hook(__FILE__,'tnt_install_videos_cat_table');
    register_activation_hook(__FILE__,'tnt_install_videos_type_table');
    register_activation_hook(__FILE__,'tnt_install_data_videos_type_table');
    register_activation_hook(__FILE__,'tnt_install_data_videos_cat_table');

    /**
     * Message
     */
    require_once(TNT_INC_PATH . '/message.php');
	
	/**
	 * Create Menus
	 */
	require_once(TNT_INC_PATH . '/menus.php');
    require_once(TNT_INC_PATH . '/menus-view.php');
    require_once(TNT_INC_PATH . '/menus-process.php');

    /**
     * Shortcode
     */
    require_once(TNT_INC_PATH . '/shortcode.php');

    /**
     * Template
     */
    require_once(TNT_INC_PATH . '/template.php');

    /**
     * Options
     */
    require_once(TNT_INC_PATH . '/options.php');    
    register_activation_hook(__FILE__,'tnt_videos_create_options');
    register_activation_hook(__FILE__,'tnt_update_databaseoption_videolistmanager');
?>