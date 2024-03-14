<?php
/**
 * @package smart-maintenance-mode
 * @version 1.4.4
 */
/*
Plugin Name: Smart Maintenance Mode
Plugin URI: http://wordpress.org/extend/plugins/smart-maintenance-mode/
Description: Smart Maintenance Mode is a plugin which allows you to set your site to maintenance mode so that your readers see the Coming Soon page while you can see the actual development of your site. You can create ranges and define the IP range which will see the actual site using Smart Maintenance Mode.
Version: 1.4.4
Text Domain: smart-maintenance-mode
Domain Path: /languages/
Author: Brijesh Kothari
Author URI: https://profiles.wordpress.org/brijeshk89/
License: GPLv3 or later
*/

/*
Copyright (C) 2013  Brijesh Kothari (email : admin@wpinspired.com)
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

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

function smart_maintenance_mode_load_plugin_textdomain(){
    load_plugin_textdomain( 'smart-maintenance-mode', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'smart_maintenance_mode_load_plugin_textdomain' );

define('smm_version', '1.4.4');

// Ok so we are now ready to go
register_activation_hook( __FILE__, 'smart_maintenance_mode_activation');

function smart_maintenance_mode_activation(){

global $wpdb;

$sql = "
--
-- Table structure for table `".$wpdb->prefix."smart_maintenance_mode`
--

CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smart_maintenance_mode` (
  `rid` int(10) NOT NULL AUTO_INCREMENT,
  `start` bigint(20) NOT NULL,
  `end` bigint(20) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `date` int(10) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

	$wpdb->query($sql);
	
	add_option('smm_version', smm_version);
	add_option('disable_smm', 0);

}

add_action( 'plugins_loaded', 'smart_maintenance_mode_update_check' );

function smart_maintenance_mode_update_check(){

global $wpdb;

	$sql = array();
	$current_version = get_option('smm_version');

	if($current_version < smm_version){
		foreach($sql as $sk => $sv){
			$wpdb->query($sv);
		}

		update_option('smm_version', smm_version);
	}

}

function maintenance_mode_page(){
	
	global $wpdb, $current_user;
	
	$logged_ip = smm_getip();
	$result = '';
	$disable_smm = get_option('disable_smm');
	$smm_heading = base64_decode(get_option('smm_heading'));
	$smm_subheading = base64_decode(get_option('smm_subheading'));
	$smm_image = get_option('smm_image');
	$smm_roles = unserialize(get_option('smm_roles'));
	$smm_html = base64_decode(get_option('smm_html'));
	$smm_countdown = unserialize(get_option('smm_countdown'));
	
	$current_user_role = current($current_user->roles);
	
	$smm_heading = (!empty($smm_heading) ? $smm_heading : 'Website Under Maintenance');
	$smm_subheading = (!empty($smm_subheading) ? $smm_subheading : 'This website is currently under going maintenance. We will be back online shortly. Please check back later !');
	
	$query = "SELECT * FROM ".$wpdb->prefix."smart_maintenance_mode WHERE ".ip2long($logged_ip)." BETWEEN `start` AND `end` AND `status` = 1";
	$result = smm_selectquery($query);
	
	// Show the maintenance mode page
	if(!empty($_REQUEST['smm_preview']) || ((empty($result) && empty($smm_roles[$current_user_role]) && empty($disable_smm)))){
		
		if(!empty($smm_html)){
			echo $smm_html;
			exit;
		}
		
		if(!empty($smm_countdown)){
						
			// What is the countdown date ?
			$count_date = (float) $smm_countdown['year'].$smm_countdown['month'].$smm_countdown['day'].$smm_countdown['hour'].$smm_countdown['minute'].$smm_countdown['second'];
			
			$cur_date = date('YmdHis');
			
			// Just for debug
			if(!empty($_GET['debug'])){
				echo $cur_date.'<br />';
				echo $count_date.'<br />';
			}
			
			// If we have passed the coundown time we need to exit
			if($cur_date >= $count_date){
				return true;
			}
		
			echo '<script src="wp-content/plugins/smart-maintenance-mode/js/countdown.js" type="text/javascript"></script>';			
			echo '<title>'.get_option('blogname').' | Maintenance Mode</title>';
			echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
			
			if(!empty($smm_countdown['heading'])){
				echo '<div align="center" style="font-family: Verdana; color: black;">';
				echo '<h1>'.base64_decode($smm_countdown['heading']).'</h1>';
				echo '</div>';
			}
			
			echo '<center>
				<script type="application/javascript">
				
					function smm_done_handler(){
						window.location.href =  "'.get_option('siteurl').'";
					}
					
					var offset = (new Date().getTimezoneOffset()/60) * -1;
					
					var myCountdown1 = new Countdown({
						year: '.$smm_countdown['year'].', 
						month: '.$smm_countdown['month'].',
						day: '.$smm_countdown['day'].',
						hour: '.$smm_countdown['hour'].',
						minute: '.$smm_countdown['minute'].',
						second: '.$smm_countdown['second'].',
						width:550,
						height:90,
						onComplete : smm_done_handler,
						rangeHi:"'.($smm_countdown['year'] == date('Y') ? 'month' : 'year').'",
						offset: offset,
						style:"flip"	// <- no comma on last item!
					});
				</script>
			</center>';
			
			exit;
		
		}
		
		echo '<title>'.get_option('blogname').' | Maintenance Mode</title>';
		
		if(!empty($smm_image) && file_exists(dirname(__FILE__).'/images/'.$smm_image)){
			echo '<center><img src="'.plugins_url('smart-maintenance-mode/images/'.$smm_image).'" alt="Maintenance Mode"></center>';
			exit;
		}
		
		echo '<div align="center" style="font-family: Verdana; color: black;">';
		echo '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
		echo '<h1>'.$smm_heading.'</h1>';
		echo $smm_subheading;
		echo '</div>';
		exit;
	}
}


add_action('template_redirect', 'maintenance_mode_page');

// Add settings link on plugin page
function smm_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=smart-maintenance-mode">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'smm_settings_link' );

add_action('admin_menu', 'smart_maintenance_mode_admin_menu');

function smm_getip(){
	if(isset($_SERVER["REMOTE_ADDR"])){
		return $_SERVER["REMOTE_ADDR"];
	}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(isset($_SERVER["HTTP_CLIENT_IP"])){
		return $_SERVER["HTTP_CLIENT_IP"];
	}
}

function smm_selectquery($query){
	global $wpdb;
	
	$result = $wpdb->get_results($query, 'ARRAY_A');
	return current($result);
}

function smart_maintenance_mode_admin_menu() {
	global $wp_version;

	// Modern WP?
	if (version_compare($wp_version, '3.0', '>=')) {
	    add_options_page('Maintenance Mode', 'Maintenance Mode', 'manage_options', 'smart-maintenance-mode', 'smart_maintenance_mode_option_page');
	    return;
	}

	// Older WPMU?
	if (function_exists("get_current_site")) {
	    add_submenu_page('wpmu-admin.php', 'Maintenance Mode', 'Maintenance Mode', 9, 'smart-maintenance-mode', 'smart_maintenance_mode_option_page');
	    return;
	}

	// Older WP
	add_options_page('Maintenance Mode', 'Maintenance Mode', 9, 'smart-maintenance-mode', 'smart_maintenance_mode_option_page');
}

function smm_sanitize_variables($variables = array()){
	
	if(is_array($variables)){
		foreach($variables as $k => $v){
			$variables[$k] = trim($v);
			$variables[$k] = escapeshellcmd($v);
			$variables[$k] = esc_sql($v);
		}
	}else{
		$variables = esc_sql(escapeshellcmd(trim($variables)));
	}
	
	return $variables;
}

function smm_valid_ip($ip){

	if(!ip2long($ip)){
		return false;
	}	
	return true;
}

function smm_is_checked($post){

	if(!empty($_POST[$post])){
		return true;
	}	
	return false;
}

function smm_report_error($error = array()){

	if(empty($error)){
		return true;
	}
	
	$error_string = '<b>Please fix the below error(s) :</b> <br />';
	
	foreach($error as $ek => $ev){
		$error_string .= '* '.$ev.'<br />';
	}
	
	echo '<div id="message" class="error"><p>'
					. __($error_string, 'smart-maintenance-mode')
					. '</p></div>';
}

function smm_report_notice($notice = array()){

	global $wp_version;
	
	if(empty($notice)){
		return true;
	}
	
	// Which class do we have to use ?
	if(version_compare($wp_version, '3.8', '<')){
		$notice_class = 'updated';
	}else{
		$notice_class = 'updated';
	}
	
	$notice_string = '<b>Please check the below notice(s) :</b> <br />';
	
	foreach($notice as $ek => $ev){
		$notice_string .= '* '.$ev.'<br />';
	}
	
	echo '<div id="message" class="'.$notice_class.'"><p>'
					. __($notice_string, 'smart-maintenance-mode')
					. '</p></div>';
}

function smm_objectToArray($d){
  if(is_object($d)){
    $d = get_object_vars($d);
  }
  
  if(is_array($d)){
    return array_map(__FUNCTION__, $d); // recursive
  }elseif(is_object($d)){
    return smm_objectToArray($d);
  }else{
    return $d;
  }
}

function smart_maintenance_mode_option_page(){

	global $wpdb, $wp_roles;
	 
	if(!current_user_can('manage_options')){
		wp_die('Sorry, but you do not have permissions to change settings.');
	}

	/* Make sure post was from this page */
	if(count($_POST) > 0){
		check_admin_referer('smart-maintenance-mode-options');
	}
	
	if(isset($_POST['save_user_roles'])){
		$_user_roles = array();
		
		foreach($wp_roles->roles as $srk => $srv){
			$_user_roles[$srk] = (smm_is_checked('role_'.$srk) ? 1 : 0);
		}
		
		update_option('smm_roles', serialize($_user_roles));		

		$saved = true;
			
		if(!empty($saved)){
			echo '<div id="message" class="updated fade"><p>'
				. __('The settings were saved successfully', 'smart-maintenance-mode')
				. '</p></div>';
		}
	}
	
	if(isset($_POST['save_smm'])){
		
		$options = array();
		$options['disable_smm'] = (smm_is_checked('disable_smm') ? 1 : 0);
		$smart_smm_heading = base64_encode(stripslashes(trim($_POST['smm_heading'])));
		$smart_smm_subheading = base64_encode(stripslashes(trim($_POST['smm_subheading'])));
		$smart_smm_html = base64_encode(stripslashes(trim($_POST['smm_html'])));
		$smm_countdown_year = trim($_POST['smm_countdown_year']);
		$smm_countdown_month = trim($_POST['smm_countdown_month']);
		$smm_countdown_day = trim($_POST['smm_countdown_day']);
		$smm_countdown_hour = trim($_POST['smm_countdown_hour']);
		$smm_countdown_minute = trim($_POST['smm_countdown_minute']);
		$smm_countdown_second = trim($_POST['smm_countdown_second']);
		
		// Default values
		if(!empty($smm_countdown_year)){
			
			if(empty($smm_countdown_month)){
				$smm_countdown_month = '01';
			}
			
			if(empty($smm_countdown_day)){
				$smm_countdown_day = '01';
			}
			
			if(empty($smm_countdown_hour)){
				$smm_countdown_hour = '00';
			}
			
			if(empty($smm_countdown_minute)){
				$smm_countdown_minute = '00';
			}
			
			if(empty($smm_countdown_second)){
				$smm_countdown_second = '00';
			}
		}
		
		$options['del_smm_image'] = (smm_is_checked('del_smm_image') ? 1 : 0);
		$options['del_smm_heading'] = (smm_is_checked('del_smm_heading') ? 1 : 0);
		$options['del_smm_subheading'] = (smm_is_checked('del_smm_subheading') ? 1 : 0);
		$options['del_smm_html'] = (smm_is_checked('del_smm_html') ? 1 : 0);
		$options['del_smm_countdown'] = (smm_is_checked('del_smm_countdown') ? 1 : 0);

		$smart_maintenance_mode_options = smm_sanitize_variables($smart_maintenance_mode_options);
	
		if(!empty($smm_countdown_year)){
			if(date('YmdHis') > $smm_countdown_year.$smm_countdown_month.$smm_countdown_day.$smm_countdown_hour.$smm_countdown_minute.$smm_countdown_second){
				$error[] = 'The date for the Countdown cannot be a date in past';
			}
		}
		
		if(!empty($options['del_smm_image'])){
			$smm_image = get_option('smm_image');
			update_option('smm_image', '');
			@unlink(dirname(__FILE__)."/images/".$smm_image);
		}
	
		if(!empty($options['del_smm_heading'])){
			update_option('smm_heading', '');
			$_POST['smm_heading'] = '';
		}
	
		if(!empty($options['del_smm_subheading'])){
			update_option('smm_subheading', '');
			$_POST['smm_subheading'] = '';
		}
	
		if(!empty($options['del_smm_html'])){
			update_option('smm_html', '');
			$_POST['smm_html'] = '';
		}
	
		if(!empty($options['del_smm_countdown'])){
			update_option('smm_countdown', '');
			$_POST['smm_countdown_year'] = '';
			$_POST['smm_countdown_month'] = '';
			$_POST['smm_countdown_day'] = '';
			$_POST['smm_countdown_hour'] = '';
			$_POST['smm_countdown_minute'] = '';
			$_POST['smm_countdown_second'] = '';
		}
		
		if(!empty($_FILES["smm_file"]["name"])){
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["smm_file"]["name"]);
			$extension = end($temp);
			
			if((($_FILES["smm_file"]["type"] == "image/gif")
			|| ($_FILES["smm_file"]["type"] == "image/jpeg")
			|| ($_FILES["smm_file"]["type"] == "image/jpg")
			|| ($_FILES["smm_file"]["type"] == "image/pjpeg")
			|| ($_FILES["smm_file"]["type"] == "image/x-png")
			|| ($_FILES["smm_file"]["type"] == "image/png"))
			&& in_array($extension, $allowedExts)){
				
				if($_FILES["smm_file"]["error"] > 0){
					$error[] = "Error Uploading image! Return Code: ".$_FILES["smm_file"]["error"];
				}else{
									
					if(file_exists(dirname(__FILE__)."/images/".$_FILES["smm_file"]["name"])){
						$error[] = $_FILES["smm_file"]["name"]." already exists.";
					}else{
						$_smm_image = true;
						if(!@move_uploaded_file($_FILES["smm_file"]["tmp_name"],	dirname(__FILE__)."/images/".$_FILES["smm_file"]["name"])){
							$notice[] = 'Could not upload the custom image. Please upload the file '.$_FILES["smm_file"]["name"].' manually at '.plugin_dir_path( __FILE__ ).'images/';
						}
					}
				}
			}else{
				$error[] = "Invalid file. Allowed extensions are : ".implode(', ', $allowedExts);
			}
		}
		
		if(empty($error)){
			
			update_option('disable_smm', $options['disable_smm']);	
		
			if(!empty($smart_smm_heading) && empty($options['del_smm_heading'])){			
				update_option('smm_heading', $smart_smm_heading);			
			}
			
			if(!empty($smart_smm_subheading) && empty($options['del_smm_subheading'])){			
				update_option('smm_subheading', $smart_smm_subheading);			
			}
			
			if(!empty($_smm_image)){
				update_option('smm_image', $_FILES["smm_file"]["name"]);			
			}
			
			if(!empty($smart_smm_html) && empty($options['del_smm_html'])){			
				update_option('smm_html', $smart_smm_html);			
			}
			
			if(!empty($smm_countdown_year) && empty($options['del_smm_countdown'])){
				$smm_countdown = array();
				$smm_countdown['heading'] = $smart_smm_heading;
				$smm_countdown['year'] = $smm_countdown_year;
				$smm_countdown['month'] = $smm_countdown_month;
				$smm_countdown['day'] = $smm_countdown_day;
				$smm_countdown['hour'] = $smm_countdown_hour;
				$smm_countdown['minute'] = $smm_countdown_minute;
				$smm_countdown['second'] = $smm_countdown_second;
				$smm_countdown = serialize($smm_countdown);
				update_option('smm_countdown', $smm_countdown);
			}
									
			$saved = true;
			
		}else{
			smm_report_error($error);
		}
	
		if(!empty($notice)){
			smm_report_notice($notice);	
		}
			
		if(!empty($saved)){
			echo '<div id="message" class="updated fade"><p>'
				. __('The settings were saved successfully', 'smart-maintenance-mode')
				. '</p></div>';
		}
	
	}
	
	if(isset($_GET['delid'])){
		
		$delid = (int) smm_sanitize_variables($_GET['delid']);
		
		$wpdb->query("DELETE FROM ".$wpdb->prefix."smart_maintenance_mode WHERE `rid` = '".$delid."'");
		echo '<div id="message" class="updated fade"><p>'
			. __('IP range has been deleted successfully', 'smart-maintenance-mode')
			. '</p></div>';	
	}
	
	if(isset($_GET['statusid'])){
		
		$statusid = (int) smm_sanitize_variables($_GET['statusid']);
		$setstatus = smm_sanitize_variables($_GET['setstatus']);
		$_setstatus = ($setstatus == 'disable' ? 0 : 1);
		
		$wpdb->query("UPDATE ".$wpdb->prefix."smart_maintenance_mode SET `status` = '".$_setstatus."' WHERE `rid` = '".$statusid."'");
		echo '<div id="message" class="updated fade"><p>'
			. __('IP range has been '.$setstatus.'d successfully', 'smart-maintenance-mode')
			. '</p></div>';	
	}
	
	if(isset($_POST['add_iprange'])){
		global $smart_maintenance_mode_options;

		$smart_maintenance_mode_options['start'] = trim($_POST['start_ip']);
		$smart_maintenance_mode_options['end'] = trim($_POST['end_ip']);

		$smart_maintenance_mode_options = smm_sanitize_variables($smart_maintenance_mode_options);
				
		if(!smm_valid_ip($smart_maintenance_mode_options['start'])){
			$error[] = 'Please provide a valid start IP';
		}
		
		if(!smm_valid_ip($smart_maintenance_mode_options['end'])){
			$error[] = 'Please provide a valid end IP';			
		}
		
		// This is to check if there is any other range exists with the same Start or End IP
		$ip_exists_query = "SELECT * FROM ".$wpdb->prefix."smart_maintenance_mode WHERE 
		`start` BETWEEN '".ip2long($smart_maintenance_mode_options['start'])."' AND '".ip2long($smart_maintenance_mode_options['end'])."'
		OR `end` BETWEEN '".ip2long($smart_maintenance_mode_options['start'])."' AND '".ip2long($smart_maintenance_mode_options['end'])."';";
		$ip_exists = $wpdb->get_results($ip_exists_query);
		//print_r($ip_exists);
		
		if(!empty($ip_exists)){
			$error[] = 'The Start IP or End IP submitted conflicts with an existing IP range!';
		}
		
		// This is to check if there is any other range exists with the same Start IP
		$start_ip_exists_query = "SELECT * FROM ".$wpdb->prefix."smart_maintenance_mode WHERE 
		'".ip2long($smart_maintenance_mode_options['start'])."' BETWEEN `start` AND `end`;";
		$start_ip_exists = $wpdb->get_results($start_ip_exists_query);
		//print_r($start_ip_exists);
		
		if(!empty($start_ip_exists)){
			$error[] = 'The Start IP is present in an existing range!';
		}
		
		// This is to check if there is any other range exists with the same End IP
		$end_ip_exists_query = "SELECT * FROM ".$wpdb->prefix."smart_maintenance_mode WHERE 
		'".ip2long($smart_maintenance_mode_options['end'])."' BETWEEN `start` AND `end`;";
		$end_ip_exists = $wpdb->get_results($end_ip_exists_query);
		//print_r($end_ip_exists);
		
		if(!empty($end_ip_exists)){
			$error[] = 'The End IP is present in an existing range!';
		}
		
		if(ip2long($smart_maintenance_mode_options['start']) > ip2long($smart_maintenance_mode_options['end'])){
			$error[] = 'The end IP cannot be smaller than the start IP';			
		}
		
		if(empty($error)){
			
			$options = array();
			$options['start'] = ip2long($smart_maintenance_mode_options['start']);
			$options['end'] = ip2long($smart_maintenance_mode_options['end']);
			$options['status'] = (smm_is_checked('status') ? 1 : 0);
			$options['date'] = date('Ymd');
			
			$wpdb->insert($wpdb->prefix.'smart_maintenance_mode', $options);
			
			if(!empty($wpdb->insert_id)){
				echo '<div id="message" class="updated fade"><p>'
					. __('IP range added successfully', 'smart-maintenance-mode')
					. '</p></div>';
			}else{
				echo '<div id="message" class="updated fade"><p>'
					. __('There were some errors while adding IP range', 'smart-maintenance-mode')
					. '</p></div>';			
			}
			
		}else{
			smm_report_error($error);
		}
	}
	
	$ipranges = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."smart_maintenance_mode;", 'ARRAY_A');
	$disable_smm = get_option('disable_smm');
	$smm_heading = base64_decode(get_option('smm_heading'));
	$smm_subheading = base64_decode(get_option('smm_subheading'));
	$smm_image = get_option('smm_image');
	$smm_roles = unserialize(get_option('smm_roles'));
	$smm_html = base64_decode(get_option('smm_html'));
	$smm_countdown = unserialize(get_option('smm_countdown'));
	//print_r($smm_roles);
	
	$show_popup = 0;
	$donate_popup = get_option('smm_donate_popup');
	if(!empty($donate_popup)){
		if($donate_popup <= date('Ymd', strtotime('-1 month'))){
			$show_popup = 1;
			update_option('smm_donate_popup', date('Ymd'));
		}
	}else{
		$show_popup = 1;
		update_option('smm_donate_popup', date('Ymd'));
	}
	$show_popup = 0;
	
	echo '<script>
	var donate_popup = '.$show_popup.';
	if(donate_popup == 1){
		if(confirm("Donate $5 for Smart Maintenance Mode to support the development")){
			window.location.href =  "http://www.wpinspired.com/smart-maintenance-mode";
		}
	}
	</script>';
	
	?>
    
	<div class="wrap">
    	<!--This is intentional-->
	  <h2></h2>
      
	  <h2><?php echo __('Customize your Maintenance Mode page','smart-maintenance-mode'); ?></h2>
      <?php echo __('<a href="'.get_option('siteurl').'/?smm_preview=1">Preview Maintenance Mode</a>.','smart-maintenance-mode'); ?>
      
	  <form action="options-general.php?page=smart-maintenance-mode" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('smart-maintenance-mode-options'); ?>
	    <table class="form-table">
		  <tr>
			<th scope="row" valign="top"><?php echo __('Maintenance Mode Status','smart-maintenance-mode'); ?></th>
			<td>
            	<input type="radio" <?php if(empty($disable_smm) || @$_POST['disable_smm'] == 0) echo 'checked="checked"'; ?> name="disable_smm" value="0"/> Enabled &nbsp; &nbsp;
                <input type="radio" <?php if(!empty($disable_smm) || @$_POST['disable_smm'] == 1) echo 'checked="checked"'; ?> name="disable_smm" value="1" /> Disabled
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="smm_heading"><?php echo __('Heading','smart-maintenance-mode'); ?></label></th>
			<td>
			  <input type="text" size="25" value="<?php echo(htmlentities(isset($_POST['smm_heading']) ? stripslashes($_POST['smm_heading']) : (!empty($smm_heading) ? $smm_heading : ''))); ?>" name="smm_heading" id="smm_heading" /> <?php echo __('Enter the Heading for Maintenance Mode page','smart-maintenance-mode'); ?> <br /><br />
                <?php if(!empty($smm_heading)){
					echo '<input type="checkbox" name="del_smm_heading" '.(smm_is_checked('del_smm_heading') ? 'checked="checked"' : '').' />';
					echo __('Choose this checkbox to use default Heading ','smart-maintenance-mode');
				}
				?>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="smm_subheading"><?php echo __('Sub Heading','smart-maintenance-mode'); ?></label></th>
			<td>
			  <input type="text" size="25" value="<?php echo(htmlentities(isset($_POST['smm_subheading']) ? stripslashes($_POST['smm_subheading']) : (!empty($smm_subheading) ? $smm_subheading : ''))); ?>" name="smm_subheading" id="smm_subheading"/> <?php echo __('Enter the Sub Heading for Maintenance Mode page','smart-maintenance-mode'); ?> <br /><br />
                <?php if(!empty($smm_subheading)){
					echo '<input type="checkbox" name="del_smm_subheading" '.(smm_is_checked('del_smm_subheading') ? 'checked="checked"' : '').' />';
					echo __('Choose this checkbox to use default Sub Heading ','smart-maintenance-mode');
				}
				?>
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><?php echo __('Choose custom image','smart-maintenance-mode'); ?></th>
			<td>
                <input type="file" name="smm_file" id="smm_file" /> <?php echo __('This will display the image you upload on Maintenance mode page','smart-maintenance-mode'); ?> <br /><br />
                <?php if(!empty($smm_image)){
					echo '<input type="checkbox" name="del_smm_image" '.(smm_is_checked('del_smm_image') ? 'checked="checked"' : '').' />';
					echo __('Choose this checkbox to delete the current Custom image ','smart-maintenance-mode');
				}
				?>
			</td>
		  </tr>
          
		  <tr>
			<th scope="row" valign="top"><?php echo __('Countdown','smart-maintenance-mode'); ?></th>
			<td>
            	Year :
          		<select name="smm_countdown_year" id="smm_countdown_year">
                	<option value="">-</option>
                	<?php
						for($i = 0; $i < 10; $i++){
							echo '<option value="'.(date('Y') + $i).'" '.(($smm_countdown['year'] == (date('Y') + $i)) || ($_POST['smm_countdown_year'] == (date('Y') + $i)) ? 'selected="selected"' : '').'>'.(date('Y') + $i).'</option>';
						}
                    ?>
                </select>
            	&nbsp; Month :
          		<select name="smm_countdown_month" id="smm_countdown_month">
                	<option value="">-</option>
                	<?php
						for($i = 1; $i < 13; $i++){
							echo '<option value="'.(strlen($i) < 2 ? '0'.$i : $i).'" '.(($smm_countdown['month'] == $i) || ($_POST['smm_countdown_month'] == $i) ? 'selected="selected"' : '').'>'.(strlen($i) < 2 ? '0'.$i : $i).'</option>';
						}
                    ?>
                </select>
            	&nbsp; Day :
          		<select name="smm_countdown_day" id="smm_countdown_day">
                	<option value="">-</option>
                	<?php
						for($i = 1; $i <= 31; $i++){
							echo '<option value="'.(strlen($i) < 2 ? '0'.$i : $i).'" '.(($smm_countdown['day'] == $i) || ($_POST['smm_countdown_day'] == $i) ? 'selected="selected"' : '').'>'.(strlen($i) < 2 ? '0'.$i : $i).'</option>';
						}
                    ?>
                </select>
            	&nbsp; Hour :
          		<select name="smm_countdown_hour" id="smm_countdown_hour">
                	<option value="">-</option>
                	<?php
						for($i = 0; $i <= 24; $i++){
							echo '<option value="'.(strlen($i) < 2 ? '0'.$i : $i).'" '.(($smm_countdown['hour'] == $i) || ($_POST['smm_countdown_hour'] == $i) ? 'selected="selected"' : '').'>'.(strlen($i) < 2 ? '0'.$i : $i).'</option>';
						}
                    ?>
                </select>
            	&nbsp; Minute :
          		<select name="smm_countdown_minute" id="smm_countdown_minute">
                	<option value="">-</option>
                	<?php
						for($i = 0; $i < 60; $i++){
							echo '<option value="'.(strlen($i) < 2 ? '0'.$i : $i).'" '.(($smm_countdown['minute'] == $i) || ($_POST['smm_countdown_minute'] == $i) ? 'selected="selected"' : '').'>'.(strlen($i) < 2 ? '0'.$i : $i).'</option>';
						}
                    ?>
                </select>
            	&nbsp; Second :
          		<select name="smm_countdown_second" id="smm_countdown_second">
                	<option value="">-</option>
                	<?php
						for($i = 0; $i < 60; $i++){
							echo '<option value="'.(strlen($i) < 2 ? '0'.$i : $i).'" '.(($smm_countdown['second'] == $i) || ($_POST['smm_countdown_second'] == $i) ? 'selected="selected"' : '').'>'.(strlen($i) < 2 ? '0'.$i : $i).'</option>';
						}
                    ?>
                </select><br /><br />
			  <?php echo __('Choose the time when your site will be live. <br /><br />Maintenance Mode will be disabled once the Countdown date is crossed.','smart-maintenance-mode'); ?><br /><br />
              
              <?php echo __('Current Server Time is '.date('r'), 'smart-maintenance-mode'); ?> <br /><br />
              
              <?php if(!empty($smm_countdown)){
					echo '<input type="checkbox" name="del_smm_countdown" '.(smm_is_checked('del_smm_countdown') ? 'checked="checked"' : '').' />';
					echo __('Choose this checkbox to remove the Countdown ','smart-maintenance-mode');
				}
				?>
			</td>
		  </tr>
          
		  <tr>
			<th scope="row" valign="top"><label for="smm_html"><?php echo __('Custom HTML content','smart-maintenance-mode'); ?></label></th>
			<td>
            	<textarea rows="4" cols="50" name="smm_html" id="smm_html"><?php echo(htmlentities(isset($_POST['smm_html']) ? stripslashes($_POST['smm_html']) : (!empty($smm_html) ? $smm_html : ''))); ?></textarea>
                <br />
                <?php echo __('This will display Custom HTML content you provide on the Maintenance Mode page','smart-maintenance-mode'); ?> <br /><br />
                <?php if(!empty($smm_html)){
					echo '<input type="checkbox" name="del_smm_html" '.(smm_is_checked('del_smm_html') ? 'checked="checked"' : '').' />';
					echo __('Choose this checkbox to delete the Custom HTML content ','smart-maintenance-mode');
				}
				?>
			</td>
		  </tr>
		</table><br />
		<input name="save_smm" class="button action" value="<?php echo __('Save Settings','smart-maintenance-mode'); ?>" type="submit" />		
	  </form>
      <br />
      <h3>Content Preference for Maintenance Mode Page</h3>
      1. Custom HTML content<br />
      2. Countdown<br />
      3. Custom Image<br />
      4. Custom Heading and Sub Heading<br />
      5. Default Heading and Sub Heading
      <br /><br />
      <hr />      
	  <h2><?php echo __('Disable Maintenance Mode for User Roles','smart-maintenance-mode'); ?></h2>
	  <?php echo __('Choose the User Roles to see the actual site when the users are logged in with that user role','smart-maintenance-mode'); ?>
      
	  <form action="options-general.php?page=smart-maintenance-mode" method="post">
		<?php wp_nonce_field('smart-maintenance-mode-options'); ?>
	    <table class="form-table">
		  <tr>
          	<td>
				<?php
                    foreach($wp_roles->roles as $rk => $rv){
                        echo '<input type="checkbox" '.(!empty($smm_roles[$rk]) ? 'checked="checked"' : '').' name="role_'.$rk.'" /> ';
                        echo __($rv['name'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'smart-maintenance-mode');
                    }			
                ?>
            </td>
		  </tr>
		</table><br />
		<input name="save_user_roles" class="button action" value="<?php echo __('Save Settings','smart-maintenance-mode'); ?>" type="submit" />		
	  </form>
      
      <br /><br />
      <hr />      
	  <h2><?php echo __('Disable Maintenance Mode for your IP','smart-maintenance-mode'); ?></h2>
	  <?php echo __('Enter your IP to see the actual site while you are developing it','smart-maintenance-mode'); ?>
	  <form action="options-general.php?page=smart-maintenance-mode" method="post">
		<?php wp_nonce_field('smart-maintenance-mode-options'); ?>
	    <table class="form-table">
		  <tr>
			<th scope="row" valign="top"><label for="start_ip"><?php echo __('Start IP','smart-maintenance-mode'); ?></label></th>
			<td>
			  <input type="text" size="25" value="<?php echo((isset($_POST['start_ip']) ? trim($_POST['start_ip']) : '')); ?>" name="start_ip" id="start_ip"/> <?php echo __('Start IP of the range','smart-maintenance-mode'); ?> <br />
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><label for="end_ip"><?php echo __('End IP','smart-maintenance-mode'); ?></label></th>
			<td>
			  <input type="text" size="25" value="<?php echo((isset($_POST['end_ip']) ? trim($_POST['end_ip']) : '')); ?>" name="end_ip" id="end_ip"/> <?php echo __('End IP of the range','smart-maintenance-mode'); ?> <br />
			</td>
		  </tr>
		  <tr>
			<th scope="row" valign="top"><?php echo __('Active','smart-maintenance-mode'); ?></th>
			<td>
			  <input type="checkbox" <?php if(!isset($_POST['add_iprange']) || smm_is_checked('status')) echo 'checked="checked"'; ?> name="status" /> <?php echo __('Select the checkbox to set this range as active','smart-maintenance-mode'); ?> <br />
			</td>
		  </tr>
		</table><br />
		<input name="add_iprange" class="button action" value="<?php echo __('Add IP range','smart-maintenance-mode'); ?>" type="submit" />		
	  </form>
	</div>	
	<?php
	
	if(!empty($ipranges)){
		?>
		<br /><br />
        <?php echo __('The following IP ranges will see the actual site and other visitors will see the Maintenance Mode page. <a href="'.get_option('siteurl').'/?smm_preview=1">Preview Maintenance Mode</a>.','smart-maintenance-mode'); ?>
        <br /><br />
		<table class="wp-list-table widefat fixed users">
			<tr>
				<th scope="row" valign="top"><?php echo __('Start IP','smart-maintenance-mode'); ?></th>
				<th scope="row" valign="top"><?php echo __('End IP','smart-maintenance-mode'); ?></th>
				<th scope="row" valign="top"><?php echo __('Options','smart-maintenance-mode'); ?></th>
			</tr>
			<?php
				
				foreach($ipranges as $ik => $iv){
					$status_button = (!empty($iv['status']) ? 'disable' : 'enable');
					echo '
					<tr>
						<td>
							'.long2ip($iv['start']).'
						</td>
						<td>
							'.long2ip($iv['end']).'
						</td>
						<td>
							<a class="submitdelete" href="options-general.php?page=smart-maintenance-mode&delid='.$iv['rid'].'" onclick="return confirm(\'Are you sure you want to delete this IP range ?\')">Delete</a>&nbsp;&nbsp;
							<a class="submitdelete" href="options-general.php?page=smart-maintenance-mode&statusid='.$iv['rid'].'&setstatus='.$status_button.'" onclick="return confirm(\'Are you sure you want to '.$status_button.' this IP range ?\')">'.ucfirst($status_button).'</a>
						</td>
					</tr>';
				}
			?>
		</table>
		<?php
	}
	
	echo '<br /><br /><br /><br /><hr />
	Smart Maintenance Mode v'.smm_version.' is developed by <a href="https://wordpress.org/plugins/smart-maintenance-mode/" target="_blank">WP Inspired</a>. 
	You can report any bugs <a href="http://wordpress.org/support/plugin/smart-maintenance-mode" target="_blank">here</a>. 
	You can provide any valuable feedback <a href="http://wordpress.org/support/plugin/smart-maintenance-mode" target="_blank">here</a>.';
}	

// Sorry to see you going
register_uninstall_hook( __FILE__, 'smart_maintenance_mode_deactivation');

function smart_maintenance_mode_deactivation(){

global $wpdb;

$sql = "DROP TABLE ".$wpdb->prefix."smart_maintenance_mode;";
$wpdb->query($sql);

delete_option('smm_version');
delete_option('disable_smm');
delete_option('smm_heading');
delete_option('smm_subheading');
delete_option('smm_image');
delete_option('smm_roles');
delete_option('smm_html');
delete_option('smm_donate_popup');
delete_option('smm_countdown');

}

?>
