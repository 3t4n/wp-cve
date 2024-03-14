<?php
/*
Plugin Name: Point Maker
Plugin URI: https://dev.back2nature.jp/en/point-maker/
Description: Make a frame that can be easily used for the main points.
Version: 0.1.4
Author: YAHMAN
Author URI: https://back2nature.jp/
License: GNU General Public License v3 or later
Text Domain: point-maker
Domain Path: /languages/
*/

/*
    Point Maker
    Copyright (C) 2020 YAHMAN

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

    defined( 'ABSPATH' ) || exit;

    $data = get_file_data( __FILE__, array( 'Version' ) );

    define( 'POINT_MAKER_VERSION', $data[0] );
    define( 'POINT_MAKER_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'POINT_MAKER_URI', trailingslashit( esc_url( plugin_dir_url( __FILE__ ) ) ) );
    define( 'POINT_MAKER_PLUGIN_FILE', __FILE__ );


    if(is_admin()){
    	
    	require_once POINT_MAKER_DIR . 'inc/admin/admin.php';
    }else{
    	
    	require_once POINT_MAKER_DIR . 'shortcode.php';
    }


