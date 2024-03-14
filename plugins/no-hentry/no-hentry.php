<?php
namespace YoungMedia;


/**
 * Plugin Name: Hatom/hentry remover (Fix errors in Google Webmaster Tools)
 * Description: Remove .hentry-class with a post_class-filter to get rid of errors in Google Webmaster Tools.
 * Author: Rasmus Kjellberg
 * Author URI: https://www.rasmuskjellberg.se
 * Version: 1.3.1
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) 
	exit; 

// Remove .hentry-class from HTML output
function remove_hentry_from_post_class_filter( $classes ) {
	$classes = str_replace('hentry', 'placeholder-for-hentry', $classes);
	return $classes;
}

/**
 * No hentry javascript
 * Removes hentry tag from Google indexing, but adds it again to
 * prevent display errors in theme using the class for CSS-styling.
*/
function footer_script_that_adds_hentry_again() {
?>
<script type="text/javascript">
jQuery(document).on('ready', function() {
	jQuery('.placeholder-for-hentry').addClass('hentry');
	jQuery('.placeholder-for-hentry').removeClass('placeholder-for-hentry');
});
</script>
<?php
}

function no_hentry_rate_link ( $links ) {
	$mylinks = array(
		'<a href="https://wordpress.org/support/view/plugin-reviews/no-hentry" target="_blank">Please 5 star this plugin if it removed your errors!</a>',
	);
	return array_merge( $links, $mylinks );
}

add_filter( 'post_class', '\YoungMedia\remove_hentry_from_post_class_filter' );
add_filter( 'wp_footer', '\YoungMedia\footer_script_that_adds_hentry_again' );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), '\YoungMedia\no_hentry_rate_link' );
