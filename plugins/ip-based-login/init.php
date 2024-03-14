<?php

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

define('ipbl_version', '2.3.10');

define('IPBL_DIR', dirname(IPBL_FILE));
define('IPBL_LIB_DIR', dirname(IPBL_FILE).'/lib');
define('IPBL_URL', plugins_url('', IPBL_FILE));
define('IPBL_PRO_URL', 'https://wp-inspired.com/product/ip-based-login-pro');
define('IPBL_PRICING_URL', 'https://wp-inspired.com/product/ip-based-login-pro');

define('IPBL_CENTRAL_SERVER_URL', 'https://wp-inspired.com/api.php');

include_once(IPBL_DIR.'/functions.php');

// This function adds a link in admin toolbar
function ipbl_admin_bar() {
	global $wp_admin_bar;
	$siteurl = get_option('siteurl');

	$wp_admin_bar->add_node(array(
		'id'    => 'ipbl-link',
		'title' => __( 'Logged in by IP Based Login ', 'ip-based-login' ).'('.ipbl_getip().')',
		'href'  => 'https://wordpress.org/plugins/ip-based-login/'
	));

	$wp_admin_bar->add_node(array(
		'id'    => 'ipbl-logoff-1',
		'title' => __('Disable auto login for 1 minute', 'ip-based-login'),
		'parent' => 'ipbl-link',
		'href'  => $siteurl.'/wp-admin/admin.php?page=ip-based-login&no_login=1'
	));

	$wp_admin_bar->add_node(array(
		'id'    => 'ipbl-logoff-15',
		'title' => __('Disable auto login for 15 minutes', 'ip-based-login'),
		'parent' => 'ipbl-link',
		'href'  => $siteurl.'/wp-admin/admin.php?page=ip-based-login&no_login=15'
	));

	$wp_admin_bar->add_node(array(
		'id'    => 'ipbl-logoff-30',
		'title' => __('Disable auto login for 30 minutes', 'ip-based-login'),
		'parent' => 'ipbl-link',
		'href'  => $siteurl.'/wp-admin/admin.php?page=ip-based-login&no_login=30'
	));

	$wp_admin_bar->add_node(array(
		'id'    => 'ipbl-logoff-60',
		'title' => __('Disable auto login for 1 hour', 'ip-based-login'),
		'parent' => 'ipbl-link',
		'href'  => $siteurl.'/wp-admin/admin.php?page=ip-based-login&no_login=60'
	));

}

// Ok so we are now ready to go
register_activation_hook( IPBL_FILE, 'ip_based_login_activation');

function ip_based_login_activation(){

global $wpdb;

$sql = "
--
-- Table structure for table `".$wpdb->prefix."ip_based_login`
--

CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."ip_based_login` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `start` VARBINARY(128) NOT NULL,
  `end` VARBINARY(128) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `redirect_to` VARCHAR(255) NOT NULL DEFAULT '',
  `usage` INT(10) NOT NULL DEFAULT '0',
  `used` INT(10) NOT NULL DEFAULT '0',
  `src` VARCHAR(50) NOT NULL DEFAULT '',
  `date` int(10) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$wpdb->query($sql);

add_option('ipbl_version', ipbl_version);
	
	$ipbl_wp_roles = wp_roles();
	
	foreach($ipbl_wp_roles->role_objects as $role){
		$role->add_cap('manage_ip_ranges', false);
	}

}

add_action( 'plugins_loaded', 'ip_based_login_update_check' );

