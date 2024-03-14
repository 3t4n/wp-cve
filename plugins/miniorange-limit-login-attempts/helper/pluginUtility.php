<?php
/** Copyright (C) 2015  miniOrange

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
* @package 		miniOrange OAuth
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*
**/


// need to have different classes here for each ipblocking, whitelisting, htaccess and transaction related functions
class Mo_lla_MoWpnsHandler
{

	function is_ip_blocked($ipAddress)
	{
		global $Mo_lla_wpnsDbQueries;
		if(empty($ipAddress))
			return false;
		$blocking_type = get_option('mo_lla_time_of_blocking_type');
	
		//should have option to detect if it is checking for bruteforce or 
		$user_count 	= $Mo_lla_wpnsDbQueries->get_ip_blocked_count($ipAddress,$blocking_type);
	
		if($user_count)
			$user_count = intval($user_count);
		if($user_count>0)
			return true;
		
		return false;
	}
    
    function is_ip_blocked_manual_ip($ipAddress){
        global $Mo_lla_wpnsDbQueries;
		if(empty($ipAddress))
			return false;
		
		//should have option to detect if it is checking for bruteforce or 
		$user_count 	= $Mo_lla_wpnsDbQueries->get_ip_blocked_count($ipAddress,'permanent');
		
		if($user_count)
			$user_count = intval($user_count);
		if($user_count>0)
			return true;
		
		return false;
	}

