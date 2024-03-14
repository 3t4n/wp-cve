<?php
/*

The following script is used to automatically provision new WordPress MU users who 
have successfully authenticated with CAS using the wpCAS plugin.

To use this, make the necessary local modifications and put it in the root of your 
WordPress MU install. Then in your wpcas-conf.php file (or anywhere else that it'll 
be callable) defines a wpcas_nowpuser() function like this:

function wpcas_nowpuser( $user_name ){
	wp_redirect('your-provisioning-script.php');
}

Then, whenever a user successfully authenticates with CAS, but doesn't have a 
matching username in your MU site, it will redirect to this script to provision 
the user's account and blog.

*/

/*

Copyright (C) 2008 Casey Bisson

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307	 USA 

*/ 

// setup the WordPress environment
define( "WP_INSTALLING", true );
require( dirname(__FILE__) . '/wp-load.php' );
require( 'wp-blog-header.php' );
require_once( ABSPATH . WPINC . '/registration.php' );
global $domain, $base; // these variables aren't reliable. It's actually better to force them as you'll see below.

// ensure the user is authenticated via CAS
if( !phpCAS::isAuthenticated() || !$username = phpCAS::getUser() ){
	wpCAS::authenticate();
	die( 'requires authentication' );
}

// a function that filters (and, um, shortcircuits) 
// MU's wpmu_signup_blog_notification filter to give users instant notification
// of their new blog info
function psu_signup_blog_notification( $domain, $path, $title, $user, $user_email, $key, $meta ){

	$activated = wpmu_activate_signup( $key );

	if( is_array( $activated) && $activated['blog_id'] && $activated['user_id'] ){ 
		// successfully created blog
		update_blog_option( $activated['blog_id'], 'stylesheet', 'pressrow' );
		update_blog_option( $activated['blog_id'], 'template', 'pressrow' );

		// they're already CAS authenticated, 
		// so set the WP authentication cookie for them
		wp_set_auth_cookie( $activated['user_id'] );
		?>

		<h2>Yay! We made a blog for you</h2>
		<p>Click this link to access your dashboard:</p>
		<ul><li><a href="http://<?php echo $domain . $path; ?>wp-admin/"><?php echo $domain . $path; ?>wp-admin/</a></li></ul>
		<p>Go ahead and bookmark it once you get there, we hope you come back.</p><?php
	}else{ 
		// error will robinson ?>
		<h2>hrrm...</h2>
		<p>There seems to have been an error.</p>
		<p>You may already have a blog at this address: <a href="http://<?php echo $domain . $path; ?>" target="_blank"><?php echo $domain . $path; ?></a>.</p>
		<p>Please call the <a href="http://url.path.to/your/helpdesk" target="_blank">Help Desk</a> with the following:</p>
		<p><pre><?php print_r( $activated->errors );?></pre></p><?php
	}

	return( FALSE );
}
add_filter( 'wpmu_signup_blog_notification', 'psu_signup_blog_notification', 11, 7 );

// we don't want crawlers to index this page, if they ever get here.
function signuppageheaders() {
	echo "<meta name='robots' content='noindex,nofollow' />\n";
}
add_action( 'wp_head', 'signuppageheaders' ) ;

// put a header on the page
get_header();

/*

	Set the information about the user and his/her new blog.
	Make changes here as appropriate for your site.

*/
$user_email = $username .'@site.org'; 
/*
	Set the url for the new blog based on the username returned from CAS.
	Underscores aren't allowed in MU blog addresses; 
	You may have to clean your usernames in other ways as well.
*/
$sitename = str_replace('_','-',$username);
/*
	We can't use the global $domain, it turns out, because it isn't set to the 
	base domain, but to the subdomain of whatever blog the user is currently visiting.
*/
$domain = $sitename .'.blogs.site.org';

// provision it
wpmu_signup_blog( $domain, $base, $sitename, $username, $user_email );
wpmu_validate_blog_signup();

?>