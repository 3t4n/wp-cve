<?php
/*
Plugin Name: TagCloudShortCode
Plugin URI: 
Description: This plugin provides a [tagcloud] shortcode to let you embed a tag cloud on your site without having to fuss around with page templates.
Version: 0.1
Author: D'Arcy Norman
Author URI: http://www.darcynorman.net
*/
 
/*
== Installation ==
 
1. Download the TagCloudShortCode.zip file to a directory of your choice(preferably the wp-content/plugins folder)
2. Unzip the TagCloudShortCode.zip file into the wordpress plugins directory: 'wp-content/plugins/'
3. Activate the plugin through the 'Plugins' menu in WordPress
*/
 
/*
/--------------------------------------------------------------------\
|                                                                    |
| License: GPL                                                       |
|                                                                    |
| TagCloudShortcode - brief description                           |
| Copyright (C) 2009, D'Arcy Norman & The University of Calgary      |
| All rights reserved.                                               |
|                                                                    |
| This program is free software; you can redistribute it and/or      |
| modify it under the terms of the GNU General Public License        |
| as published by the Free Software Foundation; either version 2     |
| of the License, or (at your option) any later version.             |
|                                                                    |
| This program is distributed in the hope that it will be useful,    |
| but WITHOUT ANY WARRANTY; without even the implied warranty of     |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      |
| GNU General Public License for more details.                       |
|                                                                    |
| You should have received a copy of the GNU General Public License  |
| along with this program; if not, write to the                      |
| Free Software Foundation, Inc.                                     |
| 51 Franklin Street, Fifth Floor                                    |
| Boston, MA  02110-1301, USA                                        |   
|                                                                    |
\--------------------------------------------------------------------/
*/

/**
 * Creation of the TagCloudShortcode
 * This class should host all the functionality that the plugin requires.
 */
/*
 * first get the options necessary to properly display the plugin
 */



if ( !class_exists( "TagCloudShortcode" ) ) {
    
    class TagCloudShortcode {

        /**
         * Shortcode Function
         */
         function shortcode($atts)
         {

      		$out = "";
			$out .='<style type="text/css">div#tagcloud { margin-bottom: 50px; width: 90%; margin-left: auto; margin-right: auto; text-align: center; }</style>';

			$out .= '<div id="tagcloud">';
			
			// do something intelligent to pull attributes to set up the parameters properly, with defaults. (not working yet. deal with it.)
			$listparams = 'number=100&echo=0';
			
			$out .= wp_tag_cloud($listparams);
			
			$out .='</div>';
            
            return $out;
         }
    } // End Class TagCloudShortcode
} 


/**
 * Initialize the admin panel function 
 */

if (class_exists("TagCloudShortcode")) {

    $TagCloudShortcodeInstance = new TagCloudShortcode();
}


/**
  * Set Actions, Shortcodes and Filters
  */
// Shortcode events
if (isset($TagCloudShortcodeInstance)) {
    add_shortcode('tagcloud',array(&$TagCloudShortcodeInstance, 'shortcode'));
}
?>
