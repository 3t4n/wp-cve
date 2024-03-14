<?php
/*
Plugin Name: Email JavaScript Cloaker
Plugin URI: http://cgarvey.ie/
Description: A simple plugin to use JavaScript to cloak email addresses
Version: 1.03
Author: Cathal Garvey
Author URI: http://cgarvey.ie/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Copyright 2013-2014 Cathal Garvey (http://cgarvey.ie/)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Email JavaScript Cloaker
 * @copyright 2013-2014 Cathal Garvey
*/

// Plugin priority (allow it to be overridden externally)
if( !defined( "EMAIL_JS_CLOAK_PRIORITY" ) ) define( "EMAIL_JS_CLOAK_PRIORITY", 9000 );

// Register plugin to filter common content
foreach( array( "the_content", "the_excerpt", "widget_text", "comment_text", "comment_excerpt") as $filter ) {
	add_filter( $filter, "email_js_cloak", EMAIL_JS_CLOAK_PRIORITY );
}

// Search content for email address shortcodes, and replace with links/cloaked addresses.
function email_js_cloak( $string ) {
	// Replace / reformat email addresses (to reduce risk of harvesting) (for [email x@y.z] shortcut)
	$regex = "/\[ *email +([^\]]*)\]/";
	$ret = preg_replace_callback( $regex, 'email_js_cloak_regex_callback', $string );

	// Add text to explain email address format for no-JavaScript clients (if [emailnojs] shortcode is present)
	$regex = "/\[ *emailnojs *\]/";
	$sNoJSText = "<noscript><p><strong>A note on email addresses:</strong><br />As you have JavaScript disabled, we protect email addresses on this page to reduce the amount of spam. Before using the email address, you'll have to replace &quot;-at-&quot; with the &quot;@&quot; symbol, and &quot;-dot-&quot; with a &quot;.&quot; (a period/full-stop symbol). Also, remove any spaces in the address.</p></noscript>";
	$ret = preg_replace( $regex, $sNoJSText, $ret );

	return $ret;
}

// Function called for each regex match ([email] shortcode),
// reformats the email address to be 'user -at- something -dot- com'.
// JavaScript then converts that back to a real address on document load.
function email_js_cloak_regex_callback( $matches ) {
	$email_cloak = "";

	if( sizeof( $matches ) == 2 ) {
		$email = $matches[1];
		$email_cloak = $email;
		$email_cloak = preg_replace( "/@/", " -at- ", $email_cloak );
		$email_cloak = preg_replace( "/\./", " -dot- ", $email_cloak );
	}

	if( $email_cloak != "" ) return "<span class=\"spEmailJSCloak\">" . $email_cloak . "</span>";
	else return "n/a";
}

function email_js_cloak_init() {
	if( !is_admin()) {
		// load a JS file from my theme: js/theme.js
		wp_enqueue_script( "email-js-cloak", plugins_url( "js/email-js-cloak.js", __FILE__ ), array( "jquery" ), "1.0", true );
	}
}
add_action( "init", "email_js_cloak_init" );

