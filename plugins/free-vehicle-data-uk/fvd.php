<?php

if (!defined('ABSPATH'))exit;
    /*
    Plugin Name: Free Vehicle Data UK
    Plugin URI:  https://www.rapidcarcheck.co.uk/
    Description: The Free Vehicle UK API Plugin lets you show UK vehicle data on your website via the Rapid Car Check API.
    Version:     1.39
    Author:      Rapid Car Check
    Author URI:  https://www.rapidcarcheck.co.uk/about/
    License:     GPL2

    Free Vehicle Data UK: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.

    Free Vehicle Data UK WordPress Plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Free Vehicle Data UK WordPress Plugin. If not, see https://www.gnu.org/licenses/gpl.txt.
    Text Domain: fvd
    */
    if ( !defined('ABSPATH') ) {
        die("-1");   
    }
    define('FVD_BASE_URL','https://rapidcarcheck.co.uk');
    define('FVD_FILE', __FILE__);
    define('FVD_URL', plugin_dir_url(__FILE__ ));
    define('FVD_VERSION', '1.39');
    
    
    add_action( 'init', 'fvd_language_load' );
    function fvd_language_load()
    {
        load_plugin_textdomain( 'fvd', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
    }
    if (!class_exists('FreeVehicleData'))
    {
        include_once 'classes/FreeVehicleData.php';
        include_once 'classes/Assets.php';
        include_once 'classes/Admin.php';
        include_once 'classes/Shortcodes.php';
    }
    if (!function_exists('FreeVehicleData'))
    {
        function FreeVehicleData()
        {
            return \FreeVehicleData\FreeVehicleData::Instance();
        }
    }
    $GLOBALS['FreeVehicleData'] = FreeVehicleData();
    register_activation_hook(__FILE__, ['\FreeVehicleData\FreeVehicleData', 'Activate']);
   

	
	
