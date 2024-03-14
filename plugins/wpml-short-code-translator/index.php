<?php
/**
 * @Package : WPML Shortcode Translator
 * @version: 1.0
 */

/*
Plugin Name: WPML Shortcode Translator
PluginURI: http://www.CloverValleyApps.com/
Description: WPML Shortcode Translator plugins enable to add short code  for a language.
Author: CloverValleyApps
Author URI: https://wordpress.org/support/profile/clovervalleyapps
License: GPL2 
Note: Thanks to the folks on http://wpml.org/forums/topic/conditional-language-shortcode/ who inspired the plugin’s creation.
*/


/*  2014  CloverValleyApps  (email : contact@clovervalleyapps.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Class Wpml_Shortcode_Translator
 */
class Wpml_Shortcode_Translator
{

    /**
     * Constructor
     */
    public function  __construct()
    {

        // Init Hook
        add_action('init', array($this, 'init'));

    }

    /**
     * Plugin Initialization
     */
    public function init()
    {

        // WMPL shortcode callback
        add_shortcode('wpml_language', array($this, 'translate_shortcode'));

    }

    /**
     * WMPL shortcode callback function
     * @param $attribs
     * @param string $content
     * @return string
     */
    public function translate_shortcode($attribs, $content = '')
    {

        // Return variable initialization
        $return = '';

        // Extract the shortcode atributes
        extract(shortcode_atts(array('language' => ''), $attribs));


        if (isset($language) && !empty($language)) {
            if (trim($language) == ICL_LANGUAGE_CODE) {
                $return = do_shortcode($content);
            }

        }
        return $return;

    }
}

// Instance of Plugin class and defining global object for the plugins
$GLOBALS['wmpl_shortcode_translator'] = new Wpml_Shortcode_Translator();
