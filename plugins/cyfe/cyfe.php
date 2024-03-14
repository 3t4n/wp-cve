<?php
/*
Plugin Name: Cyfe
Plugin URI: https://www.cyfe.com/
Description: This plugin lets you see your WordPress data inside your Cyfe dashboard.
Author: Cyfe, Inc
Version: 1.4
Author URI: https://www.cyfe.com/
*/

if(!empty($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/cyfe/cyfe') !== false)
{
	if(function_exists('date_default_timezone_set'))
		date_default_timezone_set('UTC');
	
	$dashboard_type = 'overview';
	$dashboard_sdate = date('Ymd', strtotime('-29 day'));
	$dashboard_edate = date('Ymd', strtotime('now'));
	
	if(!empty($_GET['type']))
		$dashboard_type = $_GET['type'];
		
	if(!empty($_GET['sdate']))
		$dashboard_sdate = $_GET['sdate'];
		
	if(!empty($_GET['edate']))
		$dashboard_edate = $_GET['edate'];
	
	if(!empty($_GET['key']))
	{
		if(file_exists('../../../wp-config.php'))
			include_once('../../../wp-config.php');
		elseif(file_exists('../../../../wp-config.php'))
			include_once('../../../../wp-config.php');
		else
			exit;
		
		cyfe_process_request($_GET['key'], $dashboard_type, $dashboard_sdate, $dashboard_edate);
		exit;
	}
}

function cyfe_process_request($key_in, $type_in, $sdate_in, $edate_in)
{
	$key = get_option('cyfe_key');
	
	if($key !== false && $key == $key_in)
	{
		cyfe_process_data($type_in, $sdate_in, $edate_in);
	}
}

function cyfe_process_data($type, $sdate, $edate)
{
	$data = array();
	
	global $wpdb;
	
	if($type == 'overview' || $type == 'users')
	{
		$data[date('Y-m-d', strtotime($sdate))]['users'] = 0;
		
		$daily_users = $wpdb->get_results("SELECT COUNT(*) as cnt, DATE(user_registered) dte FROM $wpdb->users WHERE user_registered>='".date('Y-m-d', strtotime($sdate))."' AND user_registered<='".date('Y-m-d', strtotime($edate))."' GROUP BY dte");
		
		foreach($daily_users as $value)
		{
			$date = str_replace('-', '', $value->dte);
			$data[$date]['users'] = $value->cnt;
		}
	}
	
	if($type == 'overview' || $type == 'posts')
	{
		$data[date('Y-m-d', strtotime($sdate))]['posts'] = 0;
		
		$daily_posts = $wpdb->get_results("SELECT COUNT(*) as cnt, DATE(post_date) dte FROM $wpdb->posts WHERE post_date>='".date('Y-m-d', strtotime($sdate))."' AND post_date<='".date('Y-m-d', strtotime($edate))."' AND post_type='post' AND post_status='publish' GROUP BY dte");
		
		foreach($daily_posts as $value)
		{
			$date = str_replace('-', '', $value->dte);
			$data[$date]['posts'] = $value->cnt;
		}
	}
	
	if($type == 'overview' || $type == 'comments')
	{
		$data[date('Y-m-d', strtotime($sdate))]['comments'] = 0;
		
		$daily_comments = $wpdb->get_results("SELECT COUNT(*) as cnt, DATE(comment_date) dte FROM $wpdb->comments WHERE comment_date>='".date('Y-m-d', strtotime($sdate))."' AND comment_date<='".date('Y-m-d', strtotime($edate))."' AND comment_approved='1' GROUP BY dte");
		
		foreach($daily_comments as $value)
		{
			$date = str_replace('-', '', $value->dte);
			$data[$date]['comments'] = $value->cnt;
		}
	}
	
	echo json_encode($data);
}

add_action('admin_menu', 'cyfe_config_page');

register_deactivation_hook(__FILE__, 'cyfe_deactivate');

function cyfe_config_page()
{
	if(function_exists('add_submenu_page'))
		add_submenu_page('plugins.php', __('Cyfe Configuration'), __('Cyfe Configuration'), 'manage_options', 'cyfe-key-config', 'cyfe_conf');
}

function cyfe_conf()
{
	$key = get_option('cyfe_key');
	
	if($key === false)
	{
		$key = cyfe_gen_uuid();
		
		if(!update_option('cyfe_key', $key))
			exit;
	}
	
	$url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'cyfe.php?key='.$key;
	
	echo '<div class="wrap"><h2>Cyfe Configuration</h2><br class="clear" /><p>To see WordPress data inside your Cyfe dashboard, simply add a new WordPress widget inside your dashboard and copy/paste the following URL into the "API URL" field during widget configuration.</p><h4>API URL</h4><input id="url" name="url" type="text" size="100" onclick="this.select();" value="'.$url.'" readonly="readonly" /></div>';	
}

function cyfe_deactivate()
{
	delete_option('cyfe_key');
}

function cyfe_gen_uuid($len=32)
{
    $hex = md5("8s7a8+_@^%sg98a77896ai&*(^" . uniqid("", true));
    $pack = pack('H*', $hex);
    $uid = base64_encode($pack);
    $uid = preg_replace("/[^a-zA-Z0-9]/", "", $uid);
    if ($len<4)
        $len=4;
    if ($len>128)
        $len=128;
    while (strlen($uid)<$len)
        $uid = $uid . cyfe_gen_uuid(22);
    return substr($uid, 0, $len).md5(rand(1, 9999999999));
}

?>
