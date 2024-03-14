<?php
/*
Plugin Name: YAHMAN Add-ons
Plugin URI: https://dev.back2nature.jp/en/yahman-add-ons/
Description: YAHMAN Add-ons has Multiple functions.Page views,Google Adsense,Analytics,Social,Profile,Table of contents,Related Posts,sitemap,SEO,JSON-LD structured data,Open Graph protocol(OGP),Blog card,Twitter timeline,Facebook timeline,Carousel Slider etc...
Version: 0.9.28
Author: YAHMAN
Author URI: https://back2nature.jp/
License: GNU General Public License v3 or later
Text Domain: yahman-add-ons
Domain Path: /languages/
*/

/*
    YAHMAN Add-ons
    Copyright (C) 2018 YAHMAN

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

    define( 'YAHMAN_ADDONS_VERSION', $data[0] );
    define( 'YAHMAN_ADDONS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'YAHMAN_ADDONS_URI', trailingslashit( esc_url( plugin_dir_url( __FILE__ ) ) ) );
    define( 'YAHMAN_ADDONS_PLUGIN_FILE', __FILE__ );


    
    

    
    require_once YAHMAN_ADDONS_DIR . 'inc/action_plugins_loaded.php';

