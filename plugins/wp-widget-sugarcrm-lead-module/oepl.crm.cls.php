<?php
if ( isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit(esc_html__('Please don\'t access this file directly.', 'WP2SL'));
}

class WP2SLSugarCRMClass  {
	function __construct()  {
		$this->DropSugarTblOnUninstall = false;

		$this->ModuleList = array('Leads');
		$this->ModuleListName = array('Leads' => 'Leads');
		$this->ModuleListStr = "'" . implode( "','", $this->ModuleList) . "'";
			
		$this->SugarURL  = '';
		$this->SugarUser = ''; 
		$this->SugarPass = ''; 
		$this->SugarSessData = '';
		
		$this->isHtaccessProtected = FALSE; 
		$this->HtaccessAdminUser = '';
		$this->HtaccessAdminPass = '';
		
		$this->SugarClient = ''; 
		$this->SugarSessID = ''; 
		$this->ExcludeFields = array('id', 'date_entered', 'date_modified', 'modified_user_id', 'modified_by_name', 'created_by', 'created_by_name', 'deleted', 'assigned_user_id', 'assigned_user_name', 'team_id', 'team_set_id', 'team_count', 'team_name', 'email_addresses_non_primary', 'account_description', 'opportunity_name', 'opportunity_amount', 'email2', 'invalid_email', 'email_opt_out', 'webtolead_email1', 'webtolead_email2', 'webtolead_email_opt_out', 'webtolead_invalid_email', 'email', 'full_name', 'reports_to_id', 'report_to_name', 'contact_id', 'account_id', 'opportunity_id', 'refered_by', 'c_accept_status_fields', 'm_accept_status_fields','lead_remote_ip_c');
		$this->ExcludeFieldTypes = array();
	}