	function get_blocked_attacks_count($attackName)
	{
		global $Mo_lla_wpnsDbQueries;
		$attackCount = $Mo_lla_wpnsDbQueries->get_blocked_attack_count($attackName);
		if($attackCount)
			$attackCount =  intval($attackCount);
		return $attackCount;
	}
	function get_blocked_countries()
	{
		$countrycodes 	= get_option('mo_lla_countrycodes');
		$countries 		= explode(';', $countrycodes);
		return sizeof($countries)-1;
	}
	function get_blocked_ip_waf()
	{
		global $Mo_lla_wpnsDbQueries;
		$ip_count = $Mo_lla_wpnsDbQueries->get_total_blocked_ips_waf();
		if($ip_count)
			$ip_count = intval($ip_count);

		return $ip_count;
	}
	function get_manual_blocked_ip_count()
	{
		global $Mo_lla_wpnsDbQueries;
		$ip_count = $Mo_lla_wpnsDbQueries->get_total_manual_blocked_ips();
		if($ip_count)
			$ip_count = intval($ip_count);

		return $ip_count;
	}
	function get_blocked_ips()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_blocked_ip_list();
	}
	function get_blocked_sqli()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_blocked_sqli_list();
	}
	function get_blocked_rfi()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_blocked_rfi_list();	
	}
	function get_blocked_lfi()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_blocked_lfi_list();
	}
	function get_blocked_rce()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_blocked_rce_list();
	}
	function get_blocked_xss()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_blocked_xss_list();	
	}
	
	function block_ip($ipAddress, $reason, $permenently)
	{
		global $Mo_lla_wpnsDbQueries;
		if(empty($ipAddress))
			return;
		if($this->is_ip_blocked($ipAddress))
			return;
		$blocked_for_time = null;
		if(!$permenently && get_option('mo_lla_time_of_blocking_type'))
		{
			$blocking_type = get_option('mo_lla_time_of_blocking_type');
			$time_of_blocking_val = 3;
			if(get_option('mo_lla_time_of_blocking_val'))
				$time_of_blocking_val = get_option('mo_lla_time_of_blocking_val');
			if($blocking_type=="months")
				$blocked_for_time = current_time( 'timestamp' )+$time_of_blocking_val * 30 * 24 * 60 * 60;
			else if($blocking_type=="days")
				$blocked_for_time = current_time( 'timestamp' )+$time_of_blocking_val * 24 * 60 * 60;
			else if($blocking_type=="hours")
				$blocked_for_time = current_time( 'timestamp' )+$time_of_blocking_val * 60 * 60;
			else if($blocking_type=="minutes")
				$blocked_for_time = current_time( 'timestamp' )+$time_of_blocking_val * 60 ;
	
		}
		
		$Mo_lla_wpnsDbQueries->insert_blocked_ip($ipAddress, $reason,$blocked_for_time);
		$this->move_failed_transactions_to_past_failed($ipAddress);
		//send notification
		global $mollaUtility;
		if(get_option('mo_lla_enable_ip_blocked_email_to_admin'))
			$mollaUtility->sendIpBlockedNotification($ipAddress,Mo_lla_MoWpnsConstants::LOGIN_ATTEMPTS_EXCEEDED);
			
	}
	
	function unblock_ip_entry($entryid)
	{
		global $Mo_lla_wpnsDbQueries;
		$Mo_lla_wpnsDbQueries->delete_blocked_ip($entryid);
	}
	
	function unblock_ip_using_ip($entryip){
		global $Mo_lla_wpnsDbQueries;
		$Mo_lla_wpnsDbQueries->delete_given_blocked_ip($entryip);
	}

	function remove_htaccess_ips()
	{
		global $Mo_lla_wpnsDbQueries;
		$myrows = $Mo_lla_wpnsDbQueries->get_blocked_ip_list();
		$base = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		$hpath = $base.DIRECTORY_SEPARATOR.".htaccess";
		$contents = file_get_contents($hpath);
		$changed = 0;
		foreach($myrows as $row)
		{
			$ip_address = $row->ip_address;
			if (strpos($contents, "\ndeny from ".trim($ip_address)) !== false) 
			{
				$contents = str_replace("\ndeny from ".trim($ip_address), '', $contents);
				$changed = 1;
			}
		}
		if($changed==1)
			file_put_contents($hpath, $contents);
	}
	
	function add_htaccess_ips()
	{
		global $Mo_lla_wpnsDbQueries;
		$myrows = $Mo_lla_wpnsDbQueries->get_blocked_ip_list();
		$base = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		$hpath = $base.DIRECTORY_SEPARATOR.".htaccess";
		$contents = file_get_contents($hpath);
		$f = fopen($hpath, "a");
		foreach($myrows as $row)
		{
			$ip_address = $row->ip_address;
			if (strpos($contents, "\ndeny from ".trim($ip_address)) === false)
				fwrite($f, "\ndeny from ".trim($ip_address));
		}
		fclose($f);
	}
	
	
	function is_whitelisted($ipAddress)
	{
		global $Mo_lla_wpnsDbQueries;
		$count = $Mo_lla_wpnsDbQueries->get_whitelisted_ip_count($ipAddress);
		if(empty($ipAddress))
			return false;
		if($count)
			$count = intval($count);

		if($count>0)
			return true;
		return false;
	}
	
	function whitelist_ip($ipAddress)
	{
		global $Mo_lla_wpnsDbQueries;	
		if(empty($ipAddress))
			return;
		if($this->is_whitelisted($ipAddress))
			return;
		$count1=$Mo_lla_wpnsDbQueries->get_ip_blocked_count($ipAddress,"permanent");
		if($count1!=0){
			$Mo_lla_wpnsDbQueries->delete_given_blocked_ip($ipAddress);
		}
		$Mo_lla_wpnsDbQueries->insert_whitelisted_ip($ipAddress);

	}
	function mollm_check_ip_duration(){
		
		$lla_database = new Mo_lla_MoWpnsDB;
		$lla_count_ips_blocked = $lla_database->get_time_of_block_ip();

	}
	function remove_whitelist_entry($entryid)
	{
		global $Mo_lla_wpnsDbQueries;
		$Mo_lla_wpnsDbQueries->delete_whitelisted_ip($entryid);
	}
	
	function get_whitelisted_ips()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_whitelisted_ips_list();
	}
	
	function is_email_sent_to_user($username, $ipAddress)
	{
		global $Mo_lla_wpnsDbQueries;
		if(empty($ipAddress))
			return false;
		$sent_count = $Mo_lla_wpnsDbQueries->get_email_audit_count($ipAddress,$username);
		if($sent_count)
			$sent_count = intval($sent_count);
		if($sent_count>0)
			return true;
		return false;
	}
	
	function audit_email_notification_sent_to_user($username, $ipAddress, $reason)
	{
		if(empty($ipAddress) || empty($username))
			return;
		global $Mo_lla_wpnsDbQueries;
		$Mo_lla_wpnsDbQueries->insert_email_audit($ipAddress,$username,$reason);
	}
	
	function add_transactions($ipAddress, $username, $type, $status, $url=null)
	{
		global $Mo_lla_wpnsDbQueries;
		$Mo_lla_wpnsDbQueries->insert_transaction_audit($ipAddress, $username, $type, $status, $url);
	}

	function get_login_transaction_report()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_login_transaction_report();
	}
	
	function get_error_transaction_report()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_error_transaction_report();
	}


	function get_all_transactions()
	{
		global $Mo_lla_wpnsDbQueries;
		return $Mo_lla_wpnsDbQueries->get_transasction_list();
	}
	
	function move_failed_transactions_to_past_failed($ipAddress)
	{
		global $Mo_lla_wpnsDbQueries;
		$Mo_lla_wpnsDbQueries->update_transaction_table(array('status'=>Mo_lla_MoWpnsConstants::FAILED),
			array('status'=>Mo_lla_MoWpnsConstants::PAST_FAILED));
	}
	
	function remove_failed_transactions($ipAddress)
	{
		global $Mo_lla_wpnsDbQueries;
		$Mo_lla_wpnsDbQueries->delete_transaction($ipAddress);	
	}
	
	function get_failed_attempts_count($ipAddress)
	{
		global $Mo_lla_wpnsDbQueries;
		$count = $Mo_lla_wpnsDbQueries->get_failed_transaction_count($ipAddress);
		if($count)
		{
			$count = intval($count);
			return $count;
		}
		return 0;
	}
	
	function is_ip_blocked_in_anyway($userIp)
	{
		$isBlocked = array();
		$isBlocked['status'] = false;
		$isBlocked['message'] = '';
		if($this->is_ip_blocked($userIp)){
			$isBlocked['message'] = "Your ip has been blocked by administrator.";
			$isBlocked['status'] = true;
		}else if($this->is_ip_range_blocked($userIp)){
		    $isBlocked['message'] = "Your ip has been blocked by administrator.";
			$isBlocked['status'] = true;
		}else if($this->is_browser_blocked()){
			$isBlocked['message'] = "Your browser cannot access this website.";
			$isBlocked['status'] = true;
		}else if($this->is_country_blocked($userIp)){
			$isBlocked['message'] = "Access from your Country was disabled by the administrator.";
			$isBlocked['status'] = true;
		}else if($this->is_referer_blocked()){
			$isBlocked['message'] = "Requests from referer ".sanitize_text_field($_SERVER['HTTP_REFERER'])." are blocked.";
			$isBlocked['status'] = true;
        }
		return $isBlocked;
	}

	function is_ip_range_blocked($userIp)
	{
		if(empty($userIp))
			return false;
		$range_count = 0;
		if(is_numeric(get_option('mo_lla_iprange_count')))
			$range_count = intval(get_option('mo_lla_iprange_count'));
		for($i = 1 ; $i <= $range_count ; $i++){ 
			$blockedrange  = get_option('mo_lla_iprange_range_'.$i);
			$rangearray = explode("-",$blockedrange);
			if(sizeof($rangearray)==2){
				$lowip = ip2long(trim($rangearray[0]));
				$highip = ip2long(trim($rangearray[1]));
				if(ip2long($userIp)>=$lowip && ip2long($userIp)<=$highip){
					$mo_lla_config = new Mo_lla_MoWpnsHandler();
					$mo_lla_config->block_ip($userIp, Mo_lla_MoWpnsConstants::IP_RANGE_BLOCKING, true);
					return true;
				}
			}
		}
		return false;
	}
	
	
	function is_browser_blocked()
	{
		global $mollaUtility;
		if(get_option( 'mo_lla_enable_user_agent_blocking'))
		{			
			$current_browser = $mollaUtility->getCurrentBrowser();
			if(get_option('mo_lla_block_chrome') && $current_browser=='chrome')
				return true;
			else if(get_option('mo_lla_block_firefox') && $current_browser=='firefox')
				return true;
			else if(get_option('mo_lla_block_ie') && $current_browser=='ie')
				return true;
			else if(get_option('mo_lla_block_opera') && $current_browser=='opera')
				return true;
			else if(get_option('mo_lla_block_safari')&& $current_browser=='safari')
				return true;
			else if(get_option('mo_lla_block_edge') && $current_browser=='edge')
				return true;
		}
		return false;
	}
	
	
	function is_country_blocked($userIp)
	{			
		$countrycodes = get_option('mo_lla_countrycodes');
		if($countrycodes && !empty($countrycodes)){
			 $ip_data=wp_remote_get("http://www.geoplugin.net/json.gp?ip=".$userIp);
					if( !is_wp_error( $ip_data ) ) {
                        $ip_data=wp_remote_retrieve_body( $ip_data);
                    }   
			if($ip_data && $ip_data->geoplugin_countryName != null){
				$country_code = $ip_data->geoplugin_countryCode;
				if(!empty($country_code)){
					$countrycodes = get_option('mo_lla_countrycodes');
					$codes = explode(";", $countrycodes);
					foreach($codes as $code){
						if(!empty($code) && strcasecmp($code,$country_code)==0)
							return true;
					}
				}
			}
		}
		return false;
	}


	function is_referer_blocked()
	{
		if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && get_option('mo_lla_referrers')){
			$userreferer = sanitize_text_field($_SERVER['HTTP_REFERER']);
			$referrers = explode(";",get_option('mo_lla_referrers'));
			foreach($referrers as $referrer){
				if(!empty($referrer) && strpos(strtolower($userreferer), strtolower($referrer)) !== false){
					return true;
				}
			}
		}
		return false;
	}
	
} ?>