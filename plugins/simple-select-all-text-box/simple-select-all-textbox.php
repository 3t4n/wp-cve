<?php
/*
Plugin Name: Simple Select All Text Box
Plugin URI: http://www.grimmdude.com/simple-select-all-text-box/
Description: This plugin makes it easy to create a simple text box which automatically selects everything inside with one click.
Version: 3.2
Author: Garrett Grimm
Author URI: http://www.grimmdude.com
*/
/*  Copyright 2012  Garrett Grimm  (email : garrett@grimmdude.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
*/

if (!class_exists('SimpleSelectTextBox')) {
    class SimpleSelectTextBox
    {
        public static function shortcode( $atts, $content) {
            // If width is defined in the shortcode use that, otherwise fallback on global width
            $rows = is_numeric($atts['rows']) ? ' rows="'.$atts['rows'].'" ' : '';
            $width = is_numeric($atts['width']) ? ' style = "width:'.$atts['width'].'px;" ' : '';
            $content = strtolower($atts['code']) == 'true' ? do_shortcode($content) : do_shortcode(strip_tags($content));

            //this is what the shortcode outputs
            $shortcode_output = <<< EOS
                    <textarea $rows $width class="select_all_textbox {$atts['class']}" onClick="this.focus();this.select();" onChange="this.value = this.getAttribute('data-content');" data-content="$content">$content</textarea><br />
EOS;
            
            return $shortcode_output;
        }

        // Add the settings link to the plugins page
        public static function settings_link($links)
        {
            $links[] = '<a href="https://wordpress.org/plugins/simple-select-all-text-box/" target="_blank">Instructions</a>';
            return $links;
        }
    }

    // Link up with Wordpress
    add_shortcode('textbox', array('SimpleSelectTextBox', 'shortcode'));
    add_filter("plugin_action_links_".plugin_basename(__FILE__), array('SimpleSelectTextBox', 'settings_link' ));
}