	function wp2sl_activate() {
		global $wpdb;
		$sql = "CREATE TABLE IF NOT EXISTS `".OEPL_TBL_MAP_FIELDS."` (
				  `pid` int(11) NOT NULL AUTO_INCREMENT,
				  `module` varchar(100) NOT NULL,
				  `field_type` enum('text','select','radio','checkbox','textarea','file','filler') NOT NULL DEFAULT 'text',
				  `data_type` varchar(50) NOT NULL,
				  `field_name` varchar(255) NOT NULL,
				  `field_value` text NOT NULL,
				  `wp_meta_key` varchar(150) NOT NULL,
				  `wp_meta_label` varchar(200) NOT NULL,
				  `wp_custom_label` varchar(50) NOT NULL,
				  `display_order` int(11) NOT NULL,
				  `required` enum('Y','N') NOT NULL DEFAULT 'N',
				  `hidden` enum('Y','N') NOT NULL DEFAULT 'N',
				  `is_show` enum('Y','N') NOT NULL DEFAULT 'N',
				  `show_column` enum('1','2') NOT NULL DEFAULT '1',
				  `custom_field` enum('Y','N') NOT NULL DEFAULT 'N',
				  PRIMARY KEY (`pid`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
		$wpdb->query($sql);

		$sql = "ALTER TABLE `".OEPL_TBL_MAP_FIELDS."` ADD hidden_field_value VARCHAR(255) NOT NULL AFTER custom_field";
		$wpdb->query($sql);

		add_option('OEPL_SugarCRMSuccessMessage', 'Thank you! Your message was sent successfully.');
		add_option('OEPL_SugarCRMFailureMessage', 'Sorry. An error occurred while sending the message!');
		add_option('OEPL_SugarCRMReqFieldsMessage', 'Please fill in all the required fields.'); 
		add_option('OEPL_SugarCRMInvalidCaptchaMessage', 'Invalid captcha ! Please try again.');
		add_option('OEPL_Captcha_status', 'Y');
	}

	function LoginToSugar() {
		$login_parameters = array(
			"user_auth" => array(
				"user_name"	=> $this->SugarUser,
							   "password"	=> $this->SugarPass,
							   "version"	=> "1"
			),
			"application_name"	=>	"RestTest",
			"name_value_list"	=>	array(),
		);
																						
		$this->SugarSessData = $this->SugarCall("login", $login_parameters, $this->SugarURL);
		if(isset($this->SugarSessData->id)){
			$this->SugarSessID = $this->SugarSessData->id;
			if($this->SugarSessID === '') {
				$subject = get_option("blogname") . esc_html_e("CRM connection failed.", "WP2SL");
				$body = get_option('siteurl') . esc_html_e("not able to connect with CRM. Plesae review your CRM settings", "WP2SL");
				wp_mail( get_option('admin_email'), $subject, $body);
			}
			return $this->SugarSessID;
		}
		return false;
	}

	function LogoutToSugar() {
		$login_parameters = array(
			"user_auth" => array(
				"user_name"	=>  $this->SugarUser,
								"password"	=>	md5($this->SugarPass),
								"version"	=>	"1"
			),
			"application_name"	=>	"RestTest",
			"name_value_list"	=>	array(),
		);
		$this->SugarCall("logout", $login_parameters, $this->SugarURL);
	}

	function get_size($file, $type) {
		$filesize = filesize($file);
	
		switch ($type) {
			case "KB":
				$filesize /= 1024; // bytes to KB
				break;
			case "MB":
				$filesize /= 1024 ** 2; // bytes to MB
				break;
			case "GB":
				$filesize /= 1024 ** 3; // bytes to GB
				break;
		}
	
		if ($filesize <= 0) {
			return 0; // or return 'unknown file size';
		} else {
			return round($filesize, 2);
		}
	}

	function ErrorLogWrite($content) {
		$text = "\n\n\n";
		$text .= 'Log: ' . "\n" . $content . "\n" ;
		$myFile = OEPL_PLUGIN_DIR . 'Log.txt';
		if($this->get_size($myFile, 'MB') > 2 ) {
			@rename(OEPL_PLUGIN_DIR . 'Log.txt', OEPL_PLUGIN_DIR . 'Log_till_'.date('d-M-Y-H-i-s-u').'.txt');
		} 

		$myFile = OEPL_PLUGIN_DIR . 'Log.txt';
		$fh = fopen($myFile, 'a+') or wp_die( "can't open file" );
		fwrite($fh, $text);
		fclose($fh);
		return NULL;
	}

	function SugarCall($method, $parameters, $url) {
		if($this->SugarURL === '') return null;
		$headers = array();

		if($this->isHtaccessProtected === TRUE) {
            $basicauth = 'Basic ' . base64_encode( $this->HtaccessAdminUser . ':' . $this->HtaccessAdminPass );
            $headers['Authorization'] = $basicauth;
        }
		
		$jsonEncodedData = json_encode($parameters);
		
		$postArray = array(
            "method" 		=> $method,
            "input_type" 	=> "JSON",
            "response_type" => "JSON",
            "rest_data" 	=> $jsonEncodedData
        );
		
		$args = array(  
			'method'        => 'POST',
			'timeout'       => 45,
			'redirection'   => 5,
			'httpversion'   => '1.0',
			'blocking'      => true,
			'headers'       => $headers,
			'body'          => $postArray,
			'cookies'       => array(),
		);
        
        $result = wp_remote_post( $url, $args );    
		
		if(isset($result->errors) && isset($result->errors['http_request_failed']) && ($result->errors['http_request_failed']['0']) && !empty($result->errors['http_request_failed']['0'])){
			$response = $result->errors;
		} else {
			$response = json_decode($result['body']);
		}
        return $response;
	}

	function IsUserAdministrator() {
		global $current_user;
		$Role= '';

		if(is_array($current_user->roles)) {
			foreach($current_user->roles as $k => $v) {
				if ($v === 'administrator' && $Role === '') {
					$Role = 'administrator';
				}
			}
		}

		if($Role === 'administrator') {
			return true;
		} else {
			return false;
		}
	}

	function getLeadFieldsList() {
		$result = (object)array();
		if($this->SugarSessID === '') {
			$this->LoginToSugar();
		}
		
		if($this->SugarSessID) {
			$set_entry_parameters = array(	 
				"session" 		=> $this->SugarSessID,
				"module_name"	=> "Leads"
			);
			$result = $this->SugarCall("get_module_fields", $set_entry_parameters, $this->SugarURL);
		}
		return $result;
	}
	
	function InsertLeadToSugar($FileToSugar = array()) {
		global $wpdb;
		
		wp_unslash($_POST);
		
		if($this->SugarSessID === '') {
			$this->LoginToSugar();
		}

		if($this->SugarSessID) {
			$sql = $wpdb->prepare( "SELECT wp_meta_key, data_type, field_name FROM ".OEPL_TBL_MAP_FIELDS." as mp WHERE mp.module='%s'", 'Leads');
			$ContRS = $wpdb->get_results($sql,ARRAY_A);
			$fCnt 	= count($ContRS);
			$name_value_list = array();
			for ($i=0; $i < $fCnt; $i++) {
				if ( isset($_POST[$ContRS[$i]['wp_meta_key']]) ) {
					if ($ContRS[$i]['data_type'] === 'date') {
						$sql = "SELECT STR_TO_DATE('".sanitize_text_field($_POST[$ContRS[$i]['wp_meta_key']])."','%m/%d/%Y') as date";
 						$date = $wpdb->get_results($sql,ARRAY_A);
						$_POST[$ContRS[$i]['wp_meta_key']] = $date[0]['date'];
					} else if ($ContRS[$i]['data_type'] === 'datetimecombo') {
						$sql = "SELECT STR_TO_DATE('".sanitize_text_field($_POST[$ContRS[$i]['wp_meta_key']])."','%m/%d/%Y %H:%i') as date";
						$date = $wpdb->get_results($sql,ARRAY_A);
						$_POST[$ContRS[$i]['wp_meta_key']] = $date[0]['date'];
					}

					$name_value_list[] = array(
						"name" => $ContRS[$i]['field_name'], 
						"value" => sanitize_text_field(trim($_POST[ $ContRS[$i]['wp_meta_key'] ]))
					);
				}
			}
			$IPaddrStatus = get_option('OEPL_auto_IP_addr_status');
			if($IPaddrStatus === 'Y' && isset($_SERVER['REMOTE_ADDR'])){
				$name_value_list[] = array("name" => 'lead_remote_ip_c', "value" => sanitize_text_field($_SERVER['REMOTE_ADDR']));
			}	
			
			if(count($name_value_list) > 0 &&  $this->SugarSessID != '') {
				$set_entry_parameters = array(	 
					"session" 			=> $this->SugarSessID,
					"module_name"		=> "Leads",
					"name_value_list" 	=> $name_value_list
				);
				$resultLead = $this->SugarCall("set_entry", $set_entry_parameters, $this->SugarURL);
			}
			
			if($resultLead->id != '' && is_array($FileToSugar) && count($FileToSugar) > 0) {
				for($p=0; $p<count($FileToSugar); $p++) {
					if ($FileToSugar[$p]['name'] != '' && file_exists($FileToSugar[$p]['file']) && !is_dir($FileToSugar[$p]['file'])) {
						$name_value_list = array();
						$name_value_list[] = array("name" => 'parent_type', "value" => 'Leads');
						$name_value_list[] = array("name" => 'parent_id',   "value" => $resultLead->id);
						$name_value_list[] = array("name" => 'name',        "value" => 'Attachment-'.($p+1));
						$set_entry_parameters = array(	 
							"session" 			=> $this->SugarSessID,
							"module_name"		=> "Notes",
							"name_value_list" 	=> $name_value_list
						);
						$resultNote = $this->SugarCall("set_entry", $set_entry_parameters, $this->SugarURL);
					
						if($resultNote->id != '') {
							$contents = file_get_contents ($FileToSugar[$p]['file']);					 
							$attachment=array('id'				=>	$resultNote->id,
											  'filename'		=>	$FileToSugar[$p]['name'],
											  'file_mime_type'	=>	$FileToSugar[$p]['type'],
											  'file'			=>	base64_encode($contents)
											);								
							$note_attachment=array( 
								'session' => $this->SugarSessID,
								'note' 	  => $attachment
							);
							$this->SugarCall('set_note_attachment', $note_attachment, $this->SugarURL);
							@unlink($FileToSugar[$p]['file']);
						}
					}
				}
			}
			return $resultLead->id;
		}
		return false;
	}
}
