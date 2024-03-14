<?php

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

define('loginsecurity_VERSION', '1.0.0');
define('loginsecurity_DIR', WP_PLUGIN_DIR.'/'.basename(dirname(loginsecurity_FILE)));
define('loginsecurity_URL', plugins_url('', loginsecurity_FILE));

include_once(loginsecurity_DIR.'/functions.php');

// Ok so we are now ready to go
register_activation_hook(loginsecurity_FILE, 'loginsecurity_activation');

// Is called when the ADMIN enables the plugin
function loginsecurity_activation(){

	global $wpdb;

	$sql = array();
	
	$sql[] = "CREATE TABLE `".$wpdb->prefix."loginsecurity_logs` (
				`username` varchar(255) NOT NULL DEFAULT '',
				`time` int(10) NOT NULL DEFAULT '0',
				`count` int(10) NOT NULL DEFAULT '0',
				`lockout` int(10) NOT NULL DEFAULT '0',
				`ip` varchar(255) NOT NULL DEFAULT '',
				UNIQUE KEY `ip` (`ip`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	foreach($sql as $sk => $sv){
		$wpdb->query($sv);
	}
	
	add_option('loginsecurity_version', loginsecurity_VERSION);
	add_option('loginsecurity_options', array());
	add_option('loginsecurity_last_reset', 0);
	add_option('loginsecurity_whitelist', array());
	add_option('loginsecurity_blacklist', array());

}

// Checks if we are to update ?
function loginsecurity_update_check(){

global $wpdb;

	$sql = array();
	$current_version = get_option('loginsecurity_version');
	
	// It must be the 1.0 pre stuff
	if(empty($current_version)){
		$current_version = get_option('lz_version');
	}
	
	$version = (int) str_replace('.', '', $current_version);
	
	// No update required
	if($current_version == loginsecurity_VERSION){
		return true;
	}
	
	// Is it first run ?
	if(empty($current_version)){
		
		// Reinstall
		loginsecurity_activation();
		
		// Trick the following if conditions to not run
		$version = (int) str_replace('.', '', loginsecurity_VERSION);
		
	}
	
	// Is it less than 1.0.1 ?
	if($version < 101){
		
		// TODO : GET the existing settings
	
		// Get the existing settings		
		$lz_failed_logs = lz_selectquery("SELECT * FROM `".$wpdb->prefix."lz_failed_logs`;", 1);
		$lz_options = lz_selectquery("SELECT * FROM `".$wpdb->prefix."lz_options`;", 1);
		$lz_iprange = lz_selectquery("SELECT * FROM `".$wpdb->prefix."lz_iprange`;", 1);
				
		// Delete the three tables
		$sql = array();
		$sql[] = "DROP TABLE IF EXISTS ".$wpdb->prefix."lz_failed_logs;";
		$sql[] = "DROP TABLE IF EXISTS ".$wpdb->prefix."lz_options;";
		$sql[] = "DROP TABLE IF EXISTS ".$wpdb->prefix."lz_iprange;";

		foreach($sql as $sk => $sv){
			$wpdb->query($sv);
		}
		
		// Delete option
		delete_option('lz_version');
	
		// Reinstall
		loginsecurity_activation();
	
		// TODO : Save the existing settings

		// Update the existing failed logs to new table
		if(is_array($lz_failed_logs)){
			foreach($lz_failed_logs as $fk => $fv){
				$wpdb->query("INSERT INTO ".$wpdb->prefix."loginsecurity_logs SET `username` = '".$fv['username']."', `time` = '".$fv['time']."', `count` = '".$fv['count']."', `lockout` = '".$fv['lockout']."', `ip` = '".$fv['ip']."';");
			}			
		}

		// Update the existing options to new structure
		if(is_array($lz_options)){
			foreach($lz_options as $ok => $ov){
				
				if($ov['option_name'] == 'lz_last_reset'){
					update_option('loginsecurity_last_reset', $ov['option_value']);
					continue;
				}
				
				$old_option[str_replace('lz_', '', $ov['option_name'])] = $ov['option_value'];
			}
			// Save the options
			update_option('loginsecurity_options', $old_option);
		}

		// Update the existing iprange to new structure
		if(is_array($lz_iprange)){
			
			$old_blacklist = array();
			$old_whitelist = array();
			$bid = 1;
			$wid = 1;
			foreach($lz_iprange as $ik => $iv){
				
				if(!empty($iv['blacklist'])){
					$old_blacklist[$bid] = array();
					$old_blacklist[$bid]['start'] = long2ip($iv['start']);
					$old_blacklist[$bid]['end'] = long2ip($iv['end']);
					$old_blacklist[$bid]['time'] = strtotime($iv['date']);
					$bid = $bid + 1;
				}
				
				if(!empty($iv['whitelist'])){
					$old_whitelist[$wid] = array();
					$old_whitelist[$wid]['start'] = long2ip($iv['start']);
					$old_whitelist[$wid]['end'] = long2ip($iv['end']);
					$old_whitelist[$wid]['time'] = strtotime($iv['date']);
					$wid = $wid + 1;
				}
			}
			
			if(!empty($old_blacklist)) update_option('loginsecurity_blacklist', $old_blacklist);
			if(!empty($old_whitelist)) update_option('loginsecurity_whitelist', $old_whitelist);
		}
		
	}
	
	// Save the new Version
	update_option('loginsecurity_version', loginsecurity_VERSION);
	
}

// Add the action to load the plugin 
add_action('plugins_loaded', 'loginsecurity_load_plugin');

// The function that will be called when the plugin is loaded
function loginsecurity_load_plugin(){
	
	global $loginsecurity;
	
	// Check if the installed version is outdated
	loginsecurity_update_check();
	
	$options = get_option('loginsecurity_options');
	
	$loginsecurity = array();
	$loginsecurity['max_retries'] = empty($options['max_retries']) ? 3 : $options['max_retries'];
	$loginsecurity['lockout_time'] = empty($options['lockout_time']) ? 900 : $options['lockout_time']; // 15 minutes
	$loginsecurity['max_lockouts'] = empty($options['max_lockouts']) ? 5 : $options['max_lockouts'];
	$loginsecurity['lockouts_extend'] = empty($options['lockouts_extend']) ? 86400 : $options['lockouts_extend']; // 24 hours
	$loginsecurity['reset_retries'] = empty($options['reset_retries']) ? 86400 : $options['reset_retries']; // 24 hours
	$loginsecurity['notify_email'] = empty($options['notify_email']) ? 0 : $options['notify_email'];
		
	// Load the blacklist and whitelist
	$loginsecurity['blacklist'] = get_option('loginsecurity_blacklist');
	$loginsecurity['whitelist'] = get_option('loginsecurity_whitelist');
	
	// When was the database cleared last time
	$loginsecurity['last_reset']  = get_option('loginsecurity_last_reset');
	
	//print_r($loginsecurity);
	
	// Clear retries
	if((time() - $loginsecurity['last_reset']) >= $loginsecurity['reset_retries']){
		loginsecurity_reset_retries();
	}
	
	$ins_time = get_option('loginsecurity_ins_time');
	if(empty($ins_time)){
		$ins_time = time();
		update_option('loginsecurity_ins_time', $ins_time);
	}
	$loginsecurity['ins_time'] = $ins_time;
	
	// Set the current IP
	$loginsecurity['current_ip'] = lz_getip();

	/* Filters and actions */
	
	// Use this to verify before WP tries to login
	// Is always called and is the first function to be called
	//add_action('wp_authenticate', 'loginsecurity_wp_authenticate', 10, 2);// Not called by XML-RPC
	add_filter('authenticate', 'loginsecurity_wp_authenticate', 10001, 3);// This one is called by xmlrpc as well as GUI
	
	// Is called when a login attempt fails
	// Hence Update our records that the login failed
	add_action('wp_login_failed', 'loginsecurity_login_failed');
	
	// Is called before displaying the error message so that we dont show that the username is wrong or the password
	// Update Error message
	add_action('wp_login_errors', 'loginsecurity_error_handler', 10001, 2);
	
	// Is the premium features there ?
	if(file_exists(loginsecurity_DIR.'/premium.php')){
		
		// Include the file
		include_once(loginsecurity_DIR.'/premium.php');
		
		loginsecurity_security_init();
		
	}

}

// Should return NULL if everything is fine
function loginsecurity_wp_authenticate($user, $username, $password){
	
	global $loginsecurity, $lz_error, $lz_cannot_login, $lz_user_pass;
	
	if(!empty($username) && !empty($password)){
		$lz_user_pass = 1;
	}
	
	// Are you whitelisted ?
	if(loginsecurity_is_whitelisted()){
		$loginsecurity['ip_is_whitelisted'] = 1;
		return $user;
	}
	
	// Are you blacklisted ?
	if(loginsecurity_is_blacklisted()){
		$lz_cannot_login = 1;
		return new WP_Error('ip_blacklisted', implode('', $lz_error), 'loginsecurity');
	}
	
	if(loginsecurity_can_login()){
		return $user;
	}
	
	$lz_cannot_login = 1;
	
	return new WP_Error('ip_blocked', implode('', $lz_error), 'loginsecurity');
	
}

function loginsecurity_can_login(){
	
	global $wpdb, $loginsecurity, $lz_error;
	
	// Get the logs
	$result = lz_selectquery("SELECT * FROM `".$wpdb->prefix."loginsecurity_logs` WHERE `ip` = '".$loginsecurity['current_ip']."';");
	
	if(!empty($result['count']) && ($result['count'] % $loginsecurity['max_retries']) == 0){
		
		// Has he reached max lockouts ?
		if($result['lockout'] >= $loginsecurity['max_lockouts']){
			$loginsecurity['lockout_time'] = $loginsecurity['lockouts_extend'];
		}
		
		// Is he in the lockout time ?
		if($result['time'] >= (time() - $loginsecurity['lockout_time'])){
			$banlift = ceil((($result['time'] + $loginsecurity['lockout_time']) - time()) / 60);
			
			//echo 'Current Time '.date('m/d/Y H:i:s', time()).'<br />';
			//echo 'Last attempt '.date('m/d/Y H:i:s', $result['time']).'<br />';
			//echo 'Unlock Time '.date('m/d/Y H:i:s', $result['time'] + $loginsecurity['lockout_time']).'<br />';
			
			$_time = $banlift.' minute(s)';
			
			if($banlift > 60){
				$banlift = ceil($banlift / 60);
				$_time = $banlift.' hour(s)';
			}
			
			$lz_error['ip_blocked'] = 'You have exceeded maximum login retries<br /> Please try after '.$_time;
			
			return false;
		}
	}
	
	return true;
}

function loginsecurity_is_blacklisted(){
	
	global $wpdb, $loginsecurity, $lz_error;
	
	$blacklist = $loginsecurity['blacklist'];
			
	foreach($blacklist as $k => $v){
		
		// Is the IP in the blacklist ?
		if(ip2long($v['start']) <= ip2long($loginsecurity['current_ip']) && ip2long($loginsecurity['current_ip']) <= ip2long($v['end'])){
			$result = 1;
			break;
		}
		
		// Is it in a wider range ?
		if(ip2long($v['start']) >= 0 && ip2long($v['end']) < 0){
			
			// Since the end of the RANGE (i.e. current IP range) is beyond the +ve value of ip2long, 
			// if the current IP is <= than the start of the range, it is within the range
			// OR
			// if the current IP is <= than the end of the range, it is within the range
			if(ip2long($v['start']) <= ip2long($loginsecurity['current_ip'])
				|| ip2long($loginsecurity['current_ip']) <= ip2long($v['end'])){				
				$result = 1;
				break;
			}
			
		}
		
	}
		
	// You are blacklisted
	if(!empty($result)){
		$lz_error['ip_blacklisted'] = 'Your IP has been blacklisted';
		return true;
	}
	
	return false;
	
}

function loginsecurity_is_whitelisted(){
	
	global $wpdb, $loginsecurity, $lz_error;
	
	$whitelist = $loginsecurity['whitelist'];
			
	foreach($whitelist as $k => $v){
		
		// Is the IP in the blacklist ?
		if(ip2long($v['start']) <= ip2long($loginsecurity['current_ip']) && ip2long($loginsecurity['current_ip']) <= ip2long($v['end'])){
			$result = 1;
			break;
		}
		
		// Is it in a wider range ?
		if(ip2long($v['start']) >= 0 && ip2long($v['end']) < 0){
			
			// Since the end of the RANGE (i.e. current IP range) is beyond the +ve value of ip2long, 
			// if the current IP is <= than the start of the range, it is within the range
			// OR
			// if the current IP is <= than the end of the range, it is within the range
			if(ip2long($v['start']) <= ip2long($loginsecurity['current_ip'])
				|| ip2long($loginsecurity['current_ip']) <= ip2long($v['end'])){				
				$result = 1;
				break;
			}
			
		}
		
	}
		
	// You are whitelisted
	if(!empty($result)){
		return true;
	}
	
	return false;
	
}


// When the login fails, then this is called
// We need to update the database
function loginsecurity_login_failed($username){
	
	global $wpdb, $loginsecurity, $lz_cannot_login;

	if(empty($lz_cannot_login) && empty($loginsecurity['ip_is_whitelisted']) && empty($loginsecurity['no_loginsecurity_logs'])){
		
		$result = lz_selectquery("SELECT * FROM `".$wpdb->prefix."loginsecurity_logs` WHERE `ip` = '".$loginsecurity['current_ip']."';");
		
		if(!empty($result)){
			$lockout = floor((($result['count']+1) / $loginsecurity['max_retries']));
			$sresult = $wpdb->query("UPDATE `".$wpdb->prefix."loginsecurity_logs` SET `username` = '".$username."', `time` = '".time()."', `count` = `count`+1, `lockout` = '".$lockout."' WHERE `ip` = '".$loginsecurity['current_ip']."';");
			
			// Do we need to email admin ?
			if(!empty($loginsecurity['notify_email']) && $lockout >= $loginsecurity['notify_email']){
				
				$sitename = lz_is_multisite() ? get_site_option('site_name') : get_option('blogname');
				$mail = array();
				$mail['to'] = lz_is_multisite() ? get_site_option('admin_email') : get_option('admin_email');	
				$mail['subject'] = 'Failed Login Attempts from IP '.$loginsecurity['current_ip'].' ('.$sitename.')';
				$mail['message'] = 'Hi,

'.($result['count']+1).' failed login attempts and '.$lockout.' lockout(s) from IP '.$loginsecurity['current_ip'].'

Last Login Attempt : '.date('d/m/Y H:i:s', time()).'
Last User Attempt : '.$username.'
IP has been blocked until : '.date('d/m/Y H:i:s', time() + $loginsecurity['lockout_time']).'

Regards,
loginsecurity';

				@wp_mail($mail['to'], $mail['subject'], $mail['message']);
			}
		}else{
			$insert = $wpdb->query("INSERT INTO `".$wpdb->prefix."loginsecurity_logs` SET `username` = '".$username."', `time` = '".time()."', `count` = '1', `ip` = '".$loginsecurity['current_ip']."', `lockout` = '0';");
		}
	
		// We need to add one as this is a failed attempt as well
		$result['count'] = $result['count'] + 1;
		$loginsecurity['retries_left'] = ($loginsecurity['max_retries'] - ($result['count'] % $loginsecurity['max_retries']));
		$loginsecurity['retries_left'] = $loginsecurity['retries_left'] == $loginsecurity['max_retries'] ? 0 : $loginsecurity['retries_left'];
		
	}
}

// Handles the error of the password not being there
function loginsecurity_error_handler($errors, $redirect_to){
	
	global $wpdb, $loginsecurity, $lz_user_pass, $lz_cannot_login;
	
	//echo 'loginsecurity_error_handler :';print_r($errors->errors);echo '<br>';
	
	// Remove the empty password error
	if(is_wp_error($errors)){
		
		$codes = $errors->get_error_codes();
		
		foreach($codes as $k => $v){
			if($v == 'invalid_username' || $v == 'incorrect_password'){
				$show_error = 1;
			}
		}
		
		$errors->remove('invalid_username');
		$errors->remove('incorrect_password');
		
	}
	
	// Add the error
	if(!empty($lz_user_pass) && !empty($show_error) && empty($lz_cannot_login)){
		$errors->add('invalid_userpass', '<b>ERROR:</b> Incorrect Username or Password');
	}
	
	// Add the number of retires left as well
	if(count($errors->get_error_codes()) > 0 && isset($loginsecurity['retries_left'])){
		$errors->add('retries_left', loginsecurity_retries_left());
	}
	
	return $errors;
	
}

// Returns a string with the number of retries left
function loginsecurity_retries_left(){
	
	global $wpdb, $loginsecurity, $lz_user_pass, $lz_cannot_login;
	
	// If we are to show the number of retries left
	if(isset($loginsecurity['retries_left'])){
		return '<b>'.$loginsecurity['retries_left'].'</b> attempt(s) left';
	}
	
}

function loginsecurity_reset_retries(){
	
	global $wpdb, $loginsecurity;
	
	$deltime = time() - $loginsecurity['reset_retries'];	
	$result = $wpdb->query("DELETE FROM `".$wpdb->prefix."loginsecurity_logs` WHERE `time` <= '".$deltime."';");
	
	update_option('loginsecurity_last_reset', time());
	
}

add_filter("plugin_action_links_$plugin_loginsecurity", 'loginsecurity_plugin_action_links');

// Add settings link on plugin page
function loginsecurity_plugin_action_links($links) {
	
	

	$settings_link = '<a href="admin.php?page=loginsecurity">Settings</a>';	
	array_unshift($links, $settings_link); 
	
	return $links;
}

add_action('admin_menu', 'loginsecurity_admin_menu');

// Shows the admin menu of loginsecurity
function loginsecurity_admin_menu() {
	
	global $wp_version, $loginsecurity;
	
	// Add the menu page
	add_menu_page(__('loginsecurity Dashboard'), __('loginsecurity Security'), 'activate_plugins', 'loginsecurity', 'loginsecurity_page_dashboard');
	
	// Dashboard
	add_submenu_page('loginsecurity', __('loginsecurity Dashboard'), __('Dashboard'), 'activate_plugins', 'loginsecurity', 'loginsecurity_page_dashboard');
	
	// Brute Force
	add_submenu_page('loginsecurity', __('loginsecurity Brute Force Settings'), __('Brute Force'), 'activate_plugins', 'loginsecurity_brute_force', 'loginsecurity_page_brute_force');
	

	
}

// The loginsecurity Admin Options Page
function loginsecurity_page_header($title = 'loginsecurity'){
	/*wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
	wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
	
	echo '
<script>
jQuery(document).ready( function() {
	//add_postbox_toggles("loginsecurity");
});
</script>';*/

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
			<td valign="top"><h3>'.$title.'</h3></td>
			<td align="right"><a target="_blank" class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/rename-wp-loginphp-to-anything-you-want">Review loginsecurity</a></td>
		</tr>
	</table>
	<hr />
	
	<!--Main Table-->
	<table cellpadding="8" cellspacing="1" width="100%" class="fixed">
	<tr>
		<td valign="top">';

}

// The loginsecurity Theme footer
function loginsecurity_page_footer(){
	
	echo '</td>
	<td width="200" valign="top" id="loginsecurity-right-bar">';
	
	
	
	echo '</td>
	</tr>
	</table>
	<br />
	<br />
	
	<script>
	function dotweet(ele){
		window.open(jQuery("#"+ele.id).attr("action")+"?"+jQuery("#"+ele.id).serialize(), "_blank", "scrollbars=no, menubar=no, height=400, width=500, resizable=yes, toolbar=no, status=no");
		return false;
	}
	</script>
	
	<hr />
	<a href="http://travis.ga" target="_blank">loginsecurity</a> You can report any bugs <a href="https://wordpress.org/support/plugin/rename-wp-loginphp-to-anything-you-want" target="_blank">here</a>.

</div>	
</div>
</div>
</div>';

}

// The loginsecurity Admin Options Page
function loginsecurity_page_dashboard(){
	
	global $loginsecurity, $lz_error, $lz_env;
	
	// Is there a license key ?
	if(isset($_POST['save_lz'])){
	
		$license = lz_optpost('lz_license');
		
		// Check if its a valid license
		if(empty($license)){
			$lz_error['lic_invalid'] = __('The license key was not submitted', 'loginsecurity');
			return loginsecurity_page_dashboard_T();
		}
		
		$resp = wp_remote_get(loginsecurity_API.'license.php?license='.$license);
		
		if(is_array($resp)){
			$json = json_decode($resp['body'], true);
			//print_r($json);
		}
		
		// Save the License
		if(empty($json)){
		
			$lz_error['lic_invalid'] = __('The license key is invalid', 'loginsecurity');
			return loginsecurity_page_dashboard_T();
			
		}else{
			
			update_option('loginsecurity_license', $json);
			
			// Mark as saved
			$GLOBALS['lz_saved'] = true;
		}
		
	}
	
	loginsecurity_page_dashboard_T();
	
}

// The loginsecurity Admin Options Page - THEME
function loginsecurity_page_dashboard_T(){
	
	global $loginsecurity, $lz_error, $lz_env;

	loginsecurity_page_header('loginsecurity Dashboard');
?>
<style>
.welcome-panel{
	margin: 0px;
	padding: 10px;
}

input[type="text"], textarea, select {
    width: 70%;
}

.form-table label{
	font-weight:bold;
}

.exp{
	font-size:12px;
}
</style>
	
	<?php	
	

	// Saved ?
	if(!empty($GLOBALS['lz_saved'])){
		echo '<div id="message" class="updated"><p>'. __('The settings were saved successfully', 'loginsecurity'). '</p></div><br />';
	}
	
	// Any errors ?
	if(!empty($lz_error)){
		lz_report_error($lz_error);echo '<br />';
	}
	
	?>	
	
	<div class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: Getting Started</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('Getting Started', 'loginsecurity'); ?></span>
		</h2>
		
		<div class="inside">
		
		<form action="" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('loginsecurity-options'); ?>
		<table class="form-table">
			<tr>
				<td scope="row" valign="top" colspan="2" style="line-height:150%">
					<i>Welcome to loginsecurity Security. By default the <b>Brute Force Protection</b> is immediately enabled. You should start by going over the default settings and tweaking them as per your needs.</i>
					<?php 
					if(defined('loginsecurity_PREMIUM')){
						echo '<br><i>In the Premium version of loginsecurity you have many more features. We recommend you enable features like <b>reCAPTCHA, Two Factor Auth or Email based PasswordLess</b> login. These features will improve your websites security.</i>';
					} 
					?>
				</td>
			</tr>
		</table>
		</form>
		
		</div>
	</div>
	
	<div class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: System Information</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('System Information', 'loginsecurity'); ?></span>
		</h2>
		
		<div class="inside">
		
		<form action="" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('loginsecurity-options'); ?>
		<table class="wp-list-table fixed striped users" cellspacing="1" border="0" width="95%" cellpadding="10" align="center">
		<?php
			echo '
			<tr>				
				<th align="left" width="25%">'.__('loginsecurity Version', 'loginsecurity').'</th>
				<td>'.loginsecurity_VERSION.(defined('loginsecurity_PREMIUM') ? ' (Security PRO Version)' : '').'</td>
			</tr>';
			
			if(defined('loginsecurity_PREMIUM')){
			echo '
			<tr>			
				<th align="left" valign="top">'.__('loginsecurity License', 'loginsecurity').'</th>
				<td align="left">
					'.(empty($loginsecurity['license']) ? '<span style="color:red">Unlicensed</span> &nbsp; &nbsp;' : '').' 
					<input type="text" name="lz_license" value="'.(empty($loginsecurity['license']) ? '' : $loginsecurity['license']['license']).'" size="30" placeholder="e.g. WXCSE-SFJJX-XXXXX-AAAAA-BBBBB" style="width:300px;" /> &nbsp; 
					<input name="save_lz" class="button button-primary" value="Update License" type="submit" />';
					
					if(!empty($loginsecurity['license'])){
						
						$expires = $loginsecurity['license']['expires'];
						$expires = substr($expires, 0, 4).'/'.substr($expires, 4, 2).'/'.substr($expires, 6);
						
						echo '<div style="margin-top:10px;">License Active : '.(empty($loginsecurity['license']['active']) ? '<span style="color:red">No</span>' : 'Yes').' &nbsp; &nbsp; &nbsp; 
						License Expires : '.($loginsecurity['license']['expires'] <= date('Ymd') ? '<span style="color:red">'.$expires.'</span>' : $expires).'
						</div>';
					}
					
					
				echo 
				'</td>
			</tr>';
			}
			
			echo '<tr>
				<th align="left">'.__('URL', 'loginsecurity').'</th>
				<td>'.get_site_url().'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Path', 'loginsecurity').'</th>
				<td>'.ABSPATH.'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Server\'s IP Address', 'loginsecurity').'</th>
				<td>'.$_SERVER['SERVER_ADDR'].'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Your IP Address', 'loginsecurity').'</th>
				<td>'.$_SERVER['REMOTE_ADDR'].'</td>
			</tr>
			<tr>				
				<th align="left">'.__('wp-config.php is writable', 'loginsecurity').'</th>
				<td>'.(is_writable(ABSPATH.'/wp-config.php') ? '<span style="color:red">Yes</span>' : '<span style="color:green">No</span>').'</td>
			</tr>';
			
			if(file_exists(ABSPATH.'/.htaccess')){
				echo '
			<tr>				
				<th align="left">'.__('.htaccess is writable', 'loginsecurity').'</th>
				<td>'.(is_writable(ABSPATH.'/.htaccess') ? '<span style="color:red">Yes</span>' : '<span style="color:green">No</span>').'</td>
			</tr>';
			
			}
			
		?>
		</table>
		</form>
		
		</div>
	</div>
	
	<div id="" class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: File Permissions</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('File Permissions', 'loginsecurity'); ?></span>
		</h2>
		
		<div class="inside">
		
		<form action="" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('loginsecurity-options'); ?>
		<table class="wp-list-table fixed striped users" border="0" width="95%" cellpadding="10" align="center">
			<?php
			
			echo '
			<tr>
				<th style="background:#EFEFEF;">'.__('Relative Path', 'loginsecurity').'</th>
				<th style="width:10%; background:#EFEFEF;">'.__('Suggested', 'loginsecurity').'</th>
				<th style="width:10%; background:#EFEFEF;">'.__('Actual', 'loginsecurity').'</th>
			</tr>';
			
			$wp_content = basename(dirname(dirname(dirname(__FILE__))));
			
			$files_to_check = array('/' => '0755',
								'/wp-admin' => '0755',
								'/wp-includes' => '0755',
								'/wp-config.php' => '0444',
								'/'.$wp_content => '0755',
								'/'.$wp_content.'/themes' => '0755',
								'/'.$wp_content.'/plugins' => '0755',
								'.htaccess' => '0444');
			
			$root = ABSPATH;
			
			foreach($files_to_check as $k => $v){
				
				$path = $root.'/'.$k;
				$stat = @stat($path);
				$suggested = $v;
				$actual = substr(sprintf('%o', $stat['mode']), -4);
				
				echo '
			<tr>
				<td>'.$k.'</td>
				<td>'.$suggested.'</td>
				<td><span '.($suggested != $actual ? 'style="color: red;"' : '').'>'.$actual.'</span></td>
			</tr>';
				
			}
			
			?>
		</table>
		</form>
		
		</div>
	</div>

<?php
	
	loginsecurity_page_footer();

}

