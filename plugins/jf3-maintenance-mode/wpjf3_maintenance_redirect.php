<?php
/*
 * Plugin Name:		Maintenance Redirect
 * Plugin URI:		https://www.fabulosawebdesign.co.uk
 * Description:		Display a maintenance mode page and allow invited visitors to bypass the functionality to preview the site.
 * Version:			2.0.1
 * Requires at least:	5.1
 * Tested up to:		6.1.1
 * Requires PHP:		5.6
 * Author:      		Peter Hardy-vanDoorn
 * Author URI:		https://www.fabulosawebdesign.co.uk
 * Contributors:		petervandoorn,jfinch3
 * License:			GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: 		jf3-maintenance-mode
 * Copyright:		Modifications: 2018+ Peter Hardy-vanDoorn	(email: wordpress@fabulosa.co.uk)
				Original: 2010-2012  Jack Finch (email: jack@hooziewhats.com - nb: when checked in Dec 2017 this domain is not functioning)
   				
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.

 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

define( "WPJF3_VERSION", "2.0.1" );

if( !class_exists("wpjf3_maintenance_redirect") ) {
	class wpjf3_maintenance_redirect {
		
		var $admin_options_name;
		var $maintenance_html_head;
		var $maintenance_html_foot;
		var $maintenance_html_body;
		
		// (php) constructor.
		function __construct() { 
			$this->admin_options_name	= "wpjf3_mr";
			$this->maintenance_html_head	= '<html><head><link href="[[WP_STYLE]]" rel="stylesheet" type="text/css" /><title>[[WP_TITLE]]</title></head><body><h1 style="margin-left:auto; margin-right: auto; max-width: 500px; border:1px solid #000; color: #000; background-color: #fff; padding: 10px; margin-top:200px; text-align:center;">';
			$this->maintenance_html_foot	= '</h1></body></html>';
			$this->maintenance_html_body	= esc_html__( 'This site is currently undergoing maintenance. Please check back later.' );
		}
		
		// (php) initialize.
		function init() {
			global $wpdb;
			
			// create keys table if needed.
			$tbl = $wpdb->prefix . $this->admin_options_name . "_access_keys";
    			if( $wpdb->get_var( "SHOW TABLES LIKE '$tbl'" ) != $tbl ) {
				$sql = "create table $tbl ( id int auto_increment primary key, name varchar(100), access_key varchar(20), email varchar(100), created_at datetime not null default '0000-00-00 00:00:00', active int(1) not null default 1 )";
				$wpdb->query($sql);
			}
			
			// create IPs table if needed
			$tbl = $wpdb->prefix . $this->admin_options_name . "_unrestricted_ips";
    			if( $wpdb->get_var( "SHOW TABLES LIKE '$tbl'" ) != $tbl ) {
				$sql = "create table $tbl ( id int auto_increment primary key, name varchar(100), ip_address varchar(20), created_at datetime not null default '0000-00-00 00:00:00', active int(1) not null default 1 )";
				$wpdb->query($sql);
			}
			
			// setup options
			// See explanation of this in the get_admin_options() function below
			$try_option = get_option( "wpjf3_maintenance_redirect_version" );
			$notoptions = wp_cache_get( "notoptions", "options" );
			if ( isset( $notoptions[ "wpjf3_maintenance_redirect_version" ] ) )
				update_option( "wpjf3_maintenance_redirect_version", WPJF3_VERSION );
			
			$tmp_opt = $this->get_admin_options();
			
		}
		
		// (php) check on plugin update
		
		function after_plugin_upgrade( $upgrader_object, $hook_extra ) {
			
			if ( isset( $hook_extra['plugins'] ) && is_array( $hook_extra['plugins'] ) ) {
				foreach ( $hook_extra['plugins'] as $index => $plugin ) {
					if ( $plugin == plugin_basename( __FILE__ ) ) {
						// Update options on plugin update if old version is less than 2.0
						$this->wpjf3_mr_process_updater();
						break;
					}
				}
			}

		}
		
		// (php) function to update settings as above
		
		function wpjf3_mr_process_updater() {
			
			if ( version_compare( get_option( "wpjf3_maintenance_redirect_version" ), '2.0', '<' ) ) {

				$wpjf3_mr_saved_options = get_option( $this->admin_options_name );

				if ( $wpjf3_mr_saved_options[ "method" ] === 'message' ) {
					
					$wpjf3_mr_saved_options[ "maintenance_message" ] = strip_tags( $wpjf3_mr_saved_options[ "maintenance_html" ] );
					
				}

				$wpjf3_mr_saved_options[ "active_tab" ]  = "#about";
				$wpjf3_mr_saved_options[ "hide_coffee" ] = false;
				$wpjf3_mr_saved_options[ "uninstall" ]   = false;
				
				update_option( $this->admin_options_name, $wpjf3_mr_saved_options );
				
			}

			update_option( "wpjf3_maintenance_redirect_version", WPJF3_VERSION );
			
		}
		
		// (php) find user IP.
		function get_user_ip(){
			$ip = ( isset( $_SERVER['HTTP_X_FORWARD_FOR'] ) ) ? $_SERVER['HTTP_X_FORWARD_FOR'] : $_SERVER['REMOTE_ADDR'];
			return $ip;
		}

		// (php) determine user class c
		function get_user_class_c(){
			$ip = $this->get_user_ip();
			$ip_parts = explode( '.', $ip );
			$class_c = $ip_parts[0] . '.' . $ip_parts[1] . '.' .$ip_parts[2] . '.*';
			return $class_c;
		}
		
		// (php) get and return an array of admin options. if no options set, initialize.
		function get_admin_options() {
			
			/* Left in for debugging */ // delete_option( $this->admin_options_name );
			
			// 1. Try to get the options from the database
			$wpjf3_mr_saved_options = get_option( $this->admin_options_name );
			
			/* 2. If the options have not yet been set, the fact that the above request failed 
			      will recorded be in the "notoptions" cache */
			$notoptions = wp_cache_get( "notoptions", "options" );
			
			/* 3. If it is in the "notoptions" cache, then it has not been set, 
			   so we set it using the defaults. */
			if ( isset( $notoptions[ $this->admin_options_name ] ) ) {
				$wpjf3_mr_options = array(
					'enable_redirect'     => 'no',
					'header_type' 	    => '200',
					'method'              => 'message',
					'maintenance_message' => $this->maintenance_html_body,
					'maintenance_html'    => "<html><head><title>" . esc_html__( "Under construction" ) . "</title></head><body><h1 style='margin-left:auto;margin-right: auto;max-width: 500px;border:1px solid #000;color: #000;background-color: #fff;padding: 10px;margin-top:200px'>" . $this->maintenance_html_body . "</h1></body></html>",
					'static_page'         => '',
					'active_tab'	    => '#about',
					'hide_coffee'         => false,
					'uninstall'           => false
				);
				update_option( $this->admin_options_name, $wpjf3_mr_options );
			}
			
			return get_option( $this->admin_options_name );
		}
		
		// (php) generate key
		function alphastring( $len = 20, $valid_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ){
			$str  = '';
			$chrs = explode( ' ', $valid_chars );
			for( $i=0; $i<$len; $i++ ){
				$str .= $valid_chars[ rand( 1, strlen( $valid_chars ) - 1 ) ];
			}
			return $str;
		}
		
		// (php) generate maintenance page
		function generate_maintenance_page( $msg_override = '', $skip_header_footer = false ){
			if ( $skip_header_footer ){
				$html  = ( $msg_override != '' ) ? stripslashes( $msg_override ) : $this->maintenance_html_body;
			} else {
				$html  = $this->maintenance_html_head;
				$html  = str_replace( '[[WP_TITLE]]', get_bloginfo('name'), $html );
				$html  = str_replace( '[[WP_STYLE]]', get_bloginfo('stylesheet_url'), $html );
				$html .= ( $msg_override != '' ) ? stripslashes( $msg_override ) : $this->maintenance_html_body;
				$html .= $this->maintenance_html_foot;
			}
			$wpjf3_mr_options = $this->get_admin_options();
			if ( $wpjf3_mr_options['header_type'] == "200" ) {
				header( 'HTTP/1.1 200 OK' );
				header( 'Status: 200 OK' );
			} else {
				header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
				header( 'Status: 503 Service Temporarily Unavailable' );
			}
			header( 'Retry-After: 600' );
			echo $html;
			exit();
		}
		
		// (php) find out if we need to redirect or not.
		function process_redirect() {
			global $wpdb;
			$valid_ips      = array();
			$valid_class_cs = array();
			$valid_aks      = array();
			$wpjf3_matches  = apply_filters( 'wpjf3_matches', array() );
			
			// set cookie if needed
			if ( isset( $_GET['wpjf3_mr_temp_access_key'] ) && trim( $_GET['wpjf3_mr_temp_access_key'] ) != '' ) {
				// get valid access keys
				$sql = "select access_key from " . $wpdb->prefix . $this->admin_options_name . "_access_keys where active = 1";
				$aks = $wpdb->get_results($sql, OBJECT);
				if( $aks ){
					foreach( $aks as $ak ){
						$valid_aks[] = $ak->access_key;
					}
				}
				
				// set cookie if there's a match
				if( in_array( $_GET['wpjf3_mr_temp_access_key'], $valid_aks ) ){
					$wpjf3_mr_cookie_time = time()+(60*60*24*365);
					setcookie( 'wpjf3_mr_access_key', $_GET['wpjf3_mr_temp_access_key'], $wpjf3_mr_cookie_time, '/' );
					$_COOKIE['wpjf3_mr_access_key'] = $_GET['wpjf3_mr_temp_access_key'];
				}
			}
			
			// get plugin options
			$wpjf3_mr_options = $this->get_admin_options();
			
			// skip admin pages by default
			$url_parts = explode( '/', $_SERVER['REQUEST_URI'] );
			if( in_array( 'wp-admin', $url_parts ) ) {
				$wpjf3_matches[] = "<!-- WPJF_MR: SKIPPING ADMIN -->";
			}else{
				// determine if user is admin.. if so, bypass all of this.
				if ( current_user_can( apply_filters( 'wpjf3_user_can', 'manage_options' ) ) ) {
					$wpjf3_matches[] = "<!-- WPJF_MR: USER IS ADMIN -->";
				}else{
					if( $wpjf3_mr_options['enable_redirect'] == "YES" ){
						// get valid unrestricted IPs
						$sql = "select ip_address from " . $wpdb->prefix . $this->admin_options_name . "_unrestricted_ips where active = 1";
						$ips = $wpdb->get_results($sql, OBJECT);
						if( $ips ){
							foreach( $ips as $ip ){
								$ip_parts = explode( '.', $ip->ip_address );
								if( $ip_parts[3] == '*' ){
									$valid_class_cs[] = $ip_parts[0] . '.' . $ip_parts[1] . '.' . $ip_parts[2];
								}else{
									$valid_ips[] = $ip->ip_address;
								}
							}
						}
						
						// get valid access keys
						$valid_aks = array();
						$sql = "select access_key from " . $wpdb->prefix . $this->admin_options_name . "_access_keys where active = 1";
						$aks = $wpdb->get_results($sql, OBJECT);
						if( $aks ){
							foreach( $aks as $ak ){
								$valid_aks[] = $ak->access_key;
							}
						}
						
						// manage cookie filtering
						if( isset( $_COOKIE['wpjf3_mr_access_key'] ) && $_COOKIE['wpjf3_mr_access_key'] != '' ){
							// check versus active codes
							if( in_array( $_COOKIE['wpjf3_mr_access_key'], $valid_aks ) ){
								$wpjf3_matches[] = "<!-- WPJF_MR: COOKIE MATCH -->";
							}
						}
						
						// manage ip filtering 
						if( in_array( $this->get_user_ip(), $valid_ips ) ) {
							$wpjf3_matches[] = "<!-- WPJF_MR: IP MATCH -->";
						}else{
							// check for partial ( class c ) match
							$ip_parts     = explode( '.', $this->get_user_ip() );
							$user_class_c = $ip_parts[0] . '.' . $ip_parts[1] . '.' . $ip_parts[2];
							if( in_array( $user_class_c, $valid_class_cs ) ) {
								$wpjf3_matches[] = "<!-- WPJF_MR: CLASS C MATCH -->";
							}
						}
						
						if ( count( $wpjf3_matches ) == 0 ) {
							// no match found. show maintenance page / message
							switch ( $wpjf3_mr_options['method'] ) {
								case 'redirect' :
									header( 'HTTP/1.1 307 Temporary Redirect' );
									header( 'Status: 307 Temporary Redirect' );
									header( 'Retry-After: 600' );
									header( 'Location:'.$wpjf3_mr_options['static_page'] );
									exit();
									break;
								case 'html' :
									$this->generate_maintenance_page( $wpjf3_mr_options['maintenance_html'], true );
									break;
								default:
									$this->generate_maintenance_page( $wpjf3_mr_options['maintenance_message'] );
							}
						}
					}else{
						$wpjf3_matches[] = "<!-- WPJF_MR: REDIR DISABLED -->";
					}
				}
			}
		}
		
		// (php) add new IP
		function add_new_ip() {
			if ( !current_user_can('manage_options') ) wp_die("Oh no you don't!");
			check_ajax_referer( 'wpjf3_nonce', 'security' );
			global $wpdb;
			$tbl        = $wpdb->prefix . $this->admin_options_name . '_unrestricted_ips';
			$name       = esc_sql( stripslashes( $_POST['wpjf3_mr_ip_name'] ) );
			$ip_address = esc_sql( stripslashes( trim( $_POST['wpjf3_mr_ip_ip'] ) ) );
			$sql        = "insert into $tbl ( name, ip_address, created_at ) values ( '$name', '$ip_address', NOW() )";
			$rs         = $wpdb->query( $sql );
			if( $rs ){
				// send table data
				$this->print_unrestricted_ips();
			}else{
				echo __( 'Unable to add IP because of a database error. Please reload the page.' );
			}
			die();
		}
		
		// (php) toggle IP status
		function toggle_ip_status(){
			if ( !current_user_can('manage_options') ) wp_die("Oh no you don't!");
			check_ajax_referer( 'wpjf3_nonce', 'security' );
			global $wpdb;
			$tbl       = $wpdb->prefix . $this->admin_options_name . '_unrestricted_ips';
			$ip_id     = esc_sql( $_POST['wpjf3_mr_ip_id'] );
			$ip_active = ( $_POST['wpjf3_mr_ip_active'] == 1 ) ? 1 : 0;
			$sql       = "update $tbl set active = '$ip_active' where id = '$ip_id'";
			$rs        = $wpdb->query( $sql );
			if( $rs ){
				// $this->print_unrestricted_ips();
				echo 'SUCCESS' . '|' . $ip_id . '|' . $ip_active;
			}else{
				// echo 'There was an unknown database error. Please reload the page.';
				echo 'ERROR';
			}
			die();
		}
		
		// (php) delete IP
		function delete_ip(){
			if ( !current_user_can('manage_options') ) wp_die("Oh no you don't!");
			check_ajax_referer( 'wpjf3_nonce', 'security' );
			global $wpdb;
			$tbl       = $wpdb->prefix . $this->admin_options_name . '_unrestricted_ips';
			$ip_id     = esc_sql( $_POST['wpjf3_mr_ip_id'] );
			$sql       = "delete from $tbl where id = '$ip_id'";
			$rs        = $wpdb->query( $sql );
			if( $rs ){
				$this->print_unrestricted_ips();
			}else{
				echo __( 'Unable to delete IP because of a database error. Please reload the page.' );
			}
			die();
		}
		
		// (php) add new Access Key
		function add_new_ak() {
			if ( !current_user_can('manage_options') ) wp_die("Oh no you don't!");
			check_ajax_referer( 'wpjf3_nonce', 'security' );
			global $wpdb;
			$tbl        = $wpdb->prefix . $this->admin_options_name . '_access_keys';
			$name       = esc_sql( stripslashes( $_POST['wpjf3_mr_ak_name'] ) );
			$email      = sanitize_email( $_POST['wpjf3_mr_ak_email'] );
			$access_key = esc_sql( $this->alphastring(20) );
			$sql        = "insert into $tbl ( name, email, access_key, created_at ) values ( '$name', '$email', '$access_key', NOW() )";
			$rs         = $wpdb->query( $sql );
			if( $rs ){
				// email user
				$subject    = sprintf( /* translators: %s = name of the website/blog */ __( "Access Key Link for %s" ), get_bloginfo() );
				$full_msg   = sprintf( /* translators: %s = name of the website/blog */ __( "The following link will provide you temporary access to %s:" ), get_bloginfo() ) . "\n\n"; 
				$full_msg  .= __( "Please note that you must have cookies enabled for this to work." ) . "\n\n";
				$full_msg  .= get_bloginfo('url') . '?wpjf3_mr_temp_access_key=' . $access_key;
				$mail_sent  = wp_mail( $email, $subject, $full_msg );
				echo ( $mail_sent ) ? '<!-- SEND_SUCCESS -->' : '<!-- SEND_FAILURE -->';
				// send table data
				$this->print_access_keys();
			}else{
				echo __( "Unable to add Access Key because of a database error. Please reload the page." );
			}
			die();
		}
		
		// (php) toggle Access Key status
		function toggle_ak_status(){
			if ( !current_user_can('manage_options') ) wp_die("Oh no you don't!");
			check_ajax_referer( 'wpjf3_nonce', 'security' );
			global $wpdb;
			$tbl       = $wpdb->prefix . $this->admin_options_name . '_access_keys';
			$ak_id     = esc_sql( $_POST['wpjf3_mr_ak_id'] );
			$ak_active = ( $_POST['wpjf3_mr_ak_active'] == 1 ) ? 1 : 0;
			$sql       = "update $tbl set active = '$ak_active' where id = '$ak_id'";
			$rs        = $wpdb->query( $sql );
			if( $rs ){
				// $this->print_access_keys();
				echo 'SUCCESS' . '|' . $ak_id . '|' . $ak_active;
			}else{
				// echo 'There was an unknown database error. Please reload the page.';
				echo 'ERROR';
			}
			die();
		}
		
		// (php) delete Access Key
		function delete_ak(){
			if ( !current_user_can('manage_options') ) wp_die("Oh no you don't!");
			check_ajax_referer( 'wpjf3_nonce', 'security' );
			global $wpdb;
			$tbl       = $wpdb->prefix . $this->admin_options_name . '_access_keys';
			$ak_id     = esc_sql( $_POST['wpjf3_mr_ak_id'] );
			$sql       = "delete from $tbl where id = '$ak_id'";
			$rs        = $wpdb->query( $sql );
			if( $rs ){
				$this->print_access_keys();
			}else{
				echo __( 'Unable to delete Access Key because of a database error. Please reload the page.' );
			}
			die();
		}
		
		// (php) resend Access Key email
		function resend_ak(){
			if ( !current_user_can('manage_options') ) wp_die("Oh no you don't!");
			check_ajax_referer( 'wpjf3_nonce', 'security' );
			global $wpdb;
			$tbl       = $wpdb->prefix . $this->admin_options_name . '_access_keys';
			$ak_id     = esc_sql( $_POST['wpjf3_mr_ak_id'] );
			$sql       = "select * from $tbl where id = '$ak_id'";
			$ak        = $wpdb->get_row( $sql );
			if( $ak ){
				$subject    = sprintf( /* translators: %s = name of the website */ __( "Access Key Link for %s" ), get_bloginfo() );
				$full_msg   = sprintf( /* translators: %s = name of the website */ __( "The following link will provide you temporary access to %s:" ), get_bloginfo() ) . "\n\n"; 
				$full_msg  .= __( "Please note that you must have cookies enabled for this to work." ) . "\n\n";
				$full_msg  .= get_bloginfo('url') . '?wpjf3_mr_temp_access_key=' . $ak->access_key;
				$mail_sent  = wp_mail( $ak->email, $subject, $full_msg );
				echo ( $mail_sent ) ? 'SEND_SUCCESS' : 'SEND_FAILURE';
			}else{
				echo __( 'ERROR' );
			}
			die();
		}
		
		// (php) generate IP table data 
		function print_unrestricted_ips( ){
			global $wpdb;
			?>
			<table class="widefat fixed wpjf3-table" cellspacing="0">
				<thead>
					<tr>
						<th class="column-wpjf3-ip-name"  ><?php esc_html_e( "Name" ); ?></th>
						<th class="column-wpjf3-ip-ip"    ><?php esc_html_e( "IP" ); ?></th>
						<th class="column-wpjf3-ip-active"><?php esc_html_e( "Active" ); ?></th>
						<th class="column-wpjf3-actions"  ><?php esc_html_e( "Actions" ); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$sql = "select * from " . $wpdb->prefix . $this->admin_options_name . "_unrestricted_ips order by name";
					$ips = $wpdb->get_results($sql, OBJECT);
					$ip_row_class = 'alternate';
					if( $ips ){
						foreach( $ips as $ip ){
							?>
							<tr id="wpjf-ip-<?php echo $ip->id; ?>" valign="middle"  class="<?php echo $ip_row_class; ?>">
								<td class="column-wpjf3-ip-name"><?php echo $ip->name; ?></td>
								<td class="column-wpjf3-ip-ip"><?php echo $ip->ip_address; ?></td>
								<td class="column-wpjf3-ip-active" id="wpjf3_mr_ip_status_<?php echo $ip->id; ?>" ><?php echo ( $ip->active == 1) ? __('Yes') : __('No'); ?></td>
								<td class="column-wpjf3-actions">
									<span class='edit' id="wpjf3_mr_ip_status_<?php echo $ip->id; ?>_action">
										<?php if( $ip->active == 1 ){ ?>
											<a href="javascript:wpjf3_mr_toggle_ip( 0, <?php echo $ip->id; ?> );"><?php esc_html_e( "Disable" ); ?></a> | 
										<?php }else{ ?>
											<a href="javascript:wpjf3_mr_toggle_ip( 1, <?php echo $ip->id; ?> );"><?php esc_html_e( "Enable" ); ?></a> | 
										<?php } ?>
									</span>
									<span class='delete'>
										<a class='submitdelete' href="javascript:wpjf3_mr_delete_ip( <?php echo $ip->id ?>, '<?php echo addslashes( $ip->ip_address ) ?>' );" ><?php esc_html_e( "Delete" ); ?></a>
									</span>
								</td>
							</tr>
							<?php
							$ip_row_class = ( $ip_row_class == '' ) ? 'alternate' : '';
						}
					}
					?>
					
					<tr id="wpjf-ip-NEW" valign="middle"  class="<?php echo $ip_row_class; ?>">
						<td class="column-wpjf3-ip-name">
							<input class="wpjf3_mr_disabled_field" type="text" id="wpjf3_mr_new_ip_name" name="wpjf3_mr_new_ip_name" value="<?php esc_attr_e( "Enter Name:" ); ?>" onfocus="wpjf3_mr_undim_field('wpjf3_mr_new_ip_name','<?php esc_attr_e( "Enter Name:" ); ?>');" onblur="wpjf3_mr_dim_field('wpjf3_mr_new_ip_name','<?php esc_attr_e( "Enter Name:" ); ?>');">
						</td>
						<td class="column-wpjf3-ip-ip">
							<input class="wpjf3_mr_disabled_field" type="text" id="wpjf3_mr_new_ip_ip" name="wpjf3_mr_new_ip_ip" value="<?php esc_attr_e( "Enter IP:" ); ?>" 
							onfocus="wpjf3_mr_undim_field('wpjf3_mr_new_ip_ip','<?php esc_attr_e( "Enter IP:" ); ?>');" onblur="wpjf3_mr_dim_field('wpjf3_mr_new_ip_ip','<?php esc_attr_e( "Enter IP:" ); ?>');">
						</td>
						<td class="column-wpjf3-ip-active">&nbsp;</td>
						<td class="column-wpjf3-actions">
							<span class='edit' id="wpjf3_mr_add_ip_link">
								<a href="javascript:wpjf3_mr_add_new_ip( );"><?php esc_html_e( "Add New IP" ); ?></a>
							</span>
						</td>
					</tr>
					
				</tbody>
			</table>
			<?php
		}
		
		// (php) genereate Access Key table data
		function print_access_keys(){
			global $wpdb;
			?>
			<table class="widefat fixed wpjf3-table" cellspacing="0">
				<thead>
					<tr>
						<th class="column-wpjf3-ak-name"  ><?php esc_html_e( "Name" ); ?></th>
						<th class="column-wpjf3-ak-email" ><?php esc_html_e( "Email" ); ?></th>
						<th class="column-wpjf3-ak-key"   ><?php esc_html_e( "Access Key" ); ?></th>
						<th class="column-wpjf3-ak-active"><?php esc_html_e( "Active" ); ?></th>
						<th class="column-wpjf3-actions"  ><?php esc_html_e( "Actions" ); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					$sql   = "select * from " . $wpdb->prefix . $this->admin_options_name . "_access_keys order by name";
					$codes = $wpdb->get_results($sql, OBJECT);
					$ak_row_class = 'alternate';
					if( $codes ){
						foreach( $codes as $code ){
							?>
							<tr id="wpjf-ak-<?php echo $code->id; ?>" valign="middle"  class="<?php echo $ak_row_class; ?>">
								<td class="column-wpjf3-ak-name"><?php echo $code->name; ?></td>
								<td class="column-wpjf3-ak-email"><a href="mailto:<?php echo $code->email; ?>" title="email <?php echo $code->email; ?>"><?php echo $code->email; ?></a></td>
								<td class="column-wpjf3-ak-key"><?php echo $code->access_key; ?></td>
								<td class="column-wpjf3-ak-active" id="wpjf3_mr_ak_status_<?php echo $code->id; ?>" ><?php echo ( $code->active == 1) ? 'Yes' : 'No'; ?></td>
								<td class="column-wpjf3-actions">
									<span class='edit' id="wpjf3_mr_ak_status_<?php echo $code->id; ?>_action">
										<?php if( $code->active == 1 ){ ?>
											<a href="javascript:wpjf3_mr_toggle_ak( 0, <?php echo $code->id; ?> );"><?php esc_html_e( "Disable" ); ?></a> | 
										<?php }else{ ?>
											<a href="javascript:wpjf3_mr_toggle_ak( 1, <?php echo $code->id; ?> );"><?php esc_html_e( "Enable" ); ?></a> | 
										<?php } ?>
									</span>
									<span class='resend'>
										<a class='submitdelete' id='submit_resend_<?php echo $code->id ?>' href="javascript:wpjf3_mr_resend_ak( <?php echo $code->id ?>, '<?php echo addslashes( $code->name ) ?>', '<?php echo addslashes( $code->email ) ?>' );" ><?php esc_html_e( "Resend Code" ); ?></a> | 
									</span>
									<span class='copy'>
										<a class='submitdelete' id='submit_copy_<?php echo $code->id ?>' href="javascript:wpjf3_mr_copy_ak( <?php echo $code->id ?>, '<?php echo $code->access_key ?>' );" ><?php esc_html_e( "Copy Link" ); ?></a> | 
									</span>
									<span class='delete'>
										<a class='submitdelete' id='submit_delete_<?php echo $code->id ?>' href="javascript:wpjf3_mr_delete_ak( <?php echo $code->id ?>, '<?php echo addslashes( $code->name ) ?>' );" ><?php esc_html_e( "Delete" ); ?></a>
									</span>
								</td>
							</tr>
							<?php
							$ak_row_class = ( $ak_row_class == '' ) ? 'alternate' : '';
						}
					}
					/*
					?>
					<tr id="wpjf-ak-NONE" valign="middle"  class="<?php echo $ak_row_class; ?>">
						<td colspan="5">Enter a New Access Code</td>
					</tr>
					<?php
					$ak_row_class = ( $ak_row_class == '' ) ? 'alternate' : '';
					*/
					?>
					<tr id="wpjf-ak-NEW" valign="middle"  class="<?php echo $ak_row_class; ?>">
						<td class="column-wpjf3-ak-name">
							<input class="wpjf3_mr_disabled_field" type="text" id="wpjf3_mr_new_ak_name" name="wpjf3_mr_new_ak_name" value="<?php esc_attr_e( "Enter Name:" ); ?>" onfocus="wpjf3_mr_undim_field('wpjf3_mr_new_ak_name','<?php esc_attr_e( "Enter Name:" ); ?>');" onblur="wpjf3_mr_dim_field('wpjf3_mr_new_ak_name','<?php esc_attr_e( "Enter Name:" ); ?>');">
						</td>
						<td class="column-wpjf3-ak-email">
							<input class="wpjf3_mr_disabled_field" type="text" id="wpjf3_mr_new_ak_email" name="wpjf3_mr_new_ak_email" value="<?php esc_attr_e( "Enter Email:" ); ?>" onfocus="wpjf3_mr_undim_field('wpjf3_mr_new_ak_email','<?php esc_attr_e( "Enter Email:" ); ?>');" onblur="wpjf3_mr_dim_field('wpjf3_mr_new_ak_email','<?php esc_attr_e( "Enter Email:" ); ?>');">
						</td>
						<td class="column-wpjf3-ak-key">&nbsp;</td>
						<td class="column-wpjf3-ak-active">&nbsp;</td>
						<td class="column-wpjf3-actions">
							<span class='edit' id="wpjf3_mr_add_ak_link">
								<a href="javascript:wpjf3_mr_add_new_ak( );"><?php esc_html_e( "Add New Access Key" ); ?></a>
							</span>
						</td>
					</tr>
					
				</tbody>
			</table>
			<?php
		}
		
		// (php) display redirect status if active
		function display_status_if_active(){
			global $wpdb;
			$wpjf3_mr_options = $this->get_admin_options();
			$show_notice      = false;
			
			if ( $wpjf3_mr_options['enable_redirect'] == 'YES' ) $show_notice = true;
			if ( isset( $_POST['update_wp_maintenance_redirect_settings'] ) && $_POST['wpjf3_mr_enable_redirect'] == 'YES' ) $show_notice = true;
			if ( isset( $_POST['update_wp_maintenance_redirect_settings'] ) && $_POST['wpjf3_mr_enable_redirect'] == 'NO'  ) $show_notice = false;
			
			if ( $show_notice ){
				$current_screen = get_current_screen();
				$settingslink = ( $current_screen->id != "settings_page_JF3_Maint_Redirect" ) ? ' <a href="'.admin_url( 'options-general.php?page=JF3_Maint_Redirect' ).'">'.__( 'Settings' ).'</a>' : '';
				echo '<div class="error" id="wpjf3_mr_enabled_notice"><p><strong>' . sprintf( /* translators: %s = "Maintenance Redirect", the name of the plugin, */ esc_html__( "%s is Enabled" ), "Maintenance Redirect" ). '</strong>'.$settingslink.'</p></div>'; 
			}
		}
		
		/** (php) add status to admin bar
		 * 
		 * @param WP_Admin_Bar $admin_bar An instance of the WP_Admin_Bar class.
		 */
		
		function adminbar_site_status( WP_Admin_Bar $admin_bar ) {
			if ( current_user_can( 'manage_options' ) ) {
				$wpjf3_mr_options = $this->get_admin_options();
				$show_notice      = false;

				if ( $wpjf3_mr_options['enable_redirect'] == 'YES' ) $show_notice = true;
				if ( isset( $_POST['update_wp_maintenance_redirect_settings'] ) && $_POST['wpjf3_mr_enable_redirect'] == 'YES' ) $show_notice = true;
				if ( isset( $_POST['update_wp_maintenance_redirect_settings'] ) && $_POST['wpjf3_mr_enable_redirect'] == 'NO'  ) $show_notice = false;
			
				if ( $show_notice ){
					$site_status_menu = array(
						'id'     => 'wpjf3-status',
						'parent' => 'top-secondary',
						'href'   => admin_url( 'options-general.php?page=JF3_Maint_Redirect' ),
						'title'  => '<div style="background-color:#E01C1C;color:white; padding: 0 16px;">' . esc_html__( 'Redirect Enabled' ) . '</div>',
						'meta'   => array(
							'title' => sprintf( /* translators: %s = "Maintenance Redirect", the name of the plugin, */ esc_attr__( "%s is Enabled" ), "Maintenance Redirect" ),
						),
					);
					$admin_bar->add_menu( $site_status_menu );
				}
			}
		}
		
		// (php) add settings link to plugin page
		function plugin_settings_link( $links ) { 
			$settings_link = '<a href="'.admin_url( 'options-general.php?page=JF3_Maint_Redirect' ).'">'.__( 'Settings' ).'</a>'; 
			array_unshift($links, $settings_link); 
			return $links; 
		}
		
		// (php) add coffee link to plugin page
		function plugin_info_link( $links_array, $plugin_file_name, $plugin_data, $status ) {
			
			if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
				$links_array[] = '<a href="https://paypal.me/fabulosawebdesigns" target="_blank">' . esc_html__( "Buy me a coffee!" ) . '</a>';
			}

			return $links_array;
		}
		
		// (php) site health stuff
		
		function wpjf3_add_site_health( $tests ) {
			$tests['direct']['wpjf3_status'] = array(
				'label' => __( 'Maintenance Redirect' ),
				'test'  => array( $this, 'wpjf3_site_health' ),
			);
			return $tests;
		}

		function wpjf3_site_health() {
			$wpjf3_mr_options = $this->get_admin_options();
			
			$result = array(
				'label'       => __( 'Maintenance Redirect is not enabled' ),
				'status'      => 'good',
				'badge'       => array(
					'label' => __( 'Visibility' ),
					'color' => 'blue',
				),
				'description' => sprintf(
					'<p>%s</p>',
					__( 'Maintenance Redirect is not enabled and your site is visible to visitors.' )
				),
				'actions'     => sprintf(
					'<p><a href="options-general.php?page=JF3_Maint_Redirect">%s</a></p>',
					__( 'Settings' )
				),
				'test'        => 'wpjf3_status',
			);

			if ( $wpjf3_mr_options['enable_redirect'] == 'YES' ) {
			
				if ( $wpjf3_mr_options['header_type'] == "200" or $wpjf3_mr_options['method'] == "redirect" ) {
					$result['status'] = 'recommended';
					$result['label'] = __( 'Maintenance Redirect is enabled' );
					$result['description'] = sprintf(
						'<p>%s</p>',
						__( 'Maintenance Redirect is enabled and your site is not visible to visitors.' )
					);
				} else {
					$result['status'] = 'critical';
					$result['badge']['color'] = 'red';
					$result['label'] = __( 'Maintenance Redirect is enabled' );
					$result['description'] = sprintf(
						'<p>%s</p>',
						__( 'Maintenance Redirect is enabled and your site is not visible to visitors. Your redirection type is set to 503, which could harm your Google ranking if left on for any length of time.' )
					);
				}
				
			}
			return $result;
		}

		// (php) create the admin page
		function print_admin_page() {
			global $wpdb;
			global $ajax_nonce;
			
			$wpjf3_mr_options = $this->get_admin_options();

			// process update
			if ( isset( $_POST[ 'update_wp_maintenance_redirect_settings' ] ) ) {
			
				check_admin_referer( 'wpjf3_nonce' );  
				
				// prepare options
				$wpjf3_mr_options[ 'enable_redirect' ]     = sanitize_text_field( trim( $_POST[ 'wpjf3_mr_enable_redirect' ] ) );
				$wpjf3_mr_options[ 'header_type' ]         = sanitize_text_field( trim( $_POST[ 'wpjf3_mr_header_type' ] ) );
				$wpjf3_mr_options[ 'method' ]              = sanitize_text_field( trim( $_POST[ 'wpjf3_mr_method' ] ) );
				$wpjf3_mr_options[ 'maintenance_message' ] = wp_kses_post( $_POST[ 'wpjf3_mr_maintenance_message' ] );
				$wpjf3_mr_options[ 'static_page' ]         = esc_url_raw( trim( $_POST[ 'wpjf3_mr_static_page' ] ) );
				$wpjf3_mr_options[ 'maintenance_html' ]    = trim( $_POST['wpjf3_mr_maintenance_html'] );
				
				$wpjf3_mr_options[ 'active_tab' ]          = sanitize_text_field( trim( $_POST[ 'wpjf3_mr_active_tab' ] ) );
				$wpjf3_mr_options[ 'hide_coffee' ] = false;
				if ( array_key_exists( 'wpjf3_mr_hide_coffee', $_POST ) ) 
					if ( $_POST[ 'wpjf3_mr_hide_coffee' ] == "yes" ) $wpjf3_mr_options[ 'hide_coffee' ] = true;
				$wpjf3_mr_options[ 'uninstall' ] = false;
				if ( array_key_exists( 'wpjf3_mr_uninstall', $_POST ) ) 
					if ( $_POST[ 'wpjf3_mr_uninstall' ] == "yes" ) $wpjf3_mr_options[ 'uninstall' ] = true;
				
				// update options
				update_option( $this->admin_options_name, $wpjf3_mr_options );
				
				//Process plugin update request
				if ( array_key_exists( 'wpjf3_mr_run_updater', $_POST ) ) { 
					if ( $_POST[ 'wpjf3_mr_run_updater' ] === "yes" ) { 

						$this->wpjf3_mr_process_updater();
						$wpjf3_mr_options = $this->get_admin_options();

					}
				}
				
				echo '<div class="updated"><p><strong>' . __( "Settings Updated" ) . '</strong></p></div>';
				
			}

			$wpjf3_mr_options = $this->get_admin_options();

?>
			
			<script type="text/javascript" charset="utf-8">
				// bind actions
				jQuery( document ).ready( function( $ ) {
					// enable disable toggle
					$( 'input:radio[name="wpjf3_mr_enable_redirect"]' ).change( function(){ wpjf3_mr_toggle_main_options(); });
					// method mode toggle
					$( '#wpjf3_mr_method' ).change( function(){ wpjf3_mr_toggle_method_options(); });
					// Tabs
					$( '#tabs-nav li a' ).click( function(){
						$( '#tabs-nav li a' ).removeClass( 'active' );
						$( this ).addClass( 'active' );
						$( '.tab-content' ).hide();

						var active_tab = $( this ).attr( 'href' );
						$( active_tab ).fadeIn();
						$( '#wpjf3_mr_active_tab' ).val( active_tab );
						return false;
					});

				});
				
				// (js) sleep time expects milliseconds
				function wpjf3_sleep( time ) {
					return new Promise( ( resolve ) => setTimeout( resolve, time ) );
				}
				
				// (js) active tab
				<?php $active_tab = $wpjf3_mr_options[ 'active_tab' ] ? $wpjf3_mr_options[ 'active_tab' ] : "#about";
			
					if ( isset( $_POST[ 'wpjf3_mr_active_tab' ] ) ) 
						$active_tab = sanitize_text_field( trim( $_POST[ 'wpjf3_mr_active_tab' ] ) ); 
								
				?>
				jQuery( document ).ready( function() {
					jQuery( "#tabs-nav a" ).removeClass( "active" );
					jQuery( ".tab-content" ).hide();
					jQuery( "a[href='<?php echo $active_tab; ?>']").addClass( "active" );
					jQuery( "<?php echo $active_tab; ?>" ).show();
				});
				
				// (js) update form layout based on main option
				function wpjf3_mr_toggle_main_options () {
					if( jQuery( ".enable-button:checked" ).val() == 'YES' ){
						jQuery( "#wpjf3_main_options" ).slideDown( 'fast' );
					}else{
						jQuery( "#wpjf3_main_options" ).slideUp( 'fast' );
					}
				}
				
				// (js) update form layout based on method option
				function wpjf3_mr_toggle_method_options () {
					jQuery( ".wpjf3_method_input" ).hide();
					jQuery( "#wpjf3_method_"+jQuery( "#wpjf3_mr_method" ).val() ).show();
				}
				
				// (js) undim field
				function wpjf3_mr_undim_field( field_id, default_text ) {
					if( jQuery('#'+field_id).val() == default_text ) jQuery('#'+field_id).val('');
					jQuery('#'+field_id).css('color','#000');
				}
				// (js) dim field
				function wpjf3_mr_dim_field( field_id, default_text ) {
					if( jQuery('#'+field_id).val() == '' ) {
						jQuery('#'+field_id).val(default_text);
						jQuery('#'+field_id).css('color','#888');
					}
				}
				
				// (js) validate IP4 address
				function ValidateIPaddress(ipaddress) {  
					if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|\*))$/.test(ipaddress)) {  
						return (true)  
					}  
				}
				
				// (js) add new IP
				function wpjf3_mr_add_new_ip () {
					// validate entries before posting ajax call
					var error_msg = '';
					if ( jQuery('#wpjf3_mr_new_ip_name').val() == '' ) 
						error_msg += '<?php echo esc_js( __( "You must enter a Name" ) ); ?>.\n';
					if ( jQuery('#wpjf3_mr_new_ip_name').val() == '<?php echo esc_js( __( "Enter Name:" ) ); ?>' ) 
						error_msg += '<?php echo esc_js( __( "You must enter a Name" ) ); ?>.\n';
					if ( jQuery('#wpjf3_mr_new_ip_ip'  ).val() == '' ) 
						error_msg += '<?php echo esc_js( __( "You must enter an IP" ) ); ?>.\n';
					if ( jQuery('#wpjf3_mr_new_ip_ip'  ).val() == '<?php echo esc_js( __( "Enter IP:" ) ); ?>'   ) 
						error_msg += '<?php echo esc_js( __( "You must enter an IP" ) ); ?>.\n';
					if ( ValidateIPaddress( jQuery('#wpjf3_mr_new_ip_ip'  ).val() ) != true ) 
						error_msg += '<?php echo esc_js( __( "IP address not valid" ) ); ?>.\n';
					if ( error_msg != '' ) {
						alert( '<?php echo esc_js( __( "There is a problem with the information you have entered" ) ); ?>.\n\n' + error_msg );
					} else {
						// prepare ajax data
						var data = {
							action:		'wpjf3_mr_add_ip',
							security:		'<?php echo $ajax_nonce; ?>',
							wpjf3_mr_ip_name:	jQuery('#wpjf3_mr_new_ip_name').val(),
							wpjf3_mr_ip_ip:	jQuery('#wpjf3_mr_new_ip_ip').val() 
						};
						
						// set section to loading img
						var img_url = '<?php echo plugins_url( 'images/ajax_loader_16x16.gif', __FILE__ ); ?>';
						jQuery( '#wpjf3_mr_ip_tbl_container' ).html('<img src="' + img_url + '">');
						
						// send ajax request
						jQuery.post( ajaxurl, data, function(response) {
							jQuery('#wpjf3_mr_ip_tbl_container').html( response );
						});
					}
				}
				
				// (js) toggle IP status
				function wpjf3_mr_toggle_ip ( status, ip_id ) {
					// prepare ajax data
					var data = {
						action:             	'wpjf3_mr_toggle_ip',
						security:			'<?php echo $ajax_nonce; ?>',
						wpjf3_mr_ip_active: 	status,
						wpjf3_mr_ip_id:     	ip_id 
					};
					
					// (js) set status to loading img
					var img_url = '<?php echo plugins_url( 'images/ajax_loader_16x16.gif', __FILE__ ); ?>';
					jQuery( '#wpjf3_mr_ip_status_' + ip_id ).html('<img src="' + img_url + '">');
					
					// send ajax request
					jQuery.post( ajaxurl, data, function(response) {
						var split_response = response.split('|');
						if( split_response[0] == 'SUCCESS' ){
							var ip_id     = split_response[1];
							var ip_active = split_response[1];
							// update divs / 1 = id / 2 = status
							if( split_response[2] == '1' ){
								// active
								jQuery('#wpjf3_mr_ip_status_' + split_response[1] ).html( 'Yes' );
								jQuery('#wpjf3_mr_ip_status_' + split_response[1] + '_action' ).html( '<a href="javascript:wpjf3_mr_toggle_ip( 0, ' + split_response[1] + ' );"><?php echo esc_js( __( "Disable" ) ); ?></a> | ' );
							}else{
								// disabled
								jQuery('#wpjf3_mr_ip_status_' + split_response[1] ).html( 'No' );
								jQuery('#wpjf3_mr_ip_status_' + split_response[1] + '_action' ).html( '<a href="javascript:wpjf3_mr_toggle_ip( 1, ' + split_response[1] + ' );"><?php echo esc_js( __( "Enable" ) ); ?></a> | ' );
							} 
						}else{
							alert( '<?php echo esc_js( __( "There was a database error. Please reload this page" ) ); ?>' );
						}
					});
				}
				
				// (js) delete IP
				function wpjf3_mr_delete_ip ( ip_id, ip_addr ) {
					if ( confirm('<?php echo esc_js( __( "You are about to delete the IP address:" ) ); ?>\n\n\'' + ip_addr + '\'\n\n') ) {
						// prepare ajax data
						var data = {
							action:		'wpjf3_mr_delete_ip',
							security:		'<?php echo $ajax_nonce; ?>',
							wpjf3_mr_ip_id:   ip_id
						};
						
						// set section to loading img
						var img_url = '<?php echo plugins_url( 'images/ajax_loader_16x16.gif', __FILE__ ); ?>';
						jQuery( '#wpjf3_mr_ip_tbl_container' ).html( '<img src="' + img_url + '">' );
						
						// send ajax request
						jQuery.post( ajaxurl, data, function(response) {
							jQuery( '#wpjf3_mr_ip_tbl_container' ).html( response );
						});
					}
				}
				
				// (js) add new Access Key
				function wpjf3_mr_add_new_ak () {
					// validate entries before posting ajax call
					var error_msg = '';
					if ( jQuery( '#wpjf3_mr_new_ak_name' ).val() == '' ) 
						error_msg += '<?php echo esc_js( __( "You must enter a Name" ) ); ?>.\n';
					if ( jQuery( '#wpjf3_mr_new_ak_name' ).val() == '<?php echo esc_js( __( "Enter Name:" ) ); ?>' ) 
						error_msg += '<?php echo esc_js( __( "You must enter a Name" ) ); ?>.\n';
					if ( jQuery( '#wpjf3_mr_new_ak_email' ).val() == '' ) 
						error_msg += '<?php echo esc_js( __( "You must enter an Email" ) ); ?>.\n';
					if ( jQuery( '#wpjf3_mr_new_ak_email' ).val() == '<?php echo esc_js( __( "Enter Email:" ) ); ?>' ) 
						error_msg += '<?php echo esc_js( __( "You must enter an Email" ) ); ?>.\n';
					if ( error_msg != '' ) {
						alert( '<?php echo esc_js( __( "There is a problem with the information you have entered" ) ); ?>.\n\n' + error_msg );
					} else {
						// prepare ajax data
						var data = {
							action:		'wpjf3_mr_add_ak',
							security:		'<?php echo $ajax_nonce; ?>',
							wpjf3_mr_ak_name:  jQuery( '#wpjf3_mr_new_ak_name' ).val(),
							wpjf3_mr_ak_email: jQuery( '#wpjf3_mr_new_ak_email' ).val() 
						};

						// set section to loading img
						var img_url = '<?php echo plugins_url( 'images/ajax_loader_16x16.gif', __FILE__ ); ?>';
						jQuery( '#wpjf3_mr_ak_tbl_container' ).html('<img src="' + img_url + '">');

						// send ajax request
						jQuery.post( ajaxurl, data, function(response) {
							jQuery('#wpjf3_mr_ak_tbl_container').html( response );
						});
					}
				}

				// (js) toggle Access Key status
				function wpjf3_mr_toggle_ak ( status, ak_id ) {
					// prepare ajax data
					var data = {
						action:			'wpjf3_mr_toggle_ak',
						security:			'<?php echo $ajax_nonce; ?>',
						wpjf3_mr_ak_active: 	status,
						wpjf3_mr_ak_id:     	ak_id 
					};

					// set status to loading img
					var img_url = '<?php echo plugins_url( 'images/ajax_loader_16x16.gif', __FILE__ ); ?>';
					jQuery( '#wpjf3_mr_ak_status_' + ak_id ).html('<img src="' + img_url + '">');

					// send ajax request
					jQuery.post( ajaxurl, data, function(response) {
						var split_response = response.split('|');
						if( split_response[0] == 'SUCCESS' ){
							var ak_id     = split_response[1];
							var ak_active = split_response[1];
							// update divs / 1 = id / 2 = status
							if( split_response[2] == '1' ){
								// active
								jQuery( '#wpjf3_mr_ak_status_' + split_response[1] ).html( 'Yes' );
								jQuery( '#wpjf3_mr_ak_status_' + split_response[1] + '_action' ).html( '<a href="javascript:wpjf3_mr_toggle_ak( 0, ' + split_response[1] + ' );"><?php echo esc_js( __( "Disable" ) ); ?></a> | ' );
							}else{
								// disabled
								jQuery('#wpjf3_mr_ak_status_' + split_response[1] ).html( 'No' );
								jQuery('#wpjf3_mr_ak_status_' + split_response[1] + '_action' ).html( '<a href="javascript:wpjf3_mr_toggle_ak( 1, ' + split_response[1] + ' );"><?php echo esc_js( __( "Enable" ) ); ?></a> | ' );
							} 
						}else{
							alert( '<?php echo esc_js( __( "There was a database error. Please reload this page" ) ); ?>' );
						}
					});
				}

				// (js) delete Access Key
				function wpjf3_mr_delete_ak ( ak_id, ak_name ) {
					if ( confirm('<?php echo esc_js( __( "You are about to delete this Access Key:" ) ); ?>\n\n\'' + ak_name + '\'\n\n') ) {
						// prepare ajax data
						var data = {
							action:		'wpjf3_mr_delete_ak',
							security:		'<?php echo $ajax_nonce; ?>',
							wpjf3_mr_ak_id:	ak_id
						};

						// set section to loading img
						var img_url = '<?php echo plugins_url( 'images/ajax_loader_16x16.gif', __FILE__ ); ?>';
						jQuery( '#wpjf3_mr_ak_tbl_container' ).html('<img src="' + img_url + '">');

						// send ajax request
						jQuery.post( ajaxurl, data, function(response) {
							jQuery('#wpjf3_mr_ak_tbl_container').html( response );
						});
					}
				}
				
				// (js) re-send Access Key
				function wpjf3_mr_resend_ak ( ak_id, ak_name, ak_email ) {
					if ( confirm('<?php echo esc_js( __( "You are about to email an Access Key link to " ) ); ?>' + ak_email + '\n\n') ) {
						// prepare ajax data
						var data = {
							action:		'wpjf3_mr_resend_ak',
							security:		'<?php echo $ajax_nonce; ?>',
							wpjf3_mr_ak_id:	ak_id
						};
						
						// send ajax request
						jQuery.post( ajaxurl, data, function(response) {
							if( response == 'SEND_SUCCESS' ){
								alert( '<?php echo esc_js( __( "Notification Sent." ) ); ?>' );
							}else{
								alert( '<?php echo esc_js( __( "Notification Failure. Please check your server settings." ) ); ?>' );
							}
						});
					}
				}
			
				// (js) copy Access Key
				function wpjf3_mr_copy_ak ( ak_id, ak_code ) {
					navigator.clipboard.writeText( '<?php echo addslashes( get_bloginfo('url') ); ?>?wpjf3_mr_temp_access_key=' + ak_code );
					var savedTxt = document.getElementById( 'submit_copy_' + ak_id ).innerHTML;
					document.getElementById( 'submit_copy_' + ak_id ).innerHTML = '<span style="color:green"><?php esc_html_e( 'Copied' ); ?></span>';
					wpjf3_sleep( 5000 ).then(() => {
						document.getElementById( 'submit_copy_' + ak_id ).innerHTML = savedTxt;
					});
				}
			
			</script>
			
			<style type="text/css" media="screen">
				.wpjf3_mr_admin_section    { border: 1px solid #ddd; padding: 16px; margin: 0; }
				.wpjf3_mr_disabled_field   { color: #888;	}
				.wpjf3_mr_small_dim        { font-size: 11px; font-weight: normal; color: #444; }
				.wpjf3_mr_admin_section dt { font-weight: bold; }
				.wpjf3_mr_admin_section dd { margin-left: 0; }
				.wpjf3_mr_admin_section h3 { margin-top: 0; }
				.column-wpjf3-ak-active    { width: 100px; }
				input[type="text"],
				input[type="email"]        { width: 100%; }
				.wpjf3_mr_admin_section a  { cursor: pointer; }
				
				/* Tabs */
				.tabs {
					width: 100%;
					background-color: transparent;
					margin: 2em 0;
				}
				#tabs-nav {
					list-style: none;
					margin: 0;
					padding: 0;
					display: flex;
					gap: 8px;
				}
				#tabs-nav li {
					display: block;
					margin: 0;
				}
				#tabs-nav li a {
					display: block;
					padding: 8px 10px;
					border-radius: 8px 8px 0 0;
					cursor: pointer;
					text-decoration: none;
					background-color: #dcdcde;
					color: #3c434a;
				}
				#tabs-nav li a:hover,
				#tabs-nav li a.active {
					background-color: #3c434a;
					color: white;
				}
				.tab-content {
				}
				
				@media screen and ( max-width: 600px ) {
					#tabs-nav { display: none; }
					.tab-content { display: block !important; margin-bottom: 16px; }
					.about-contents { flex-direction: column; }
					.about-contents input[type=checkbox] + label { width: calc( 100% - 30px ); }
					.tab-content select { width: 100% !important; }
					.wpjf3-table thead { 
						position: absolute;
						top: -9999px;
						left: -9999px;
					}
					.wpjf3-table, .wpjf3-table tbody, .wpjf3-table tr, .wpjf3-table td {
						display: block;
					}
					.wpjf3-table td {
						border: none;
						position: relative;
						padding-left: 30%;
					}
					.wpjf3-table td:before { 
						display: block;
						position: absolute;
						left: 0; top: 0;
						width: 30%; 
						padding: 8px 10px; 
						white-space: nowrap;
					}
					.wpjf3-table td.column-wpjf3-ip-name:before { content: "<?php esc_html_e( "Name" ); ?>"; }
					.wpjf3-table td.column-wpjf3-ip-ip:before { content: "<?php esc_html_e( "IP" ); ?>"; }
					.wpjf3-table td.column-wpjf3-ip-active:before { content: "<?php esc_html_e( "Active" ); ?>"; }
					.wpjf3-table td.column-wpjf3-actions:before { content: "<?php esc_html_e( "Actions" ); ?>"; }
					.wpjf3-table td.column-wpjf3-ak-name:before { content: "<?php esc_html_e( "Name" ); ?>"; }
					.wpjf3-table td.column-wpjf3-ak-email:before { content: "<?php esc_html_e( "Email" ); ?>"; }
					.wpjf3-table td.column-wpjf3-ak-key:before { content: "<?php esc_html_e( "Access Key" ); ?>"; }
					.wpjf3-table td.column-wpjf3-ak-active:before { content: "<?php esc_html_e( "Active" ); ?>"; }
					#wpjf-ip-NEW td.column-wpjf3-ip-active, #wpjf-ak-NEW td.column-wpjf3-ak-active, #wpjf-ak-NEW td.column-wpjf3-ak-key { display: none; }
					#wpjf-ip-NEW input, #wpjf-ak-NEW input { width: auto; }

				}
				
				/* Switch */
				
				.switch-field {
					display: flex;
					margin-bottom: 36px;
					overflow: hidden;
				}

				.switch-field input {
					position: absolute !important;
					clip: rect(0, 0, 0, 0);
					height: 1px;
					width: 1px;
					border: 0;
					overflow: hidden;
				}

				.switch-field label {
					background-color: #e4e4e4;
					color: rgba(0, 0, 0, 0.6);
					font-size: 14px;
					line-height: 1;
					text-align: center;
					padding: 8px 16px;
					margin-right: -1px;
					border: 1px solid rgba(0, 0, 0, 0.2);
					box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
					transition: all 0.1s ease-in-out;
				}

				.switch-field label:hover {
					cursor: pointer;
				}

				.switch-field #radio-yes:checked + label {
					background-color: #a5dc86;
					box-shadow: none;
				}

				.switch-field #radio-no:checked + label {
					background-color: #dc8686;
					box-shadow: none;
				}

				.switch-field label:first-of-type {
					border-radius: 4px 0 0 4px;
				}

				.switch-field label:last-of-type {
					border-radius: 0 4px 4px 0;
				}
				
				/* About */
				.about-contents {
					display: flex;
					gap: 16px;
				}
				
				.about-right {
					max-width: 400px;
				}
				
				.coffee-panel {
					padding: 16px;
					background-color: white;
				}
				
				.about-contents input[type=checkbox] + label {
					display: inline-block;
					width: calc( 100% - 20px );
				}

				#about input[type=checkbox] {
					vertical-align: top;
					margin-top: 2px;
				}
					
			</style>
			
			<div class="wrap">
				<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" onsubmit="return wpjf3_mr_validate_form();">
					<h2>Maintenance Redirect</h2>
					
					<h3><?php esc_html_e( "Enable Maintenance Mode:" ); ?></h3>
					
					<div class="switch-field">
						<input type="radio" id="radio-yes" name="wpjf3_mr_enable_redirect" class="enable-button" value="YES" <?php if( $wpjf3_mr_options['enable_redirect'] == "YES" ) echo "checked"; ?> />
						<label for="radio-yes">On</label>
						<input type="radio" id="radio-no" name="wpjf3_mr_enable_redirect" class="enable-button" value="NO" <?php if( $wpjf3_mr_options['enable_redirect'] == "NO" ) echo "checked"; ?> />
						<label for="radio-no">Off</label>
					</div>

					<div id="wpjf3_main_options" <?php if( $wpjf3_mr_options['enable_redirect'] == "NO" ) echo 'style="display:none;"'; ?> class="tabs">

						<ul id="tabs-nav">
							<li><a href="#header-type"><?php esc_html_e( 'Header Type' ); ?></a></li>
							<li><a href="#unrestricted-ip"><?php esc_html_e( 'Unrestricted IP addresses' ); ?></a></li>
							<li><a href="#access-keys"><?php esc_html_e( 'Access Keys' ); ?></a></li>
							<li><a href="#message"><?php esc_html_e( 'Maintenance Message' ); ?></a></li>
							<li><a href="#about"><?php esc_html_e( 'About & Options' ); ?></a></li>
						</ul> <!-- END tabs-nav -->

						<div id="header-type" class="wpjf3_mr_admin_section tab-content">
							<h3><?php esc_html_e( "Header Type:" ); ?></h3>
							<p><?php esc_html_e( "When redirect is enabled we can send 2 different header types:" ); ?> </p>
							<dl>
								<dt><?php esc_html_e( '200 OK' ); ?></dt>
								<dd><?php esc_html_e( "Best used for when the site is under development." ); ?></dd>
								<dt><?php esc_html_e( '503 Service Temporarily Unavailable' ); ?></dt> 
								<dd><?php esc_html_e( "Best for when the site is temporarily taken offline for small amendments." ); ?> <em><?php esc_html_e( "If used for a long period of time, 503 can damage your Google ranking." ); ?></em></dd>
							</dl>
							<p><?php echo sprintf( esc_html__( /* translators: %s = separately translated text for "307 Temporary Redirect" */ 'Note: When "Redirect" is selected under "Maintenance Message", this setting will be ignored and %s used instead' ), '<strong>' . esc_html__( '307 Temporary Redirect' ) . '</strong>' ); ?></p>
							<select name="wpjf3_mr_header_type" id="wpjf3_mr_header_type" style="width:30%" >
								<option value="200" <?php if( $wpjf3_mr_options['header_type'] == "200" ) echo "selected"; ?>><?php esc_html_e( '200 OK' ); ?></option>
								<option value="503" <?php if( $wpjf3_mr_options['header_type'] == "503" ) echo "selected"; ?>><?php esc_html_e( '503 Service Temporarily Unavailable' ); ?></option>
							</select>
						</div>

						<div id="unrestricted-ip" class="wpjf3_mr_admin_section tab-content">
							<h3><?php esc_html_e( "Unrestricted IP addresses:" ); ?></h3>
							<p><?php esc_html_e( "Users with unrestricted IP addresses will bypass maintenance mode entirely. Using this option is useful to allow an entire office of clients to view the site without needing to jump through any extra hoops." ); ?></p> 
							<p><?php esc_html_e( "Your IP address is:" ); ?>&nbsp;<a id="wpjf3_set_ip"><?php echo $this->get_user_ip(); ?></a> - <?php esc_html_e( "Your Class C is:" ); ?>&nbsp;<a id="wpjf3_set_ip_c"><?php echo $this->get_user_class_c(); ?></a></p>
							<script>
								jQuery( "#wpjf3_set_ip" ).click( function(){ jQuery( "#wpjf3_mr_new_ip_ip" ).val( "<?php echo $this->get_user_ip(); ?>" )});
								jQuery( "#wpjf3_set_ip_c" ).click( function(){ jQuery( "#wpjf3_mr_new_ip_ip" ).val( "<?php echo $this->get_user_class_c(); ?>" )});
							</script>
							<div id="wpjf3_mr_ip_tbl_container">
								<?php $this->print_unrestricted_ips(); ?>
							</div>
						</div>

						<div id="access-keys" class="wpjf3_mr_admin_section tab-content">
							<h3><?php esc_html_e( "Access Keys:" ); ?></h3>
							<p><?php esc_html_e( "You can allow users temporary access by sending them the access key. When a new key is created, a link to create the access key cookie will be emailed to the email address provided. Access can then be revoked either by disabling or deleting the key." ); ?></p>

							<div id="wpjf3_mr_ak_tbl_container">
								<?php $this->print_access_keys(); ?>
							</div>
						</div>

						<div id="message" class="wpjf3_mr_admin_section tab-content">	
							<h3><?php esc_html_e( "Maintenance Message:" ); ?></h3>
							<p><?php esc_html_e( "You have three options for how to specify what you want to show users when your site is in maintenance mode. You can display a message, display a static HTML page (which you enter into the box below), or redirect to an existing static HTML page (the file of which must exist on your server)." ); ?></p>
							<p><select name="wpjf3_mr_method" id="wpjf3_mr_method" style="width:50%" >
								<option value="message" <?php if( $wpjf3_mr_options['method'] == "message" ) echo "selected"; ?> ><?php esc_html_e( "Message Only (the easiest option)" ); ?></option>
								<option value="redirect" <?php if( $wpjf3_mr_options['method'] == "redirect" ) echo "selected"; ?> ><?php esc_html_e( "Redirect (a little harder)" ); ?></option>
								<option value="html" <?php if( $wpjf3_mr_options['method'] == "html" ) echo "selected"; ?> ><?php esc_html_e( "HTML Entered Here (best for web developers)" ); ?></option>
							</select></p>

							<div id="wpjf3_method_message" class="wpjf3_method_input" style="<?php if( $wpjf3_mr_options['method'] != "message" ) echo "display:none;"; ?>" >
								<strong><?php esc_html_e( "Maintenance Mode Message:" ); ?></strong>
								<p><?php esc_html_e( "This is the message that will be displayed while your site is in maintenance mode." ); ?></p>
								<p style="margin-bottom: 0;"><input name="wpjf3_mr_maintenance_message" type="text" style="width:100%" value="<?php echo esc_attr( stripslashes( $wpjf3_mr_options['maintenance_message'] ) ); ?>"></p>
							</div>

							<div id="wpjf3_method_redirect" class="wpjf3_method_input" style="<?php if( $wpjf3_mr_options['method'] != "redirect" ) echo "display:none;"; ?>" >
								<strong><?php esc_html_e( "Static Maintenance URL:" ); ?></strong>
								<p><?php esc_html_e( "To use this method you need to upload a static HTML page to your site and enter it's URL below, or paste the URL of a different site to redirect to. Do not paste a URL for a WordPress page or post on this site as that will cause an infinite redirection loop." ); ?></p>
								<p><input type="text" name="wpjf3_mr_static_page" value="<?php echo $wpjf3_mr_options['static_page']; ?>" id="wpjf3_mr_static_page" style="width:100%"></p>
							</div>
							
							<div id="wpjf3_method_html" class="wpjf3_method_input" style="<?php if( $wpjf3_mr_options['method'] != "html" ) echo "display:none;"; ?>" >
								<strong><?php esc_html_e( "Maintenance Mode HTML:" ); ?></strong>
								<p><?php esc_html_e( "Paste the full HTML for the page to be displayed." ); ?></p>
								<p style="margin-bottom: 0;"><textarea name="wpjf3_mr_maintenance_html" rows="10" style="width:100%"><?php echo stripslashes( $wpjf3_mr_options['maintenance_html'] ); ?></textarea></p>
							</div>


						</div>
						<div id="about" class="wpjf3_mr_admin_section tab-content">	
							<div class="about-contents">
								<div class="about-left">
									<h3><?php esc_html_e( "About:" ); ?></h3>
									<p><?php esc_html_e( "This plugin is intended primarily for designers / developers that need to allow clients to preview sites before being available to the general public. Any logged in user with WordPress administrator privileges will be allowed to view the site regardless of the settings above." ); ?></p>
									<p><strong><?php esc_html_e( "Note:" ); ?></strong> <em><?php esc_html_e( "This plugin is designed to block only the normal display of pages in the web browser. It will not effect any other calls to WordPress, such as the Rest API. If you wish to completely lock down your site's data then you will need to find an additional solution to block those calls." ); ?></em></p>
									<p><?php esc_html_e( "More information can be found on the plugin's WordPress repository page." ); ?> <a href="https://wordpress.org/plugins/jf3-maintenance-mode/" target="_blank"><?php esc_html_e( "Click here" ); ?></a></p>
									<h3><?php esc_html_e( "History:" ); ?></h3>
									<p><?php esc_html_e( "The plugin was originally created by a developer named Jack Finch back in 2010, and it quickly became my go-to option for developing new sites, allowing me to easily show a holding page to casual visitors, whilst giving an access key to the client so that they could preview the site before going live. " ); ?></p>
									<p><?php esc_html_e( "However, in late 2017 it became apparent that Jack had abandoned the project, and the plugin was in danger of being removed from the repository as it was out of date and the code was not in keeping with best security practices. So, I tried to contact Jack with an offer to take it over and, having received no reply after 3 months, I then contacted the WordPress repository moderators and they handed it over to me. Following a baptism-by-fire crash-course in making WordPress plugins compliant with said best security practices, I released version 1.3 in March 2018." ); ?></p>
									
									<h3><?php esc_html_e( "Changelog:" ); ?></h3>
									<p><?php esc_html_e( "You can find the changelog on the plugin's WordPress repository page." ); ?> <a href="https://wordpress.org/plugins/jf3-maintenance-mode/#developers" target="_blank"><?php esc_html_e( "Click here" ); ?></a></p>
									
									<h3><?php esc_html_e( "Further Options:" ); ?></h3>
									<p><input type="checkbox" id="wpjf3_mr_hide_coffee" name="wpjf3_mr_hide_coffee" value="yes" <?php if( $wpjf3_mr_options['hide_coffee'] ) echo "checked"; ?>><label for="wpjf3_mr_hide_coffee"><?php esc_html_e( 'Hide the "buy me a coffee" panel' ); ?></label></p>
									<p><input type="checkbox" id="wpjf3_mr_uninstall" name="wpjf3_mr_uninstall" value="yes" <?php if( $wpjf3_mr_options['uninstall'] ) echo "checked"; ?>><label for="wpjf3_mr_uninstall"><?php esc_html_e( 'Delete all options next time this plugin is deleted' ); ?><br/><span class="wpjf3_mr_small_dim"><?php esc_html_e( "Ticking this will do nothing now, but when you next delete the plugin using the Plugins screen it will delete all options, IP addresses and Access Keys. However, nothing will be deleted if you just deactivate the plugin." ); ?> <em><?php esc_html_e( "NB: You will not be asked again, so be careful!" ); ?></em><br/><em><?php esc_html_e( "One further note - to delete the IP addresses and Access Keys your hosting account has to allow full access privileges to the database. If it doesn't then they won't be deleted and you may see an error along the lines of \"DROP command denied to user\". If that happens you should contact your hosting company." ); ?></em></span></label></p>
								</div>
								<?php if ( ! $wpjf3_mr_options[ 'hide_coffee' ] ) { ?>
								<div class="about-right">
									<div class="coffee-panel">
										<h3><?php esc_html_e( "Buy me a coffee!" ); ?></h3>
										<p><?php esc_html_e( "As I did not originate this plugin, I do not ever intend to monetise it - I will never ask you to upgrade to a \"pro\" version or bombard you with annoying upsells." ); ?></p>
										<p><?php esc_html_e( "That said, I have been asked if it's possible to \"buy me a coffee\" by way of saying thanks for my time and effort in maintaining it." ); ?></p>
										<p><?php esc_html_e( "So, if you feel so inclined, I would greatly appreciate any contribution you may care to make to help me keep Maintenance Redirect updated and, above all, free." ); ?></p>
										<a href="https://paypal.me/fabulosawebdesigns" class="button" target="_blank"><?php echo "\u{2615}&nbsp;&nbsp;" . esc_html__( "Buy me a coffee!" ); ?></a>
										<p style="margin-bottom: 0;"><?php echo esc_html__( "Many thanks" ) , "<br/>Peter"; ?></p>
									</div>
									<p><?php echo esc_html__( "Plugin settings version: " ) . get_option( "wpjf3_maintenance_redirect_version" ) . ". "; ?></p>
									<?php if ( get_option( "wpjf3_maintenance_redirect_version" ) !== WPJF3_VERSION ) { ?>
											<p><input type="checkbox" id="wpjf3_mr_run_updater" name="wpjf3_mr_run_updater" value="yes"><label for="wpjf3_mr_run_updater"><?php esc_html_e( "Settings updater didn't run. To run the updater, tick the box and click the Update Settings button" ); ?></label></p>
									<?php } ?>
									
								</div>
								<?php } ?>
							</div>
						</div>

					</div>

					<input type="hidden" id="wpjf3_mr_active_tab" name="wpjf3_mr_active_tab" value="<?php echo $wpjf3_mr_options[ 'active_tab' ] ? $wpjf3_mr_options[ 'active_tab' ] : "#about"; ?>">
					
					<div class="settings-submit">
						<?php wp_nonce_field( 'wpjf3_nonce' ); ?>
						<input type="submit" name="update_wp_maintenance_redirect_settings" value="<?php esc_attr_e( 'Update Settings' ); ?>" />
						<p class="wpjf3_mr_small_dim"><?php esc_html_e( "You do not need to use this button if you have only made changes in the Unrestricted IP addresses or Access Keys panels." ); ?></p>
					</div>
					
				</form>
			</div>
				
			<?php
		} // end function print_admin_page()
	} // end class wpjf3_maintenance_redirect
}

