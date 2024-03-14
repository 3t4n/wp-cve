<?php

/**

* Plugin Name: Web Font Display

* Plugin URI: https://wordpress.org/plugins/search/webfont-display/

* Description: This plugin is use for improve [Google PageSpeed Insights](https://developers.google.com/speed/pagespeed/insights/) page speed. You can resolve "Ensure text remain visible during web font load" error for google fonts.

* Version: 1.0

* Author: AIS Technolabs

* Author URI: http://aistechnolabs.com

* License: GPL2 or latest 

WebFont Display is free software: you can redistribute it and/or modify

it under the terms of the GNU General Public License as published by

the Free Software Foundation, either version 2 of the License, or

any later version.

 

WebFont Display is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the

GNU General Public License for more details.

 

You should have received a copy of the GNU General Public License

along with Google Fonts Display. If not, see http://www.gnu.org/licenses/gpl-3.0.html.

*/
// create plugin directory path
define('WFD_PATH', plugin_dir_path( __FILE__ ));
// include function file
require_once(WFD_PATH.'/wfd-function.php');
//add path for css js files
define( 'WFD_URL', plugin_dir_url( __FILE__ ));

