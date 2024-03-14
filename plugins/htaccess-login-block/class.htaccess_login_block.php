<?php
defined('ABSPATH') or die("No script kiddies please!");

class htaccess_login_block extends htaccess_login_block_base {
	private static $initiated = false;
	
	private static $o;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}

		// get plugin options
		self::$o = get_option(self::$wp_option_name);
	}
	
	private static function init_hooks() {
		self::$initiated = true;
		
		add_action( 'wp_login_failed', array('htaccess_login_block', 'act') ); // hook failed login
		add_filter( 'xmlrpc_pingback_error', array( 'htaccess_login_block', 'act_xmlrpc' ), 1 );
		
		if (!is_array(self::$o))
			self::$o = array("disable_xmlrpc_withauth"=>0, "treat_xmlrpc_as_failure"=>0, "hide_json_user_expose"=>1);
		if (!isset(self::$o["disable_xmlrpc_withauth"]))
			self::$o["disable_xmlrpc_withauth"]=0;
		if (!isset(self::$o["treat_xmlrpc_as_failure"]))
			self::$o["treat_xmlrpc_as_failure"]=0;
		if (!isset(self::$o["hide_json_user_expose"]))
			self::$o["hide_json_user_expose"]=1;
		
		if (self::$o["disable_xmlrpc_withauth"]==1) {
            // Disable XMLRPC calls, that require authentication
            add_filter( 'xmlrpc_enabled', '__return_false' );
		}
		
		if (self::$o["treat_xmlrpc_as_failure"]==1) {
			add_filter( 'xmlrpc_login_error', array( 'htaccess_login_block', 'act_xmlrpc_autherror' ), 10, 2 ) ;
		}


		if (self::$o["hide_json_user_expose"]==1) {
			add_filter( 'rest_user_query', '__return_null' );
			add_filter( 'rest_prepare_user', '__return_null' );
		}
	}
	

	static function is_whitelisted($ip) {
		$is_white=false;
		
		$ips = explode("\n", self::$o["whitelist_ip"]);
		if (count($ips)>0) {
			foreach($ips as $w_ip) {
				if (trim($w_ip)==trim($ip))
					$is_white=true;
			}
		}
		
		return $is_white;
	}
	
	
	static function act($username) {
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"]!="")
			$report_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else
			$report_ip = $_SERVER["REMOTE_ADDR"];
		
		
		if (!self::is_whitelisted($report_ip)) {
			self::register_failure(array("failed_user"=>$username, "referrer"=>$_SERVER['HTTP_REFERER'], "url"=>home_url(), "visitor_ip"=>$report_ip, "server_timestamp"=>time()));
			self::make_htaccess();
		}
	}
	
	static function act_xmlrpc($xmlrpc_error) {
		
		if ( $xmlrpc_error->code === 48 ) return $xmlrpc_error;
		
		
		
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"]!="")
			$report_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else
			$report_ip = $_SERVER["REMOTE_ADDR"];
		
		
		$tmp_site_name = "unknown";
		if( function_exists( "get_bloginfo" ) ) {
            $tmp_site_name = get_bloginfo('name') ;
            if( !empty( $tmp_site_name ) ) {
                $site_name = $tmp_site_name;
            }
        }
		
		if (!self::is_whitelisted($report_ip)) {
			self::register_failure(array("failed_user"=>"XML-RPC ERROR: ".$xmlrpc_error->code, "referrer"=>$_SERVER['HTTP_REFERER'], "url"=>$site_name, "visitor_ip"=>$report_ip, "server_timestamp"=>time()));
			self::make_htaccess();
		}
	}
	
	
	static function act_xmlrpc_autherror($error, $user) {
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"]!="")
			$report_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else
			$report_ip = $_SERVER["REMOTE_ADDR"];
		
		
		$tmp_site_name = "unknown";
		if( function_exists( "get_bloginfo" ) ) {
            $tmp_site_name = get_bloginfo('name') ;
            if( !empty( $tmp_site_name ) ) {
                $site_name = $tmp_site_name;
            }
        }
		
		if (!self::is_whitelisted($report_ip)) {
			self::register_failure(array("failed_user"=>"XML-RPC AUTH ERROR: ".$user, "referrer"=>$_SERVER['HTTP_REFERER'], "url"=>$site_name, "visitor_ip"=>$report_ip, "server_timestamp"=>time()));
			self::make_htaccess();
		}
	}
		
	
	
	static function plugin_deactivation() {
		global $wpdb;

		# remove our tables. clean after ourselves in a good manner
		$wpdb->query("DROP TABLE IF EXISTS ".self::log_name().";");
		$wpdb->query("DROP TABLE IF EXISTS ".self::block_name().";");
		
		# clean our options
		delete_site_option(self::$wp_option_name);
		self::clean_htaccess();
	}
	
	static function plugin_activation() {
		
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$log_name = self::log_name();
		$block_name = self::block_name();
		
		$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE IF NOT EXISTS ".$log_name." (
  from_ip varchar(20),
  failed_login varchar(255),
  action_time int(11) unsigned,
  action_datetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  referrer varchar(255),
  site_url varchar(255)
) ".$charset_collate.";";

		dbDelta( $sql );
		
		
$sql = "CREATE TABLE IF NOT EXISTS ".$block_name." (
	ip varchar(20) NOT NULL,
	from_time int(11) UNSIGNED, 
	until_time int(11) UNSIGNED,
	failure_count int(11) UNSIGNED
) ".$charset_collate.";";

		dbDelta($sql);
		
		
		# save default plugin settings.		
		$odef = array(
			"block_after_failures"=>3,
			"block_type"=>"wp_login",
			"ip_block_limit"=>100,
			"whitelist_ip"=>$_SERVER["REMOTE_ADDR"],
			"count_within_period"=>1800,
			"block_for_period"=>14400,
			"disable_xmlrpc_withauth"=>0,
			"treat_xmlrpc_as_failure"=>1,
			"hide_json_user_expose"=>1
		);
		
		update_site_option(self::$wp_option_name, $odef);
		self::$o=$odef;
		
		self::make_htaccess();
		
		return true;
	}
}
