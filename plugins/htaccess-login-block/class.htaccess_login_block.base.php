<?php

defined('ABSPATH') or die("No script kiddies please!");

class htaccess_login_block_base {
	static $wp_option_name = "htaccess_login_block_options";
	static $htaccess_slug  = "htaccessLoginBlockplugin";
	
	static $default_wp_htaccess = "
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
";
	
	static function log_name() {
		global $wpdb;
		
		return $wpdb->prefix."slbl_log";
	}
	
	static function block_name() {
		global $wpdb;
		return $wpdb->prefix."slbl_blocks";
	}
	
	
	static function register_failure($o) {
		global $wpdb;
		
		# array("failed_user"=>$username, "referrer"=>$referrer, "url"=>home_url(), "visitor_ip"=>$report_ip, "server_timestamp"=>time(), "login_referrer"=>$_SERVER["HTTP_REFERRER"])
		

		if ($o["visitor_ip"]!="" && $o["visitor_ip"]!="unknown") {
			$wpdb->insert( 
				self::log_name(), 
				array( 
					'from_ip' => $o["visitor_ip"], 
					'failed_login' => $o["failed_user"], 
					'action_time' => $o["server_timestamp"], 
					"action_datetime"=>date("Y-m-d H:i:s", $o["server_timestamp"]),
					"referrer"=>$o["referrer"],
					"site_url"=>$o["url"]
				) 
			);
		}

	}
			
	static function htaccess_location() {
		if (!function_exists("get_home_path"))
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		
		return(get_home_path()."/.htaccess");
	}
	
	static function read_htaccess() {
		$htaccess_location=self::htaccess_location();
		
		if (file_exists($htaccess_location))
			return(file_get_contents($htaccess_location));
		else
			return(self::$default_wp_htaccess);
	}
	
	static function write_htaccess($content) {

		if (function_exists("insert_with_markers")) {
			insert_with_markers(self::htaccess_location(), self::$htaccess_slug, $content);
		}
		
		#$fh = fopen(self::htaccess_location(), "wb");
		#fwrite($fh, $content);
		#fclose($fh);
	}
		
		
	static function remove_old_block_ip() {
		global $wpdb;
		
		self::clean_old_log_entries();
		
		$expired_blocks = self::get_expired_blocks();
		
		if (count($expired_blocks)>0) {
			# clean old records.
			$wpdb->get_results("delete from ".self::block_name()." where until_time<".time().";");
			# self::make_htaccess();
		}
	}	
	
	static function remove_block_ip($ip) {
		global $wpdb;
		
		$wpdb->get_results("delete from ".self::block_name()." where ip='".$ip."';");
		$wpdb->get_results("delete from ".self::log_name()." where from_ip='".$ip."'");
		self::make_htaccess();
	}
	
	# add record to block_ip table if not exists or if exists = add time
	static function block_ip($ip, $block_time=1800) {
		global $wpdb;
		
		if ($block_time>86400)
			$block_time=86400;
		if ($block_time<600)
			$block_time=600;

		
		if (self::is_valid_ip($ip)) {		
			$data = $wpdb->get_results("select * from ".self::block_name()." where ip='".$ip."' and until_time>'".time()."';");
			if (count($data)>0) {
				$wpdb->update(
					self::block_name(),
					array("until_time"=>(time()+$block_time)),
					array("ip"=>$ip)
				);
				
			} else {
				$wpdb->insert( 
					self::block_name(), 
					array( 
						'ip' => $ip, 
						'from_time' => time(), 
						'until_time' => (time()+$block_time), 
						"failure_count"=>0
					) 
				);
			}
		}
	}		
	
	
	static function clean_old_log_entries() {
		global $wpdb;
		
		$wpdb->get_results("delete from ".self::log_name()." where action_time<'".(time()-5184000)."'");
	}
	
	
	static function get_expired_blocks() {
		global $wpdb;
		
		return($wpdb->get_results("select * from ".self::block_name()." where until_time<".time().";"));
	}
	
	static function get_current_blocks() {
		global $wpdb;
		return($wpdb->get_results("select * from ".self::block_name()." where until_time>".time().";"));
	}
			
	static function is_valid_ip($ip) {
		$is_valid = preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/", $ip);
		
		if ($is_valid==1)
			return true;
		else
			return false;
	}		
			
