<?php
/*
Plugin Name: wpCAS
Version: 1.07
Plugin URI: http://maisonbisson.com/projects/wpcas
Description: Plugin to integrate WordPress or WordPressMU with existing <a href="http://en.wikipedia.org/wiki/Central_Authentication_Service">CAS</a> single sign-on architectures. Based largely on <a href="http://schwink.net">Stephen Schwink</a>'s <a href="http://wordpress.org/extend/plugins/cas-authentication/">CAS Authentication</a> plugin. Optionally, you can set a function to execute when a CAS username isn't found in WordPress (so, for example, you could provision a WordPress account for them). 
Author: Casey Bisson
Author URI: http://maisonbisson.com/
*/

/* 
 Copyright (C) 2008 Casey Bisson

 This plugin owes a huge debt to 
 Stephen Schwink's CAS Authentication plugin, copyright (C) 2008 
 and released under GPL. 
 http://wordpress.org/extend/plugins/cas-authentication/

 This plugin honors and extends Schwink's work, and is licensed under the same terms.



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
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	 02111-1307	 USA 
*/


$error_reporting = error_reporting(0); // hide any warnings when attempting to fetch the optional config file
include_once( dirname(__FILE__).'/wpcas-conf.php' ); // attempt to fetch the optional config file
error_reporting( $error_reporting ); // unhide warnings

// do we have a valid options array? fetch the options from the DB if not
if( !is_array( $wpcas_options )){
	$wpcas_options = get_option( 'wpcas_options' );
	add_action( 'admin_menu', 'wpcas_options_page_add' );
}

$cas_configured = true;

// try to configure the phpCAS client
if ($wpcas_options['include_path'] == '' ||
		(include_once $wpcas_options['include_path']) != true)
	$cas_configured = false;

if ($wpcas_options['server_hostname'] == '' ||
		$wpcas_options['server_path'] == '' ||
		intval($wpcas_options['server_port']) == 0)
	$cas_configured = false;

if ($cas_configured) {
	phpCAS::client($wpcas_options['cas_version'], 
		$wpcas_options['server_hostname'], 
		intval($wpcas_options['server_port']), 
		$wpcas_options['server_path']);
	
	// function added in phpCAS v. 0.6.0
	// checking for static method existance is frustrating in php4
	$phpCas = new phpCas();
	if (method_exists($phpCas, 'setNoCasServerValidation'))
		phpCAS::setNoCasServerValidation();
	unset($phpCas);
	// if you want to set a cert, replace the above few lines
 }

// plugin hooks into authentication system
add_action('wp_authenticate', array('wpCAS', 'authenticate'), 10, 2);
add_action('wp_logout', array('wpCAS', 'logout'));
add_action('lost_password', array('wpCAS', 'disable_function'));
add_action('retrieve_password', array('wpCAS', 'disable_function'));
add_action('check_passwords', array('wpCAS', 'check_passwords'), 10, 3);
add_action('password_reset', array('wpCAS', 'disable_function'));
add_filter('show_password_fields', array('wpCAS', 'show_password_fields'));

class wpCAS {
	/*
	 We call phpCAS to authenticate the user at the appropriate time 
	 (the script dies there if login was unsuccessful)
	 If the user is not provisioned, wpcas_nowpuser() is called
	*/
	function authenticate() {
		global $wpcas_options, $cas_configured;
		
		if ( !$cas_configured )
			die( __( 'wpCAS plugin not configured', 'wpcas' ));

		if( phpCAS::isAuthenticated() ){
			// CAS was successful
			if ( $user = get_userdatabylogin( phpCAS::getUser() )){ // user already exists
				// the CAS user has a WP account
				wp_set_auth_cookie( $user->ID );

				if( isset( $_GET['redirect_to'] )){
					wp_redirect( preg_match( '/^http/', $_GET['redirect_to'] ) ? $_GET['redirect_to'] : site_url( $_GET['redirect_to'] ));
					die();
				}

				wp_redirect( site_url( '/wp-admin/' ));
				die();

			}else{
				// the CAS user _does_not_have_ a WP account
				if (function_exists( 'wpcas_nowpuser' ))
					wpcas_nowpuser( phpCAS::getUser() );
				else
					die( __( 'you do not have permission here', 'wpcas' ));
			}
		}else{
			// hey, authenticate
			phpCAS::forceAuthentication();
			die();
		}
	}
	
	
	// hook CAS logout to WP logout
	function logout() {
		global $cas_configured;

		if (!$cas_configured)
			die( __( 'wpCAS plugin not configured', 'wpcas' ));

		phpCAS::logout( array( 'url' => get_settings( 'siteurl' )));
		exit();
	}

	// hide password fields on user profile page.
	function show_password_fields( $show_password_fields ) {
		return false;
	}

	// disabled reset, lost, and retrieve password features
	function disable_function() {
		die( __( 'Sorry, this feature is disabled.', 'wpcas' ));
	}