// The loginsecurity Admin Options Page
function loginsecurity_page_brute_force(){

	global $wpdb, $wp_roles, $loginsecurity;
	 
	if(!current_user_can('manage_options')){
		wp_die('Sorry, but you do not have permissions to change settings.');
	}

	/* Make sure post was from this page */
	if(count($_POST) > 0){
		check_admin_referer('loginsecurity-options');
	}
	
	// BEGIN THEME
	loginsecurity_page_header('loginsecurity - Brute Force Settings');
	
	// Load the blacklist and whitelist
	$loginsecurity['blacklist'] = get_option('loginsecurity_blacklist');
	$loginsecurity['whitelist'] = get_option('loginsecurity_whitelist');
	
	if(isset($_POST['save_lz'])){
		
		$max_retries = (int) lz_optpost('max_retries');
		$lockout_time = (int) lz_optpost('lockout_time');
		$max_lockouts = (int) lz_optpost('max_lockouts');
		$lockouts_extend = (int) lz_optpost('lockouts_extend');
		$reset_retries = (int) lz_optpost('reset_retries');
		$notify_email = (int) lz_optpost('notify_email');
		
		$lockout_time = $lockout_time * 60;
		$lockouts_extend = $lockouts_extend * 60 * 60;
		$reset_retries = $reset_retries * 60 * 60;
		
		if(empty($error)){
			
			$option['max_retries'] = $max_retries;
			$option['lockout_time'] = $lockout_time;
			$option['max_lockouts'] = $max_lockouts;
			$option['lockouts_extend'] = $lockouts_extend;
			$option['reset_retries'] = $reset_retries;
			$option['notify_email'] = $notify_email;
			
			// Save the options
			update_option('loginsecurity_options', $option);
			
			$saved = true;
			
		}else{
			lz_report_error($error);
		}
	
		if(!empty($notice)){
			lz_report_notice($notice);	
		}
			
		if(!empty($saved)){
			echo '<div id="message" class="updated"><p>'
				. __('The settings were saved successfully', 'loginsecurity')
				. '</p></div><br />';
		}
	
	}
	
	// Delete a Blackist IP range
	if(isset($_GET['bdelid'])){
		
		$delid = (int) lz_optreq('bdelid');
		
		// Unset and save
		$blacklist = $loginsecurity['blacklist'];
		unset($blacklist[$delid]);
		update_option('loginsecurity_blacklist', $blacklist);
		
		echo '<div id="message" class="updated fade"><p>'
			. __('The Blacklist IP range has been deleted successfully', 'loginsecurity')
			. '</p></div><br />';
			
	}
	
	// Delete a Whitelist IP range
	if(isset($_GET['delid'])){
		
		$delid = (int) lz_optreq('delid');
		
		// Unset and save
		$whitelist = $loginsecurity['whitelist'];
		unset($whitelist[$delid]);
		update_option('loginsecurity_whitelist', $whitelist);
		
		echo '<div id="message" class="updated fade"><p>'
			. __('The Whitelist IP range has been deleted successfully', 'loginsecurity')
			. '</p></div><br />';
			
	}
	
	if(isset($_POST['blacklist_iprange'])){

		$start_ip = lz_optpost('start_ip');
		$end_ip = lz_optpost('end_ip');
		
		if(empty($start_ip)){
			$error[] = 'Please enter the Start IP';
		}
		
		// If no end IP we consider only 1 IP
		if(empty($end_ip)){
			$end_ip = $start_ip;
		}
				
		if(!lz_valid_ip($start_ip)){
			$error[] = 'Please provide a valid start IP';
		}
		
		if(!lz_valid_ip($end_ip)){
			$error[] = 'Please provide a valid end IP';			
		}
		
		// Regular ranges will work
		if(ip2long($start_ip) > ip2long($end_ip)){
			
			// BUT, if 0.0.0.1 - 255.255.255.255 is given, it will not work
			if(ip2long($start_ip) >= 0 && ip2long($end_ip) < 0){
				// This is right
			}else{
				$error[] = 'The End IP cannot be smaller than the Start IP';
			}
			
		}
		
		if(empty($error)){
			
			$blacklist = $loginsecurity['blacklist'];
			
			foreach($blacklist as $k => $v){
				
				// This is to check if there is any other range exists with the same Start or End IP
				if(( ip2long($start_ip) <= ip2long($v['start']) && ip2long($v['start']) <= ip2long($end_ip) )
					|| ( ip2long($start_ip) <= ip2long($v['end']) && ip2long($v['end']) <= ip2long($end_ip) )
				){
					$error[] = 'The Start IP or End IP submitted conflicts with an existing IP range !';
					break;
				}
				
				// This is to check if there is any other range exists with the same Start IP
				if(ip2long($v['start']) <= ip2long($start_ip) && ip2long($start_ip) <= ip2long($v['end'])){
					$error[] = 'The Start IP is present in an existing range !';
					break;
				}
				
				// This is to check if there is any other range exists with the same End IP
				if(ip2long($v['start']) <= ip2long($end_ip) && ip2long($end_ip) <= ip2long($v['end'])){
					$error[] = 'The End IP is present in an existing range!';
					break;
				}
				
			}
			
			$newid = ( empty($blacklist) ? 0 : max(array_keys($blacklist)) ) + 1;
		
			if(empty($error)){
				
				$blacklist[$newid] = array();
				$blacklist[$newid]['start'] = $start_ip;
				$blacklist[$newid]['end'] = $end_ip;
				$blacklist[$newid]['time'] = time();
				
				update_option('loginsecurity_blacklist', $blacklist);
				
				echo '<div id="message" class="updated fade"><p>'
						. __('Blacklist IP range added successfully', 'loginsecurity')
						. '</p></div><br />';
				
			}
			
		}
		
		if(!empty($error)){
			lz_report_error($error);echo '<br />';
		}
		
	}
	
	if(isset($_POST['whitelist_iprange'])){

		$start_ip = lz_optpost('start_ip_w');
		$end_ip = lz_optpost('end_ip_w');
		
		if(empty($start_ip)){
			$error[] = 'Please enter the Start IP';
		}
		
		// If no end IP we consider only 1 IP
		if(empty($end_ip)){
			$end_ip = $start_ip;
		}
				
		if(!lz_valid_ip($start_ip)){
			$error[] = 'Please provide a valid start IP';
		}
		
		if(!lz_valid_ip($end_ip)){
			$error[] = 'Please provide a valid end IP';			
		}
			
		if(ip2long($start_ip) > ip2long($end_ip)){
			
			// BUT, if 0.0.0.1 - 255.255.255.255 is given, it will not work
			if(ip2long($start_ip) >= 0 && ip2long($end_ip) < 0){
				// This is right
			}else{
				$error[] = 'The End IP cannot be smaller than the Start IP';
			}
			
		}
		
		if(empty($error)){
			
			$whitelist = $loginsecurity['whitelist'];
			
			foreach($whitelist as $k => $v){
				
				// This is to check if there is any other range exists with the same Start or End IP
				if(( ip2long($start_ip) <= ip2long($v['start']) && ip2long($v['start']) <= ip2long($end_ip) )
					|| ( ip2long($start_ip) <= ip2long($v['end']) && ip2long($v['end']) <= ip2long($end_ip) )
				){
					$error[] = 'The Start IP or End IP submitted conflicts with an existing IP range !';
					break;
				}
				
				// This is to check if there is any other range exists with the same Start IP
				if(ip2long($v['start']) <= ip2long($start_ip) && ip2long($start_ip) <= ip2long($v['end'])){
					$error[] = 'The Start IP is present in an existing range !';
					break;
				}
				
				// This is to check if there is any other range exists with the same End IP
				if(ip2long($v['start']) <= ip2long($end_ip) && ip2long($end_ip) <= ip2long($v['end'])){
					$error[] = 'The End IP is present in an existing range!';
					break;
				}
				
			}
			
			$newid = ( empty($whitelist) ? 0 : max(array_keys($whitelist)) ) + 1;
			
			if(empty($error)){
				
				$whitelist[$newid] = array();
				$whitelist[$newid]['start'] = $start_ip;
				$whitelist[$newid]['end'] = $end_ip;
				$whitelist[$newid]['time'] = time();
				
				update_option('loginsecurity_whitelist', $whitelist);
				
				echo '<div id="message" class="updated fade"><p>'
						. __('Whitelist IP range added successfully', 'loginsecurity')
						. '</p></div><br />';
				
			}
			
		}
		
		if(!empty($error)){
			lz_report_error($error);echo '<br />';
		}
	}
	
	// Get the logs
	$result = array();
	$result = lz_selectquery("SELECT * FROM `".$wpdb->prefix."loginsecurity_logs` ORDER BY `time` DESC LIMIT 0, 15;", 1);
	//print_r($result);
	
	// Reload the settings
	$loginsecurity['blacklist'] = get_option('loginsecurity_blacklist');
	$loginsecurity['whitelist'] = get_option('loginsecurity_whitelist');
	
	?>

	<div id="" class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: Failed Login Attempts Logs</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<?php echo __('<span>Failed Login Attempts Logs</span> &nbsp; (Past '.($loginsecurity['reset_retries']/60/60).' hours)','loginsecurity'); ?>
		</h2>
		
		<div class="inside">
		<table class="wp-list-table widefat fixed users" border="0">
			<tr>
				<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('IP','loginsecurity'); ?></th>
				<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('Last Failed Attempt  (DD/MM/YYYY)','loginsecurity'); ?></th>
				<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('Failed Attempts Count','loginsecurity'); ?></th>
				<th scope="row" valign="top" style="background:#EFEFEF;" width="150"><?php echo __('Lockouts Count','loginsecurity'); ?></th>
			</tr>
			<?php
				if(empty($result)){
					echo '
					<tr>
						<td colspan="4">
							No Logs. You will see logs about failed login attempts here.
						</td>
					</tr>';
				}else{
					foreach($result as $ik => $iv){
						$status_button = (!empty($iv['status']) ? 'disable' : 'enable');
						echo '
						<tr>
							<td>
								'.$iv['ip'].'
							</td>
							<td>
								'.date('d/m/Y H:i:s', $iv['time']).'
							</td>
							<td>
								'.$iv['count'].'
							</td>
							<td>
								'.$iv['lockout'].'
							</td>
						</tr>';
					}
				}
			?>
		</table>
		</div>
	</div>	
	<br />
	
	<div id="" class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: Brute Force Settings</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('Brute Force Settings', 'loginsecurity'); ?></span>
		</h2>
		
		<div class="inside">
		
		<form action="" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('loginsecurity-options'); ?>
		<table class="form-table">
			<tr>
				<th scope="row" valign="top"><label for="max_retries"><?php echo __('Max Retries','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="3" value="<?php echo lz_optpost('max_retries', $loginsecurity['max_retries']); ?>" name="max_retries" id="max_retries" /> <?php echo __('Maximum failed attempts allowed before lockout','loginsecurity'); ?> <br />
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="lockout_time"><?php echo __('Lockout Time','loginsecurity'); ?></label></th>
				<td>
				<input type="text" size="3" value="<?php echo (!empty($lockout_time) ? $lockout_time : $loginsecurity['lockout_time']) / 60; ?>" name="lockout_time" id="lockout_time" /> <?php echo __('minutes','loginsecurity'); ?> <br />
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="max_lockouts"><?php echo __('Max Lockouts','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="3" value="<?php echo lz_optpost('max_lockouts', $loginsecurity['max_lockouts']); ?>" name="max_lockouts" id="max_lockouts" /> <?php echo __('','loginsecurity'); ?> <br />
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="lockouts_extend"><?php echo __('Extend Lockout','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="3" value="<?php echo (!empty($lockouts_extend) ? $lockouts_extend : $loginsecurity['lockouts_extend']) / 60 / 60; ?>" name="lockouts_extend" id="lockouts_extend" /> <?php echo __('hours. Extend Lockout time after Max Lockouts','loginsecurity'); ?> <br />
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="reset_retries"><?php echo __('Reset Retries','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="3" value="<?php echo (!empty($reset_retries) ? $reset_retries : $loginsecurity['reset_retries']) / 60 / 60; ?>" name="reset_retries" id="reset_retries" /> <?php echo __('hours','loginsecurity'); ?> <br />
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="notify_email"><?php echo __('Email Notification','loginsecurity'); ?></label></th>
				<td>
					<?php echo __('after ','loginsecurity'); ?>
					<input type="text" size="3" value="<?php echo (!empty($notify_email) ? $notify_email : $loginsecurity['notify_email']); ?>" name="notify_email" id="notify_email" /> <?php echo __('lockouts <br />0 to disable email notifications','loginsecurity'); ?>
				</td>
			</tr>
		</table><br />
		<input name="save_lz" class="button button-primary action" value="<?php echo __('Save Settings','loginsecurity'); ?>" type="submit" />
		</form>
	
		</div>
	</div>
	<br />
	
	<div id="" class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: Blacklist IP</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('Blacklist IP','loginsecurity'); ?></span>
		</h2>
		
		<div class="inside">
		
		<?php echo __('Enter the IP you want to blacklist from login','loginsecurity'); ?>
	
		<form action="" method="post">
		<?php wp_nonce_field('loginsecurity-options'); ?>
		<table class="form-table">
			<tr>
				<th scope="row" valign="top"><label for="start_ip"><?php echo __('Start IP','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="25" value="<?php echo(lz_optpost('start_ip')); ?>" name="start_ip" id="start_ip"/> <?php echo __('Start IP of the range','loginsecurity'); ?> <br />
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="end_ip"><?php echo __('End IP (Optional)','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="25" value="<?php echo(lz_optpost('end_ip')); ?>" name="end_ip" id="end_ip"/> <?php echo __('End IP of the range. <br />If you want to blacklist single IP leave this field blank.','loginsecurity'); ?> <br />
				</td>
			</tr>
		</table><br />
		<input name="blacklist_iprange" class="button button-primary action" value="<?php echo __('Add Blacklist IP Range','loginsecurity'); ?>" type="submit" />		
		</form>
		</div>
		
		<table class="wp-list-table fixed striped users" border="0" width="95%" cellpadding="10" align="center">
			<tr>
				<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('Start IP','loginsecurity'); ?></th>
				<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('End IP','loginsecurity'); ?></th>
				<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('Date (DD/MM/YYYY)','loginsecurity'); ?></th>
				<th scope="row" valign="top" style="background:#EFEFEF;" width="100"><?php echo __('Options','loginsecurity'); ?></th>
			</tr>
			<?php
				if(empty($loginsecurity['blacklist'])){
					echo '
					<tr>
						<td colspan="4">
							No Blacklist IPs. You will see blacklisted IP ranges here.
						</td>
					</tr>';
				}else{
					foreach($loginsecurity['blacklist'] as $ik => $iv){
						echo '
						<tr>
							<td>
								'.$iv['start'].'
							</td>
							<td>
								'.$iv['end'].'
							</td>
							<td>
								'.date('d/m/Y', $iv['time']).'
							</td>
							<td>
								<a class="submitdelete" href="admin.php?page=loginsecurity_brute_force&bdelid='.$ik.'" onclick="return confirm(\'Are you sure you want to delete this IP range ?\')">Delete</a>
							</td>
						</tr>';
					}
				}
			?>
		</table>
		<br />
		
	</div>
	
	<br />
	
	<div id="" class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: Whitelist IP</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('Whitelist IP', 'loginsecurity'); ?></span>
		</h2>
		
		<div class="inside">
		
		<?php echo __('Enter the IP you want to whitelist for login','loginsecurity'); ?>
		<form action="" method="post">
		<?php wp_nonce_field('loginsecurity-options'); ?>
		<table class="form-table">
			<tr>
				<th scope="row" valign="top"><label for="start_ip_w"><?php echo __('Start IP','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="25" value="<?php echo(lz_optpost('start_ip_w')); ?>" name="start_ip_w" id="start_ip_w"/> <?php echo __('Start IP of the range','loginsecurity'); ?> <br />
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="end_ip_w"><?php echo __('End IP (Optional)','loginsecurity'); ?></label></th>
				<td>
					<input type="text" size="25" value="<?php echo(lz_optpost('end_ip_w')); ?>" name="end_ip_w" id="end_ip_w"/> <?php echo __('End IP of the range. <br />If you want to whitelist single IP leave this field blank.','loginsecurity'); ?> <br />
				</td>
			</tr>
		</table><br />
		<input name="whitelist_iprange" class="button button-primary action" value="<?php echo __('Add Whitelist IP Range','loginsecurity'); ?>" type="submit" />
		</form>
		</div>
		
		<table class="wp-list-table fixed striped users" border="0" width="95%" cellpadding="10" align="center">
		<tr>
			<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('Start IP','loginsecurity'); ?></th>
			<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('End IP','loginsecurity'); ?></th>
			<th scope="row" valign="top" style="background:#EFEFEF;"><?php echo __('Date (DD/MM/YYYY)','loginsecurity'); ?></th>
			<th scope="row" valign="top" style="background:#EFEFEF;" width="100"><?php echo __('Options','loginsecurity'); ?></th>
		</tr>
		<?php
			if(empty($loginsecurity['whitelist'])){
				echo '
				<tr>
					<td colspan="4">
						No Whitelist IPs. You will see whitelisted IP ranges here.
					</td>
				</tr>';
			}else{
				foreach($loginsecurity['whitelist'] as $ik => $iv){
					echo '
					<tr>
						<td>
							'.$iv['start'].'
						</td>
						<td>
							'.$iv['end'].'
						</td>
						<td>
							'.date('d/m/Y', $iv['time']).'
						</td>
						<td>
							<a class="submitdelete" href="admin.php?page=loginsecurity_brute_force&delid='.$ik.'" onclick="return confirm(\'Are you sure you want to delete this IP range ?\')">Delete</a>
						</td>
					</tr>';
				}
			}
		?>
		</table>
		<br />
	
	</div>
	
<?php

loginsecurity_page_footer();

}


// Sorry to see you going
register_uninstall_hook(loginsecurity_FILE, 'loginsecurity_deactivation');

function loginsecurity_deactivation(){

global $wpdb;

	$sql = array();
	$sql[] = "DROP TABLE ".$wpdb->prefix."loginsecurity_logs;";

	foreach($sql as $sk => $sv){
		$wpdb->query($sv);
	}

	delete_option('loginsecurity_version');
	delete_option('loginsecurity_options');
	delete_option('loginsecurity_last_reset');
	delete_option('loginsecurity_whitelist');
	delete_option('loginsecurity_blacklist');

}