function ip_based_login_update_check(){

global $wpdb;
	// Check if the user wants to set no_login
	if(!empty($_REQUEST['no_login'])){

	    $current_user = wp_get_current_user();
		$no_login = ipbl_sanitize_variables($_REQUEST['no_login']);
		$expire_cookie = $no_login * 60;
		setcookie('ipbl_'.$current_user->user_login, 'no_login', time()+$expire_cookie, '/');
		wp_logout();
		wp_redirect(wp_login_url());
		exit; 
	}

	$sql = array();
	$current_version = get_option('ipbl_version');
	
	// If version is empty in db maybe someone reset it from options table
	if(!empty($current_version)){
	
		if(version_compare($current_version, '1.3', '<')){
			$sql[] = "ALTER TABLE `".$wpdb->prefix."ip_based_login` CHANGE `start` `start` BIGINT( 20 ) NOT NULL ;";
			$sql[] = "ALTER TABLE `".$wpdb->prefix."ip_based_login` CHANGE `end` `end` BIGINT( 20 ) NOT NULL ;";
			$sql[] = "ALTER TABLE `".$wpdb->prefix."ip_based_login` ADD `status` TINYINT( 2 ) NOT NULL DEFAULT '1' AFTER `end` ;";
		}
		
		if(version_compare($current_version, '2.0', '<')){
			
			// Rename the old table for backup
			$wpdb->query("RENAME TABLE `".$wpdb->prefix."ip_based_login` TO `".$wpdb->prefix."ip_based_login_old`;");
			
			// Delete option
			delete_option('ipbl_version');
			
			ip_based_login_activation();
			$ipbl_ranges = ipbl_selectquery("SELECT * FROM `".$wpdb->prefix."ip_based_login_old`;", 1);

			// Update the existing iprange to new structure
			if(!empty($ipbl_ranges) && is_array($ipbl_ranges)){
				
				foreach($ipbl_ranges as $ik => $iv){
					$wpdb->query("INSERT INTO `".$wpdb->prefix."ip_based_login` SET 
								`username` = '".$iv['username']."',
								`start` = '".long2ip($iv['start'])."',
								`end` = '".long2ip($iv['end'])."',
								`status` = '".$iv['status']."',
								`date` = '".$iv['date']."';");
					
				}
			}
		}
		
		// There was a bug in plugin activation adding the incorrect column
		if(version_compare($current_version, '2.0.2', '<') && version_compare($current_version, '2.0', '>=')){
			$sql[] = "ALTER TABLE `".$wpdb->prefix."ip_based_login` CHANGE `start` `start` VARBINARY(128) NOT NULL ;";
			$sql[] = "ALTER TABLE `".$wpdb->prefix."ip_based_login` CHANGE `end` `end` VARBINARY(128) NOT NULL ;";
		}
		
		// Add redirect_to and usage feature columns
		if(version_compare($current_version, '2.0.8', '<')){
			$sql[] = "ALTER TABLE `".$wpdb->prefix."ip_based_login`  ADD `redirect_to` VARCHAR(255) NOT NULL DEFAULT ''  AFTER `status`,  ADD `usage` INT(10) NOT NULL DEFAULT '0'  AFTER `redirect_to`,  ADD `used` INT(10) NOT NULL DEFAULT '0'  AFTER `usage` ;";
		}
		
		// Add src column
		if(version_compare($current_version, '2.2.0', '<')){
			$sql[] = "ALTER TABLE `".$wpdb->prefix."ip_based_login` ADD `src` VARCHAR(50) NOT NULL DEFAULT '' AFTER `used`;";
		}
		
		// Add src column
		if(version_compare($current_version, '2.3.9', '<')){
			
			$ipbl_wp_roles = wp_roles();
			foreach($ipbl_wp_roles->role_objects as $role){
				$role->add_cap('manage_ip_ranges', false);
			}
		}
		
	}

	if($current_version < ipbl_version){
		
		if(!empty($sql)){
			foreach($sql as $sk => $sv){
				$wpdb->query($sv);
			}
		}

		update_option('ipbl_version', ipbl_version);
	}
	
	// Is the premium features there ?
	if(file_exists(IPBL_DIR.'/premium.php')){
		
		// Include the file
		include_once(IPBL_DIR.'/premium.php');
		
		ipbl_pro_init();
	
	}

}

// For wp-security-audit-log plugin to log the auto login event
function ipbl_wsal_load_on_frontend($should_load, $frontend_events){
	return true;
}

function triger_login(){
	
	global $wpdb;
	
	$logged_ip = ipbl_getip();
	
	$logged_ip = apply_filters('ipbl_supported_ip', $logged_ip);
	
	// If the IP is not valid we need to return because the SQL query will fail
	// Do we have the IP ? 
	if(empty($logged_ip)){
		return false;
	}
	
	$ipbl_settings = get_option('ipbl_settings');
	
	if(!ipbl_valid_ip($logged_ip)){
		return false;
	}
	
	// When the data is synced from a central server there can be multiple entries for same IP with different usernames
	
	// Search for IPv6
	if(ipbl_valid_ipv6($logged_ip)){
		
		$query = "SELECT * FROM ".$wpdb->prefix."ip_based_login WHERE `status` = 1 AND INET6_ATON ( `start` ) <= INET6_ATON( '".$logged_ip."' ) and INET6_ATON ( `end` ) >= INET6_ATON( '".$logged_ip."' )";
		
	}else{ // Search for IPv4
		
		$query = "SELECT * FROM ".$wpdb->prefix."ip_based_login WHERE `status` = 1 AND INET_ATON ( `start` ) <= INET_ATON( '".$logged_ip."' ) and INET_ATON ( `end` ) >= INET_ATON( '".$logged_ip."' )";
		
	}
	
	$ip_check = ipbl_selectquery($query, 1);
	
	// Did we find any matching IP ? 
	if(!empty($ip_check)){
	
		foreach($ip_check as $ik => $range){
			
			if(!empty($range['username'])){
				
				// Does the user exist ?
				$user = get_user_by('login', $range['username']);
				
				// Take the 1st username found
				if(!empty($user->ID)){
					$username = $range['username'];
					$logged_in_range = $range;
					break;
				}
			}
			
		}
	}
	
	// PHP replaces dot (.) and space ( ) with _ in $_COOKIE
	if(!empty($username) && empty($_COOKIE['ipbl_'.str_replace(array('.', ' '), array('_', '_'), $username)])){

		if(!is_user_logged_in()){
			
			$ipbl_can_auto_login = apply_filters('ipbl_can_auto_login', true);
			
			if(empty($ipbl_can_auto_login)){
				return false;
			}
			
			// Usage tracking
			if(ipbl_is_supported_feature('Usage')){
				
				// Do we have a limit on number of logins ? 
				if(!empty($ip_check[0]['usage'])){
					
					$remaining_logins = $ip_check[0]['usage'] - $ip_check[0]['used'];
					
					// If we have exhausted the number of logins do not login
					if($remaining_logins < 1){
						return false;
					}
					
				}
				
				// Update the usage status
				$wpdb->query("UPDATE ".$wpdb->prefix."ip_based_login SET `used` = used+1 WHERE `rid` = '".$ip_check[0]['rid']."'");
				
			}
			
			// We need to trigger (wp-security-audit-log) plugin to log the login event
			if(class_exists('WpSecurityAuditLog')){
				add_filter('wsal_load_on_frontend', 'ipbl_wsal_load_on_frontend', 1, 2);
				$wpsec = new WpSecurityAuditLog();
				
				if(is_object($wpsec)){
					
					$wpsec->setup();

					// Let WSAL Track the login
					if(is_object($wpsec->alerts) && method_exists($wpsec->alerts, 'register_group')){
						$wpsec->alerts->register_group(
							array(
								esc_html__( 'Users Logins & Sessions Events', 'ip-based-login' ) => array(
									esc_html__( 'User Activity', 'ip-based-login' ) => array(
										array(
											1000,
											__( 'Low', 'ip-based-login' ),
											esc_html__( 'User logged in (via IP Based Login)', 'ip-based-login' ),
											esc_html__( 'User logged in (via IP Based Login)', 'ip-based-login' ),
											array(),
											array(),
											'user',
											'login',
										),
									)
								)
							)
						);
					}
					
				}
			}
			
			// Set a cookie now to see if they are supported by the browser.
			$secure = ( 'https' === parse_url( wp_login_url(), PHP_URL_SCHEME ) );
			$ret = setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN, $secure );

			if ( SITECOOKIEPATH !== COOKIEPATH ) {
				$ret = setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN, $secure );
			}
			
			// This cookie is set by wp-login.php page but since we are not visiting that page we simply do it ourself
			if($ret){
				$_COOKIE[TEST_COOKIE] = 'WP Cookie check';
			}
			
			// What is the user id ?
			$user = get_user_by('login', $username);
			$user_id = $user->ID;

			add_filter('attach_session_information', 'ipbl_attach_session_information', 10000, 1);
			
			if(!empty($_REQUEST['redirect_to'])){
				$redirect_to = $_REQUEST['redirect_to'];
			}
			
			// Do we have a custom redirect for this range ? 
			if(!empty($ip_check[0]['redirect_to']) && ipbl_is_supported_feature('Redirect to')){
				$redirect_to = $ip_check[0]['redirect_to'];
				$_REQUEST['redirect_to'] = $redirect_to;
			}
			
			// Lets login
			wp_set_current_user($user_id, $username);
			wp_set_auth_cookie($user_id);
			do_action('wp_login', $username, $user);
			
			define('LOGGED_IN_USING_IPBL', 1);
			
			apply_filters('ipbl_auto_logged_in', $user, $logged_in_range);
			
		}
		
		$token = wp_get_session_token();
		
		if ( $token ) {
			
			$manager = WP_Session_Tokens::get_instance( get_current_user_id() );
			
			$current_session = $manager->get($token);
			
			// Was the session created by IP Based Login ? 
			if(!empty($current_session['ipbl_logged_in'])){
				
				define('LOGGED_IN_USING_IPBL', 1);
				
			}
			
		}
		
		if(is_logged_in_using_ipbl()){
			
			// To display that the user is logged in using our plugin
			add_action('wp_before_admin_bar_render', 'ipbl_admin_bar');
			
		}
		
		if(!empty($redirect_to)){
			wp_safe_redirect($redirect_to);
			exit;
		}
		
	}elseif(is_user_logged_in()){
		
		// Do we have to destroy session when IP changed ?
		if(!empty($ipbl_settings['ip_session'])){
			
			$token = wp_get_session_token();
			
			if ( $token ) {
				
				$manager = WP_Session_Tokens::get_instance( get_current_user_id() );
				
				$current_session = $manager->get($token);
				
				// Was the session created by IP Based Login ? 
				if(!empty($current_session['ipbl_logged_in'])){
					
					// Has the IP changed ? 
					if($current_session['ip'] != $logged_ip){
						$manager->destroy($token);
						wp_clear_auth_cookie();
						wp_safe_redirect(home_url());
						exit;
					}
				}
			}
			
		}
	}
}

function ipbl_export(){
	
	global $wpdb;
	 
	 // Does the user have rights to export ? 
	if(!current_user_can('manage_options') && !current_user_can('manage_ip_ranges')){
		return false;
	}
	
	if(isset($_REQUEST['ipbl_export']) && $_REQUEST['ipbl_export'] == 'csv'){
	
		$ipranges = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ip_based_login;", 'ARRAY_A');
		
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=ip-based-login-list.csv');
		
		$allowed_fields = array('username' => 'Username', 'start' => 'Start IP', 'end' => 'End IP', 'status' => 'Status');
		
		if(ipbl_is_premium()){
			$allowed_fields += array('redirect_to' => 'Redirect to', 'usage' => 'Allowed Usage');
		}
		
		$allowed_fields += array('used' => 'Used');

		$file = fopen("php://output","w");
		
		fputcsv($file, array_values($allowed_fields));

		foreach($ipranges as $ik => $iv){
			
			$iv['start'] = $iv['start'];
			$iv['end'] = $iv['end'];
			
			if(empty($iv['usage'])){
				$iv['usage'] = 'Unlimited';
			}
			
			$row = array();
			foreach($allowed_fields as $ak => $av){
				$row[$ak] = $iv[$ak];
			}
			
			fputcsv($file, $row);
		}

		fclose($file);
		
		die();
	}
}

function ipbl_init(){
	
	triger_login();
	
}

function ipbl_admin_init(){
	// Export is required in init because we need to do this before anything is printed on the page
	ipbl_export();
}

add_filter('ipbl_supported_ip', 'ipbl_supported_ip', 1, 1);
add_filter('ipbl_is_supported_ip', 'ipbl_is_supported_ip', 1, 1);
add_action('init', 'ipbl_init', 1);
add_action('admin_init', 'ipbl_admin_init');

function ipbl_attach_session_information($session = ''){
	
	$session['ipbl_logged_in'] = 1;
	
	return $session;
	
}

add_filter("plugin_action_links_$plugin_ipbl", 'ipbl_plugin_action_links');

// Add settings link on plugin page
function ipbl_plugin_action_links($links){
	
	if(!ipbl_is_premium()){
		 $links[] = '<a href="'.IPBL_PRICING_URL.'" style="color:#3db634;" target="_blank">'._x('Go Pro', 'Plugin action link label.', 'ip-based-login').'</a>';
	}

	$settings_link = '<a href="admin.php?page=ip-based-login">Settings</a>';	
	array_unshift($links, $settings_link); 
	
	return $links;
}

add_action('admin_menu', 'ip_based_login_admin_menu');