	static function make_htaccess() {
		global $wpdb;

		#self::clean_old_log_entries();
		self::remove_old_block_ip();
		
		$o = get_option(self::$wp_option_name);
		
		# if something is wrong with this variable, force set to 15 minutes
		if ($o["block_for_period"]<30)
			$o["block_for_period"]=900;
		
		if (!isset($o["count_within_period"]) || $o["count_within_period"]<300)
			$o["count_within_period"]=86400;
		
		if ($o["count_within_period"]>864000)
			 $o["count_within_period"]=259200;
		
		# load log entries for the last 30 minutes
		$block_ips=array();
		$log = $wpdb->get_results("select * from ".self::log_name()." where action_time>='".(time()-$o["count_within_period"])."';");
		
		if (count($log)>0) {
			
			$d=array();
			
			foreach($log as $l) {
				
				if (array_key_exists($l->from_ip, $d))
					$d[$l->from_ip]++;
				else
					$d[$l->from_ip]=1;
			}
			
			foreach($d as $sip=>$scount) {
				if ($scount>=$o["block_after_failures"]) {
					$block_ips[$sip]=$scount;
					
					self::block_ip($sip, $o["block_for_period"]);
				}
			}
		}
		
		# get previous records
		$current_blocks = self::get_current_blocks();
		if (count($current_blocks)>0) {
			foreach($current_blocks as $cbl) {
				$block_ips[$cbl->ip]=1;
			}
		}
		
			
		# $content = self::clean_htaccess(true);
		
		# $plugin_content = "# BEGIN ".self::$htaccess_slug.PHP_EOL;
		
		$htaccess_content = array();
		$apache1=array();
		$apache2=array();

		if (count($block_ips)>0) {
			

			foreach($block_ips as $bip=>$bcount) {
				if (self::is_valid_ip($bip) && $bip!="unknown" && $bip!="") {
					$apache1[] = "   deny from ".$bip;
					$apache2[] = "   Require not ip ".$bip;
				} else {
					#$wpdb->get_results("delete from ".self::log_name()." where from_ip='".$bip."';");
					$wpdb->get_results("delete from ".self::block_name()." where ip='".$bip."';");
				}
			}
		}

		if (count($apache1)>0 && count($apache2)>0) {
			$htaccess_content[] = "<IfModule !mod_authz_core.c>";
			$htaccess_content[] = "   Order Deny,Allow";
			foreach($apacahe1 as $ip1)
				$htaccess_content[]=$ip1;
			$htaccess_content[] = "</IfModule>";
			$htaccess_content[] = "<IfModule mod_authz_core.c>";
			$htaccess_content[] = "   <RequireAll>";
			$htaccess_content[] = "   Require all granted";
			foreach($apache2 as $ip2)
				$htaccess_content[]=$ip2;
			$htaccess_content[] = "   </RequireAll>";
			$htaccess_content[] = "</IfModule>";
		}
		
		# @$plugin_content .= "# END ".self::$htaccess_slug.PHP_EOL;
		
		self::write_htaccess($htaccess_content);
	}
	
	static function clean_htaccess($return_content=false) {
		$htaccess_raw_data = self::read_htaccess();
		$data = explode(PHP_EOL, $htaccess_raw_data);
		$content = "";
		
		if (count($data)>0) {
			$start_found=false;
			$end_found=false;
			
			$clean_data = "";
			$plugin_data = "";
			$prev_line = "";
			
			foreach($data as $r) {
				if ($r=="# BEGIN ".self::$htaccess_slug)
					$start_found=true;
				else if ($r=="# END ".self::$htaccess_slug)
					$end_found=true;
				else if ($r!="" || ($r=="" && $prev_line!="")) {
					if (!$start_found)
						$clean_data .= $r.PHP_EOL;
					else if ($start_found && $end_found)
						$clean_data .= $r.PHP_EOL;
					else if ($start_found && !$end_found) {
						if (trim($r)!="")
							$plugin_data .= $r.PHP_EOL;
					} else
						$clean_data .= $r.PHP_EOL;
				}
				
				$prev_line=$r;
			}
			
			if ($start_found && $end_found)
				$content = $clean_data;
			else
				$content = $htaccess_raw_data;
		}

		if ($return_content) {
			# instead of saving .htaccess, we return it
			return $content;
		} else {
			# save clean .htaccess
			self::write_htaccess($content);
		}
	}
	

	
}