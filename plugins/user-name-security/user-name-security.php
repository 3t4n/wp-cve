<?php
/*
Plugin Name:  SX User Name Security
Version:      2.4
Plugin URI:   https://www.seomix.fr
Description:  Prevents WordPress from showing User login and User ID. "User Name Security" filters User Nicename, Nickname and Display Name in order to avoid showing real User Login. This plugin also filters the body_class function to remove User ID and Nicename in it.
Availables languages: en_EN, fr_FR
Tags: security, user, body_class, nicename, display nam
Author: Daniel Roch - SeoMix
Author URI: https://www.seomix.fr
Contributors: juliobox, secupress
Text Domain: user-name-security
Requires at least: 4.6
Tested up to: 6.4.2
License: GPL v3

User Name Security - SeoMix
Copyright (C) 2013-2019, Daniel Roch - contact@seomix.fr

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
  * Security
*/
defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

/**
 * Load Language Files 
 */
add_action( 'plugins_loaded', 'seomix_sx_security_init' );
function seomix_sx_security_init() {
	$location = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	load_plugin_textdomain( 'user-name-security', false, $location );
}

/**
* When do we need to reset the counter transient?
*/
add_action( 'profile_update', 'seomix_sx_reset_counter' );
add_action( 'user_register', 'seomix_sx_reset_counter' );
add_action( 'deleted_user', 'seomix_sx_reset_counter' );
register_deactivation_hook( __FILE__, 'seomix_sx_reset_counter' );
function seomix_sx_reset_counter() {
	delete_transient( 'sx_users' );
}

/**
 * Show every author on plugin Page
 */
add_filter( 'plugin_row_meta', 'seomix_sx_plugin_row_meta', 10, 2 );
function seomix_sx_plugin_row_meta( $plugin_meta, $plugin_file ) {
	// Is it this plugin?
	if ( plugin_basename( __FILE__ ) == $plugin_file ){
		// Keep the last idem
		$last = end( $plugin_meta );
		// Keep the metas
		$plugin_meta = array_slice( $plugin_meta, 0, -2 );
		$a = array();
		// Who are we?
		$authors = array(
			array(  'name'=>'Daniel Roch', 'url'=>'https://www.seomix.fr/' ),
			array(  'name'=>'Julio Potier', 'url'=>'http://www.boiteaweb.fr' ),
			array(  'name'=>'SecuPress', 'url'=>'http://blog.secupress.fr' ),
		);
		// Create the new links
		foreach ( $authors as $author ) {
			$a[] = '<a href="' . $author['url'] . '" title="' . esc_attr__( 'Visit author homepage' ) . '">' . $author['name'] . '</a>';
		}
		// Create the string
		$a = sprintf( __( 'By %s' ), wp_sprintf( '%l', $a ) );
		// Replace back the metas
		$plugin_meta[] = $a;
		// Replace back the last item
		$plugin_meta[] = $last;
	}
	return $plugin_meta;
}

/**
  * Filter body_class in order to hide User ID and User nicename
  * @var array $wp_classes holds every default classes for body_class function
  * @var array $extra_classes holds every extra classes for body_class function
  */
