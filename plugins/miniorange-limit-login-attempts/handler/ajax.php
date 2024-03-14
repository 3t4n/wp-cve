<?php

class Mo_lla_AjaxHandler
{
	function __construct()
	{
		add_action( 'admin_init'  , array( $this, 'mo_lla_saml_actions' ) );
	}

	function mo_lla_saml_actions()
	{
		global $mollaUtility,$mo_lla_dirName;

		if (current_user_can( 'manage_options' ) && isset( $_REQUEST['option'] ))
		{ 
			switch(sanitize_text_field($_REQUEST['option']))
			{
				case "iplookup":
					$this->lookupIP(sanitize_text_field($_GET['ip']));	break;
				case "dissmissfeedback":
					$this->handle_feedback();		break;
				case "whitelistself":
					$this->whitelist_self();		break;
				case "dissmissbruteforce":
					$this->handle_bruteforce();		break;

			}
		}
	}
	
	private function handle_bruteforce(){
		update_site_option("lla_dont_show_enable_brute_force",true);
		wp_send_json('success');
	}

	private function lookupIP($ip)
	{
         $result=wp_remote_get("http://www.geoplugin.net/json.gp?ip=".$ip);

        if( !is_wp_error( $result ) ) {
            $result=wp_remote_retrieve_body( $result);
        }
		$hostname 	= gethostbyaddr($result["geoplugin_request"]);
		try{
            $timeoffset	= timezone_offset_get(new DateTimeZone($result["geoplugin_timezone"]),new DateTime('now'));
            $timeoffset = $timeoffset/3600;

        }catch(Exception $e){
            $result["geoplugin_timezone"]="";
            $timeoffset="";
        }

		$ipLookUpTemplate  = Mo_lla_MoWpnsConstants::IP_LOOKUP_TEMPLATE;
		if($result['geoplugin_request']==$ip) {

            $ipLookUpTemplate = str_replace("{{status}}", $result["geoplugin_status"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{ip}}", $result["geoplugin_request"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{region}}", $result["geoplugin_region"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{country}}", $result["geoplugin_countryName"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{city}}", $result["geoplugin_city"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{continent}}", $result["geoplugin_continentName"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{latitude}}", $result["geoplugin_latitude"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{longitude}}", $result["geoplugin_longitude"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{timezone}}", $result["geoplugin_timezone"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{curreny_code}}", $result["geoplugin_currencyCode"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{curreny_symbol}}", $result["geoplugin_currencySymbol"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{per_dollar_value}}", $result["geoplugin_currencyConverter"], $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{hostname}}", $hostname, $ipLookUpTemplate);
            $ipLookUpTemplate = str_replace("{{offset}}", $timeoffset, $ipLookUpTemplate);

            $result['ipDetails'] = $ipLookUpTemplate;
        }else{
            $result["ipDetails"]["status"]="ERROR";
        }

		wp_send_json( $result );
    }
	private function handle_feedback()
	{
		update_option('donot_show_feedback_message',1);
		wp_send_json('success');
	}

	private function whitelist_self()
	{
		global $mollaUtility;
		$moPluginsUtility = new Mo_lla_MoWpnsHandler();
		$moPluginsUtility->whitelist_ip($mollaUtility->get_client_ip());
		wp_send_json('success');
	}

}new Mo_lla_AjaxHandler;