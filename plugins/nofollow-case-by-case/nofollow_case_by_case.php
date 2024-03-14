<?php

/* 
Plugin Name: Nofollow Case by Case
Plugin URI: http://www.fob-marketing.de/marketing-blog-184-wordpress-nofollow-seo-plugin-nofollow-case-by-case.html 
Description: Nofollow Case by Case is a follow (nofollow and dofollow) plugin for WordPress. It allows you to selectively apply nofollow to your comments.
Version: 1.5.6
Author: Oliver Bockelmann (fob marketing, Hamburg)
Author URI: http://www.fob-marketing.de/
Update Server: http://wordpress.org/extend/plugins/nofollow-case-by-case/
License: Compatible with the GPL2
*/

/*
Copyright 2007 - 2013 Oliver Bockelmann (email : fob@fob-marketing.de)

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/* NEW VERSION SINCE 2013-01-15 **************************************************************************************************************************************** */

// Nofollow and other things to be removed
$diverse_nofollow_variants = array(' rel="nofollow"', " rel='nofollow'", ' rel="external"', " rel='external'", ' rel="external nofollow"', " rel='external nofollow'", ' rel="nofollow external"', " rel='nofollow external'", ' target="_blank"', " target='_blank'");


/* START INPUT FILTERS */

function nfcbc_wp_rel_follow( $text ) {
	global $wp_version;
	// This is a pre save filter, so text is already escaped.
	$text = stripslashes($text);
	$text = preg_replace_callback('|<a (.+?)>|i', 'nfcbc_wp_rel_follow_callback', $text);
	if (isset($wp_version) && version_compare($wp_version, '3.5.2', '<=')) {
		$text = esc_sql($text);
	} else {
		// New in WP 3.6
		$text = wp_slash($text);
	}
	return $text; // pre_comment_content
}

function nfcbc_wp_rel_follow_callback( $matches ) {
	global $diverse_nofollow_variants;
	$text = $matches[1];
	$text = str_replace($diverse_nofollow_variants, '', $text);
	// $text is now nofollow free and can be fitted with other stuff
	return "<a $text>"; 
}


/* START OUTPUT FILTERS */

// For use with WordPress 3.4 and newer instances we use $r because accidential links (within links) have already been cleaned up in $r.
function nfcbc_make_clickable_new_wp($r) {
	$r = preg_replace_callback('|<a (.+?)>|i', 'nfcbc_make_clickable_callback', $r ); // Prepare the links
	$r = str_replace( array('//dontfollow</a>', '/dontfollow</a>'), '</a>', $r ); // Remove visable dontfollows from the content
	return $r;
}

// Use $ret to support old versions of WordPress
function nfcbc_make_clickable_old_wp($ret) {
	$ret = preg_replace_callback( '|<a (.+?)>|i', 'nfcbc_make_clickable_callback', $ret ); // Prepare the links
	$ret = str_replace( array('//dontfollow</a>', '/dontfollow</a>'), '</a>', $ret ); // Remove visable dontfollows from the content
	return $ret;
}

// Clean up author links
function nfcbc_clean_up_author_links( $return ) {
	$return = preg_replace_callback( '|<a (.+?)>|i', 'nfcbc_make_clickable_callback', $return ); // Prepare the links
	$return = str_replace( array('//dontfollow</a>', '/dontfollow</a>'), '</a>', $return ); // Remove visable dontfollows from accidential author links
	return $return;
}

// Validate all links from the output
function nfcbc_make_clickable_callback( $matches ) {
	global $diverse_nofollow_variants;
	$nfcbc_link = $matches[0];
    $nfcbc_rel_exclude_internal_links = home_url();

	$nfcbc_link = str_replace($diverse_nofollow_variants, "", $nfcbc_link); // Strip nofollow, external and blanks
	$nfcbc_link = str_replace( array('/dontfollow"', "/dontfollow'"), array('" rel="external nofollow"', "' rel='external nofollow'"), $nfcbc_link ); // Replace modifications

    if ( strpos( $nfcbc_link, 'rel=' ) === false && strpos( $nfcbc_link, $nfcbc_rel_exclude_internal_links ) === false ) {
        // $nfcbc_link = preg_replace( '/(?<=<a\s)/', 'rel="external" ', $nfcbc_link ); // Use external only for external links (if not exists)
		// Use str_replace here because of better performance against preg_replace. New callback of NFCBC 1.5.2 helps. Repair untrimmed anchors for some people by the way:
		$nfcbc_link = str_replace( array( '">', "'>", '" >', "' >" ), array( '" rel="external">', "' rel='external'>", '" rel="external">', "' rel='external'>" ), $nfcbc_link );
	}

    return $nfcbc_link;
}

// Modify pre save function (pre save without nofollow)
remove_filter('pre_comment_content', 'wp_rel_nofollow', 16);
add_filter('pre_comment_content', 'nfcbc_wp_rel_follow', 17); // Filter for the database entries

// Optimize the output

// Filter the output for new versions of WordPress:
global $wp_version;
if (isset($wp_version) && version_compare($wp_version, '3.4', '>=')) {
	add_filter('comment_text','nfcbc_make_clickable_new_wp', 18);
} else { // Filter the output for old versions of WordPress
	add_filter('comment_text','nfcbc_make_clickable_old_wp', 18);
}

add_filter('get_comment_author_link','nfcbc_clean_up_author_links', 19); // Output filter for the normal comment author link (depends on template functions used)
add_filter('get_comment_author_url_link', 'nfcbc_clean_up_author_links', 19); // Output filter for further comment author links (depends on template functions used)


// Use XHTML valid version of target="_blank" plus output cleaning option
function nfcbc_open_external_links_script() {
	// Register external scripts after load (footer option). 
	// Includes new window for external links and /dofollow output cleaning for accidential (author) links that can not be modified with PHP on the fly.
	wp_register_script('nfcbc_external_links_script', plugins_url('/js/nfcbc_scripts.js', __FILE__), array('jquery'), '0.1', true);

	// Include this script on pages with comments (only)
	if ( !is_admin() && !is_archive() && get_comments_number() > 0 ) {
		wp_enqueue_script('nfcbc_external_links_script');
	}
}

add_action('wp_enqueue_scripts', 'nfcbc_open_external_links_script'); // This is an XHTML valid target=_blank option


// Add individual Theme Support
// Hybrid Theme Avatars

// Clean up avatar links
function nfcbc_clean_up_hybrid_avatars( $avatar ) {
	$avatar = preg_replace_callback( '|<a (.+?)>|i', 'nfcbc_make_clickable_callback', $avatar ); // Prepare the links
	return $avatar;
}

if ( get_option('show_avatars') && file_exists(get_template_directory('/library/hybrid.php')) ) {
	add_filter( sanitize_key( get_template() ).'_avatar', 'nfcbc_clean_up_hybrid_avatars');
}