	// set the passwords on user creation
	// patched Mar 25 2010 by Jonathan Rogers jonathan via findyourfans.com
	function check_passwords( $user, $pass1, $pass2 ) {
		$random_password = substr( md5( uniqid( microtime( ))), 0, 8 );
		$pass1=$pass2=$random_password;
	}
}

//----------------------------------------------------------------------------
//		ADMIN OPTION PAGE FUNCTIONS
//----------------------------------------------------------------------------

function wpcas_options_page_add() {
	add_options_page( __( 'wpCAS', 'wpcas' ), __( 'wpCAS', 'wpcas' ), 8, basename(__FILE__), 'wpcas_options_page');
} 

function wpcas_options_page() {
	global $wpdb;
	
	// Setup Default Options Array
	$optionarray_def = array(
				 'new_user' => FALSE,
				 'redirect_url' => '',
				 'email_suffix' => 'yourschool.edu',
				 'cas_version' => CAS_VERSION_1_0,
				 'include_path' => '',
				 'server_hostname' => 'yourschool.edu',
				 'server_port' => '443',
				 'server_path' => ''
				 );
	
	if (isset($_POST['submit']) ) {		 
		// Options Array Update
		$optionarray_update = array (
				 'new_user' => $_POST['new_user'],
				 'redirect_url' => $_POST['redirect_url'],
				 'email_suffix' => $_POST['email_suffix'],
				 'include_path' => $_POST['include_path'],
				 'cas_version' => $_POST['cas_version'],
				 'server_hostname' => $_POST['server_hostname'],
				 'server_port' => $_POST['server_port'],
				 'server_path' => $_POST['server_path']
				 );

		update_option('wpcas_options', $optionarray_update);
	}
	
	// Get Options
	$optionarray_def = get_option('wpcas_options');
	
	?>
	<div class="wrap">
	<h2>CAS Authentication Options</h2>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . basename(__FILE__); ?>&updated=true">
	<h3><?php _e( 'wpCAS options', 'wpcas' ) ?></h3>
	<h4><?php _e( 'Note', 'wpcas' ) ?></h4>
	<p><?php _e( 'Now that you’ve activated this plugin, WordPress is attempting to authenticate using CAS, even if it’s not configured or misconfigured.', 'wpcas' ) ?></p>
	<p><?php _e( 'Save yourself some trouble, open up another browser or use another machine to test logins. That way you can preserve this session to adjust the configuration or deactivate the plugin.', 'wpcas' ) ?></p>
	<h4><?php _e( 'Also note', 'wpcas' ) ?></h4>
	<p><?php _e( 'These settings are overridden by the <code>wpcas-conf.php</code> file, if present.', 'wpcas' ) ?></p>

	<h4><?php _e( 'phpCAS include path', 'wpcas' ) ?></h4>
	<table width="700px" cellspacing="2" cellpadding="5" class="editform">
		<tr>
			<td colspan="2"><?php _e( 'Full absolute path to CAS.php script', 'wpcas' ) ?></td>
		</tr>
		<tr valign="center"> 
			<th width="300px" scope="row"><?php _e( 'CAS.php path', 'wpcas' ) ?></th> 
			<td><input type="text" name="include_path" id="include_path_inp" value="<?php echo $optionarray_def['include_path']; ?>" size="35" /></td>
		</tr>
	</table>		
	
	<h4><?php _e( 'phpCAS::client() parameters', 'wpcas' ) ?></h4>
	<table width="700px" cellspacing="2" cellpadding="5" class="editform">
		<tr valign="center"> 
			<th width="300px" scope="row">CAS verions</th> 
			<td><select name="cas_version" id="cas_version_inp">
				<option value="2.0" <?php echo ($optionarray_def['cas_version'] == '2.0')?'selected':''; ?>>CAS_VERSION_2_0</option>
				<option value="1.0" <?php echo ($optionarray_def['cas_version'] == '1.0')?'selected':''; ?>>CAS_VERSION_1_0</option>
			</td>
		</tr>
		<tr valign="center"> 
			<th width="300px" scope="row"><?php _e( 'server hostname', 'wpcas' ) ?></th> 
			<td><input type="text" name="server_hostname" id="server_hostname_inp" value="<?php echo $optionarray_def['server_hostname']; ?>" size="35" /></td>
		</tr>
		<tr valign="center"> 
			<th width="300px" scope="row"><?php _e( 'server port', 'wpcas' ) ?></th> 
			<td><input type="text" name="server_port" id="server_port_inp" value="<?php echo $optionarray_def['server_port']; ?>" size="35" /></td>
		</tr>
		<tr valign="center"> 
			<th width="300px" scope="row"><?php _e( 'server path', 'wpcas' ) ?></th> 
			<td><input type="text" name="server_path" id="server_path_inp" value="<?php echo $optionarray_def['server_path']; ?>" size="35" /></td>
		</tr>
	</table>

	<div class="submit">
		<input type="submit" name="submit" value="<?php _e('Update Options') ?> &raquo;" />
	</div>
	</form>
<?php
}
?>
