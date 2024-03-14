<?php
/*
Plugin Name:	Image Size Manager
Plugin URI:		https://plugins.codecide.net/plugin/ism
Description:	Manage WordPress image scaling functionality
Version:		1.0.0
Author:			Codecide Group (codecide.net)
Author URI:		https://plugins.codecide.net
License:		GPL-2.0+
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
Hello: world

This plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with This plugin. If not, see {URI to Plugin License}.
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once('ImageSizeManager.class.php');

$ism = new imageSizeManager();
switch($ism->scaleEnabled()[0]) {
    default:
    case 'disable':
        add_filter( 'big_image_size_threshold', '__return_false' );
    break;     
    case 'custom':
        add_filter( 'big_image_size_threshold', 'ism_set_image_size', 1, 4 );
    break;
    case 'noaction':
    break;
}

function ism_set_image_size() {
    return get_option('ism_customSize');
} 
