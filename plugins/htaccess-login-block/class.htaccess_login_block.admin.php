<?php

defined('ABSPATH') or die("No script kiddies please!");

class htaccess_login_block_admin extends htaccess_login_block_base {

	private static $initiated = false;
	private static $o;
	
	public static function init() {
		# only work for admin
		if (current_user_can("manage_options")) {
			if ( ! self::$initiated ) {
				self::init_hooks();
			}
			
			self::$o = get_option(self::$wp_option_name);
		}
	}
	
	private static function init_hooks() {
		self::$initiated = true;
		
		$hook = is_multisite() ? 'network_' : '';
		add_action( "{$hook}admin_menu", array("htaccess_login_block_admin", "admin_menu" ) );

		register_activation_hook( __FILE__, array( 'htaccess_login_block', 'plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( 'htaccess_login_block', 'plugin_deactivation' ) );

		# add_action( 'init', array( 'htaccess_login_block_admin', 'init' ) );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array('htaccess_login_block_admin', 'add_action_links') );
	}
	
	static function add_action_links ( $links ) {
		 $mylinks = array(
		 '<a href="' . admin_url( 'options-general.php?page=htaccess_login_block' ) . '">Settings</a>',
		 );
		return array_merge( $links, $mylinks );
	}
	
	
	public static function admin_menu() {
		add_options_page( '.htaccess login block', '.htaccess login block', 'manage_options', 'htaccess_login_block', array("htaccess_login_block_admin", "admin_menu_options") );
	}
	
	
	static function admin_menu_options() {
		if ( !is_super_admin() )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		
		if (isset($_POST["subaction"]) && $_POST["subaction"]=="save_options") {
		
			$set_options = self::$o;
			$set_options["block_after_failures"]=$_POST["slbl_block_after_failures"];
			$set_options["block_type"]=(isset($_POST["slbl_block_type"])?$_POST["slbl_block_type"]:"");
			$set_options["whitelist_ip"]=$_POST["slbl_whitelist_ip"];
			$set_options["ip_block_limit"]=$_POST["slbl_ip_block_limit"];
			$set_options["count_within_period"]=$_POST["slbl_count_within_period"];
			$set_options["block_for_period"]=$_POST["slbl_block_for_period"];
			
			if (!isset($_POST["slbl_disable_xmlrpc_withauth"])) $_POST["slbl_disable_xmlrpc_withauth"]=0;
			if ($_POST["slbl_disable_xmlrpc_withauth"]==1)
				$set_options["disable_xmlrpc_withauth"]=1;
			else 
				$set_options["disable_xmlrpc_withauth"]=0;
			
			if (!isset($_POST["slbl_treat_xmlrpc_as_failure"])) 
				$_POST["slbl_treat_xmlrpc_as_failure"]=0;
			
			if ($_POST["slbl_treat_xmlrpc_as_failure"]==1)
				$set_options["treat_xmlrpc_as_failure"]=1;
			else
				$set_options["treat_xmlrpc_as_failure"]=0;
			

			if (!isset($_POST["slbl_hide_json_user_expose"]))
				$_POST["slbl_hide_json_user_expose"]=1;

			if ($_POST["slbl_hide_json_user_expose"]==1)
				$set_options["hide_json_user_expose"]=1;
			else
				$set_options["hide_json_user_expose"]=0;

			
			update_option(self::$wp_option_name, $set_options);
			self::make_htaccess();

			self::$o = $set_options;
			
			print("
				<div class='updated'><p><strong>".__('Great, settings have been saved successfully!')."</strong></p></div>
			");
		}
		
		if (isset($_POST["rebuild_htaccess"]) && $_POST["rebuild_htaccess"]==1)
			self::make_htaccess();
		
		
		if (isset($_GET["unblock_ip"]) && $_GET["unblock_ip"]!="") {
			self::remove_block_ip($_GET["unblock_ip"]);
			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit();
		}

		
		$log = self::get_log(30);		
		if (count($log)>0) {
			
			$log_html = "
			<table bgcolor='#555' cellspacing='1' cellpadding='4' width='90%' align='center'>
				<thead>
					<tr style='background:#AAA;'><th colspan='".((is_multisite())?"4":"3")."'>Last 30 failed login attempts</th></tr>
					<tr style='background:#AAA;'>
						<th>".__("Date")."</th>
						<th>".__("From IP")."</th>
						<th>".__("Username")."</th>
						".((is_multisite())?"<th>".__("Site")."</th>":"")."
					</tr>
				</thead>
				<tbody>
			";
			
			foreach($log as $r) {
				$log_html .= "<tr style='background:#FFF;'><td>".date("H:i d.M.Y", $r->action_time)."</td><td>".$r->from_ip."</td><td>".$r->failed_login."</td>" . ((is_multisite())?"<td>".$r->site_url."</td>":"")."</tr>";
			}
			$log_html .= "</tbody></table>";
			
		} else {
			$log_html = "<div align='center'>Failed login log is empty.</div>";
		}
		
		
		$blocks_html = "";
		$active_blocks = self::get_current_blocks();
		if (count($active_blocks)>0) {
			$blocks_html = "
				<table bgcolor='#555' cellspacing='1' cellpadding='4'  width='90%' align='center'>
					<thead>
						<tr style='background:#AAA;'>
							<th colspan='4'>Currently active blocks</th>
						</tr>
						
						<tr style='background:#AAA;'>
							<th>IP</th>
							<th>From</th>
							<th>Until</th>
							<th>&nbsp;</th>
						</tr>
					</thead><tbody>";
					
			foreach($active_blocks as $abl) {
				$blocks_html .= "
					<tr style='background:#FFF;'>
						<td>".$abl->ip."</td>
						<td>".date("H:i d.M.Y", $abl->from_time)."</td>
						<td>".date("H:i d.M.Y", $abl->until_time)."</td>
						<td><a href='?page=".$_REQUEST["page"]."&unblock_ip=".$abl->ip."'>Unblock</a></td>
					</tr>";
			}		
			$blocks_html .= "</tbody></table>";
		} else {
			$blocks_html = "<div align='center'>We are not blocking any IPs at the moment.</div>";
		}
		
		
		# $blocks = self::get_active_block();
		
		
		
		print("
		<div class='wrap'>
		
			<table width='100%'>
				<tbody><tr><td valign='top'>
			

				<p>&nbsp;</p>
			
				".$blocks_html."
				
				<p>&nbsp;</p>
				
				".$log_html."
				
				<form action='' method='post' class='form-horizontal'>
					<input type='hidden' name='rebuild_htaccess' value='1'>
					<p class='submit' style='text-align:center'>
						<input class='button button-primary' type='submit' value='".__("Force rebuild .htaccess file")."'>
					</p>
				</form>
				
				</td><td valign='top' style='border-left: 1px solid black;padding-left: 10px;'>
				
		
			<h2>".__("Plugin configuration")."</h2>
		
			<form action='' method='post' class='form-horizontal'>
			<input type='hidden' name='subaction' value='save_options'>
			
			<table class='form-table'>
				<tbody>
					
					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Block IP after failed login attempts")."</label>
						</th>
						<td>
							<input type='text' name='slbl_block_after_failures' value='".self::$o["block_after_failures"]."' class='form-control'>
						</td>
					</tr>
					
					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Count failed attempts within")."</label>
						</th>
						<td>
							<select name='slbl_count_within_period' class='form-control'>
								<option value='600'".((self::$o["count_within_period"]==600)?" selected":"").">10 minutes</option>
								<option value='1800'".((self::$o["count_within_period"]==1800)?" selected":"").">30 minutes</option>
								<option value='3600'".((self::$o["count_within_period"]==3600)?" selected":"").">1 hour</option>
								<option value='7200'".((self::$o["count_within_period"]==7200)?" selected":"").">2 hours</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Block IP for")."</label>
						</th>
						<td>
							<select name='slbl_block_for_period' class='form-control'>
								<option value='600'".((self::$o["block_for_period"]==600)?" selected":"").">10 minutes</option>
								<option value='1800'".((self::$o["block_for_period"]==1800)?" selected":"").">30 minutes</option>
								<option value='3600'".((self::$o["block_for_period"]==3600)?" selected":"").">1 hour</option>
								<option value='14400'".((self::$o["block_for_period"]==14400)?" selected":"").">4 hours</option>
								<option value='43200'".((self::$o["block_for_period"]==43200)?" selected":"").">12 hours</option>
								<option value='86400'".((self::$o["block_for_period"]==86400)?" selected":"").">24 hours</option>
								
							</select>
						</td>
					</tr>
					

					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Maximum number of blocked IPs in .htaccess")."</label>
						</th>
						<td>
							<input type='text' name='slbl_ip_block_limit' value='".self::$o["ip_block_limit"]."' class='form-control'>
						</td>
					</tr>
					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Never block the following IPs")."</label>
						</th>
						<td>
							<textarea name='slbl_whitelist_ip' class='form-control'>".self::$o["whitelist_ip"]."</textarea>
						</td>
					</tr>
					
					
					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Disable XML-RPC calls, that require authentication")."</label>
						</th>
						<td>
							<input type='checkbox' class='form-control' value='1' name='slbl_disable_xmlrpc_withauth'".((self::$o["disable_xmlrpc_withauth"]==1)?" checked":"").">
						</td>
					</tr>
					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Treat failed XML-RPC authentications as login-failure")."</label>
						</th>
						<td>
							<input type='checkbox' class='form-control' value='1' name='slbl_treat_xmlrpc_as_failure'".((self::$o["treat_xmlrpc_as_failure"]==1)?" checked":"").">
						</td>
					</tr>
					<tr>
						<th scope='row'>
							<label class='col-sm-6 control-label'>".__("Hide registered user exposure through JSON requests")."</label>
						</th>
						<td>
							<input type='checkbox' class='form-control' value='1' name='slbl_hide_json_user_expose'".((self::$o["hide_json_user_expose"]==1)?" checked":"").">
						</td>
					</tr>

					
					
				</tbody>
			</table>
			
			<p class='submit'>
				<input class='button button-primary' type='submit' value='".__("Save settings!")."'>
			</p>
			
			</form>
			
			</td></tr>
			</tbody>
			</table>
		
		</div>
		");
		
	}
	
	static function get_log($limit=30) {
		global $wpdb;
		
		$log = $wpdb->get_results("select * from ".self::log_name()." order by action_time desc limit ".$limit.";");
		
		return $log;
	}
}