// The IP Based Login Admin Options Page
function ipbl_page_header($title = 'IP Based Login'){

?>
<style>
.lz-right-ul{
	padding-left: 10px !important;
}

.lz-right-ul li{
	list-style: circle !important;
}
</style>
<?php
	
	echo '<div style="margin: 10px 20px 0 2px;">	
<div class="metabox-holder columns-2">
<div class="postbox-container">	
<div id="top-sortables" class="meta-box-sortables ui-sortable">
	
	<table cellpadding="2" cellspacing="1" width="100%" class="fixed" border="0">
		<tr>
			<td valign="top"><h2>IP Based Login '.$title.'</h2></td>
			
			<td align="right"><a target="_blank" class="button button-primary" href="https://wordpress.org/support/plugin/ip-based-login/reviews/#new-post">'.__('Review IP Based Login', 'ip-based-login').'</a></td>
		</tr>
	</table>
	<hr />
	
	<!--Main Table-->
	<table cellpadding="8" cellspacing="1" width="100%" class="fixed">
	<tr>
		<td valign="top">';

}

// The IP Based Login Theme footer
function ipbl_page_footer(){
	
	if(!ipbl_is_premium()){
		echo '<script>
		jQuery("[ipbl-premium-only]").each(function(index) {
			jQuery(this).find( "input, textarea, select" ).attr("disabled", true);
		});
		</script>';
	}
	
	echo '</td>
	
	<style>
.ipbl-ribbon-2 {
--f: 10px; /* control the folded part*/
--r: 15px; /* control the ribbon shape */
--t: 16px; /* the top offset */

position: absolute;
inset: var(--t) calc(-1*var(--f)) auto auto;
padding: 7px 10px var(--f) calc(10px + var(--r));
clip-path: 
polygon(0 0,100% 0,100% calc(100% - var(--f)),calc(100% - var(--f)) 100%,
calc(100% - var(--f)) calc(100% - var(--f)),0 calc(100% - var(--f)),
var(--r) calc(50% - var(--f)/2));
background: #BD1550;
color: #ffffff;
margin-top:30px;
box-shadow: 0 calc(-1*var(--f)) 0 inset #0005;
font-weight: 600;
}

.ipbl-animate-color {
  animation: color-change 1.5s infinite;
}

@keyframes color-change {
  0% { background: #BD1550; }
  50% { background: #2271b1; }
  100% { background: #BD1550; }
}
	</style>
	
	<td width="200" valign="top" id="ipbl-right-bar">';
	
	if(!ipbl_is_premium()){
	
		echo '
	<div class="postbox" style="min-width:0px !important;">
		<div class="postbox-header">
			<h2 class="hndle ui-sortable-handle">
				<span>PRO Version</span>
			</h2>
		</div>
		
		<div class="inside">
			<i>Upgrade to the pro version and get the following features </i>:<br>
			<ul class="lz-right-ul">
				<li>IPv6 Support</li>
				<li>EZProxy Support</li>
				<li>more features will be added...</li>
			</ul>
			<center><a class="button button-primary" target="_blank" href="'.IPBL_PRICING_URL.'">Upgrade to PRO</a></center>
		</div>
	</div>';
	
	}else{
		
	}
	
	echo '
	<div class="postbox" style="min-width:0px !important;">
		<div class="postbox-header">
			<h2 class="hndle ui-sortable-handle">
				<span>IP Based Login Analytics</span>
			</h2>
		</div>
		<div class="ipbl-ribbon-2 ipbl-animate-color" style="padding-bottom: 19px;">Introductory Offer</div>
		<br />
		<div class="inside" style="margin-top: 35px;">
			<i>Get detailed analytics about the auto logins</i>:<br>
			<ul class="lz-right-ul">
				<li>Number of Users Logged In</li>
				<li>No of Sessions</li>
				<li>Browsing Time</li>
				<li>Userwise Stats</li>
				<li>Search Stats by date range and username</li>
				<li>% of returning users</li>
				<li>CSV Export</li>
				<li>and much more...</li>
			</ul>
			<center><a class="button button-primary" target="_blank" href="https://wp-inspired.com/product/ip-based-login-analytics/">More Details</a></center>
		</div>
	</div>
	
	<div class="postbox" style="min-width:0px !important;">
		<div class="postbox-header">
		<h2 class="hndle ui-sortable-handle">
			<a target="_blank" href="https://wp-inspired.com/product/the-ip-registry-manager-pro/" style="font-size:16px; text-decoration:none;color:#000000;">The IP Registry Manager</a>
		</h2>
		</div>
		<div class="inside">
			<i>Automatically manage the IPs from <a target="_blank" href="https://theipregistry.org">theipregistry.org</a> for your Library or Publishing house </i>:<br>
			<ul class="lz-right-ul">
				<li>Add the API key from theipregistry.org</li>
				<li>Set time interval to fetch IPs</li>
				<li>Add Customer</li>
				<li>That\'s it!</li>
				<li>The IPs will be added/deleted for auto login automatically as updated by The IP Registry as per the interval you set</li>
				<li><a target="_blank" href="https://api.theipregistry.org/swagger/ui/index">Official APIs used</a></li>
				<li>Optimized usage of APIs</li>
				<li>Export/Import Customers via CSV</li>
			</ul>
			<center><a class="button button-primary" target="_blank" href="https://wp-inspired.com/product/the-ip-registry-manager-pro/">Buy Now</a></center>
		</div>
	</div>';
	
	echo '
	<div class="postbox" style="min-width:0px !important;">
		<div class="postbox-header">
		<h2 class="hndle ui-sortable-handle">
			<a target="_blank" href="https://wp-inspired.com/product/hash-based-login-pro" style="font-size:16px; text-decoration:none;color:#000000;">Hash Based Login</a>
		</h2>
		</div>
		<div class="inside">
			<i>Share temporary Login access to your WordPress site by just sharing a link </i>:<br>
			<ul class="lz-right-ul">
				<li>Create/Delete Login URLs</li>
				<li>Works with EZproxy</li>
				<li>Redirect to custom page after login</li>
				<li>Choose the user to be logged with the URL</li>
				<li>Unlimited Login URLs</li>
				<li>Safe & Secure</li>
				<li>Many more features ...</li>
			</ul>
			<center><a class="button button-primary" target="_blank" href="https://wp-inspired.com/product/hash-based-login-pro/">More Details</a></center>
		</div>
	</div>';
	
	echo '
	<div class="postbox" style="min-width:0px !important;">
		<div class="postbox-header">
		<h2 class="hndle ui-sortable-handle">
			<a target="_blank" href="plugin-install.php?s=smart-maintenance-mode&tab=search&type=tag" style="font-size:15px; text-decoration:none;color:#000000;">Smart Maintenance Mode</a>
		</h2>
		</div>
		<div class="inside">
			<i>Maintenance mode for visitors while live site for you </i>:<br>
			<ul class="lz-right-ul">
				<li>Live site for selected IPs</li>
				<li>Live site when logged in</li>
				<li>Supports Custom HTML</li>
				<li>Countdown</li>
				<li>Safe & Secure</li>
				<li>more features will be added ...</li>
			</ul>
			<center><a class="button button-primary" target="_blank" href="plugin-install.php?s=smart-maintenance-mode&tab=search&type=tag">Install Free Now</a></center>
		</div>
	</div>';
	
	echo '</td>
	</tr>
	</table>';
	
		echo '<br />
	<hr />
	IP Based Login v'.ipbl_version.' is developed by <a href="https://wp-inspired.com" target="_blank">WP Inspired Team</a>. 
	You can report any bugs <a href="https://wordpress.org/support/plugin/ip-based-login" target="_blank">here</a>.';
	
	echo '
</div>	
</div>
</div>
</div>';

}

function ip_based_login_admin_menu() {
	global $wp_version;
	
	// Modern WP?
	if (version_compare($wp_version, '3.0', '>=')) {
		
	    add_options_page('IP Based Login', 'IP Based Login', 'manage_options', 'ip-based-login', 'ip_based_login_option_page');
	
		// Add the menu page
		
		if(current_user_can('activate_plugins')){
	
			add_menu_page(__('IP Based Login Settings', 'ip-based-login'), __('IP Based Login', 'ip-based-login'), 'activate_plugins', 'ip-based-login', 'ip_based_login_option_page');
	
			// Settings
			add_submenu_page('ip-based-login', __('IP Based Login Settings', 'ip-based-login'), __('Settings', 'ip-based-login'), 'activate_plugins', 'ip-based-login', 'ip_based_login_option_page');
			
		}elseif(current_user_can('manage_ip_ranges')){
			
			add_menu_page(__('IP Based Login Settings', 'ip-based-login'), __('IP Based Login', 'ip-based-login'), 'manage_ip_ranges', 'ip-based-login', 'ip_based_login_option_page');
	
			// Settings
			add_submenu_page('ip-based-login', __('IP Based Login Settings', 'ip-based-login'), __('Settings', 'ip-based-login'), 'manage_ip_ranges', 'ip-based-login', 'ip_based_login_option_page');
		}
		
		// Go Pro link
		if(!ipbl_is_premium()){
			add_submenu_page('ip-based-login', __('IP Based Login Go Pro', 'ip-based-login'), __('Go Pro', 'ip-based-login'), 'activate_plugins', IPBL_PRICING_URL);
		}
		
	    return;
	}

	// Older WPMU?
	if (function_exists("get_current_site")) {
	    add_submenu_page('wpmu-admin.php', 'IP Based Login', 'IP Based Login', 9, 'ip-based-login', 'ip_based_login_option_page');
	    return;
	}

	// Older WP
	add_options_page('IP Based Login', 'IP Based Login', 9, 'ip-based-login', 'ip_based_login_option_page');
}

function ip_based_login_option_page(){

	global $wpdb, $error;
	 
	if(!current_user_can('manage_options') && !current_user_can('manage_ip_ranges')){
		wp_die('Sorry, but you do not have permissions to change settings.');
	}

	/* Make sure post was from this page */
	if(count($_POST) > 0){
		check_admin_referer('ip-based-login-options');
	}

	ipbl_page_header('Settings');
	
	do_action('ipbl_pre_page_settings');
	
	if(isset($_GET['users_dropdown'])){		
		$users_dropdown = (int) ipbl_sanitize_variables($_GET['users_dropdown']);
		if(!empty($users_dropdown)){
			update_option('ipbl_dropdown', '1');
		}else{
			update_option('ipbl_dropdown', '');			
		}
	}
	
	if(isset($_GET['delid'])){
		
		// Delete all IP ranges
		if($_GET['delid'] == 'all'){
			
			$wpdb->query("DELETE FROM ".$wpdb->prefix."ip_based_login");
			echo '<div id="message" class="updated fade"><p>'
				. __('All IP ranges have been deleted successfully', 'ip-based-login')
				. '</p></div>';	
		
		// Delete selected IP ranges
		}else{
		
			$delid = (int) ipbl_sanitize_variables($_GET['delid']);
			
			$wpdb->query("DELETE FROM ".$wpdb->prefix."ip_based_login WHERE `rid` = '".$delid."'");
			echo '<div id="message" class="updated fade"><p>'
				. __('IP range has been deleted successfully', 'ip-based-login')
				. '</p></div>';	
			
		}
	}
	
	if(isset($_GET['statusid'])){
		
		$statusid = (int) ipbl_sanitize_variables($_GET['statusid']);
		$setstatus = ipbl_sanitize_variables($_GET['setstatus']);
		
		if($setstatus == 'enable'){
			$success_msg = __('IP range has been enabled successfully', 'ip-based-login');
			$_setstatus = 1;
		}else{
			$success_msg = __('IP range has been disabled successfully', 'ip-based-login');
			$_setstatus = 0;
		}
		
		$wpdb->query("UPDATE ".$wpdb->prefix."ip_based_login SET `status` = '".$_setstatus."' WHERE `rid` = '".$statusid."'");
		
		echo '<div id="message" class="updated fade"><p>'
			. $success_msg
			. '</p></div>';	
	}
	
	if(isset($_POST['ipbl_settings_but'])){
		
		$ipbl_ip_options['ipbl_ip_pref'] = trim($_POST['ipbl_ip_pref']);
		$ipbl_ip_options = ipbl_sanitize_variables($ipbl_ip_options);
		$ipbl_ip_pref = $ipbl_ip_options['ipbl_ip_pref'];
		
		if($ipbl_ip_pref == 'PROXY_REMOTE_ADDR' && !ipbl_is_supported_feature()){
			$error[] = sprintf( __('EZProxy support is available in the Pro version. %1$sUpgrade to Pro here%2$s', 'ip-based-login'), '<a href="'.IPBL_PRICING_URL.'" target="_blank">', '</a>');
		}
		
		if(empty($error)){
			
			update_option('ipbl_ip_pref', $ipbl_ip_pref);
		
			$ipbl_settings = array();
			$ipbl_settings = get_option('ipbl_settings');
			
			$ipbl_settings['ip_session'] = (ipbl_is_checked('ip_session_checkbox') ? 1 : 0);
			$ipbl_settings['range_sort'] = ipbl_sanitize_variables($_POST['ipbl_range_sort']);
			
			update_option('ipbl_settings', $ipbl_settings);
			
			echo '<div id="message" class="updated fade"><p>'
				. __('Settings saved successfully !', 'ip-based-login')
				. '</p></div>';
		}else{
			ipbl_report_error($error);
		}
		
	}
	
	if(isset($_POST['ipbl_sync_now_button'])){
		$synced = ipbl_sync_ips(1);
		
		if(!empty($synced)){
			
			echo '<div id="message" class="updated fade"><p>'
				. __('IPs Synced successfully !', 'ip-based-login')
				. '</p></div>';
				
		}
	}
	
	if(isset($_POST['ipbl_sync_settings_button'])){
		
		ipbl_is_supported_feature('Central IP Management', 1);
		
		if(empty($error)){
		
			$ipbl_sync_options['ipbl_central_server'] = '';
			$ipbl_sync_options['apikey'] = trim($_POST['apikey']);
			$ipbl_sync_options['apisecret'] = trim($_POST['apisecret']);
			$ipbl_sync_options['ipbl_sync_frequency'] = (int) trim($_POST['ipbl_sync_frequency']);
			$ipbl_sync_options['ipbl_sync_notify'] = (int) trim($_POST['ipbl_sync_notify']);
			$ipbl_sync_options['ipbl_sync_notify_email'] = trim($_POST['ipbl_sync_notify_email']);
			
			$ipbl_sync_options = ipbl_sanitize_variables($ipbl_sync_options);
			
			if(empty($ipbl_sync_options['apikey'])){
				$error[] = sprintf( __('API Key is required! Get the API Key %1$shere%2$s', 'ip-based-login'), '<a href="https://wp-inspired.com/my-account/ipbl-central/" target="_blank">', '</a>');
			}
			
			if(empty($ipbl_sync_options['apisecret'])){
				$error[] = sprintf( __('API Secret is required! Get the API Secret %1$shere%2$s', 'ip-based-login'), '<a href="https://wp-inspired.com/my-account/ipbl-central/" target="_blank">', '</a>');
			}
			
			if(!empty($ipbl_sync_options['ipbl_sync_notify_email']) && !filter_var($ipbl_sync_options['ipbl_sync_notify_email'], FILTER_VALIDATE_EMAIL)){
				$error[] = __('Email for notification is invalid', 'ip-based-login');
			}
			
		}
		
		// Validate the API credentials
		if(empty($error)){
		
			$curl_args = array();
			$curl_args['method'] = 'POST';
			$curl_args['body'] = array(
									'apikey' => $ipbl_sync_options['apikey'],
									'apisecret' => $ipbl_sync_options['apisecret'],
									'source_site' => rawurlencode(home_url())
								);
			
			$resp = wp_remote_post(IPBL_CENTRAL_SERVER_URL, $curl_args);
			
			if(!empty($resp['body'])){
				
				$resp_data = json_decode($resp['body'], true);
				//print_r($resp_data);
				
				if(!empty($resp_data)){
					if(empty($resp_data['credentials']) || $resp_data['credentials'] != 'ok'){
						$error[] = __('Invalid API credentials', 'ip-based-login');
					}
				}else{
					$error[] = __('Invalid response received from central server', 'ip-based-login');
				}
				
			}else{
				$error[] = __('Failed to connect to central server', 'ip-based-login');
			}
			
		}
		
		if(empty($error)){
		
			update_option('ipbl_sync_settings', $ipbl_sync_options);
			
			ipbl_reschedule_event();
			
			echo '<div id="message" class="updated fade"><p>'
				. __('Sync settings saved successfully !', 'ip-based-login')
				. '</p></div>';
			
		}else{
			ipbl_report_error($error);
		}
		
	}
	
	if(isset($_POST['add_iprange'])){
		
		global $ip_based_login_options;

		$ip_based_login_options['username'] = trim($_POST['username']);
		$ip_based_login_options['start'] = trim($_POST['start_ip']);
		$ip_based_login_options['end'] = trim($_POST['end_ip']);
		$ip_based_login_options['redirect_to'] = trim($_POST['redirect_to']);
		$ip_based_login_options['usage'] = (int) trim($_POST['usage']);
		
		// Take the start IP as end IP if end IP is empty
		if(empty($ip_based_login_options['end'])){
			$ip_based_login_options['end'] = $ip_based_login_options['start'];
		}

		$ip_based_login_options = ipbl_sanitize_variables($ip_based_login_options);
		
		$user = get_user_by('login', $ip_based_login_options['username']);
		
		if(empty($user)){
			$error[] = __('The username does not exist', 'ip-based-login');
		}
		
		if(!empty($ip_based_login_options['usage']) && $ip_based_login_options['usage'] < 0){
			$error[] = __('Please provide a valid value for Usage. Should be greater than 0', 'ip-based-login');
		}
		
		if(!ipbl_valid_ip($ip_based_login_options['start'])){
			$error[] = __('Please provide a valid start IP', 'ip-based-login');
		}
		
		apply_filters('ipbl_is_supported_ip', $ip_based_login_options['start']);
		
		if(!ipbl_valid_ip($ip_based_login_options['end'])){
			$error[] = __('Please provide a valid end IP', 'ip-based-login');
		}
		
		apply_filters('ipbl_is_supported_ip', $ip_based_login_options['end']);
		
		// To use smaller variables
		$start_ip = $ip_based_login_options['start'];
		$end_ip = $ip_based_login_options['end'];
		
		// Regular ranges will work
		if(inet_ptoi($start_ip) > inet_ptoi($end_ip)){
			
			// BUT, if 0.0.0.1 - 255.255.255.255 is given, it will not work
			if(inet_ptoi($start_ip) >= 0 && inet_ptoi($end_ip) < 0){
				// This is right
			}else{
				$error[] = __('The End IP cannot be smaller than the Start IP', 'ip-based-login');
			}
			
		}
		
		if(empty($error)){
			
			$query = "SELECT * FROM ".$wpdb->prefix."ip_based_login";
			$ip_ranges = ipbl_selectquery($query, 1);
			
			foreach($ip_ranges as $k => $v){
				
				// This is to check if there is any other range exists with the same Start or End IP
				if(( inet_ptoi($start_ip) <= inet_ptoi($v['start']) && inet_ptoi($v['start']) <= inet_ptoi($end_ip) )
					|| ( inet_ptoi($start_ip) <= inet_ptoi($v['end']) && inet_ptoi($v['end']) <= inet_ptoi($end_ip) )
				){
					$error[] = __('The Start IP or End IP submitted conflicts with an existing IP range !', 'ip-based-login');
					break;
				}
				
				// This is to check if there is any other range exists with the same Start IP
				if(inet_ptoi($v['start']) <= inet_ptoi($start_ip) && inet_ptoi($start_ip) <= inet_ptoi($v['end'])){
					$error[] = __('The Start IP is present in an existing range !', 'ip-based-login');
					break;
				}
				
				// This is to check if there is any other range exists with the same End IP
				if(inet_ptoi($v['start']) <= inet_ptoi($end_ip) && inet_ptoi($end_ip) <= inet_ptoi($v['end'])){
					$error[] = __('The End IP is present in an existing range!', 'ip-based-login');
					break;
				}
				
			}
			
		}
		
		// Redirect to a specific URL
		if(!empty($ip_based_login_options['redirect_to'])){
			if(!ipbl_is_supported_feature('Redirect to', 1)){
				unset($ip_based_login_options['redirect_to']);
			}
		}
		
		// Limit the number of times someone can login
		if(!empty($ip_based_login_options['usage'])){
			if(!ipbl_is_supported_feature('Usage', 1)){
				unset($ip_based_login_options['usage']);
			}
		}
		
		if(empty($error)){
			
			$options['username'] = $ip_based_login_options['username'];
			$options['start'] = $ip_based_login_options['start'];
			$options['end'] = $ip_based_login_options['end'];
			$options['status'] = (ipbl_is_checked('status') ? 1 : 0);
			$options['redirect_to'] = !empty($ip_based_login_options['redirect_to']) ? $ip_based_login_options['redirect_to'] : '';
			$options['usage'] = !empty($ip_based_login_options['usage']) ? $ip_based_login_options['usage'] : 0;
			$options['date'] = date('Ymd');
			
			$wpdb->insert($wpdb->prefix.'ip_based_login', $options);
			
			if(!empty($wpdb->insert_id)){
				echo '<div id="message" class="updated fade"><p>'
					. __('IP range added successfully', 'ip-based-login')
					. '</p></div>';
			}else{
				echo '<div id="message" class="updated fade"><p>'
					. __('There were some errors while adding IP range', 'ip-based-login')
					. '</p></div>';
			}
			
		}else{
			ipbl_report_error($error);
		}
	}
	
	if(isset($_POST['ipbl_import'])){
		
		if(empty($_FILES['ipbl_import_file']['tmp_name'])){
			$error[] = __('Please choose a file to import', 'ip-based-login');
		}
		
		if(!empty($_FILES['ipbl_import_file']['error']) && empty($error)){
			$error[] = __('Import file is invalid', 'ip-based-login');
		}
		
		$ext = pathinfo($_FILES['ipbl_import_file']['name'], PATHINFO_EXTENSION);
		if($ext != 'csv' && empty($error)){
			$error[] = __('Only CSV files can be imported', 'ip-based-login');
		}
		
		if(empty($error)){
			$csv_as_array = array_map('str_getcsv', file($_FILES['ipbl_import_file']['tmp_name']));
		
			if(!is_array($csv_as_array) || empty($csv_as_array)){
				$error[] = __('Invalid data in import file', 'ip-based-login');
			}
		}
		
		if(!empty($error)){
			ipbl_report_error($error);
		}else{
			$saved_csv = 0;
			$row_no = 0;
			$skipped_csv = array();
			foreach($csv_as_array as $ik => $iv){
				
				$row_no++;
				
				$iv[0] = trim($iv[0]);
				$iv[1] = trim($iv[1]);
				$iv[2] = trim($iv[2]);
				$iv[3] = trim($iv[3]);
				
				// Is this the heading row ?
				if($iv[0] == 'Username' || $iv[1] == 'Start IP' || $iv[2] == 'End IP' || $iv[3] == 'Status'){
					continue;
				}
				
				// If the start or end ip is invalid skip the row
				if(!ipbl_valid_ip($iv[1])){
					$skipped_csv[] = '* '.__('Invalid Start IP ', 'ip-based-login').' - <b>'.$iv[1].'</b> - Row no. '.$row_no;
					continue;
				}
				
				if(!ipbl_supported_ip($iv[1])){
					$skipped_csv[] = '* '.__('IPv6 Start IP not supported', 'ip-based-login').' - <b>'.$iv[1].'</b> - Row no. '.$row_no;
					continue;
				}
				
				if(!ipbl_valid_ip($iv[2])){
					$skipped_csv[] = '* '.__('Invalid End IP ', 'ip-based-login').' - <b>'.$iv[2].'</b> - Row no. '.$row_no;
					continue;
				}
				
				if(!ipbl_supported_ip($iv[2])){
					$skipped_csv[] = '* '.__('IPv6 End IP not supported', 'ip-based-login').' - <b>'.$iv[2].'</b> - Row no. '.$row_no;
					continue;
				}
				
				// If there is no username skip the row
				if(empty($iv[0])){
					$skipped_csv[] = '* '.__('Empty Username ', 'ip-based-login').' - Row no. '.$row_no;
					continue;
				}
		
				if(!empty($iv[4])){
					if(!ipbl_is_supported_feature('Redirect to')){
						$skipped_csv[] = '* '.__('Redirect to feature is not supported in the Free version ', 'ip-based-login').' - <b>'.$iv[4].'</b> - Row no. '.$row_no;
						continue;
					}
				}
		
				if(!empty($iv[5])){
					if(!ipbl_is_supported_feature('Usage')){
						$skipped_csv[] = '* '.__('Usage feature is not supported in the Free version ', 'ip-based-login').' - <b>'.$iv[5].'</b> - Row no. '.$row_no;
						continue;
					}
				}
				
				$options = array();
				$options['username'] = $iv[0];
				$options['start'] = $iv[1];
				$options['end'] = $iv[2];
				$options['status'] = (!empty($iv[3]) ? 1 : 0);
				$options['redirect_to'] = (!empty($iv[4]) ? $iv[4] : '');
				$options['usage'] = (!empty($iv[5]) ? $iv[5] : 0);
				$options['date'] = date('Ymd');
				
				$wpdb->insert($wpdb->prefix.'ip_based_login', $options);
				
				if(!empty($wpdb->insert_id)){
					$saved_csv++;
				}
			}
			
			if(!empty($saved_csv)){
				echo '<div id="message" class="updated fade"><p>
					<b>'.$saved_csv.'</b> ' . __('IP ranges imported successfully', 'ip-based-login')
					. '</p></div>';
			}
			
			if(!empty($skipped_csv)){
				echo '<div id="message" class="error fade"><p>
					'.__('The following IP ranges were not imported', 'ip-based-login').': <br />
					'.implode('<br />', $skipped_csv).'</p></div>';
			}
		}
	}
	
	// A list of all users
	$_users = get_users(array('number' => -1, 'fields' => array('user_login')));
	
	$users_dropdown = get_option('ipbl_dropdown');
	
	$ipbl_ip_pref = get_option('ipbl_ip_pref');
	
	$ipbl_settings = get_option('ipbl_settings');
	
	$order_by = 'ORDER BY `rid` ASC';
	if(!empty($ipbl_settings['range_sort'])){
		if($ipbl_settings['range_sort'] == 1){
			$order_by = 'ORDER BY `rid` DESC';
		}elseif($ipbl_settings['range_sort'] == 2){
			$order_by = 'ORDER BY `username`';
		}
	}
	
	$ipranges = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ip_based_login ".$order_by.";", 'ARRAY_A');
	
	$ipbl_sync_settings = get_option('ipbl_sync_settings', array());
	
	echo '<script>
	
	function ipbl_save_sort(sort){
		document.getElementById("ipbl_range_sort").value = sort.value;
		document.getElementById("ipbl_settings_but").click();
	}
	
	function ipbl_show_sync_settings(){
		
		var status = document.getElementById("ipbl_sync_settings_form").style.display;
		
		if(status == "none"){
			document.getElementById("ipbl_sync_settings_form").style.display = "block";
			document.getElementById("ipbl_central_but").style.display = "none";
		}else{
			document.getElementById("ipbl_sync_settings_form").style.display = "none";
			document.getElementById("ipbl_central_but").style.display = "block";
		}
		
	}
	
	</script>';
	
	$disabled_field = '';
	$disabled_exp = '';
	
	if(!ipbl_is_supported_feature()){
		$disabled_field = 'disabled="disabled"';
		$disabled_exp = '<a href="'.IPBL_PRICING_URL.'" target="_blank" style="text-decoration:none;">
			<span style="color:red;">'.__('This feature is supported in the Pro version. Upgrade to Pro !','ip-based-login').'</span>
		</a>';
	}
	
	?>
	<br />
	<style>
	.not-supported {
	color:red;
	}
	</style>
	  <form action="admin.php?page=ip-based-login" method="post">
		<?php wp_nonce_field('ip-based-login-options'); ?>
		
		<div class="postbox">
		<div class="postbox-header">
		<h2 class="hndle ui-sortable-handle" style="margin:5px;"><span><?php echo __('Global Settings','ip-based-login'); ?></span></h2>
		</div>
		<div class="inside">
	    <table class="form-table">
		  <tr>
			<th scope="row" valign="top" style="width:30%"><label for="ipbl_ip_pref"><b><?php echo __('IP Detection Preference (Optional)','ip-based-login'); ?> : </b></label>
			</th>
			<td>
            	<select name="ipbl_ip_pref" id="ipbl_ip_pref" style="font-size:13px;">
					<option value="0">REMOTE_ADDR (Default)</option>
					<option value="HTTP_X_FORWARDED_FOR" <?php if(!empty($ipbl_ip_pref) && $ipbl_ip_pref == 'HTTP_X_FORWARDED_FOR') echo 'selected="selected"'; ?>>HTTP_X_FORWARDED_FOR</option>
					<option value="HTTP_CLIENT_IP" <?php if(!empty($ipbl_ip_pref) && $ipbl_ip_pref == 'HTTP_CLIENT_IP') echo 'selected="selected"'; ?>>HTTP_CLIENT_IP</option>
					<option value="HTTP_X_ORIGINAL_FORWARDED_FOR" <?php if(!empty($ipbl_ip_pref) && $ipbl_ip_pref == 'HTTP_X_ORIGINAL_FORWARDED_FOR') echo 'selected="selected"'; ?>>HTTP_X_ORIGINAL_FORWARDED_FOR</option>
					<option value="HTTP_CF_CONNECTING_IP" <?php if(!empty($ipbl_ip_pref) && $ipbl_ip_pref == 'HTTP_CF_CONNECTING_IP') echo 'selected="selected"'; ?>>(Cloudflare) HTTP_CF_CONNECTING_IP</option>
					<option value="PROXY_REMOTE_ADDR" class="<?php echo (!ipbl_is_supported_feature() ? 'not-supported' : ''); ?>" <?php if(!empty($ipbl_ip_pref) && $ipbl_ip_pref == 'PROXY_REMOTE_ADDR') echo 'selected="selected"'; ?>>(EZProxy) PROXY_REMOTE_ADDR<?php echo (!ipbl_is_supported_feature() ? ' - Upgrade to Pro' : ''); ?></option>
				</select>
				<br /><b><?php echo __('Your current IP : ','ip-based-login').ipbl_getip(); ?></b>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="ip_session_checkbox"><?php echo __('Terminate Session on IP Change','ip-based-login'); ?></label></th>
			<td>
			  <input type="checkbox" <?php if(!isset($_POST['ipbl_settings_but']) && !empty($ipbl_settings['ip_session']) || ipbl_is_checked('ip_session_checkbox')) echo 'checked="checked"'; ?> name="ip_session_checkbox" id="ip_session_checkbox" /> <?php echo __('Select the checkbox to destroy the session if the session IP is changed.','ip-based-login'); ?> <br />
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  <?php echo __('Note : This will affect only sessions created by IP Based Login.','ip-based-login'); ?>
			  
			</td>
		  </tr>
		
		</table>
		<input name="ipbl_range_sort" id="ipbl_range_sort" value="<?php echo (!empty($ipbl_settings['range_sort']) ? $ipbl_settings['range_sort'] : 0); ?>" type="hidden" />
		<input name="ipbl_settings_but" id="ipbl_settings_but" class="button button-primary" value="<?php echo __('Save','ip-based-login'); ?>" type="submit" />
		</div>
		</div>
		
		<div class="postbox" id="ipbl_sync_settings_form" style="display:<?php echo (!empty($ipbl_sync_settings['apikey']) || !empty($_POST['apikey']) ? 'block' : 'none') ?>">
		<div class="postbox-header">
		<h2 class="hndle ui-sortable-handle" style="margin:5px;"><span><?php echo __('Sync IP Ranges from Central Server','ip-based-login'); ?></span></h2>
		</div>
		<div class="inside">
		<span><a href="https://wp-inspired.com/my-account/ipbl-central/" target="_blank"><?php echo __('Have many sites to manage ? Update IP ranges on a central server and it will be automatically synced to all your sites.','ip-based-login'); ?></a></span>
		<?php echo (!empty($disabled_exp) ? '<br /><br />'.$disabled_exp : ''); ?>
	    <table class="form-table">
		  <tr>
			<th scope="row" valign="top"><label for="ipbl_central_server"><?php echo __('Central Server','ip-based-login'); ?></label><br />
			<span style="font-size:12px; color:#757575;"><?php echo __('Choose the server to sync data from','ip-based-login'); ?></span>
			</th>
			<td>
				<select name="ipbl_central_server"  <?php echo $disabled_field; ?>>
					<option value=""><?php echo __('IP Based Login Server (Default)','ip-based-login'); ?></option>
				</select>
				&nbsp;
				<input name="ipbl_sync_now_button" class="button button-primary" value="<?php echo __('Sync Now','ip-based-login'); ?>" type="submit" />
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="apikey"><?php echo __('API Key','ip-based-login'); ?></label><br />
			<a href="https://wp-inspired.com/my-account/ipbl-central/" target="_blank" style="font-size:12px; color:#757575;"><?php echo __('Find API Key','ip-based-login'); ?></a>
			</th>
			<td>
			  <input type="text" size="40" value="<?php echo((isset($_POST['apikey']) ? trim($_POST['apikey']) : (!empty($ipbl_sync_settings['apikey']) ? $ipbl_sync_settings['apikey'] : ''))); ?>" name="apikey" id="apikey" <?php echo $disabled_field; ?> /> 
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="apisecret"><?php echo __('API Secret','ip-based-login'); ?></label><br />
			<a href="https://wp-inspired.com/my-account/ipbl-central/" target="_blank" style="font-size:12px; color:#757575;"><?php echo __('Find API Secret','ip-based-login'); ?></a>
			</th>
			<td>
			  <input type="password" size="40" value="<?php echo ((isset($_POST['apisecret']) ? trim($_POST['apisecret']) : (!empty($ipbl_sync_settings['apisecret']) ? $ipbl_sync_settings['apisecret'] : ''))); ?>" name="apisecret" id="apisecret" <?php echo $disabled_field; ?> /> 
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="ipbl_sync_frequency"><?php echo __('Sync Frequency','ip-based-login'); ?></label><br />
			<span style="font-size:12px; color:#757575;"><?php echo __('Default once a day','ip-based-login'); ?></span>
			</th>
			<td>
				<input type="text" size="10" value="<?php echo((isset($_POST['ipbl_sync_frequency']) ? trim($_POST['ipbl_sync_frequency']) : (!empty($ipbl_sync_settings['ipbl_sync_frequency']) ? $ipbl_sync_settings['ipbl_sync_frequency'] : ''))); ?>" name="ipbl_sync_frequency" id="ipbl_sync_frequency"  <?php echo $disabled_field; ?> />
				<span style="font-size:12px; color:#757575;"><?php echo __('hours','ip-based-login'); ?><br /><?php echo __('Leave blank for default','ip-based-login'); ?></span>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="ipbl_sync_notify"><?php echo __('Email Notification','ip-based-login'); ?></label></th>
			<td>
				<select name="ipbl_sync_notify" <?php echo $disabled_field; ?>>
					<option value="" <?php echo (isset($_POST['ipbl_sync_notify']) && empty($_POST['ipbl_sync_notify']) ? 'selected="selected"' : ''); ?> ><?php echo __('Never','ip-based-login'); ?></option>
					<option value="1" <?php echo (isset($_POST['ipbl_sync_notify']) && ($_POST['ipbl_sync_notify'] == 1) ? 'selected="selected"' : (!empty($ipbl_sync_settings['ipbl_sync_notify']) && $ipbl_sync_settings['ipbl_sync_notify'] == 1 ? 'selected="selected"' : '')); ?> ><?php echo __('Only when IPs list changed','ip-based-login'); ?></option>
					<option value="2" <?php echo (isset($_POST['ipbl_sync_notify']) && ($_POST['ipbl_sync_notify'] == 2) ? 'selected="selected"' : (!empty($ipbl_sync_settings['ipbl_sync_notify']) && $ipbl_sync_settings['ipbl_sync_notify'] == 2 ? 'selected="selected"' : '')); ?> ><?php echo __('Always','ip-based-login'); ?></option>
				</select>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="ipbl_sync_notify_email"><?php echo __('Email for Notification','ip-based-login'); ?></label></th>
			<td>
			  <input type="text" size="25" value="<?php echo ((isset($_POST['ipbl_sync_notify_email']) ? trim($_POST['ipbl_sync_notify_email']) : (!empty($ipbl_sync_settings['ipbl_sync_notify_email']) ? $ipbl_sync_settings['ipbl_sync_notify_email'] : ''))) ?>" name="ipbl_sync_notify_email" id="ipbl_sync_notify_email" <?php echo $disabled_field; ?> />  <br /><?php echo __('Default admin email','ip-based-login'); ?> : <?php echo get_option('admin_email'); ?><br />
			</td>
		  </tr>
		</table><br />
		<input name="ipbl_sync_settings_button" class="button button-primary" value="<?php echo __('Save','ip-based-login'); ?>" type="submit" />
		</div>
		<br /><br />
		</div>
		
		<div class="postbox">
		<div class="postbox-header">
		<h2 class="hndle ui-sortable-handle" style="margin:5px;"><span><?php echo __('Add IP Range','ip-based-login'); ?></span>
		<input name="ipbl_central_but" id="ipbl_central_but" class="button button-primary" value="<?php echo __('Sync IPs from a Central Server','ip-based-login'); ?>" type="button" onclick="ipbl_show_sync_settings();" style="display:<?php echo (empty($ipbl_sync_settings['apikey']) || !empty($_POST['apikey']) ? 'block' : 'none') ?>" /></h2>
		</div>
		<div class="inside">
	    <table class="form-table">
		  <tr>
			<th scope="row" valign="top"><label for="username"><?php echo __('Username','ip-based-login'); ?></label><br />
				<?php
				
					if(empty($users_dropdown)){
						echo '<a class="submitdelete" href="admin.php?page=ip-based-login&users_dropdown=1" style="font-size:10px;">'.__('Show the list of users in a drop down','ip-based-login').'</a>';
					}else{						
						echo '<a class="submitdelete" href="admin.php?page=ip-based-login&users_dropdown=0" style="font-size:12px;">'.__("Don't show the list of users in a drop down",'ip-based-login').'</a>';
					}
					
                ?>
			</th>
			<td>
            	<?php
				
					if(!empty($users_dropdown)){
						echo '<select name="username">';
						
						foreach($_users as $_user){
							echo '<option value="'.$_user->user_login.'" '.((!empty($ip_based_login_options['username']) && $ip_based_login_options['username'] == $_user->user_login) ? 'selected="selected"' : '').'>'.$_user->user_login.'</option>';
						}
						
						echo '</select>&nbsp;&nbsp;';
					}else{
						echo '<input type="text" size="25" value="'.((isset($_POST['username']) ? trim($_POST['username']) : '')).'" name="username" id="username" />';
					}
					
				?>
				<?php echo __('Username to be logged in as when accessed from the below IP range','ip-based-login'); ?> <br />
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="start_ip"><?php echo __('Start IP','ip-based-login'); ?></label><br />
			<span style="font-size:12px; color:#757575;"><?php echo __('First IP of the range','ip-based-login'); ?></span>
			</th>
			<td>
			  <input type="text" size="25" value="<?php echo((isset($_POST['start_ip']) ? trim($_POST['start_ip']) : '')); ?>" name="start_ip" id="start_ip" /> 
			  
			  <?php ipbl_ipv6_support(); ?>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="end_ip"><?php echo __('End IP','ip-based-login'); ?></label>
			<span style="font-size:12px; color:#757575;"><?php echo __('(Optional)','ip-based-login'); ?><br /><?php echo __('Last IP of the range','ip-based-login'); ?></span>
			</th>
			<td>
			  <input type="text" size="25" value="<?php echo((isset($_POST['end_ip']) ? trim($_POST['end_ip']) : '')); ?>" name="end_ip" id="end_ip" /> 
			  
			  <?php ipbl_ipv6_support(); ?>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="status_checkbox"><?php echo __('Active','ip-based-login'); ?></label></th>
			<td>
			  <input type="checkbox" <?php if(!isset($_POST['add_iprange']) || ipbl_is_checked('status')) echo 'checked="checked"'; ?> name="status" id="status_checkbox" /> <?php echo __('Select the checkbox to set this range as active','ip-based-login'); ?> <br />
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="redirect_to"><?php echo __('Redirect to','ip-based-login'); ?></label></th>
			<td>
			  <input type="text" size="25" value="<?php echo ((isset($_POST['redirect_to']) ? trim($_POST['redirect_to']) : '')) ?>" name="redirect_to" id="redirect_to" <?php echo $disabled_field; ?> />  <?php echo $disabled_exp.'<br />'; ?> <?php echo __('Enter a link where the user should be redirected after login','ip-based-login'); ?> <br />
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="usage"><?php echo __('Usage','ip-based-login'); ?></label></th>
			<td>
			  <input type="number" size="25" value="<?php echo ((isset($_POST['usage']) ? trim($_POST['usage']) : '')) ?>" name="usage" id="usage" <?php echo $disabled_field; ?> /> <?php echo $disabled_exp.'<br />'; ?> <?php echo __('Enter the number of logins after which this range will expire','ip-based-login'); ?> <br /><?php echo __('Default: 0 means unlimited','ip-based-login'); ?> <br />
			</td>
		  </tr>
		</table><br />
		<input name="add_iprange" class="button action" value="<?php echo __('Add IP range','ip-based-login'); ?>" type="submit" />	
		&nbsp;
		<input name="ipbl_import_but" class="button action" value="<?php echo __('Import from CSV','ip-based-login'); ?>" type="button" onclick="jQuery('#ipbl_import_form').toggle();jQuery(this).hide();">	
		</form>
		</div>
		</div>
		
		<div style="display:none;" id="ipbl_import_form">
		<br />
		<form action="admin.php?page=ip-based-login" method="post" enctype="multipart/form-data">
			<?php wp_nonce_field('ip-based-login-options'); ?>
			<input type="file" name="ipbl_import_file">
			<input name="ipbl_import" class="button action" value="<?php echo __('Start Import','ip-based-login'); ?>" type="submit">
		</form>
		</div>
	
	<?php
	
	if(!empty($ipranges)){
		?>
		<br /><br />
		<div style="float:right;padding-right:10px;margin-bottom:10px;">
			<?php echo __('Sort by','ip-based-login'); ?> :
			<select name="ipbl_sort_pref" id="ipbl_sort_pref" style="font-size:13px;" onchange="ipbl_save_sort(this);">
				<option value="0"><?php echo __('Date Ascending (Default)','ip-based-login'); ?></option>
				<option value="1" <?php if(!empty($ipbl_settings['range_sort']) && $ipbl_settings['range_sort'] == '1') echo 'selected="selected"'; ?>><?php echo __('Date Descending','ip-based-login'); ?></option>
				<option value="2" <?php if(!empty($ipbl_settings['range_sort']) && $ipbl_settings['range_sort'] == '2') echo 'selected="selected"'; ?>><?php echo __('Username','ip-based-login'); ?></option>
			</select>
		</div>
		<div style="float:right;padding-right:10px;margin-bottom:10px;">
			<a href="admin.php?page=ip-based-login&delid=all" rel="nofollow" style="text-decoration:none;" onclick="return confirm('<?php echo __('Are you sure you want to delete all IP ranges ?','ip-based-login'); ?>')">
				<input name="ipbl_del_all_but" class="button action" value="<?php echo __('Delete All IP Ranges','ip-based-login'); ?>" type="button">
			</a>
		</div>
		<div style="float:right;padding-right:10px;margin-bottom:10px;">
			<a href="admin.php?page=ip-based-login&ipbl_export=csv" rel="nofollow" style="text-decoration:none;">
				<input name="ipbl_export_but" class="button action" value="<?php echo __('Export as CSV','ip-based-login'); ?>" type="button">
			</a>
		</div>
		<font style="font-size:20px;"><?php echo __('IP Ranges','ip-based-login'); ?></font>
		
		<table class="wp-list-table widefat fixed users">
			<tr>
				<th scope="row" valign="top" style="background-color:#EFEFEF;"><b><?php echo __('Username','ip-based-login'); ?></b></th>
				<th scope="row" valign="top" style="background-color:#EFEFEF;"><b><?php echo __('Start IP','ip-based-login'); ?></b></th>
				<th scope="row" valign="top" style="background-color:#EFEFEF;"><b><?php echo __('End IP','ip-based-login'); ?></b></th>
				<th scope="row" valign="top" style="background-color:#EFEFEF;"><b><?php echo __('Status','ip-based-login'); ?></b></th>
				<th scope="row" valign="top" style="background-color:#EFEFEF;"><b><?php echo __('Redirect to','ip-based-login'); ?></b></th>
				<th scope="row" valign="top" style="background-color:#EFEFEF;"><b><?php echo __('Usage','ip-based-login'); ?></b></th>
				<th scope="row" valign="top" style="background-color:#EFEFEF;"><b><?php echo __('Date Added','ip-based-login'); ?></b></th>
				<th scope="row" valign="top"style="background-color:#EFEFEF;"><b><?php echo __('Options','ip-based-login'); ?></b></th>
			</tr>
			<?php
				
				foreach($ipranges as $ik => $iv){
					
					if(!empty($iv['src']) && !empty($ipbl_sync_settings['ipbl_hide_ranges'])){
						continue;
					}
					
					if(!empty($iv['status'])){
						$status_href = 'disable';
						$status_button = __('disable','ip-based-login');
						$change_status_link = __('Are you sure you want to disable this IP range ?','ip-based-login');
					}else{
						$status_href = 'enable';
						$status_button = __('enable','ip-based-login');
						$change_status_link = __('Are you sure you want to enable this IP range ?','ip-based-login');
					}
					
					if(!empty($iv['usage'])){
						$iv['remaining'] = $iv['usage'] - $iv['used'];
					}
					
					echo '
					<tr>
						<td>
							'.$iv['username'].'
						</td>
						<td>
							'.$iv['start'].'
						</td>
						<td>
							'.$iv['end'].'
						</td>
						<td>
							<i>'.(!empty($iv['status']) ? __('Enabled','ip-based-login') : __('Disabled','ip-based-login')).'</i>
						</td>
						<td>
							'.(!empty($iv['redirect_to']) ? '<a href="'.(preg_match('/^http/is', $iv['redirect_to']) ? $iv['redirect_to'] : site_url().'/'.$iv['redirect_to']).'" target="_blank" style="text-decoration:none;">'.$iv['redirect_to'].'</a>' : '<i>'.__('Home page','ip-based-login').'</i>').'
						</td>
						<td>
							'.(!empty($iv['usage']) ? sprintf( __('%1$s of %2$s remaining', 'ip-based-login'), $iv['remaining'], $iv['usage']) : '<i>'.sprintf( __('%1$s of Unlimited Used', 'ip-based-login'), $iv['used']).'</i>').'
						</td>
						<td>
							'.date("j F, Y", strtotime($iv['date'])).'
						</td>
						<td>
							<a class="submitdelete" href="admin.php?page=ip-based-login&delid='.$iv['rid'].'" onclick="return confirm(\''.__('Are you sure you want to delete this IP range ?','ip-based-login').'\')">'.__('Delete','ip-based-login').'</a>&nbsp;&nbsp;
							<a class="submitdelete" href="admin.php?page=ip-based-login&statusid='.$iv['rid'].'&setstatus='.$status_href.'" onclick="return confirm(\''.$change_status_link.'\')">'.ucfirst($status_button).'</a>
						</td>
					</tr>';
				}
			?>
		</table>
		<?php
	}

	ipbl_page_footer();
}

function is_logged_in_using_ipbl(){
	
	if(defined('LOGGED_IN_USING_IPBL')){
		return true;
	}
	
	return false;
}

function ipbl_admin_notices(){
	
	$ipbl_dismiss_sale_notice = get_option('ipbl_dismiss_sale_notice');
	
	if(current_user_can('manage_options') && date('Ymd') <= 20231130 && empty($ipbl_dismiss_sale_notice)){
		
		echo '<div class="notice notice-info is-dismissible" id="ipbl_black_friday_sale">
		<div style="padding: 5px 0 15px;">
			<p style="font-size:16px; font-weight:600;">
				IP Based Login Black Friday Sale
			</p>
			<div style="font-size:14px;">
			🚀 Announcement: Black Friday sale is live! Avail <b>Flat 50% off</b> on Purchase/Renewal of any of our plugins or custom development. 
			</div>
			<a href="https://wp-inspired.com/shop/" target="_blank">
				<button type="button" class="button button-primary" style="margin-top:10px;">
					Avail Discount Now
				</button>
			</a>
			</div>
		</div>
		
		<script type="text/javascript">
			function ipbl_dismiss_sale_notice(){
		
				var data = new Object();
				data["action"] = "ipbl_dismiss_sale_notice";
				data["nonce"]	= "'.wp_create_nonce('ip-based-login-options').'";
				
				var admin_url = "'.admin_url().'"+"admin-ajax.php";
				jQuery.post(admin_url, data, function(response){
					
				});
				
			}
			
			function loginizer_email_subscribe(){
				var subs_location = "'.$env['url'].'?email="+encodeURIComponent(jQuery("#subscribe_email").val());
				window.open(subs_location, "_blank");
			}
			jQuery(document).on("click", "#ipbl_black_friday_sale", ipbl_dismiss_sale_notice);
		</script>';

	}
}

add_action('admin_notices', 'ipbl_admin_notices');

add_action('wp_ajax_ipbl_dismiss_sale_notice', 'ipbl_dismiss_sale_notice');
function ipbl_dismiss_sale_notice(){

	// Some AJAX security
	check_ajax_referer('ip-based-login-options', 'nonce');
	 
	if(!current_user_can('manage_options')){
		wp_die('Sorry, but you do not have permissions to change settings.');
	}
	
	update_option('ipbl_dismiss_sale_notice', time());
	echo 1;
	wp_die();
}

// Sorry to see you going
register_uninstall_hook( IPBL_FILE, 'ip_based_login_deactivation');

function ip_based_login_deactivation(){

global $wpdb;

// Unschedule our cron
$timestamp = wp_next_scheduled('ipbl_sync_cron_action');

if(!empty($timestamp)){
	wp_unschedule_event($timestamp, 'ipbl_sync_cron_action');
}

$sql = "DROP TABLE ".$wpdb->prefix."ip_based_login;";
$wpdb->query($sql);

delete_option('ipbl_version');
delete_option('ipbl_dropdown');
delete_option('ipbl_ip_pref');
delete_option('ipbl_settings');
delete_option('ipbl_sync_settings');
delete_option('ipbl_sync_time');
delete_option('ipbl_donate_popup');

}