add_filter( 'body_class', 'seomix_sx_security_body_class', 10, 2 );
function seomix_sx_security_body_class( $wp_classes, $extra_classes ) {
	if ( is_author() ) {
		// Getting author Information
		$curauth = get_query_var( 'author_name' ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
		// Blacklist author-ID class
		$blacklist[] = 'author-'.$curauth->ID;
		// Blacklist author-nicename class
		$blacklist[] = 'author-'.$curauth->user_nicename;
		// Delete useless classes
		$wp_classes = array_diff( $wp_classes, $blacklist );
	}
	// Return all classes
	return array_merge( $wp_classes, (array)$extra_classes );
}


/**
  * When User is logged in, it forces Display name and Nickname to be different from User Login
  *
  */
add_action( 'wp_ajax_sx_user_fix', 'seomix_sx_security_users_change_names' );
// add_action( 'init', 'seomix_sx_security_users_change_names' );
function seomix_sx_security_users_change_names() {
	// select the user passed by AJAX or admin-post.php 
	if ( 'wp_ajax_sx_user_fix' == current_filter() && 
		isset( $_GET['id'], $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'sx_user_fix' ) ) {
		// ... via "id" parameter
		$current_user = new WP_User( (int) $_GET['id'] );
	}
	// or user is logged in
	elseif ( is_user_logged_in() ) {
		// Get Current User Global Object
		global $current_user;
	}
	// do we have a valid user?
	if ( is_a( $current_user, 'WP_User' ) ) {
		// Get Current User ID
		$userID = $current_user->ID;
		// Get Current User Display Name
		$displayname = $current_user->display_name;
		// Get current User Login
		$userlogin= $current_user->user_login;
		// Get current User nickName
		$usernickname= $current_user->nickname;
		// Random var in order to change User data
		$newname = seomix_sx_congolexicomatisation();
		// Flag : true = delete_transient needed
		$flag = false;
		// if Display Name, User login and Nickname are equal, change them to random var
		if ( $displayname == $userlogin && $usernickname == $userlogin ) {
			update_user_meta( $userID, 'nickname', $newname );
			wp_update_user( array( 'ID' => $userID, 'display_name' => $newname ) );
			$flag = true;
		}
		// if Display Name and User login are equal, change it to NickName
		elseif ( $displayname == $userlogin) {
			wp_update_user( array ('ID' => $userID, 'display_name' => $usernickname ) );
			$flag = true;
		}
		// if nickName and User login are equal, change it to Display Name
		elseif ( $usernickname == $userlogin ) {
			update_user_meta( $userID, 'nickname', $displayname );
			$flag = true;
		}
		if ( $flag ) {
			// Delete the transient, it will be reloaded soon with good values
			delete_transient( 'sx_users' );
			if ( 'wp_ajax_sx_user_fix' == current_filter() ) {
				wp_send_json_success();
			}
		}
		if ( 'wp_ajax_sx_user_fix' == current_filter() ) {
			wp_send_json_error();
		}
	}
}

/**
  * Detect user creation
  * When a new user is created, creates a global var $seomix_var_new_login
  */
add_filter( 'pre_user_login', 'seomix_sx_security_login_detector' );
function seomix_sx_security_login_detector( $login ) {
	// Creata a global var to be used in seomix_sx_security_name_filter()
	global $seomix_var_new_login;
	// Do this user already exists?
	$seomix_var_new_login = ! get_user_by( 'login', $login );
	// Do not modify, just return it.
	return $login;
}

/**
  * Filter data when registering and modification
  * When a new user is created or modified, change User Nicename, Nickname and  Display Name
  *
  */
add_filter( 'pre_user_display_name', 'seomix_sx_security_name_filter' );
add_filter( 'pre_user_nickname', 'seomix_sx_security_name_filter' );
function seomix_sx_security_name_filter( $name ) {
	global $seomix_var_new_login;
	// Test if user can be found by its nickname/display name/nicename
	$user_test = get_user_by( 'login', $name );
	// Found!
	if ( $seomix_var_new_login || is_a( $user_test, 'WP_User' ) )	{
		// Create a static to be used between the 3 hooks
		static $_name;
		// Not set yet, do it
  		if ( ! $_name ) {
  			// Generate the name, see below
			$_name = seomix_sx_congolexicomatisation();
		}
		// Use it now
		$name = $_name;
		// If we are in the nicename hook AND login==nicename + new user, use the generated new name sanitized
		if ( 'pre_user_nicename' == current_filter() && $seomix_var_new_login ) {
			$name = sanitize_key( $name );
		}
	}
	return $name;
}

/**
  * This function will check if an admin notice has to be shown
  *
  */
add_action( 'admin_init', 'seomix_sx_check_nicename', 800 );
function seomix_sx_check_nicename() {
	if ( defined('DOING_AJAX') && DOING_AJAX ) {
		return;
	}
	if ( current_user_can( 'edit_users' ) ) {
		$transient = get_transient( 'sx_users' );
		if ( false === $transient ) {
			global $wpdb;
			$req = "SELECT ID FROM $wpdb->users u, $wpdb->usermeta um WHERE u.user_login=u.display_name OR (um.user_id=u.ID AND um.meta_key='nickname' AND um.meta_value=u.user_login ) GROUP BY ID";
			$transient = $wpdb->get_col( $req );
			set_transient( 'sx_users', $transient, HOUR_IN_SECONDS * 3 );
		}
		$transient = (int) $transient;
		if ( $transient > 0 ) {
			global $current_user;
			// Get current User Login
			$userlogin = $current_user->user_login;
			// Get current User NiceName
			$nicename = $current_user->user_nicename;
			// If the nicename is equal to login
			if( $nicename === $userlogin ) {
				// Var to check if sf-author-url-control is installed (active or not)
				$is_installed__sf_author_url_control = get_plugins( '/sf-author-url-control' ); 
				// Var to check if sf-author-url-control is active
				$is_active__sf_author_url_control = is_plugin_active( 'sf-author-url-control/sf-author-url-control.php' );
				// we add the action right now
				add_action( 'admin_notices', 'seomix_sx_alert' );
				// create a global var, so i can read it in seomix_sx_alert()
				global $seomix_sx_reason;
				// If the current user can install plugins (like admin)
				if ( current_user_can( 'install_plugins' ) ) {
					// if sf-author-url-control installed but not active?
					if ( $is_installed__sf_author_url_control && !$is_active__sf_author_url_control ) {
						$seomix_sx_reason = 'install_not_active';
					// if sf-author-url-control not installed?
					} elseif ( !$is_installed__sf_author_url_control  ) {
						$seomix_sx_reason = 'not_installed';
					// sf-author-url-control is active, so change your nicename
					} else {
						$seomix_sx_reason = 'change_your_nicename';
					}
				// or the current user can not install plugins (like subscriber, author, contributor, editor)
				} else {
					// if sf-author-url-control not installed or not active?
					if ( !$is_installed__sf_author_url_control || !$is_active__sf_author_url_control ) {
						$seomix_sx_reason = 'install_or_active';
					// sf-author-url-control is active, so change your nicename
					} elseif( $is_active__sf_author_url_control ) {
						$seomix_sx_reason = 'change_your_nicename';
					}
				}
			}
		}
	}
}

/**
  * This function will show the admin notice for current_user_can( 'install_plugins' )
  *
  */
// no hook here, see seomix_sx_check_nicename()
function seomix_sx_alert() {
	global $seomix_sx_reason;
	// URL for installation of sf-author-url-control
	$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=sf-author-url-control' ), 'install-plugin_sf-author-url-control' );
	// URL for activation of sf-author-url-control
	$active_url = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=sf-author-url-control%2Fsf-author-url-control.php' ), 'activate-plugin_sf-author-url-control/sf-author-url-control.php' );
	// URL for sf-author-url-control settings
	$change_url = self_admin_url( 'profile.php#user_nicename' );?>
	<div class="error">
	<p><b>SX User Name Security</b><br>
	<?php _e( 'Your <code>login</code> and your <code>nicename</code> <em>(used in URLs)</em> are the same and this is not secure. We don\'t want to force the change of this because it can lead to a 404 error. WordPress do not permit this edit easily.', 'user-name-security' ); ?>
	<br>
	<?php
		// depending on the reason, what do we have to say?
		switch ( $seomix_sx_reason ) {
			case 'not_installed':
				printf( __( 'We recommand you to install <a href="%s">SF Author Url Control</a>. Then <a href="%s">activate it</a> and finally <a href="%s">change your nicename</a>.', 'user-name-security' ), $install_url, $active_url, $change_url );
			break;
			case 'install_not_active':
				printf( __( 'You have already installed <b>SF Author Url Control</b>. Now please, <a href="%s">activate it</a> then <a href="%s">change your nicename</a>.', 'user-name-security' ), $active_url, $change_url );
			break;
			case 'install_or_active':
				printf( __( 'To do this, please <a href="mailto:%s">ask the administrator</a> to install and activate the following plugin: <a href="http://wordpress.org/plugins/sf-author-url-control/">SF Author Url Control</a>. Thank you.', 'user-name-security' ), get_option( 'admin_email' ) );
			break;
			case 'change_your_nicename':
				printf( __( 'You are already using <b>SF Author Url Control</b>. Now please <a href="%s">change your nicename</a>.', 'user-name-security' ), $change_url );
			break;
		}
	?>
	</p></div>
<?php
}

/**
  * This function will generate a random name, human readable #fun
  * Thanks to Eddy Malou
  *
  */
function seomix_sx_congolexicomatisation( $count=8 ) {
	$v = array_flip( str_split( 'aaeeiou' ) );
	$c = array_flip( str_split( 'bcdfgjlmnprstv' ) );
	$name = '';
	for ( $i = 1; $i <= $count; $i++ ) { 
		if( ceil( $count / 2 ) == $i ) {
			$name .= ' ' . array_rand( $c ) . '. ' . array_rand( $v );
		}
		$name .= array_rand( $c ) . array_rand( $v );
	}
	return ucwords( $name );
}

/**
* On users page listing, add some CSS rules, based on transient, we color each row we need
*/
add_action( 'admin_print_styles-users.php', 'seomix_sx_print_style' );
function seomix_sx_print_style() {
	if ( current_user_can( 'edit_users' ) && $ids = get_transient( 'sx_users' ) ) {
		?><style><?php
		foreach ( $ids as $id ) {
			echo '#user-' . (int)$id . ' th{border-left:4px solid #DD3D36}' . "\n";
		}
		?>
		#sx-bar{position:relative;height:25px}
		#sx-bar .ui-progressbar-value{-webkit-transition:width 700ms}
		#sx-bar-percent{position:absolute;left:50%;top:50%;width:auto;margin-left:-150px;height:25px;margin-top:-9px;font-weight:400;text-align:center}
		span.dashicons-dismiss.seomixsx{color:#F00;position:relative;top:4px;left:4px;cursor:pointer}
		th.sx_ok{border-left-color:#7AD03A !important}
		</style><?php
	}
}

/**
* Enqueue the jquery ui custom JS
*/
add_action( 'admin_head-users.php', 'seomix_sx_enqueue_js' );
function seomix_sx_enqueue_js() {
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';
	wp_enqueue_script( 'jquery-ui-custom-sx', plugins_url( 'js/jquery-ui-1.9.2.custom'.$min.'.js', __FILE__ ), array( 'jquery-ui-core' ), '1.9.2', true );
}

/**
* Enqueue the jquery ui custom CSS
*/
add_action( 'admin_head-users.php', 'seomix_sx_enqueue_css' );
function seomix_sx_enqueue_css() {
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';
	wp_enqueue_style( 'jquery-ui-custom-sx', plugins_url( 'css/jquery-ui-1.9.2.custom'.$min.'.css', __FILE__ ), array(), '1.9.2' );
}

/**
* If there is some users who need to be fixed, display it
*/
add_action( 'admin_notices', 'seomix_sx_alert_users_left' );
function seomix_sx_alert_users_left() {
	if ( current_user_can( 'edit_users' ) && $ids = get_transient( 'sx_users' ) ) {
		$bar = '<div id="sx-bar" class="hidden"><div id="sx-bar-percent"></div></div>';
		$clic = 'users.php' != $GLOBALS['pagenow'] ? sprintf( __( ' <a href="%s">Visit the users\' page</a>.', 'user-name-security' ), admin_url( 'users.php' ) ) : '';
		echo '<div class="error seomix_sx hide-if-no-js"><p><b><big>SX User Name Security:</big></b> ' . sprintf( _n( 'You are displaying some users sensitive informations on your website. There is %d user left to treat.<br><strong>&rsaquo; Warning</strong> : it will only change every "Display Name" for all users (in order to prevent showing their real logins), but it won\'t change their URL. In order to do this, we recommand you to install and use "SF Author Url Control".%s', 'There is %d users left to treat.%s', count( $ids ), 'user-name-security' ), count( $ids ), $clic ) . $bar . '</p></div>';
		echo '<div class="error hide-if-js"><p><b>SX User Name Security:</b> ' . __( 'This plugin requires JavaScript to work well.', 'user-name-security' ) . '</p></div>';
	}
}

/**
* Add a button to perform the ajax only fix
*/
add_action( 'restrict_manage_users', 'semix_sx_restrict_manage_users' );
function semix_sx_restrict_manage_users() {
	if ( current_user_can( 'edit_users' ) && $ids = get_transient( 'sx_users' ) ) {
		?><input type="submit" name="sx_submit" id="sx_submit" class="button button-primary hide-if-no-js" value="<?php printf( _n( 'Fix %d Username', 'Fix %d Usernames', count( $ids ), 'user-name-security' ), count( $ids ) ); ?>" style="margin:1px 0 0 10px;"><?php
		echo '<span class="dashicons dashicons-dismiss seomixsx hidden" id="sx-stop" title="' . __( 'Cancel', 'user-name-security' ) . '"></span>';
		echo '<img src="' . admin_url( '/images/spinner.gif' ) . '" alt="' . __( 'Loading', 'user-name-security' ) . '" title="' . __( 'Please wait', 'user-name-security' ) . '" style="position:relative;top:4px;left:5px;" class="hidden" id="sx_loader" />';
	}
}

/**
* The javascript, all ajax queries are done here
*/
add_action( 'admin_footer-users.php', 'seomix_sx_js_for_users' );
function seomix_sx_js_for_users() {
	if ( current_user_can( 'edit_users' ) && $ids = get_transient( 'sx_users' ) ) {
		?>
		<script>
			jQuery( document ).ready( function( $ ) { 
				var sx_users = [<?php echo implode( ',', array_map( 'intval', $ids ) ); ?>];
				var sx_total = sx_users.length;
				var sx_count = 0;
				var sx_stop = false;
				$( '#sx_submit' ).on( 'click', function( e ){ 
					e.preventDefault();
					sx_stop = false;
					$( '#sx_loader' ).show();
					$( '.dashicons-dismiss.seomixsx' ).show();
					$( this ).attr( 'disabled', true );
					$( '#sx-bar' ).progressbar();
					$( '#sx-bar-percent' ).text( sx_count + '/<?php echo count( $ids ); ?>' );
					$( '#sx-bar' ).show().progressbar( 'value', ( sx_count / sx_total ) * 100 );
					sxRegenUser( sx_users.shift() );
				} );
				// Stop button
				$( '#sx-stop' ).click(function() {
					sx_stop = true;
					sxStop();
				});			
				function sxStop(){
					$( '#sx_loader' ).hide();
					$( '#sx-stop' ).hide();
					$( '#sx_submit' ).removeAttr( 'disabled' );
					$( '#sx-bar-percent' ).text( '<?php _e( 'Canceled!', 'user-name-security' ); ?>' );
				}
				// Called after each user modified.
				function sxRegenUserUpdateStatus( id ) {
					sx_count++;
					$( '#sx-bar' ).progressbar( "value", ( sx_count / sx_total ) * 100 );
					$( '#sx-bar-percent' ).text( sx_count + '/<?php echo count( $ids ); ?>' );
					$( '#user-' + id +' th').addClass( 'sx_ok' );
				}
				function sxRegenUsersFinishUp() {
					sxStop();
					if ( ! sx_stop ) {
						$( '#sx_submit' ).hide();
						$( 'div.error.seomix_sx p' ).remove();
						$( 'div.error' ).toggleClass( 'error updated' );
						$( '#sx-bar-percent' ).text( '<?php printf( __( '%s users have been successfully modified!', 'user-name-security' ), count( $ids ) ); ?>' );
					}
				}
				// Regenerate a user via AJAX
				function sxRegenUser( id ) {
				$.get( ajaxurl + '?action=sx_user_fix&_wpnonce=<?php echo wp_create_nonce( 'sx_user_fix' ); ?>&id='+id )
					.done( function( response ) {

						if ( response !== Object( response ) || ( typeof response.success === "undefined" && typeof response.error === "undefined" ) ) {
							response.success = false;
						}

						if ( response.success ) {
							sxRegenUserUpdateStatus( id );
						}

						if ( sx_users.length && ! sx_stop ) {
							sxRegenUser( sx_users.shift() );
						}
						else {
							sxRegenUsersFinishUp();
						}
					} );
				}
			} );

		</script>
		<?php
	}
}

/**
* Add Display Name to the WordPress User List Admin Page
*/
add_filter('manage_users_columns', 'sx_add_user_id_column');
function sx_add_user_id_column($columns) {
    $columns['displayname'] = 'Display Name';
    return $columns;
}
add_action('manage_users_custom_column',  'sx_show_user_id_column_content', 10, 3);
function sx_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
		if ( 'displayname' == $column_name ) {
			return $user->display_name;
		}
    return $value;
}