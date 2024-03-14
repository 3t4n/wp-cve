<?php
/*
Plugin Name: Adjustly Nextpage
Plugin URI: http://www.psdcovers.com/adjustly-nextpage/
Description: Returns the internal support for creating multi-page posts to the Wordpress Visual and HTML toolbar.  This is not a post-to-post navigation feature, this is specifically about breaking a single, very long page/post, into multiple pages. We did not create any new features with this plugin, it simply brings back a valuable feature to the toolbar.
Version: 0.1
Author: PSDCovers
Author URI: http://www.psdcovers.com/adjustly-nextpage/
*/

/*
Adjustly Nextpage (Wordpress Plugin)
Copyright (C) 2012 PSDCovers
Contact us at http://www.psdcovers.com/contact/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

*/

define('AJ_NEXTPAGE_vNum','1.0.0');

// Check for location modifications in wp-config
// Then define accordingly
if ( !defined('WP_CONTENT_URL') ) {
	define('AJ_NP_PLUGPATH',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
	define('AJ_NP_PLUGDIR', ABSPATH.'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
} else {
	define('AJ_NP_PLUGPATH',WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
	define('AJ_NP_PLUGDIR',WP_CONTENT_DIR.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
}

// Create Text Domain For Translations
load_plugin_textdomain('AJ', false, basename(dirname(__FILE__)) . '/languages/');
// visual editor: display the nextpage shortcode button
add_filter('mce_buttons','wysiwyg_editor');
// optional css dashboard modifications
add_action( 'admin_head', 'load_aj_nextpage_style' );
// html editor: display the nextpage shortcode button
add_action('admin_print_footer_scripts',  '_add_my_quicktags');

// function to unhide the EXISTING nextpage button
function wysiwyg_editor($mce_buttons) {
	$pos = array_search('wp_more',$mce_buttons,true);
	if ($pos !== false) {
		$tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
		$tmp_buttons[] = 'wp_page';
		$mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
	}
	return $mce_buttons;
}

function load_aj_nextpage_style() {
	// custom css to spruce up the way the nextpage icon looks since
	// it doesn't really convey "next page"
 	wp_enqueue_style('aj-nextpage-style', AJ_NP_PLUGPATH.'aj-nextpage.css', false, 'all');
}

// Add buttons to html editor, Wordpress 3.3+ method only
if( !function_exists('_add_my_quicktags') ){
    function _add_my_quicktags()
    { ?>
        <script type="text/javascript">
        /* Add custom Quicktag buttons to the editor Wordpress ver. 3.3 and above only
         *
         * Params for this are:
         * - Button HTML ID (required)
         * - Button display, value="" attribute (required)
         * - Opening Tag (required)
         * - Closing Tag (required)
         * - Access key, accesskey="" attribute for the button (optional)
         * - Title, title="" attribute (optional)
         * - Priority/position on bar, 1-9 = first, 11-19 = second, 21-29 = third, etc. (optional)
         */
        QTags.addButton( 'nextpage', 'nextpage', '<!--nextpage-->', '' );
        // QTags.addButton( 'h2', 'H2', '< h2>', '< /h2>' );
        </script>
    <?php }
}
?>