if ( class_exists( "wpjf3_maintenance_redirect" ) ) {
	$my_wpjf3_maintenance_redirect = new wpjf3_maintenance_redirect();
}

// initialize the admin and users panel
if ( !function_exists( "wpjf3_maintenance_redirect_ap" ) ) {
	function wpjf3_maintenance_redirect_ap() {
		if( current_user_can('manage_options') ) {
			global $my_wpjf3_maintenance_redirect;
			global $ajax_nonce; 
				 $ajax_nonce = wp_create_nonce( "wpjf3_nonce" ); 
			
			if( !isset( $my_wpjf3_maintenance_redirect ) ) return;
		
			if ( function_exists( 'add_options_page' ) ) {
				add_options_page( __( "Maintenance Redirect Options" ), __( "Maintenance Redirect" ), 'manage_options', 'JF3_Maint_Redirect', array( $my_wpjf3_maintenance_redirect, 'print_admin_page' ) );
			}
		}
	}
}

// actions and filters	
if( isset( $my_wpjf3_maintenance_redirect ) ) {
	// actions & filters
	add_action( 'admin_menu',     'wpjf3_maintenance_redirect_ap' );
	add_action( 'send_headers',   array( $my_wpjf3_maintenance_redirect, 'process_redirect' ), 1 );
	add_action( 'admin_notices',  array( $my_wpjf3_maintenance_redirect, 'display_status_if_active' ) );
	add_action( 'admin_bar_menu', array( $my_wpjf3_maintenance_redirect, 'adminbar_site_status' ), 200 );
	
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( $my_wpjf3_maintenance_redirect, 'plugin_settings_link' ) );
	add_filter( 'plugin_row_meta', array( $my_wpjf3_maintenance_redirect, 'plugin_info_link' ), 10, 4 );
	add_filter( 'site_status_tests', array( $my_wpjf3_maintenance_redirect, 'wpjf3_add_site_health' ) );

	// ajax actions
	add_action( 'wp_ajax_wpjf3_mr_add_ip',    array( $my_wpjf3_maintenance_redirect, 'add_new_ip'       ) );
	add_action( 'wp_ajax_wpjf3_mr_toggle_ip', array( $my_wpjf3_maintenance_redirect, 'toggle_ip_status' ) );
	add_action( 'wp_ajax_wpjf3_mr_delete_ip', array( $my_wpjf3_maintenance_redirect, 'delete_ip'        ) );
	add_action( 'wp_ajax_wpjf3_mr_add_ak',    array( $my_wpjf3_maintenance_redirect, 'add_new_ak'       ) );
	add_action( 'wp_ajax_wpjf3_mr_toggle_ak', array( $my_wpjf3_maintenance_redirect, 'toggle_ak_status' ) );
	add_action( 'wp_ajax_wpjf3_mr_delete_ak', array( $my_wpjf3_maintenance_redirect, 'delete_ak'        ) );
	add_action( 'wp_ajax_wpjf3_mr_resend_ak', array( $my_wpjf3_maintenance_redirect, 'resend_ak'        ) );
	
	// activation ( deactivation is later enhancement... )
	register_activation_hook( __FILE__, array( $my_wpjf3_maintenance_redirect, 'init' ) );
	
	// update hook
	add_action( 'upgrader_process_complete', array( $my_wpjf3_maintenance_redirect, 'after_plugin_upgrade' ) , 10, 2 );
}
