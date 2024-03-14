<?php
/*
Plugin Name: Enable Classic Editor
Plugin URI: https://www.ayonm.com
Description: A simple & lightweight plugin to enable the classic editor on WordPress.
Version: 2.5
Author: AYONM
Author URI: https://www.ayonm.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: enable-classic-editor


Enable Classic Editor plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.
 
Enable Classic Editor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License along
with Enable Classic Editor or WordPress. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

Copyright (c) 2023 AYONM. All rights reserved.
 */

defined( 'ABSPATH' ) || die( 'No Entry!' ); 

add_filter('use_block_editor_for_post','__return_false');

if( !function_exists('classic_widgets')){
	function classic_widgets(){
		remove_theme_support( 'widgets-block-editor' );	
	}
	add_action( 'after_setup_theme', 'classic_widgets' );
}