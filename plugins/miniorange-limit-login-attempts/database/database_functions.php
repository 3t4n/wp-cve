<?php

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	class Mo_lla_MoWpnsDB
	{
		private $transactionTable;
		private $blockedIPsTable;
		private $whitelistIPsTable;
		private $emailAuditTable;
		private $attackList;
		private $attackLogs;
		private $IPrateDetails;
		function __construct()
		{
			global $wpdb;
			$this->transactionTable		= $wpdb->base_prefix.'wpns_transactions';
			$this->blockedIPsTable 		= $wpdb->base_prefix.'wpns_blocked_ips';
			$this->attackList			= $wpdb->base_prefix.'wpns_attack_logs';
			$this->whitelistIPsTable	= $wpdb->base_prefix.'wpns_whitelisted_ips';
			$this->emailAuditTable		= $wpdb->base_prefix.'wpns_email_sent_audit';
			$this->IPrateDetails 		= $wpdb->base_prefix.'wpns_ip_rate_details';
			$this->attackLogs			= $wpdb->base_prefix.'wpns_attack_logs';
			
		}

		function mo_plugin_activate()
		{
			global $wpdb;
			
			if(!get_option('mo_wpns_dbversion',false) || get_option('mo_wpns_dbversion',999)<Mo_lla_MoWpnsConstants::DB_VERSION )
			{
				$this->generate_tables();
				$this->mo_lla_migration_update();
				update_option('mo_wpns_dbversion', Mo_lla_MoWpnsConstants::DB_VERSION );
				update_option('mo_lla_dbversion', Mo_lla_MoWpnsConstants::DB_VERSION );
			} 
			else 
			{
				$current_db_version = get_option('mo_lla_dbversion');
				if($current_db_version < Mo_lla_MoWpnsConstants::DB_VERSION)
				update_option('mo_lla_dbversion', Mo_lla_MoWpnsConstants::DB_VERSION );
			}
		}
        function mo_lla_migration_update()
        {
			//all the migration option goes here
			$this->generate_tables();

			$brute_force=false;
        	if(get_option("mo_wpns_enable_brute_force",null)=="on" || get_option("mo_wpns_enable_brute_force",null)){
        		$brute_force=true;
				$login_attempts      =  get_option("mo_wpns_allwed_login_attempts"  );
				$blocking_type       =  get_option("mo_wpns_time_of_blocking_type"  );
				$blocking_value      =  get_option("mo_wpns_time_of_blocking_val"   );
				$show_login_attempts =  get_option("mo_wpns_show_remaining_attempts");
				$molla_login_page_url =  get_option("login_page_url",false);

			    
                update_option( 'mo_lla_allwed_login_attempts'	, $login_attempts 		  );
				update_option( 'mo_lla_time_of_blocking_type'	, $blocking_type 		  );
				update_option( 'mo_lla_time_of_blocking_val' 	, $blocking_value   	  );
				update_option( 'mo_lla_show_remaining_attempts' , $show_login_attempts    ); 
        	}	

			if(get_option('login_page_url',false)){
				update_option( 'mo_lla_login_page_url',get_option('login_page_url'));
				delete_option('login_page_url');
			}


			update_option("mo_lla_enable_brute_force",$brute_force);

			$recaptcha_bp = 1;
        	if(get_option("mo_wpns_activate_recaptcha_for_buddypress_registration")!='on'){
        		$recaptcha_bp = 0;
        	}
        	update_option("mo_lla_activate_recaptcha_for_buddypress_registration",$recaptcha_bp);
            
            $recaptcha_comments=1;
			if(get_option("mo_wpns_activate_recaptcha_for_comments")!='on'){
				$recaptcha_comments = 0;
        	}
        	update_option("mo_lla_activate_recaptcha_for_comments",$recaptcha_comments);

			$recaptcha_email=1;
			if(get_option("mo_wpns_activate_recaptcha_for_email_subscription")!='on'){
        		$recaptcha_email = 0;
        	}
        	update_option("mo_lla_activate_recaptcha_for_email_subscription",$recaptcha_email);
           
			$recaptcha = 1;
			if(get_option("mo_wpns_activate_recaptcha")=="on"){
				$curr_ver=get_option("mo_wpns_recaptcha_version");
				if($curr_ver=='reCAPTCHA_v2'){
					update_option('mo_lla_recaptcha_version',$curr_ver);
					update_option('mo_lla_recaptcha_site_key',get_option('mo_wpns_recaptcha_site_key'));
					update_option('mo_lla_recaptcha_secret_key',get_option('mo_wpns_recaptcha_secret_key'));
				}
				else if($curr_ver=='reCAPTCHA_v3'){
                    update_option('mo_lla_recaptcha_version',$curr_ver);
                    update_option('mo_lla_recaptcha_site_key_v3',get_option('mo_wpns_recaptcha_site_key_v3'));
					update_option('mo_lla_recaptcha_secret_key_v3',get_option('mo_wpns_recaptcha_secret_key_v3'));
				}
				if(get_option("mo_wpns_activate_recaptcha")=="on"){
                    update_option('mo_lla_activate_recaptcha',get_option('mo_wpns_activate_recaptcha'));
					update_option('mo_lla_activate_recaptcha_for_login',get_option('mo_wpns_activate_recaptcha_for_login'));
					update_option('mo_lla_activate_recaptcha_for_woocommerce_login',get_option('mo_wpns_activate_recaptcha_for_woocommerce_login'));
					update_option('mo_lla_activate_recaptcha_for_registration',get_option('mo_wpns_activate_recaptcha_for_registration'));
					update_option('mo_lla_activate_recaptcha_for_woocommerce_registration',get_option('mo_wpns_activate_recaptcha_for_woocommerce_registration'));	
				}
				else if(get_option("mo_wpns_activate_recaptcha")==""){
					update_option('mo_lla_activate_recaptcha_for_login','');
					update_option('mo_lla_activate_recaptcha_for_woocommerce_login','');
					update_option('mo_lla_activate_recaptcha_for_registration','');
					update_option('mo_lla_activate_recaptcha_for_woocommerce_registration','');	
				}
        	}

        }
		function generate_tables()
		{
			
			global $wpdb;
			$table_Name = $this->transactionTable;
			if($wpdb->get_var("show tables like '$table_Name'") != $table_Name)
			{
				$sql = "CREATE TABLE IF NOT EXISTS " . $table_Name . " (
				`id` bigint NOT NULL AUTO_INCREMENT, `ip_address` mediumtext NOT NULL ,  `username` mediumtext NOT NULL ,`type` mediumtext NOT NULL , `url` mediumtext NOT NULL , `status` mediumtext NOT NULL , `created_timestamp` int, UNIQUE KEY id (id) );";
				$results = $wpdb->get_results($sql);
			}
			$tableName = $this->blockedIPsTable;
			if($wpdb->get_var("show tables like '$tableName'") != $tableName) 
			{
				$sql = "CREATE TABLE ".$tableName."(
				`id` int NOT NULL AUTO_INCREMENT, `ip_address` mediumtext NOT NULL , `reason` mediumtext, `blocked_for_time` int,
				`created_timestamp` int, UNIQUE KEY id (id) );";
				dbDelta($sql);
			}
			

			$tableName = $this->whitelistIPsTable;
			if($wpdb->get_var("show tables like '$tableName'") != $tableName) 
            {
                $sql = "CREATE TABLE " . $tableName . " (
                `id` int NOT NULL AUTO_INCREMENT, `ip_address` mediumtext NOT NULL , `created_timestamp` int, UNIQUE KEY id (id) );";
                dbDelta($sql);
            }
			

			$tableName = $this->emailAuditTable;
			if($wpdb->get_var("show tables like '$tableName'") != $tableName) 
			{
				$sql ="CREATE TABLE " . $tableName . " (
				`id` int NOT NULL AUTO_INCREMENT, `ip_address` mediumtext NOT NULL , `username` mediumtext NOT NULL, `reason` mediumtext, `created_timestamp` int, UNIQUE KEY id (id) );";
				dbDelta($sql);
			}
			$tableName = $this->IPrateDetails;
			if($wpdb->get_var("show tables like '$tableName'") != $tableName) 
			{
				$sql = "CREATE TABLE " . $tableName . " (
				ip varchar(20) , time bigint );";
				dbDelta($sql);
			}

			$tableName = $this->attackLogs;
			if($wpdb->get_var("show tables like '$tableName'") != $tableName) 
			{
				$sql = "create table ". $tableName ." (
						ip varchar(20),
						type varchar(20),
						time bigint,
						input mediumtext );";
				$results = $wpdb->get_results($sql);
				
			}
		}
		
		function get_ip_blocked_count($ipAddress,$blocking_type)
		{
			global $wpdb;
		    
			if($blocking_type == "permanent"){
				$sql =$wpdb->prepare( "SELECT COUNT(*) FROM $this->blockedIPsTable WHERE ip_address = %s", array( $ipAddress) );
				return $wpdb->get_var($sql);
			}
			else if(get_option('WAFEnabled')==1){	
				if(get_option('Rate_limiting')==1 && get_option('actionRateL') != 0){
                $sql =$wpdb->prepare( "SELECT COUNT(*) FROM $this->blockedIPsTable WHERE ip_address = %s", array( $ipAddress) );
				return $wpdb->get_var($sql);
				}
			}
			else{
				$sql =$wpdb->prepare( "SELECT COUNT(*) FROM $this->blockedIPsTable WHERE ip_address = %s AND blocked_for_time > %d", array( $ipAddress,time()) );
				return $wpdb->get_var($sql);
			}
			
		}
		function get_total_blocked_ips()
		{
			global $wpdb;
			$query=$wpdb->prepare("SELECT COUNT(*) FROM $this->blockedIPsTable");
			return $wpdb->get_var($query);
		}
		function get_total_manual_blocked_ips()
		{
			global $wpdb;
		    $sql = $wpdb->prepare( "SELECT COUNT(*) FROM $this->blockedIPsTable WHERE `reason` = %s", array( 'Blocked by Admin' ) );

			

			return $wpdb->get_var($sql);
			
		}
		function get_total_blocked_ips_waf()
		{
			global $wpdb;
			$sql = $wpdb->prepare( "SELECT COUNT(*) FROM {$this->blockedIPsTable} WHERE `reason` != %s", array( 'Blocked by Admin' ) );
			$totalIPBlocked = $wpdb->get_var($sql);
			return $totalIPBlocked ;
		}
		function get_blocked_attack_count($attack)
		{
			global $wpdb;
			$sql = $wpdb->prepare( "SELECT COUNT(*) FROM {$this->attackList} WHERE `type` = %s", array( $attack ) );
			return $wpdb->get_var( $sql );
		}
		
		function get_count_of_blocked_ips(){
			global $wpdb;
			return $wpdb->get_var("SELECT COUNT(DISTINCT ip_address) FROM {$this->blockedIPsTable}");

		}

		function get_time_of_block_ip()
		{	
			global $wpdb;
			$query=$wpdb->prepare( "SELECT id FROM {$this->blockedIPsTable}  WHERE blocked_for_time < %d",array(time()));
			$wpdb->get_var($query);
			
		}
		function get_blocked_ip($entryid)
		{
			global $wpdb;
			$query = $wpdb->prepare("DELETE FROM {$this->blockedIPsTable} WHERE id = %d",array($entryid));
			return $wpdb->get_results($query);
		}

		function get_blocked_ip_list()
		{
			global $wpdb;
			return $wpdb->get_results("SELECT id, reason, ip_address, created_timestamp FROM ".$this->blockedIPsTable);
		}


		function get_blocked_sqli_list()
		{
			global $wpdb;
			$query=$wpdb->prepare( "SELECT  ip, type, time, input FROM {$this->attackList}  WHERE type = %s",array('SQL attack'));
			return $wpdb->get_results($query);
		}
		function get_blocked_rfi_list()
		{
			global $wpdb;
			$query=$wpdb->prepare( "SELECT  ip, type, time, input FROM {$this->attackList}  WHERE type = %s",array('RFI attack'));
			return $wpdb->get_results($query);
		}
		function get_blocked_lfi_list()
		{
			global $wpdb;
			$query=$wpdb->prepare( "SELECT  ip, type, time, input FROM {$this->attackList}  WHERE type = %s",array('LFI attack'));
			return $wpdb->get_results($query);
		}
		function get_blocked_rce_list()
		{
			global $wpdb;
			return $wpdb->get_results("SELECT ip, type, time, input FROM ".$this->attackList."WHERE type='RCE attack'");
		}
		function get_blocked_xss_list()
		{
			global $wpdb;
			$query=$wpdb->prepare( "SELECT  ip, type, time, input FROM {$this->attackList}  WHERE type = %s",array('XSS attack'));
			return $wpdb->get_results($query);
		}

		function insert_blocked_ip($ipAddress,$reason,$blocked_for_time)
		{
			global $wpdb;
			$sql = "INSERT INTO {$this->blockedIPsTable}(ip_address,reason,blocked_for_time,created_timestamp) VALUES(%s,%s,%d,%d)";
       		$query=$wpdb->prepare($sql,array($ipAddress,$reason,$blocked_for_time,current_time('timestamp')));
      		$wpdb->query( $query); 
			return;
		}

		function delete_blocked_ip($entryid)
		{   
			global $wpdb;
			$query = $wpdb->prepare( "DELETE FROM {$this->blockedIPsTable}  WHERE id = %d",array($entryid));
			$wpdb->query($query);
			return;
		}
		//***************************************//on successfull login delete ip if it is present in blacklist************/
        function delete_given_blocked_ip($entryip){
			global $wpdb;
			$query = $wpdb->prepare( "DELETE FROM {$this->blockedIPsTable}  WHERE ip_address = %s",array($entryip));
			$wpdb->query($query);
			return;
		}
		//************************************************************************************************************************/
		function get_whitelisted_ip_count($ipAddress)
		{
			global $wpdb;
			$query = $wpdb->prepare("SELECT COUNT(*) FROM {$this->whitelistIPsTable}  WHERE ip_address = %s",array($ipAddress));
			return $wpdb->get_var($query);
		}

		function insert_whitelisted_ip($ipAddress)
		{
			global $wpdb;
			$sql = "INSERT INTO $this->whitelistIPsTable(ip_address,created_timestamp) VALUES(%s,%d)";
       		$query=$wpdb->prepare($sql,array($ipAddress,current_time( 'timestamp' )));
      		$wpdb->query( $query); 
			return;
		}

		function get_number_of_whitelisted_ips(){
			global $wpdb;
			$res= $wpdb->get_var("SELECT COUNT(*) FROM {$this->whitelistIPsTable}");
			return $res;
		}

		function delete_whitelisted_ip($entryid)
		{
			global $wpdb;
			$query = $wpdb->prepare("DELETE FROM {$this->whitelistIPsTable}  WHERE id = %d",array($entryid));
			$wpdb->query($query);
			return;
		}

		function get_whitelisted_ips_list()
		{
			global $wpdb;
			return $wpdb->get_results( "SELECT id, ip_address, created_timestamp FROM ".$this->whitelistIPsTable );
		}

		function get_email_audit_count($ipAddress,$username)
		{
			global $wpdb;
			$query = $wpdb->prepare("DELETE FROM {$this->emailAuditTable}  WHERE ip_address = %s AND username= %s ",array($ipAddress,$username));
			return $wpdb->get_var($query);
		}

		function insert_email_audit($ipAddress,$username,$reason)
		{
			global $wpdb;
			$sql = "INSERT INTO $this->emailAuditTable (ip_address,username,reason,created_timestamp) VALUES(%s,%s,%s,%d)";
       		$query=$wpdb->prepare($sql,array($ipAddress,$username,$reason,current_time( 'timestamp' )));
      		$wpdb->query( $query); 
			return;
		}

		function insert_transaction_audit($ipAddress,$username,$type,$status,$url=null)
		{
		   global $wpdb;
			$sql = "INSERT INTO $this->transactionTable (ip_address,username,type,status,created_timestamp,url) VALUES(%s,%s,%s,%s,%d,%s)";
		   $dwing = is_null($url) ? '' : sanitize_url($url);  
       	   $query=$wpdb->prepare($sql,array($ipAddress,$username,$type,$status,current_time( 'timestamp' ),$dwing));
      	   $wpdb->get_results( $query); 
			return;
		}

		function get_transasction_list()
		{
			global $wpdb;
			return $wpdb->get_results( "SELECT ip_address, username, type, status, created_timestamp FROM ".$this->transactionTable." order by id desc limit 5000" );
		}

		function get_login_transaction_report()
		{
			global $wpdb;
			$query = $wpdb->prepare("SELECT ip_address, username,status, type,created_timestamp FROM {$this->transactionTable}  WHERE type = %s order by id desc limit 5000 ",array('User Login'));
			return $wpdb->get_results($query);
		}

		function get_error_transaction_report()
		{
			global $wpdb;
			$query = $wpdb->prepare("SELECT ip_address, username,url, type,created_timestamp FROM {$this->transactionTable}  WHERE type <> %s order by id desc limit 5000 ",array('User Login'));
			return $wpdb->get_results($query);
			
		}

		function update_transaction_table($where,$update)
		{
			global $wpdb;
			$sql = "UPDATE ".$this->transactionTable." SET ";
			$i = 0;
			foreach($update as $key=>$value)
			{
				if($i%2!=0)
					$sql .= ' , ';
				$sql .= $key."='".$value."'";
				$i++;
			}
			$sql .= " WHERE ";
			$i = 0;
			foreach($where as $key=>$value)
			{
				if($i%2!=0)
					$sql .= ' AND ';
				$sql .= $key."='".$value."'";
				$i++;
			}	
			$wpdb->query($sql);
			return;
		}

		function get_count_of_attacks_blocked(){
			global $wpdb;
			$query = $wpdb->prepare("SELECT COUNT(*) FROM  {$this->transactionTable}  WHERE status = %s OR status = %s",array(Mo_lla_MoWpnsConstants::FAILED,Mo_lla_MoWpnsConstants::PAST_FAILED ));
			return $wpdb->get_var($query);
		}

		function get_failed_transaction_count($ipAddress)
		{
			global $wpdb;
			$query = $wpdb->prepare("SELECT COUNT(*) FROM  {$this->transactionTable}  WHERE ip_address = %d AND status =%s",array($ipAddress,Mo_lla_MoWpnsConstants::FAILED));
			$failed_transaction_count = $wpdb->get_var($query);
			return $failed_transaction_count;
		}

		function delete_transaction($ipAddress)
		{
			global $wpdb;
			$query = $wpdb->prepare("DELETE FROM {$this->transactionTable}  WHERE ip_address = %d AND status =%s",array($ipAddress,Mo_lla_MoWpnsConstants::FAILED));
			$wpdb->query( $query);
			return;
		}
		
	}